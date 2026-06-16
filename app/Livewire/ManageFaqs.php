<?php

namespace App\Livewire;

use App\Models\Faq;
use App\Models\FaqSetting;
use Illuminate\Support\Str;
use Livewire\Component;

class ManageFaqs extends Component
{
    public ?int $editingId = null;

    public string $category = 'General Info';
    public string $newCategory = '';
    public string $question = '';
    public string $answer = '';
    public bool $is_published = true;

    public bool $showForm = false;
    public string $search = '';

    public ?string $renamingCategory = null;
    public string $categoryNewName = '';

    // Public page header text
    public bool $editingHeader = false;
    public string $pageHeading = '';
    public string $pageIntro = '';

    public function mount(): void
    {
        $this->pageHeading = FaqSetting::get('faq_heading');
        $this->pageIntro = FaqSetting::get('faq_intro');
    }

    public function savePageHeader(): void
    {
        $this->validate([
            'pageHeading' => 'required|string|max:255',
            'pageIntro' => 'nullable|string|max:2000',
        ]);

        FaqSetting::set('faq_heading', trim($this->pageHeading));
        FaqSetting::set('faq_intro', trim($this->pageIntro));

        $this->editingHeader = false;
        $this->dispatch('flash-message', message: 'FAQ page header updated.');
    }

    public function cancelHeader(): void
    {
        $this->pageHeading = FaqSetting::get('faq_heading');
        $this->pageIntro = FaqSetting::get('faq_intro');
        $this->editingHeader = false;
        $this->resetValidation();
    }

    protected function rules(): array
    {
        return [
            'category' => 'required|string|max:255',
            'question' => 'required|string|max:255',
            'answer' => 'required|string',
            'is_published' => 'boolean',
        ];
    }

    public function create(): void
    {
        $this->resetForm();
        $this->showForm = true;
    }

    public function edit(int $id): void
    {
        $faq = Faq::findOrFail($id);

        $this->editingId = $faq->id;
        $this->category = $faq->category;
        $this->question = $faq->question;
        $this->answer = $faq->answer;
        $this->is_published = $faq->is_published;
        $this->showForm = true;
    }

    public function save(): void
    {
        // Resolve a freshly-typed category before validating the rest.
        if ($this->category === '__new__') {
            $this->validate(
                ['newCategory' => 'required|string|max:255'],
                ['newCategory.required' => 'Please enter a category name.'],
            );
            $this->category = trim($this->newCategory);
        }

        $validated = $this->validate();

        if ($this->editingId) {
            Faq::findOrFail($this->editingId)->update($validated);
            $this->dispatch('flash-message', message: 'FAQ updated successfully.');
        } else {
            // New FAQs go to the bottom of their category.
            $validated['sort_order'] = (int) Faq::where('category', $this->category)->max('sort_order') + 1;
            Faq::create($validated);
            $this->dispatch('flash-message', message: 'FAQ created successfully.');
        }

        $this->resetForm();
    }

    public function togglePublished(int $id): void
    {
        $faq = Faq::findOrFail($id);
        $faq->update(['is_published' => ! $faq->is_published]);
    }

    public function delete(int $id): void
    {
        Faq::destroy($id);
        $this->dispatch('flash-message', message: 'FAQ deleted.');

        if ($this->editingId === $id) {
            $this->resetForm();
        }
    }

    public function moveUp(int $id): void
    {
        $this->move($id, -1);
    }

    public function moveDown(int $id): void
    {
        $this->move($id, 1);
    }

    /**
     * Move a FAQ up or down within its own category, normalising sort_order
     * to a clean 1..n sequence as it goes.
     */
    private function move(int $id, int $direction): void
    {
        $faq = Faq::findOrFail($id);

        $items = Faq::where('category', $faq->category)
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get()
            ->values();

        $index = $items->search(fn ($f) => $f->id === $id);
        $target = $index + $direction;

        if ($index === false || $target < 0 || $target >= $items->count()) {
            return;
        }

        // Swap the two rows, then persist a sequential order.
        $swap = $items[$index];
        $items[$index] = $items[$target];
        $items[$target] = $swap;

        foreach ($items as $position => $item) {
            $desired = $position + 1;
            if ($item->sort_order !== $desired) {
                $item->update(['sort_order' => $desired]);
            }
        }
    }

    public function startRename(string $category): void
    {
        $this->renamingCategory = $category;
        $this->categoryNewName = $category;
        $this->resetValidation();
    }

    public function cancelRename(): void
    {
        $this->reset(['renamingCategory', 'categoryNewName']);
        $this->resetValidation();
    }

    public function saveRename(): void
    {
        $this->validate(
            ['categoryNewName' => 'required|string|max:255'],
            ['categoryNewName.required' => 'Please enter a category name.'],
        );

        $old = $this->renamingCategory;
        $new = trim($this->categoryNewName);

        if ($old !== null && $new !== '' && $new !== $old) {
            Faq::where('category', $old)->get()->each->update(['category' => $new]);
            $this->dispatch('flash-message', message: "Category renamed to \"{$new}\".");
        }

        $this->cancelRename();
    }

    public function deleteCategory(string $category): void
    {
        Faq::where('category', $category)->get()->each->delete();
        $this->dispatch('flash-message', message: "Category \"{$category}\" and its FAQs were deleted.");

        if ($this->renamingCategory === $category) {
            $this->cancelRename();
        }
    }

    public function cancel(): void
    {
        $this->resetForm();
    }

    private function resetForm(): void
    {
        $this->reset(['editingId', 'category', 'newCategory', 'question', 'answer', 'is_published', 'showForm']);
        $this->category = 'General Info';
        $this->is_published = true;
        $this->resetValidation();
    }

    public function render()
    {
        $query = Faq::ordered();

        $search = trim($this->search);
        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('question', 'like', "%{$search}%")
                    ->orWhere('answer', 'like', "%{$search}%")
                    ->orWhere('category', 'like', "%{$search}%");
            });
        }

        $faqs = $query->get();

        $categories = Faq::query()
            ->distinct()
            ->pluck('category')
            ->merge(['Getting Started', 'Training', 'General Info'])
            ->unique()
            ->sort()
            ->values();

        return view('livewire.manage-faqs', [
            'groupedFaqs' => $faqs->groupBy('category'),
            'categories' => $categories,
            'total' => Faq::count(),
            'publishedCount' => Faq::published()->count(),
            'answerPreview' => trim($this->answer) !== ''
                ? Str::markdown($this->answer, ['html_input' => 'strip', 'allow_unsafe_links' => false])
                : null,
        ]);
    }
}
