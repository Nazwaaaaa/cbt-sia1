<?php

namespace App\Filament\Test\Resources\ExamResults;

use App\Filament\Test\Resources\ExamResults\Pages\CreateExamResult;
use App\Filament\Test\Resources\ExamResults\Pages\EditExamResult;
use App\Filament\Test\Resources\ExamResults\Pages\ListExamResults;
use App\Filament\Test\Resources\ExamResults\Pages\ViewExamResult;
use App\Filament\Test\Resources\ExamResults\Schemas\ExamResultForm;
use App\Filament\Test\Resources\ExamResults\Tables\ExamResultsTable;
use App\Models\ExamResult;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class ExamResultResource extends Resource
{
    protected static ?string $model = ExamResult::class;

    protected static string|BackedEnum|null $navigationIcon =
        Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'score';

    protected static ?string $navigationLabel = 'Hasil Ujian';

    protected static ?string $modelLabel = 'Hasil Ujian';

    protected static ?string $pluralModelLabel = 'Hasil Ujian';

    /*
    |--------------------------------------------------------------------------
    | HANYA TAMPILKAN HASIL MILIK SISWA YANG LOGIN
    |--------------------------------------------------------------------------
    */

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->where('user_id', Auth::id());
    }

    public static function form(Schema $schema): Schema
    {
        return ExamResultForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ExamResultsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListExamResults::route('/'),

            'create' => CreateExamResult::route('/create'),

            'view' => ViewExamResult::route('/{record}'),

            'edit' => EditExamResult::route('/{record}/edit'),
        ];
    }
}