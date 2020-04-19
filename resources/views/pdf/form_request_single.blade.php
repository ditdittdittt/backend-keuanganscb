<!DOCTYPE html>
<html>

<head>
    <title>Form Request {{ $formRequest->id }}</title>
    <link href="{{ public_path('css/app.css') }}" rel="stylesheet">
    <style>
        .table th,
        .table td {
            padding: 0.25rem
        }
    </style>
</head>

<body style="background-color: #ffffff">
    <table class="table table-bordered">
        <tbody>
            <tr>
                <td width="75" class="text-center align-middle">
                    <span class="font-weight-bold">No. Doc</span>
                </td>
                <td width="75" class="text-center align-middle" rowspan="2" style="border-top-color:transparent; border-bottom-color:transparent; border-right-color:transparent">
                    <img src="{{ public_path('images/logo.png') }}" alt="" width="75">
                </td>
                <td class="text-center align-middle" rowspan="2" style='border-color:transparent'>
                    <h4>FORM FUND REQUEST</h4>
                </td>
                <td width="100" class="text-center align-middle" rowspan="2" style='border-color:transparent'>
                    <img src="{{ public_path('images/logo_baznas.png') }}" alt="" width="100">
                </td>
            </tr>
            <tr>
                <td class="text-center align-middle">{{ $formRequest->id }}</td>
            </tr>
        </tbody>
    </table>
    <hr style="border: 0.5px black solid">
    <table class="table table-bordered">
        <thead class="text-center table-success">
            <tr>
                <th>PIC</th>
                <th>Metode Pembayaran</th>
                <th>Divisi</th>
                <th>Date</th>
            </tr>
        </thead>
        <tbody class="text-center">
            <tr>
                <td width="25%" class="align-middle">
                    <!-- @if(strlen($formRequest->user->name) > 10)
                    @php
                    $splittedName = explode(" ", $formRequest->user->name);
                    if(count($splittedName) == 2){
                    $firstName = substr($splittedName[0], 0, 1);
                    $formRequest->user->name = $firstName . " " . $splittedName[1];
                    }
                    @endphp
                    @endif -->
                    {{ $formRequest->user->name }}
                </td>
                <td width="25%" class="align-middle">
                    {{ $formRequest->method }}
                </td>
                <td width="25%" class="align-middle">
                    {{ $formRequest->user->division }}
                </td>
                <td class="align-middle" width="25%">
                    {{ $formRequest->date }}
                </td>
            </tr>
        </tbody>
    </table>
    <table class="table table-bordered">
        <thead class="text-center table-success">
            <tr>
                <th>Budget Code</th>
                <th>Budget Name</th>
            </tr>
        </thead>
        <tbody class="text-center">
            <tr>
                <td width="50%">{{ $formRequest->budgetCode->code }}</td>
                <td width="50%">{{ $formRequest->budgetCode->name }}</td>
            </tr>
        </tbody>
    </table>
    <table class="table table-bordered text-center">
        <thead class="table-success">
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
    <table class="table table-bordered text-center">
        <thead class="table-success">
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
    <table class="table table-bordered">
        <thead class="text-center table-success">
            <tr>
                <th>Attachment</th>
                <th>Notes</th>
            </tr>
        </thead>
        <tbody class="text-center">
            <tr>
                <td width="50%" class="align-middle">{{ $formRequest->attachment }}</td>
                <td width="50%" class="align-middle">{{ $formRequest->notes }}</td>
            </tr>
        </tbody>
    </table>
    <div class="container mt-5">
        <table class="table table-bordered mt-5">
            <thead class="text-center">
                <tr>
                    <th>PIC</th>
                    <th>Head Dept</th>
                    <th>Verificator</th>
                    <th>Head Office</th>
                </tr>
            </thead>
            <tbody class="text-center">
                <tr>
                    <td height="50" width="100px"></td>
                    <td width="100px"></td>
                    <td width="100px"></td>
                    <td width="100px"></td>
                </tr>
            </tbody>
        </table>
    </div>
</body>

</html>