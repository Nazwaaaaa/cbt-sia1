<?php

namespace App\Filament\Resources\ExamResults\Tables;

use App\Filament\Resources\ExamResults\ExamResultResource;
use Filament\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ExamResultsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([

                TextColumn::make('user.name')
                    ->label('Siswa')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('exam.title')
                    ->label('Ujian')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('subject.name')
                    ->label('Mata Pelajaran')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('exam_date')
                    ->label('Tanggal Ujian')
                    ->dateTime('d M Y H:i:s')
                    ->sortable(),

                TextColumn::make('score')
                    ->label('Nilai')
                    ->numeric(decimalPlaces: 2)
                    ->sortable(),

                TextColumn::make('correct_answers')
                    ->label('Jawaban Benar')
                    ->sortable(),

                TextColumn::make('wrong_answers')
                    ->label('Jawaban Salah')
                    ->sortable(),

            ])

            ->recordActions([

                Action::make('detail')
                    ->label('Detail')
                    ->icon('heroicon-o-eye')
                    ->url(
                        fn ($record) => ExamResultResource::getUrl(
                            'view',
                            [
                                'record' => $record,
                            ]
                        )
                    ),

            ]);
    }
}