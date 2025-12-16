<?php

namespace App\Livewire;

use App\Models\TrainingAssignment;
use Livewire\Component;
use Livewire\WithPagination;

class TrainingAssignmentsTable extends Component
{
    use WithPagination;
    private mixed $assignments;
    public bool $includeInactive = false;

    public function updateAssignments() {
        if ($this->includeInactive) {
            $this->assignments = TrainingAssignment::orderBy("created_at", "desc")->paginate(25);
        } else {
            $this->assignments = TrainingAssignment::where([
                "active" => true
            ])->orderBy("created_at", "desc")->paginate(25);
        }
    }

    public function render()
    {
        $this->updateAssignments();
        return view('livewire.training-assignments-table', ["trainingAssignments" => $this->assignments]);
    }
}
