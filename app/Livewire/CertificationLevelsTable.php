<?php

namespace App\Livewire;

use App\Models\CertificationFacility;
use App\Models\CertificationLevel;
use Livewire\Component;

class CertificationLevelsTable extends Component
{
    public CertificationFacility $facility;
    public bool $editMode = false;

    public function mount($facilityId)
    {
        $this->facility = CertificationFacility::with('certificationLevels')
            ->findOrFail($facilityId);
    }

    public function onEditClick() {
        $this->editMode = true;
    }

    public function onDeleteClick($level) {
        CertificationLevel::destroy($level);
        return redirect()->back()->with('success', 'Certification level deleted successfully.');
    }

    public function onSaveClick($level) {
        $certificationLevel = CertificationLevel::findOrFail($level);
        $certificationLevel->name = request()->input('name');
        $certificationLevel->abbreviation = request()->input('abbreviation');
        $certificationLevel->save();
        $this->editMode = false;
    }
    public function render()
    {
        $this->facility->load([
            'certificationLevels' => fn ($query) => $query->orderBy('level'),
        ]);

        return view('livewire.certification-levels-table', [
            'facility' => $this->facility,
        ]);
    }
}
