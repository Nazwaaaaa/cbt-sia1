<?php

namespace App\Filament\Test\Resources\ExamResults\Tables;

use App\Filament\Test\Resources\ExamResults\ExamResultResource;
use Filament\Actions\Action;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;

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
                    ->dateTime('d M Y H:i')
                    ->sortable(),

                TextColumn::make('score')
                    ->label('Score')
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

                // DETAIL
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