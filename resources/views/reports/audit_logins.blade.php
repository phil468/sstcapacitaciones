<!DOCTYPE html>
<html>
<head>
    <title>Audit Logins Report</title>
</head>
<body>
    <h1>Audit Logins Report</h1>
    <table border="1">
        <thead>
            <tr>
                <th>App (Base de Datos)</th>
                <th>Mes</th>
                <th>Logins (Registros)</th>
            </tr>
        </thead>
        <tbody>
            @foreach($results as $result)
                <tr>
                    <td>{{ $result['App'] }}</td>
                    <td>{{ $result['Mes'] }}</td>
                    <td>{{ $result['Logins'] }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>