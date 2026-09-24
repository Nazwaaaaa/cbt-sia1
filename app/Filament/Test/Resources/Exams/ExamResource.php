<?php

namespace App\Filament\Test\Resources\Exams;

use App\Filament\Test\Resources\Exams\Pages\ManageExams;
use App\Filament\Test\Resources\Exams\Pages\StartingExam;
use App\Models\Exam;
use App\Models\ExamResult;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Resources\Resource;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class ExamResource extends Resource
{
    protected static ?string $model = Exam::class;

    protected static string|BackedEnum|null $navigationIcon =
        Heroicon::OutlinedPencilSquare;

    protected static ?string $navigationLabel = 'Sesi Ujian';

    protected static ?string $modelLabel = 'Ujian';

    public static function table(Table $table): Table
    {
        return $table

            // ==========================================
            // TAMPILKAN SEMUA UJIAN YANG TERSEDIA
            // ==========================================
            ->modifyQueryUsing(function (Builder $query) {

                $query->where('is_available', true);

            })

            ->recordTitleAttribute('title')

            ->columns([

                TextColumn::make('title')
                    ->label('Ujian')
                    ->searchable(),

                TextColumn::make('duration')
                    ->label('Durasi')
                    ->numeric()
                    ->sortable(),

                TextColumn::make('threshold')
                    ->label('Min. Score')
                    ->numeric()
                    ->sortable(),

                TextColumn::make('started_at')
                    ->label('Mulai')
                    ->dateTime('d F Y, H:i:s')
                    ->sortable(),

            ])

            ->recordActions([

                Action::make('start')

                    // ==========================================
                    // LABEL TOMBOL
                    // ==========================================
                    ->label(function ($record) {

                        $sudahDikerjakan =
                            ExamResult::where('user_id', Auth::id())
                                ->where('exam_id', $record->id)
                                ->exists();

                        // Sudah mengerjakan
                        if ($sudahDikerjakan) {
                            return 'Selesai Ujian';
                        }

                        // Belum waktunya
                        if (
                            $record->exact_time &&
                            $record->started_at &&
                            $record->started_at > now()
                        ) {
                            return 'Belum Dimulai';
                        }

                        // Waktu sudah habis
                        if (self::waktuUjianHabis($record)) {
                            return 'Waktu Habis';
                        }

                        return 'Mulai Ujian';
                    })

                    // ==========================================
                    // ICON
                    // ==========================================
                    ->icon(function ($record) {

                        $sudahDikerjakan =
                            ExamResult::where('user_id', Auth::id())
                                ->where('exam_id', $record->id)
                                ->exists();

                        if ($sudahDikerjakan) {
                            return Heroicon::OutlinedCheckCircle;
                        }

                        if (self::waktuUjianHabis($record)) {
                            return Heroicon::OutlinedClock;
                        }

                        if (
                            $record->exact_time &&
                            $record->started_at &&
                            $record->started_at > now()
                        ) {
                            return Heroicon::OutlinedClock;
                        }

                        return Heroicon::OutlinedPaperAirplane;
                    })

                    // ==========================================
                    // WARNA
                    // ==========================================
                    ->color(function ($record) {

                        $sudahDikerjakan =
                            ExamResult::where('user_id', Auth::id())
                                ->where('exam_id', $record->id)
                                ->exists();

                        if ($sudahDikerjakan) {
                            return 'success';
                        }

                        if (self::waktuUjianHabis($record)) {
                            return 'danger';
                        }

                        if (
                            $record->exact_time &&
                            $record->started_at &&
                            $record->started_at > now()
                        ) {
                            return 'gray';
                        }

                        return 'primary';
                    })

                    ->button()

                    // ==========================================
                    // DISABLE TOMBOL
                    // ==========================================
                    ->disabled(function ($record) {

                        // Sudah dikerjakan
                        $sudahDikerjakan =
                            ExamResult::where('user_id', Auth::id())
                                ->where('exam_id', $record->id)
                                ->exists();

                        if ($sudahDikerjakan) {
                            return true;
                        }

                        // Belum waktunya
                        if (
                            $record->exact_time &&
                            $record->started_at &&
                            $record->started_at > now()
                        ) {
                            return true;
                        }

                        // Waktu sudah habis
                        if (self::waktuUjianHabis($record)) {
                            return true;
                        }

                        return false;
                    })

                    // ==========================================
                    // URL MULAI UJIAN
                    // ==========================================
                    ->url(function ($record) {

                        $sudahDikerjakan =
                            ExamResult::where('user_id', Auth::id())
                                ->where('exam_id', $record->id)
                                ->exists();

                        // Jangan bisa masuk lagi
                        if ($sudahDikerjakan) {
                            return null;
                        }

                        // Belum waktunya
                        if (
                            $record->exact_time &&
                            $record->started_at &&
                            $record->started_at > now()
                        ) {
                            return null;
                        }

                        // Waktu sudah habis
                        if (self::waktuUjianHabis($record)) {
                            return null;
                        }

                        return route(
                            StartingExam::getRouteName(),
                            ['exam' => $record]
                        );
                    }),

            ]);
    }

    // ==========================================
    // CEK APAKAH WAKTU UJIAN SUDAH HABIS
    // ==========================================
    protected static function waktuUjianHabis($record): bool
    {
        $now = now();

        // Kalau menggunakan waktu ujian
        if (
            $record->exact_time &&
            $record->started_at
        ) {
            $waktuSelesai = $record->started_at
                ->copy()
                ->addMinutes($record->duration);

            return $now > $waktuSelesai;
        }

        // Kalau menggunakan expired_at
        if ($record->expired_at) {
            return $now > $record->expired_at;
        }

        return false;
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageExams::route('/'),

            'mulai' => StartingExam::route('/{exam}/mulai'),
        ];
    }
}