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
            <td>
                <input @disabled(!$editMode) class='input input-sm' type="text" value='{{ $level->name }}'>
            </td>
            <td>
                <input @disabled(!$editMode) class='input input-sm max-w-20' type="text" value='{{ $level->abbreviation }}'>
            </td>
            
            <td>
                <input @disabled(!$editMode) class='input input-sm max-w-20' type="number" value='{{ $level->level }}'>
            </td>
            <td>
                <button class='btn btn-xs btn-warning'>Edit</button>
                <button class='btn btn-xs btn-error'>Delete</button>
            </td>
        </tr>
    @endforeach
</table>