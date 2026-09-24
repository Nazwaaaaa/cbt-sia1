<?php

namespace App\Filament\Test\Resources\ExamResults\Pages;

use App\Filament\Test\Resources\ExamResults\ExamResultResource;
use Filament\Resources\Pages\ViewRecord;

class ViewExamResult extends ViewRecord
{
    protected static string $resource = ExamResultResource::class;

    protected string $view =
        'filament.test.resources.exam-results.pages.view-exam-result';

    protected function mutateFormDataBeforeFill(array $data): array
    {
        return $data;
    }

    public function mount(int|string $record): void
    {
        parent::mount($record);

        $this->record->load([
            'user',
            'exam',
            'subject',
            'answers.question.answers',
            'answers.answer',
        ]);
    }
}