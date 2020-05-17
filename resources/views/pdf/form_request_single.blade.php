<!DOCTYPE html>
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Form Request {{ $formRequest->number }}</title>
    <link href="{{ public_path('css/app.css') }}" rel="stylesheet">
    <style>
        @font-face {
            font-family: 'PTSerif';
            src: url('{{ storage_path('fonts/PTSerif-Regular.ttf') }}') format("truetype");
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

<body style="background-color: #ffffff; font-family: 'PTSerif', Times, serif" class="font-sm">
    <table class="table table-sm taber-borderless">
        <tbody>
            <tr>
                <td width="70" class="text-center align-middle p-0" rowspan="2"
                    style="border-top-color:transparent; border-bottom-color:transparent; border-right-color:transparent">
                    <img src="{{ public_path('images/logo.png') }}" alt="" width="75">
                </td>
                <td class="text-center align-middle p-0" rowspan="2" style='border-color:transparent'>
                    <span class="font-l"><strong>FORM FUND REQUEST</strong></span>
                </td>
                <td width="70" class="text-center align-middle p-0" rowspan="2" style='border-color:transparent'>
                    <img src="{{ public_path('images/logo_baznas.png') }}" alt="" width="100">
                </td>
            </tr>
        </tbody>
    </table>
    <hr style="border: 0.5px #444444 solid">
    <table class="table table-bordered table-sm">
        <thead class="thead-dark">
            <tr>
                <th>PIC</th>
                <th>Metode Pembayaran</th>
                <th>Divisi</th>
                <th>Date</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td width="25%">
                    <!-- @if(strlen($formRequest->users()->wherePivot('role_name', 'pic')->first()->name) > 10)
                    @php
                    $splittedName = explode(" ", $formRequest->user->name);
                    if(count($splittedName) == 2){
                    $firstName = substr($splittedName[0], 0, 1);
                    $formRequest->user->name = $firstName . " " . $splittedName[1];
                    }
                    @endphp
                    @endif -->
                    {{ $formRequest->users()->wherePivot('role_name', 'pic')->first()->name }}
                </td>
                <td width="25%">
                    {{ $formRequest->method }}
                </td>
                <td width="25%">
                    {{ $formRequest->users()->wherePivot('role_name', 'pic')->first()->division }}
                </td>
                <td width="25%">
                    {{ $formRequest->date }}
                </td>
            </tr>
        </tbody>
    </table>
    <table class="table table-bordered table-sm budget">
        <thead class="thead-dark">
            <tr>
                <th width="25%">
                    Budget Code
                </th>
                <th width="50%">
                    Budget Name
                </th>
                <th>
                    Nominal
                </th>
            </tr>
        </thead>
        <tbody>
            @foreach($formRequest->details as $detail)
            <tr>
                <td>
                    {{ $detail->budgetCode->code }}
                </td>
                <td>
                    {{ $detail->budgetCode->name }}
                </td>
                <td class="text-right">
                    {{ number_format($detail->nominal,2) }}
                </td>
            </tr>
            @endforeach
            <tr>
                <td colspan="2" class="thead-light font-weight-bold">
                    Total
                </td>
                <td class="text-right">
                    {{ "Rp. " . number_format($formRequest->amount, 2) }}
                </td>
            </tr>
        </tbody>
    </table>
    <table class="table table-bordered table-sm">
        <thead class="thead-dark">
            <tr>
                <th>Allocation</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>{{ $formRequest->allocation }}</td>
            </tr>
        </tbody>
    </table>
    <table class="table table-bordered table-sm">
        <thead class="thead-dark">
            <tr>
                <th>Jumlah</th>
                <th>Saldo Anggaran</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td width="50%">{{ "Rp. " . number_format($formRequest->amount, 2) }}</td>
                <td width="50%">{{ "Rp. " . number_format($formRequest->amount, 2) }}</td>
            </tr>
        </tbody>
    </table>
    <table class="table table-bordered table-sm">
        <thead class="text-center thead-dark">
            <tr>
                <th>Attachment</th>
                <th>Notes</th>
            </tr>
        </thead>
        <tbody class="text-center">
            <tr>
                <td width="50%">{{ $formRequest->attachment }}</td>
                <td width="50%">{{ $formRequest->notes }}</td>
            </tr>
        </tbody>
    </table>
    <table class="table table-bordered mt-5">
        <thead class="thead-light text-center">
            <tr>
                <th>PIC</th>
                <th>Head Dept</th>
                <th>Verificator</th>
                <th>Head Office</th>
                <th>Cashier</th>
            </tr>
        </thead>
        <tbody class="text-center">
            <tr>
                <td height="50" width="100px">
                    @if (array_key_exists("pic", $arrayOfPath))
                    <img src="{{ public_path( $arrayOfPath['pic'] ) }}" height="50">
                    @endif
                </td>
                <td width="100px">
                    @if (array_key_exists("head_dept", $arrayOfPath)) <img
                        src="{{ public_path( $arrayOfPath['head_dept'] ) }}" height="50">
                    @endif
                </td>
                <td width="100px">
                    @if (array_key_exists("verificator", $arrayOfPath)) <img
                        src="{{ public_path( $arrayOfPath['verificator'] ) }}" height="50">
                    @endif
                </td>
                <td width="100px">
                    @if (array_key_exists("head_office", $arrayOfPath)) <img
                        src="{{ public_path( $arrayOfPath['head_office'] ) }}" height="50">
                    @endif
                </td>
                <td width="100px">
                    @if (array_key_exists("cashier", $arrayOfPath)) <img
                        src="{{ public_path( $arrayOfPath['cashier'] ) }}" height="50">
                    @endif
                </td>
            </tr>
        </tbody>
    </table>
</body>

</html>