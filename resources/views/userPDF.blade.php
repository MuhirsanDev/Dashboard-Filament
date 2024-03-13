<!DOCTYPE html>
<html>
<head>
    <title>Latihan PDF Filament</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
</head>
<body>
    <h1>Asa Developer</h1>
    <p>{{ $date }}</p>

    <table class="table table-bordered" border=1>
        <thead>
            <tr border=1>
                <th>No</th>
                <th>Name</th>
                <th>Email</th>
            </tr>
        </thead>
        <tbody> @php
            $no =1 ;
        @endphp
            @foreach($users as $user)
        <tr border=1>
            <td>{{ $no++ }}</td>
            <td>{{ $user->name }}</td>
            <td>{{ $user->email }}</td>
        </tr>
        @endforeach
        </tbody>


    </table>

</body>
</html>
