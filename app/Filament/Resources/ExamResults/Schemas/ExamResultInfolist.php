<?php

namespace App\Filament\Resources\ExamResults\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class ExamResultInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([

                TextEntry::make('user.name')
                    ->label('Siswa'),

                TextEntry::make('exam.title')
                    ->label('Ujian'),

                TextEntry::make('subject.name')
                    ->label('Mata Pelajaran'),

                TextEntry::make('exam_date')
                    ->label('Tanggal Ujian')
                    ->dateTime('d M Y H:i:s'),

                TextEntry::make('score')
                    ->label('Nilai'),

                TextEntry::make('correct_answers')
                    ->label('Jawaban Benar'),

                TextEntry::make('wrong_answers')
                    ->label('Jawaban Salah'),

                TextEntry::make('created_at')
                    ->label('Dibuat Pada')
                    ->dateTime('d M Y H:i:s'),

                TextEntry::make('updated_at')
                    ->label('Diperbarui Pada')
                    ->dateTime('d M Y H:i:s'),

            ]);
    }
}