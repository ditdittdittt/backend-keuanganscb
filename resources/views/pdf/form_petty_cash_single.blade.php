<!DOCTYPE html>
<html>

<head>
    <title>Form Petty Cash {{ $formPettyCash->number }}</title>
    <link href="{{ public_path('css/app.css') }}" rel="stylesheet">
    <style>
        .table th,
        .table td {
            padding: 0.25rem
        }
    </style>
</head>

<body style="background-color: #ffffff">
    <table class="table table-borderless">
        <tbody>
            <tr>
                <td width="25%" class="text-center align-middle">
                    <img src="{{ public_path('images/logo.png') }}" alt="" width="75">
                </td>
                <td width="75%" class="text-center align-middle">
                    <h4>FORM PETTY CASH</h4>
                </td>
                <td width="25%" class="text-center align-middle">
                    <img src="{{ public_path('images/logo_baznas.png') }}" alt="" width="100">
                </td>
            </tr>
        </tbody>
    </table>
    <table class="table table-bordered text-center">
        <thead>
            <tr class="table-success">
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
                    {{ $formPettyCash->user->name }}
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

    <table class="table table-bordered budget">
        <thead class="text-center table-secondary">
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
                <td colspan="2" class="table-secondary font-weight-bold">
                    Total
                </td>
                <td class="text-right">
                    {{ "Rp. " . number_format($formPettyCash->amount, 2) }}
                </td>
            </tr>
        </tbody>
    </table>
    <table class="table table-bordered">
        <thead class="table-success">
            <tr class="text-center">
                <th>Allocation</th>
                <th>Notes</th>
            </tr>
        </thead>
        <tbody>
            <tr class="text-center">
                <td width="50%">{{ $formPettyCash->allocation }}</td>
                <td width="50%">{{ $formPettyCash->notes }}</td>
            </tr>
        </tbody>
    </table>
    <div class="container mt-5">
        <table class="table table-bordered mt-5">
            <thead class="text-center">
                <tr>
                    <th>PIC</th>
                    <th>Manager Ops</th>
                    <th>Cashier</th>
                </tr>
            </thead>
            <tbody class="text-center">
                <tr>
                    <td height="50" width="50%"></td>
                    <td width="50%"></td>
                    <td width="50%"></td>
                </tr>
            </tbody>
        </table>
    </div>
</body>

</html>