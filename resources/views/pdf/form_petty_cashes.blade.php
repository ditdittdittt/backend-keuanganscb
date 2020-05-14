<!DOCTYPE html>
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Daftar Form Petty Cash</title>
    <link href="{{ public_path('css/app.css') }}" rel="stylesheet">
</head>

<body style="background-color:#ffffff">
    <table class="table table-borderless">
        <tbody>
            <tr>
                <td width="200" class="text-center align-middle" style="padding : 0">
                    <img src="{{ public_path('images/logo.png') }}" alt="" width="100">
                </td>
                <td class="text-center align-middle" style="padding : 0">
                    <h1>Daftar Form Petty Cash</h1>
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
                <th>Status</th>
                <th>Tanggal</th>
                <th>Jumlah</th>
            </tr>
        </thead>
        <tbody>
            @php
            $i = 1
            @endphp
            @foreach($formPettyCashes as $formPettyCash)
            <tr>
                <td>{{ $i++ }}</td>
                <td>{{$formPettyCash->user->name}}</td>
                <td>{{$formPettyCash->allocation}}</td>
                <td>{{$formPettyCash->status->status}}</td>
                <td>{{$formPettyCash->date}}</td>
                <td>{{"Rp. " . number_format($formPettyCash->amount, 2)}}</td>
            </tr>
            @endforeach
            <tr>
                <td colspan="5" class="table-success font-weight-bold">
                    Total
                </td>
                <td>
                    {{"Rp. " . number_format($total, 2)}}
                </td>
            </tr>
        </tbody>
    </table>

</body>

</html>
