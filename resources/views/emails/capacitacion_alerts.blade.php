<!DOCTYPE html>
<html>
<head>
    <title>Alertas de Capacitaciones</title>
</head>
<body>
    @if(count($pendientes) > 0)
    <h1>Capacitación Pendiente</h1>
        <ul>
            @foreach($pendientes as $capacitacion)
                <li>{{ $capacitacion->capacitacion->tema->name }} ({{ $capacitacion->fecha_inicio->format('d/m/Y h:m:s a') }} - {{ $capacitacion->fecha_fin->format('d/m/Y h:m:s a') }})</li>
            @endforeach
        </ul>
    @endif

    @if(count($enDesarrollo) > 0)
    <h1>Capacitación en Desarrollo</h1>
        <ul>
            @foreach($enDesarrollo as $capacitacion)
                <li>{{ $capacitacion->capacitacion->tema->name }} ({{ $capacitacion->fecha_inicio->format('d/m/Y h:m:s a') }} - {{ $capacitacion->fecha_fin->format('d/m/Y h:m:s a') }})</li>
            @endforeach
        </ul>
    @endif

    <p><a href="{{ $link }}">Ir a la plataforma</a></p>
</body>
</html>