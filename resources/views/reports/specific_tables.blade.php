<!DOCTYPE html>
<html>
<head>
    <title>Specific Tables Report</title>
</head>
<body>
    <h1>Specific Tables Report</h1>
    <table border="1">
        <thead>
            <tr>
                <th>App (Base de Datos)</th>
                <th>Modulo (Tabla)</th>
                <th>Mes</th>
                <th>Registros</th>
            </tr>
        </thead>
        <tbody>
            @foreach($results as $result)
                <tr>
                    <td>{{ $result['App'] }}</td>
                    <td>{{ $result['Modulo'] }}</td>
                    <td>{{ $result['Mes'] }}</td>
                    <td>{{ $result['Registros'] }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>