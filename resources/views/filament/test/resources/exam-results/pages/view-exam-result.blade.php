<x-filament-panels::page>

    <h2 style="
        font-size: 24px;
        font-weight: 700;
        margin-bottom: 25px;
    ">
        Detail Hasil Ujian
    </h2>

    {{-- INFORMASI HASIL --}}
    <div style="
        border: 1px solid #555;
        border-radius: 8px;
        padding: 20px;
        margin-bottom: 30px;
    ">

        <div style="margin-bottom: 8px;">
            <strong>Siswa:</strong>
            {{ $record->user?->name ?? '-' }}
        </div>

        <div style="margin-bottom: 8px;">
            <strong>Ujian:</strong>
            {{ $record->exam?->title ?? '-' }}
        </div>

        <div style="margin-bottom: 8px;">
            <strong>Mata Pelajaran:</strong>
            {{ $record->subject?->name ?? '-' }}
        </div>

        <div style="margin-bottom: 8px;">
            <strong>Tanggal:</strong>
            {{ $record->exam_date?->format('d M Y H:i') ?? '-' }}
        </div>

        <div style="margin-bottom: 8px;">
            <strong>Score:</strong>
            {{ number_format((float) $record->score, 2) }}
        </div>

        <div style="margin-bottom: 8px;">
            <strong>Jawaban Benar:</strong>
            {{ $record->correct_answers }}
        </div>

        <div>
            <strong>Jawaban Salah:</strong>
            {{ $record->wrong_answers }}
        </div>

    </div>


    {{-- SOAL --}}
    @forelse ($record->answers as $index => $hasilJawaban)

        @php
            $question = $hasilJawaban->question;
        @endphp

        @if ($question)

            <div style="
                border: 1px solid #555;
                border-radius: 8px;
                padding: 20px;
                margin-bottom: 20px;
            ">

                {{-- SOAL --}}
                <div style="
                    font-size: 17px;
                    font-weight: 600;
                    margin-bottom: 15px;
                ">

                    {{ $index + 1 }}.

                    {!! $question->payload !!}

                </div>


                {{-- PILIHAN --}}
                @foreach ($question->answers as $jawaban)

                    @php
                        $dipilih =
                            (int) $hasilJawaban->answer_id ===
                            (int) $jawaban->id;

                        $jawabanBenar =
                            (bool) $jawaban->is_correct;
                    @endphp


                    <div style="
                        padding: 10px;
                        margin-bottom: 8px;
                        border-radius: 6px;

                        @if ($jawabanBenar)
                            background: rgba(34, 197, 94, 0.15);
                            border: 1px solid #22c55e;
                        @elseif ($dipilih)
                            background: rgba(239, 68, 68, 0.15);
                            border: 1px solid #ef4444;
                        @else
                            border: 1px solid transparent;
                        @endif
                    ">

                        <label style="
                            display: flex;
                            align-items: center;
                            gap: 8px;
                            cursor: default;
                        ">

                            <input
                                type="radio"
                                name="question_{{ $question->id }}"
                                value="{{ $jawaban->id }}"
                                @checked($dipilih)
                                disabled
                            >

                            <span>
                                {{ $jawaban->text }}
                            </span>


                            {{-- JAWABAN BENAR --}}
                            @if ($jawabanBenar)

                                <span style="
                                    color: #22c55e;
                                    font-weight: 600;
                                    margin-left: 8px;
                                ">
                                    ✓ Jawaban Benar
                                </span>

                            {{-- JAWABAN SISWA SALAH --}}
                            @elseif ($dipilih)

                                <span style="
                                    color: #ef4444;
                                    font-weight: 600;
                                    margin-left: 8px;
                                ">
                                    ✗ Jawaban Siswa
                                </span>

                            @endif

                        </label>

                    </div>

                @endforeach

            </div>

        @endif

    @empty

        <div style="
            padding: 20px;
            border: 1px solid #555;
            border-radius: 8px;
        ">
            Belum ada jawaban yang tersimpan untuk ujian ini.
        </div>

    @endforelse


    {{-- KEMBALI --}}
    <div style="margin-top: 25px;">

        <x-filament::button
            tag="a"
            href="{{ \App\Filament\Test\Resources\ExamResults\ExamResultResource::getUrl('index') }}"
        >
            Kembali
        </x-filament::button>

    </div>

</x-filament-panels::page>