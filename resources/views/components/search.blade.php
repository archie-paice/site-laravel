<form class="flex flex-col">
    <label
        for="search"
        class="label">Search</label>

    <input type="hidden" name="page" value="1">

    <input
        type="text"
        name="search"
        class="input input-xs max-w-50"
        value="{{old('search', request()->input('search'))}}">

    <button type="submit" class="btn btn-primary btn-sm mt-2 w-max">Search</button>
</form>
