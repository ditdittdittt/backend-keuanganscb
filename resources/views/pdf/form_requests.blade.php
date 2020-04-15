<!DOCTYPE html>
<html>

<head>
    <title>Daftar Form Pengajuan</title>
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
        <h2>Daftar Form Pengajuan</h2>
        <h6>Sekolah Cendekia BAZNAS</h6>
    </center>

    <table class='table table-bordered'>
        <thead>
            <tr class='table-success'>
                <th>No</th>
                <th>Nama PIC</th>
                <th>Alokasi</th>
                <th>Jumlah</th>
                <th>Tanggal</th>
                <th>Catatan</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @php $i=1 @endphp
            @foreach($formRequests as $formRequest)
            <tr>
                <td>{{ $i++ }}</td>
                <td>{{$formRequest->user->name}}</td>
                <td>{{$formRequest->allocation}}</td>
                <td>{{"Rp. " . number_format($formRequest->amount, 2)}}</td>
                <td>{{$formRequest->date}}</td>
                <td>{{$formRequest->notes}}</td>
                <td>{{$formRequest->status->status}}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

</body>

</html>