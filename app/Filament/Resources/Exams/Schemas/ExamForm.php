<?php

namespace App\Filament\Resources\Exams\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;

class ExamForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make()
                    ->columns()
                    ->columnSpanFull()
                    ->schema([
                        TextInput::make('title')
                            ->label('Jenis Ujian')
                            ->required()
                            ->placeholder('Contoh: Ujian Tengah Semester'),

                        TextInput::make('duration')
                            ->label('Durasi Ujian')
                            ->required()
                            ->numeric(),

                        TextInput::make('threshold')
                            ->label('Batas Nilai')
                            ->required()
                            ->numeric()
                            ->default(50.0),

                        Toggle::make('exact_time')
                            ->label('Waktu Ujian')
                            ->live()
                            ->required(),

                        // WAKTU MULAI TETAP SEPERTI SEBELUMNYA
                        DateTimePicker::make('started_at')
                            ->label('Waktu Mulai')
                            ->required(),

                        // HANYA BAGIAN INI YANG DIUBAH
                        DateTimePicker::make('expired_at')
                            ->label('Waktu Berakhir')
                            ->seconds(false)
                            ->native(false)
                            ->displayFormat('d F Y, H:i'),

                        Toggle::make('is_available')
                            ->required(),
                    ]),
            ]);
    }
}