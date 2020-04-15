<!DOCTYPE html>
<html>

<head>
    <title>Daftar Form Petty Cash</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
</head>

<body>
    <style type="text/css">
        table tr td,
        table tr th {
            font-size: 9pt;
        }
    </style>
    <center>
        <h2>Daftar Form Petty Cash</h2>
        <h6>Sekolah Cendekia BAZNAS</h6>
    </center>

    <table class='table table-bordered'>
        <thead>
            <tr class='table-success'>
                <th>No</th>
                <th>Nama</th>
                <th>Alokasi</th>
                <th>Jumlah</th>
                <th>Tanggal</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @php $i=1 @endphp
            @foreach($formPettyCashes as $formPettyCash)
            <tr>
                <td>{{ $i++ }}</td>
                <td>{{$formPettyCash->user->name}}</td>
                <td>{{$formPettyCash->allocation}}</td>
                <td>{{"Rp. " . number_format($formPettyCash->amount, 2)}}</td>
                <td>{{$formPettyCash->date}}</td>
                <td>{{$formPettyCash->status->status}}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

</body>

</html>