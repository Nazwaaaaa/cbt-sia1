<?php

namespace App\Filament\Resources\Questions\Schemas;

use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class QuestionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make() //ini buat card
                ->columns()
                ->columnSpanFull() //agar lebar konten full 1 baris
                ->schema([
                    Select::make('subject_id')
                        ->label('Pelajaran')
                        ->relationship('subject', 'name')
                        ->native(false)
                        // ->multiple()
                        ->preload()
                        ->required(),
                    TextInput::make('score')
                        ->label('Bobot Nilai')
                        ->required()
                        ->numeric()
                        ->default(1)
                        ->minValue(0),
                    // BAGIAN SOAL
                    RichEditor::make('payload')
                        ->label('Pertanyaan')
                        ->fileAttachmentsDisk('public')
                        ->fileAttachmentsDirectory('question-images')
                        ->required()
                        ->columnSpanFull(), 
                    Textarea::make('description')
                        ->label('Keterangan Tambahan')
                        ->columnSpanFull(),
                    Toggle::make('is_active')
                        ->label('Aktif?')
                        ->inline(false)
                        ->default(true)
                        ->required(),
                    ]),
                    
                    Section::make()
                        ->columnSpanFull()
                        ->schema([
                            Repeater::make('answers')
                                ->relationship()
                                ->label('Pilihan Jawaban')
                                ->addActionLabel('Tambah Pilihan Baru')
                                ->reorderable()
                                ->columns(2)
                                ->minItems(2)
                                ->schema([
                                    TextInput::make('text')
                                        ->label('Deskripsi Pilihan')
                                        ->required()
                                        ->columnSpanFull(),
                                    Toggle::make('is_active')
                                        ->label('Tersedia')
                                        ->default(True),
                                    Toggle::make('is_correct')
                                        ->label('Jawaban Benar'),
                                ]),
                    ]),
            ]);
    }
}
