<!DOCTYPE html>
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Form Petty Cash {{ $formPettyCash->number }}</title>
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
    <table class="table table-bordered table-sm">
        <thead class="thead-dark">
            <tr>
                <th width="50%">Payable To</th>
                <th width="25%">
                    Date
                </th>
                <th width="25%">
                    No. Doc
                </th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>
                    {{ $formPettyCash->pic()->first()->name }}
                </td>
                <td class="align-middle">
                    {{ $formPettyCash->date }}
                </td>
                <td class="align-middle">
                    {{ $formPettyCash->number }}
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
            @foreach($formPettyCash->details as $detail)
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
                <td colspan="2" class="font-weight-bold">
                    Total
                </td>
                <td class="text-right">
                    {{ "Rp. " . number_format($formPettyCash->amount, 2) }}
                </td>
            </tr>
        </tbody>
    </table>
    <table class="table table-bordered table-sm">
        <thead class="thead-dark">
            <tr>
                <th>Allocation</th>
                <th>Notes</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td width="50%">{{ $formPettyCash->allocation }}</td>
                <td width="50%">{{ $formPettyCash->notes }}</td>
            </tr>
        </tbody>
    </table>
    <div class="container">
        <table class="table table-bordered table-sm">
            <thead class="thead-light text-center">
                <tr>
                    <th>PIC</th>
                    <th>Manager Ops</th>
                    <th>Cashier</th>
                </tr>
            </thead>
            <tbody class="text-center">
                <tr>
                    <td height="50" width="100px">
                        @if (array_key_exists('pic', $pathArray))
                        <img src="{{public_path($pathArray['pic'])}}" alt="" height="50">
                        @endif
                    </td>
                    <td width="100px">
                        @if (array_key_exists('manager_ops', $pathArray))
                        <img src="{{public_path($pathArray['manager_ops'])}}" alt="" height="50">
                        @endif
                    </td>
                    <td width="100px">
                        @if (array_key_exists('cashier', $pathArray))
                        <img src="{{public_path($pathArray['cashier'])}}" alt="" height="50">
                        @endif
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</body>

</html>
