<!DOCTYPE html>
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Daftar Form Fund Request</title>
    <link href="{{ public_path('css/app.css') }}" rel="stylesheet">
    <style>
        @font-face {
            font-family: 'PTSans';
            src: url('{{ storage_path('fonts/PTSans-Regular.ttf') }}') format("truetype");
            font-weight: 400;
            font-style: normal;
        }

        * {
            font-family: 'PTSans', Arial, sans-serif !important;
        }

        .font-xl {
            font-size: 24px;
        }

        .font-l {
            font-size: 20px;
        }

        .font-m {
            font-size: 16px;
        }

        .font-sm {
            font-size: 12px;
        }

        .font-xsm {
            font-size: 8px;
        }
    </style>
</head>

<body style="background-color: #ffffff" class="font-sm">
    <table class="table table-borderless">
        <tbody>
            <tr>
                <td width="200" class="text-center p-0">
                    <img src="{{ public_path('images/logo.png') }}" alt="" height="80">
                </td>
                <td class="text-center text-uppercase p-0">
                    <div class="font-xl"><strong>Daftar Form Fund Request</strong></div>
                    <div class="font-l">SMP Cendekia BAZNAS</div>
                </td>
                <td width="200" class="text-center p-0">
                    <img src="{{ public_path('images/logo_baznas.png') }}" alt="" height="80">
                </td>
            </tr>
        </tbody>
    </table>
    <hr style="border: 0.5px #444444 solid">
    @if($request->frequency)
    <table class="table">
        <tbody>
            <tr class="text-left">
                <td width="5%" class="p-0">
                    @if($request->frequency == 'yearly')
                    Tahun
                    @elseif($request->frequency == 'monthly')
                    Bulan
                    @elseif($request->frequency == 'daily')
                    Tanggal
                    @endif
                </td>
                <td class="font-weight-bold p-0" width="15%">
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
    <table class="table table-bordered table-sm">
        <thead class="thead-dark">
            <tr>
                <th scope="col">No</th>
                <th scope="col">Number</th>
                <th scope="col">Nama</th>
                <th scope="col">Alokasi</th>
                <th scope="col">Budget Code</th>
                <th scope="col">Budget Name</th>
                <th scope="col">Tanggal</th>
                <th scope="col">Jumlah</th>
            </tr>
        </thead>
        <tbody>
            @php
            $i=1;
            @endphp
            @foreach($formRequests as $formRequest)
            @php
            $j = 1;
            @endphp
            @foreach ($formRequest->details as $detail)
            <tr>
                @if ($j == 1)
                <th rowspan="{{count($formRequest->details)}}" class="align-middle text-center">
                    {{ $i++ }}
                </th>
                @endif
                @if ($j == 1)
                <td rowspan="{{count($formRequest->details)}}" class="align-middle" style="word-wrap: break-word">
                    @if ($formRequest->number)
                    {{$formRequest->number}}
                    @else
                    {{$formRequest->invoice_number}}
                    @endif
                </td>
                @endif
                @if ($j == 1)
                <td rowspan=" {{count($formRequest->details)}}" class="align-middle" style="word-wrap: break-word">
                    {{$formRequest->pic()->first()->name}}
                </td>
                @endif
                @if ($j == 1)
                <td rowspan=" {{count($formRequest->details)}}" class="align-middle" style="word-wrap: break-word">
                    {{$formRequest->allocation}}
                </td>
                @endif
                <td class="align-middle">
                    {{$detail->budgetCode->code}}
                </td>
                <td class="align-middle">
                    {{$detail->budgetCode->name}}
                </td>
                @if ($j == 1)
                <td rowspan="{{count($formRequest->details)}}" class="align-middle">
                    @if ($formRequest->date)
                    {{$formRequest->date}}
                    @else
                    Belum Terbayarkan
                    @endif
                </td>
                @endif
                <td class="align-middle">
                    {{"Rp. " . number_format($detail->nominal, 2)}}
                </td>
            </tr>
            @php
            $j++;
            @endphp
            @endforeach
            @endforeach
            <tr>
                <td colspan="7" class="font-weight-bold table-dark">
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