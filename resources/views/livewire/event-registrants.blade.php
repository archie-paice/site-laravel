<div>
    @unless (sizeof($registrants) == 0)
        <table x-data='$registrants' class='table table-zebra table-md w-max border-2 border-base-300'>
            <thead>
            <tr class='text-xl font-bold'>
                <th colspan='4'>Registrants</th>
                <th colspan='8'>
                </th>
            </tr>
            <tr colspan='4'>
                <th>Name</th>
                <th>Position Requested</th>
                <th>Start (GMT)</th>
                <th>End (GMT)</th>
                <th>Certifications</th>
                <th>Solo Certifications</th>
                <th>Final Start</th>
                <th>Final End</th>
                <th>Final Position</th>
            </tr>
            </thead>
            <tbody>
            <tr>
            @foreach ($registrants as $registrant)
                    <td class='border-r-1 border-base-300'>{{ $registrant->user->first_name }} {{ $registrant->user->last_name }}</td>
                    <td class='border-r-1 border-base-300'>{{ $registrant->requested_position }}</td>
                    <td class='border-r-1 border-base-300'>{{ $registrant->start }}</td>
                    <td class='border-r-1 border-base-300'>{{ $registrant->end }}</td>
                    <td class='border-r-1 border-base-300'>TODO</td>
                    <td class='border-r-1 border-base-300'>TODO</td>
                    <td class='border-r-1 border-base-300'>
                        <select name="presetPositions" class="select">
                            <option disabled selected>Select position</option>
                            <option value="">No position</option>
                            @foreach ($positions as $p)
                                <option value="{{ $p }}">
                                    {{ str_replace('_', ' ', $p) }}
                                </option>
                            @endforeach
                        </select>
                    </td>
            @endforeach
            </tr>
            </tbody>
        </table>
    @else
        <h1>There are no registered controllers.</h1>
    @endunless
</div>
