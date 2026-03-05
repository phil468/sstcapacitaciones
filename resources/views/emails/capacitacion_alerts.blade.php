<!doctype html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Alertas de Capacitaciones</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial;
            background: #f4f6f8;
            margin: 0;
            padding: 0;
        }

        .wrapper {
            width: 100%;
            padding: 20px 0;
        }

        .container {
            width: 100%;
            max-width: 680px;
            margin: 0 auto;
        }

        .card {
            background: #ffffff;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.06);
            margin-bottom: 16px;
        }

        h1 {
            font-size: 20px;
            margin: 0 0 8px 0;
            color: #1f2937;
        }

        .subtitle {
            color: #6b7280;
            font-size: 14px;
            margin-bottom: 12px;
        }

        .item-title {
            font-weight: 700;
            color: #111827;
            font-size: 16px;
            margin-bottom: 6px;
        }

        .meta {
            color: #6b7280;
            font-size: 13px;
            margin-bottom: 8px;
        }

        .cta {
            display: inline-block;
            background: #4f46e5;
            color: #fff;
            text-decoration: none;
            padding: 12px 18px;
            border-radius: 8px;
            font-weight: 600;
        }

        .small {
            font-size: 12px;
            color: #9ca3af;
        }

        .footer {
            text-align: center;
            color: #9ca3af;
            font-size: 12px;
            padding: 12px 0;
        }

        .grid {
            display: flex;
            gap: 12px;
            flex-wrap: wrap;
        }

        .badge {
            background: #eef2ff;
            color: #4338ca;
            padding: 6px 10px;
            border-radius: 20px;
            font-weight: 600;
            font-size: 12px;
        }

        @media only screen and (max-width:480px) {
            .grid {
                flex-direction: column;
            }

            .card {
                padding: 14px;
            }

            .cta {
                width: 100%;
                text-align: center;
            }
        }
    </style>
</head>

<body>
    <div class="wrapper">
        <div class="container">
            <div class="card" style="text-align:center;">
                <h1>Recordatorio de Capacitaciones</h1>
                <p class="subtitle">Ingresa a la plataforma para completar las capacitaciones asignadas. Si ya las
                    realizaste, puedes omitir este recordatorio. Te recordamos que su cumplimiento es obligatorio.</p>
                <p><a class="cta" href="{{ $link }}">Ir a la plataforma</a></p>
            </div>

            @if (!empty($pendientes) && count($pendientes) > 0)
                <div class="card">
                    <div class="grid" style="align-items:center; justify-content:space-between;">
                        <div>
                            <h2 style="margin:0; font-size:18px;">Capacitaciones Pendientes</h2>
                            <p class="small">A continuación las capacitaciones que requieren su atención.</p>
                        </div>
                        <div class="badge">{{ count($pendientes) }} pendientes</div>
                    </div>
                    <div style="margin-top:12px;">
                        @foreach ($pendientes as $cap)
                            @php $c = $cap->capacitacion ?? null; @endphp
                            <div style="border-top:1px solid #eef2f6; padding-top:12px; margin-top:12px;">
                                <div class="item-title">{{ $c?->tema->name ?? '—' }}</div>
                                <div class="meta">{{ optional($cap->fecha_inicio)->format('d/m/Y H:i') }} —
                                    {{ optional($cap->fecha_fin)->format('d/m/Y H:i') }}</div>
                                @if (isset($c->descripcion) && $c->descripcion)
                                    <div class="small" style="margin-bottom:8px;">
                                        {{ \Illuminate\Support\Str::limit(strip_tags($c->descripcion), 220) }}</div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            @if (!empty($enDesarrollo) && count($enDesarrollo) > 0)
                <div class="card">
                    <div class="grid" style="align-items:center; justify-content:space-between;">
                        <div>
                            <h2 style="margin:0; font-size:18px;">Capacitaciones en Desarrollo</h2>
                            <p class="small">Capacitaciones activas en las que estás inscrito.</p>
                        </div>
                        <div class="badge">{{ count($enDesarrollo) }} activas</div>
                    </div>
                    <div style="margin-top:12px;">
                        @foreach ($enDesarrollo as $cap)
                            @php $c = $cap->capacitacion ?? null; @endphp
                            <div style="border-top:1px solid #eef2f6; padding-top:12px; margin-top:12px;">
                                <div class="item-title">{{ $c?->tema->name ?? '—' }}</div>
                                <div class="meta">{{ optional($cap->fecha_inicio)->format('d/m/Y H:i') }} —
                                    {{ optional($cap->fecha_fin)->format('d/m/Y H:i') }}</div>
                                @if (isset($c->responsable))
                                    <div class="small">Responsable: {{ $c->responsable }}</div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <div class="footer">Este es un correo automático del Sistema. Si tienes dudas, contacta con el
                administrador.</div>
        </div>
    </div>
</body>

</html>
