<table class="table table-bordered" ng-show="schedule_generated">

    <thead>

    <tr  style="text-align: center ;">
        <th></th>
        <th></th>
        <th></th>
        <th>Isgh Schedule</th>
        <th></th>
        <th></th>
        <th></th>
        <th></th>
        <th></th>
        <th></th>
        <th></th>
        <th></th>
    </tr>
    <tr  style="text-align: center">
        <th>centers</th>
        @foreach($fridays as $f)
            <th style="border-right: 1px solid #FFFFFF">{{ $f }}</th>
        @endforeach
    </tr>

    </thead>

    <tbody  style="text-align: center">


    {!! $data !!}

    </tbody>

</table>