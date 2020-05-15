<!DOCTYPE html>
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Form Submission {{ $formSubmission->number }}</title>
    <link href="{{ public_path('css/app.css') }}" rel="stylesheet">
    <style>
        @font-face {
            font-family: 'PTSerif';
            src: url('{{ storage_path('fonts/PTSerif-Regular.ttf') }}') format("truetype");
            font-weight: 400;
            font-style: normal;
        }
    </style>
</head>

<body style="background-color: #ffffff; font-family: 'PTSerif', Times, serif">
    {{-- HEADER --}}
    <table class=" table table-bordered">
        <tbody>
            <tr>
                <td width="75" class="text-center align-middle" style="padding:0.25rem">
                    <span class="font-weight-bold">No. Doc</span>
                </td>
                <td width="75" class="text-center align-middle" rowspan="2"
                    style="border-top-color:transparent; border-bottom-color:transparent; border-right-color:transparent; padding:0.25rem">
                    <img src="{{ public_path('images/logo.png') }}" alt="" width="75">
                </td>
                <td class="text-center align-middle" rowspan="2" style='border-color:transparent; padding:0.25rem'>
                    <h4>FORM FUND SUBMISSION</h4>
                </td>
                <td width="100" class="text-center align-middle" rowspan="2"
                    style='border-color:transparent; padding:0.25rem'>
                    <img src="{{ public_path('images/logo_baznas.png') }}" alt="" width="100">
                </td>
            </tr>
            <tr>
                <td class="text-center align-middle" style="padding:0.25rem">{{ $formSubmission->number }}</td>
            </tr>
        </tbody>
    </table>

    {{-- BODY --}}
    {{-- PIC Information --}}
    <table class="table table-bordered ">
        <thead class="text-center table-success">
            <tr>
                <th width="50%">
                    PIC
                </th>
                <th>
                    Division
                </th>
            </tr>
        </thead>
        <tbody class="text-center">
            <tr>
                <td>{{$formSubmission->pic()->first()->name}}</td>
                <td>{{$formSubmission->pic()->first()->division}}</td>
            </tr>
        </tbody>
    </table>

    {{-- Fund Req Information --}}
    <table class="table table-bordered">
        <thead class="text-center table-success">
            <tr>
                <th width="50%">
                    No. Doc Form Request
                </th>
                <th>
                    Date Form Request
                </th>
            </tr>
        </thead>
        <tbody class="text-center">
            <tr>
                <td>{{$formSubmission->formRequest->number}}</td>
                <td>{{$formSubmission->formRequest->date}}</td>
            </tr>
        </tbody>
    </table>

    {{-- Amount, Use and Balance --}}
    <table class="table table-bordered">
        <thead class="text-center table-success">
            <tr>
                <th width="30%">
                    Fund Req
                    <br>
                    ( permohonan dana )
                </th>
                <th width="30%">
                    Use
                    <br>
                    ( penggunaan dana )
                </th>
                <th width="30%">
                    Balancing
                    <br>
                    ( sisa dana )
                </th>
            </tr>
        </thead>
        <tbody class="text-center">
            <tr>
                <td>{{"Rp. " . number_format($formSubmission->formRequest->amount, 2)}}</td>
                <td>{{"Rp. " . number_format($formSubmission->used, 2)}}</td>
                <td>{{"Rp. " . number_format($formSubmission->balance, 2)}}</td>
            </tr>
        </tbody>
    </table>

    {{-- Allocation and notes --}}
    <table class="table table-bordered">
        <thead class="text-center table-success">
            <tr>
                <th width="50%">
                    Allocation
                </th>
                <th>
                    Notes
                </th>
            </tr>
        </thead>
        <tbody class="text-center">
            <tr>
                <td>{{$formSubmission->allocation}}</td>
                <td>{{$formSubmission->notes}}</td>
            </tr>
        </tbody>
    </table>

    {{-- SIGNATURE --}}
    <div class="container mt-5">
        <table class="table table-bordered mt-3">
            <thead class="text-center">
                <tr>
                    <th>PIC</th>
                    <th>Head Dept</th>
                    <th>Verificator</th>
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
                        @if (array_key_exists('head_dept', $pathArray))
                        <img src="{{public_path($pathArray['head_dept'])}}" alt="" height="50">
                        @endif
                    </td>
                    <td width="100px">
                        @if (array_key_exists('verificator', $pathArray))
                        <img src="{{public_path($pathArray['verificator'])}}" alt="" height="50">
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