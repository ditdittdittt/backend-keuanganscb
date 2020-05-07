<!DOCTYPE html>
<html>

<head>
    <title>Daftar Form Fund Request</title>
    <link href="{{ public_path('css/app.css') }}" rel="stylesheet">
</head>

<body style="background-color: #ffffff;">
    <table class="table table-borderless">
        <tbody>
            <tr>
                <td width="200" class="text-center align-middle" style="padding : 0">
                    <img src="{{ public_path('images/logo.png') }}" alt="" width="100">
                </td>
                <td class="text-center align-middle" style="padding : 0">
                    <h1>Daftar Form Fund Request</h1>
                    <h5>SMP Cendekia BAZNAS</h5>
                </td>
                <td width="200" class="text-center align-middle" style="padding : 0">
                    <img src="{{ public_path('images/logo_baznas.png') }}" alt="" width="125">
                </td>
            </tr>
        </tbody>
    </table>
    <hr>
    @if($request->frequency)
    <table class="table table-borderless">
        <tbody>
            <tr>
                <td width="5%" style="padding:0" class="text-left">
                    @if($request->frequency == 'yearly')
                    Tahun
                    @elseif($request->frequency == 'monthly')
                    Bulan
                    @elseif($request->frequency == 'daily')
                    Tanggal
                    @endif
                </td>
                <td class="font-weight-bold text-left" style="padding:0" width="15%">
                    @if($request->frequency == 'yearly')
                    {{ $request->year }}
                    @elseif($request->frequency == 'monthly')
                    {{ $request->month }} {{ $request->year }}
                    @elseif($request->frequency == 'daily')
                    {{ $request->date }}
                    @endif
                </td>
                <td width="80%">

                </td>
            </tr>
        </tbody>
    </table>
    @endif
    <table class='table table-bordered'>
        <thead>
            <tr class='table-success'>
                <th>No</th>
                <th>Nama</th>
                <th>Alokasi</th>
                <th>Budget Code</th>
                <th>Budget Name</th>
                <th>Tanggal</th>
                <th>Jumlah</th>
            </tr>
        </thead>
        <tbody>
            @php
            $i=1;
            @endphp
            @foreach($formRequests as $formRequest)
            <tr>
                <td>{{ $i++ }}</td>
                <td>{{$formRequest->users()->wherePivot('role_name', 'pic')->first()->name}}</td>
                <td>{{$formRequest->allocation}}</td>
                <td>{{$formRequest->budgetCode->code}}</td>
                <td>{{$formRequest->budgetCode->name}}</td>
                <td>{{$formRequest->date}}</td>
                <td>{{"Rp. " . number_format($formRequest->amount, 2)}}</td>
            </tr>
            @endforeach
            <tr>
                <td colspan="6" class="table-success font-weight-bold">
                    Total
                </td>
                <td>
                    {{"Rp. " . number_format($totalAmount, 2)}}
                </td>
            </tr>
        </tbody>
    </table>
</body>

</html>