<!DOCTYPE html>
<html>
<head>
    <title>Alerta de Inspección SST</title>
    <style>
        .button {
          border: none;
          color: white;
          padding: 8px 16px;
          text-align: center;
          text-decoration: none;
          display: inline-block;
          font-size: 16px;
          margin: 4px 2px;
          cursor: pointer;
        }
        
        .button1 {background-color: #04AA6D;} /* Green */
        .button2 {background-color: #008CBA;} /* Blue */
        </style>
</head>
<body>
    <h1>Alerta de Inspección SST</h1>
    <p>Se le ha asignado una observación pendiente de inspección.</p>
    <p><strong>Descripción:</strong> {{ $resultado->descripcion }}</p>
    <p><strong>Nivel de Riesgo:</strong> {{ $resultado->nivel_riesgo }}</p>
    <p><strong>Acción a Tomar:</strong> {{ $resultado->accion_a_tomar }}</p>
    <p><strong>Estado:</strong> {{ $resultado->estado }}</p>
    <p><strong>Fecha de Ejecución:</strong> {{ $resultado->fecha_ejecucion }}</p>
    <p><strong>Debe subsanar la observación en el plazo de corrección:</strong> {{ $resultado->fecha_ejecucion }}</p>
    {{-- <p><a href="{{ $link }}">
        <button class="button button1">Ver Inspección</button>
    </a></p> --}}
    <p><a href="{{ $link }}" class="button button1">Ver Inspección</a></p>
</body>
</html>