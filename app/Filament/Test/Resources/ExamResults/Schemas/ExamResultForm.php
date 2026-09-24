<?php

namespace App\Filament\Test\Resources\ExamResults\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class ExamResultForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('user_id')
                    ->required()
                    ->numeric(),
                TextInput::make('exam_id')
                    ->required()
                    ->numeric(),
                TextInput::make('subject_id')
                    ->required()
                    ->numeric(),
                DateTimePicker::make('exam_date')
                    ->required(),
                TextInput::make('score')
                    ->required()
                    ->numeric()
                    ->default(0.0),
                TextInput::make('correct_answers')
                    ->required()
                    ->numeric()
                    ->default(0),
                TextInput::make('wrong_answers')
                    ->required()
                    ->numeric()
                    ->default(0),
            ]);
    }
}
