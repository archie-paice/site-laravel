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
                <th>Final Position</th>
            </tr>
            </thead>
            <tbody>
            @foreach ($registrants as $registrant)
                <tr>
                    <td class='border-r-1 border-base-300'>{{ $registrant->user->first_name }} {{ $registrant->user->last_name }}</td>
                    <td class='border-r-1 border-base-300'>{{ $registrant->requested_position }}</td>
                    <td class='border-r-1 border-base-300'>{{ $registrant->start }}</td>
                    <td class='border-r-1 border-base-300'>{{ $registrant->end }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    @else
        <h1>There are no registered controllers.</h1>
    @endunless
</div>
