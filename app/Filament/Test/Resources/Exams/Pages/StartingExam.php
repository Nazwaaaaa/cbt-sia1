<?php

namespace App\Filament\Test\Resources\Exams\Pages;

use App\Filament\Test\Resources\Exams\ExamResource;
use App\Models\Answer;
use App\Models\Exam;
use App\Models\ExamResult;
use App\Models\ExamResultAnswer;
use Filament\Resources\Pages\Page;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class StartingExam extends Page
{
    protected static string $resource = ExamResource::class;

    protected string $view =
        'filament.test.resources.exams.pages.starting-exam';

    // Ujian yang sedang dikerjakan
    public Exam $exam;

    // Kumpulan mata pelajaran + soal
    public array $collections = [];

    // Jawaban siswa
    public array $jawaban = [];

    // Hasil ujian
    public array $hasil = [];

    // Status selesai
    public bool $selesai = false;

    // Timer
    public int $remainingSeconds = 0;

    // Mencegah submit dua kali
    public bool $submitting = false;

    public function mount(Exam $exam): void
    {
        $this->exam = $exam;

        /*
        |--------------------------------------------------------------------------
        | CEK APAKAH UJIAN SUDAH PERNAH DIKERJAKAN
        |--------------------------------------------------------------------------
        */

        $sudahDikerjakan = ExamResult::where('user_id', Auth::id())
            ->where('exam_id', $exam->id)
            ->exists();

        if ($sudahDikerjakan) {
            abort(403, 'Ujian ini sudah pernah dikerjakan.');
        }

        /*
        |--------------------------------------------------------------------------
        | TIMER UJIAN
        |--------------------------------------------------------------------------
        */

        $sessionKey = 'exam_started_' . $exam->id;

        // Kalau baru mulai ujian, simpan waktu mulai
        if (!session()->has($sessionKey)) {

            session([
                $sessionKey => now()->timestamp
            ]);
        }

        // Ambil waktu mulai
        $startedAt = session($sessionKey);

        // Durasi dari database dalam MENIT
        $duration = (int) $exam->duration;

        // Hitung waktu selesai
        $endTime = $startedAt + ($duration * 60);

        // Hitung sisa waktu
        $this->remainingSeconds = max(
            0,
            $endTime - now()->timestamp
        );

        /*
        |--------------------------------------------------------------------------
        | KALAU WAKTU SUDAH HABIS
        |--------------------------------------------------------------------------
        */

        if ($this->remainingSeconds <= 0) {
            $this->submitExam();

            return;
        }

        /*
        |--------------------------------------------------------------------------
        | AMBIL SOAL
        |--------------------------------------------------------------------------
        */

        $pelajarans = $exam->subjects;

        foreach ($pelajarans as $mapel) {

            $this->collections[] = [
                'id' => $mapel->id,

                'name' => $mapel->name,

                'soals' => $mapel
                    ->questions()
                    ->where('is_active', true)
                    ->with([
                        'answers' => fn ($query) =>
                            $query->inRandomOrder(),
                    ])
                    ->inRandomOrder()
                    ->limit($mapel->pivot->qty)
                    ->get()
                    ->toArray(),
            ];
        }
    }


    /*
    |--------------------------------------------------------------------------
    | UPDATE TIMER
    |--------------------------------------------------------------------------
    */

    public function updateTimer(): void
    {
        // Kalau sudah selesai, jangan lakukan apa-apa
        if ($this->selesai || $this->submitting) {
            return;
        }

        if ($this->remainingSeconds > 0) {

            $this->remainingSeconds--;

        }

        /*
        |--------------------------------------------------------------------------
        | WAKTU HABIS
        |--------------------------------------------------------------------------
        */

        if ($this->remainingSeconds <= 0) {

            $this->remainingSeconds = 0;

            $this->submitExam();
        }
    }


    /*
    |--------------------------------------------------------------------------
    | SUBMIT UJIAN
    |--------------------------------------------------------------------------
    */

    public function submitExam(): void
    {
        // Jangan submit kalau sudah selesai
        if ($this->selesai || $this->submitting) {
            return;
        }

        $this->submitting = true;

        /*
        |--------------------------------------------------------------------------
        | CEK LAGI APAKAH SUDAH PERNAH DIKERJAKAN
        |--------------------------------------------------------------------------
        */

        $sudahDikerjakan = ExamResult::where('user_id', Auth::id())
            ->where('exam_id', $this->exam->id)
            ->exists();

        if ($sudahDikerjakan) {

            $this->selesai = true;

            $this->submitting = false;

            session()->forget(
                'exam_started_' . $this->exam->id
            );

            return;
        }


        DB::transaction(function () {

            $userId = Auth::id();

            // Kosongkan hasil
            $this->hasil = [];

            /*
            |--------------------------------------------------------------------------
            | SETIAP MATA PELAJARAN = 1 EXAM RESULT
            |--------------------------------------------------------------------------
            */

            foreach ($this->collections as $pelajaran) {

                $benar = 0;

                $salah = 0;

                $totalSoal = count(
                    $pelajaran['soals']
                );


                /*
                |--------------------------------------------------------------------------
                | BUAT HASIL UJIAN
                |--------------------------------------------------------------------------
                */

                $examResult = ExamResult::create([

                    'user_id' => $userId,

                    'exam_id' => $this->exam->id,

                    'subject_id' => $pelajaran['id'],

                    'exam_date' => now(),

                    'score' => 0,

                    'correct_answers' => 0,

                    'wrong_answers' => 0,

                ]);


                /*
                |--------------------------------------------------------------------------
                | PERIKSA SOAL
                |--------------------------------------------------------------------------
                */

                foreach ($pelajaran['soals'] as $soal) {

                    $questionId = $soal['id'];

                    // Jawaban siswa
                    $answerId =
                        $this->jawaban[$questionId] ?? null;

                    $isCorrect = false;


                    /*
                    |--------------------------------------------------------------------------
                    | JAWABAN ADA
                    |--------------------------------------------------------------------------
                    */

                    if ($answerId) {

                        $answer = Answer::query()->find($answerId);

                        if (
                            $answer &&
                            (int) $answer->question_id ===
                                (int) $questionId &&
                            $answer->is_correct
                        ) {

                            $isCorrect = true;

                            $benar++;

                        } else {

                            $salah++;
                        }

                    } else {

                        /*
                        |--------------------------------------------------------------
                        | TIDAK MENJAWAB DIHITUNG SALAH
                        |--------------------------------------------------------------
                        */

                        $salah++;
                    }


                    /*
                    |--------------------------------------------------------------------------
                    | SIMPAN DETAIL JAWABAN
                    |--------------------------------------------------------------------------
                    */

                    ExamResultAnswer::create([

                        'exam_result_id' =>
                            $examResult->id,

                        'question_id' =>
                            $questionId,

                        'answer_id' =>
                            $answerId,

                        'is_correct' =>
                            $isCorrect,

                    ]);
                }


                /*
                |--------------------------------------------------------------------------
                | HITUNG NILAI
                |--------------------------------------------------------------------------
                */

                $score = $totalSoal > 0

                    ? round(
                        ($benar / $totalSoal) * 100,
                        2
                    )

                    : 0;


                /*
                |--------------------------------------------------------------------------
                | UPDATE HASIL
                |--------------------------------------------------------------------------
                */

                $examResult->update([

                    'score' =>
                        $score,

                    'correct_answers' =>
                        $benar,

                    'wrong_answers' =>
                        $salah,

                ]);


                /*
                |--------------------------------------------------------------------------
                | MASUKKAN HASIL UNTUK DITAMPILKAN
                |--------------------------------------------------------------------------
                */

                $this->hasil[] = [

                    'subject' =>
                        $pelajaran['name'],

                    'score' =>
                        $score,

                    'correct' =>
                        $benar,

                    'wrong' =>
                        $salah,

                    'total' =>
                        $totalSoal,

                ];
            }
        });


        /*
        |--------------------------------------------------------------------------
        | UJIAN SELESAI
        |--------------------------------------------------------------------------
        */

        $this->selesai = true;

        $this->submitting = false;


        /*
        |--------------------------------------------------------------------------
        | HAPUS SESSION TIMER
        |--------------------------------------------------------------------------
        */

        session()->forget(
            'exam_started_' . $this->exam->id
        );
    }
}