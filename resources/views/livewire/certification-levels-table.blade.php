<table class='table table-compact w-full mt-5'>
    <thead>
        <tr>
            <th>Name</th>
            <th>Abbreviation</th>
            <th>Level</th>
            <th>Actions</th>
        </tr>
    </thead>
    @if(count($facility->certificationLevels) == 0)
        <tr>
            <td colspan='1'>No certification levels defined.</td>
        </tr>
    @endif

    @foreach($facility->certificationLevels as $level)
        <tr>
            @livewire('certification-level-row', ['certificationLevel' => $level], key($level->id))
        </tr>
    @endforeach
</table>
