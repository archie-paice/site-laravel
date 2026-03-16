<?php

namespace App\Livewire;

use App\Models\CertificationFacility;
use Livewire\Attributes\On;
use Livewire\Component;

class CertificationLevelsTable extends Component
{
    public CertificationFacility $facility;
    public bool $facilityEditMode = false;

    public function mount(int $facilityId): void
    {
        $this->facility = CertificationFacility::with('certificationLevels')
            ->findOrFail($facilityId);
    }

    #[On('certification-level-saved')]
    public function handleSaved(): void
    {
        $this->refreshLevels();
    }

    #[On('certification-level-deleted')]
    public function handleDeleted(): void
    {
        $this->refreshLevels();
    }

    public function refreshLevels(): void
    {
        $this->facility->refresh();
    }

    public function render()
    {
        $this->facility->load([
            'certificationLevels' => fn ($query) => $query->orderBy('level', 'desc'),
        ]);

        return view('livewire.certification-levels-table', [
            'facility' => $this->facility,
        ]);
    }
}
