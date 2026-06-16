<?php

namespace App\Livewire;

use App\Models\Faq;
use App\Models\FaqSetting;
use Livewire\Attributes\Url;
use Livewire\Component;

class Faqs extends Component
{
    #[Url(as: 'q')]
    public string $search = '';

    public function render()
    {
        $query = Faq::query()->published()->ordered();

        $search = trim($this->search);
        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('question', 'like', "%{$search}%")
                    ->orWhere('answer', 'like', "%{$search}%")
                    ->orWhere('category', 'like', "%{$search}%");
            });
        }

        $faqs = $query->get();

        return view('livewire.faqs', [
            'groupedFaqs' => $faqs->groupBy('category'),
            'lastUpdated' => Faq::published()->max('updated_at'),
            'heading' => FaqSetting::get('faq_heading'),
            'intro' => FaqSetting::get('faq_intro'),
        ]);
    }
}
