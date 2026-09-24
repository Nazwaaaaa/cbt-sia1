<?php

namespace App\Filament\Test\Resources\Exams\Pages;

use App\Filament\Test\Resources\Exams\ExamResource;
use App\Models\ExamResult as ExamResultModel;
use Filament\Resources\Pages\Page;
use Illuminate\Support\Facades\Auth;

class ExamResult extends Page
{
    protected static string $resource = ExamResource::class;

    protected string $view = 'filament.test.resources.exams.pages.exam-result';

    public ExamResultModel $result;

    public function mount(int $result): void
    {
        $this->result = ExamResultModel::with([
            'exam',
            'subject',
        ])
        ->where('user_id', Auth::id())
        ->findOrFail($result);
    }
}