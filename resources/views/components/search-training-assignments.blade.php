<form class="flex flex-col w-max">
    <div class='flex flex-col'>
        <label
            for="search"
            class="label">Search</label>

        <input
            type="text"
            name="search"
            class="input input-xs max-w-50"
            value="{{old('search', request()->input('search'))}}">
    </div>

    <div class='flex flex-col mt-2'>
        <label for="trainingType" class='label'>Training Type</label>
        <select name="trainingType" id="" class='select  max-w-50 select-xs'>
            <option value="">None</option>
            @foreach (App\Enums\TrainingType::cases() as $trainingType)
                <option 
                value='{{ $trainingType->value }}'
                @selected(request()->input('trainingType') == $trainingType->value)
                >{{ $trainingType->name }}</option>            
            @endforeach
        </select>
    </div>

    <button type="submit" class="btn btn-primary btn-sm mt-2 w-max">Search</button>
</form>
