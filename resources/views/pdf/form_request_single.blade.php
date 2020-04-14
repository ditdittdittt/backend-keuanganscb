<!DOCTYPE html>
<html>

<head>
    <title>Form Request</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
</head>

<body>

    <div>
        <p class="text-center" style="font-size: 20; font-weight: bold">Form Request</p>
        <p>PIC : {{$formRequest->user->name}}</p>
        <p>Divisi : {{$formRequest->user->division}}</p>
        <p>Method : {{$formRequest->method}}</p>
        <p>Date :{{$formRequest->date}}</p>
        <p>Budget Code : {{$formRequest->budgetCode->code}}</p>
        <p>Budget name : {{$formRequest->budgetCode->name}}</p>
        <p>Allocation : {{$formRequest->allocation}}</p>
        <p>Amount : {{number_format($formRequest->amount, 2)}}</p>
        <p>Notes : {{$formRequest->notes}}</p>
    </div>

</body>

</html>