<?php

namespace App\Livewire;

use App\Models\CertificationLevel;
use Livewire\Component;

class CertificationLevelsTable extends Component
{
    public $facility;
    public bool $editMode = false;

    public function mount($facilityId)
    {
        $this->facility = CertificationLevel::where('facility_id', $facilityId)->first()->facility;
    }
    
    public function render()
    {
        $certificationLevels = CertificationLevel::where('facility_id', $this->facility->id)->get();
        return view('livewire.certification-levels-table', ['certificationLevels' => $certificationLevels]);
    }
}
