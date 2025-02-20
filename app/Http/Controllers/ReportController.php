<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function auditLogins()
    {
        $databases = [
            'mysql_37' => ['gth_asistencia_personal', 'acta_responsabilidad', 'transporte2'],
            'mysql_51' => ['pallets', 'inventario_activos_ti', 'ensayos'],
            'mysql_54' => ['inventario_fisico', 'capacitaciones', 'evaluaciones_de_desempeno'],
        ];

        $results = [];

        foreach ($databases as $connection => $dbs) {
            foreach ($dbs as $db) {
                DB::purge($connection);
                config(["database.connections.$connection.database" => $db]);
                DB::reconnect($connection);

                $logins = DB::connection($connection)->table('auditlogin')
                    ->select(DB::raw('COUNT(*) as logins, MONTH(date) as month, YEAR(date) as year'))
                    ->where(function($query) {
                        $query->where(function($query) {
                            $query->whereMonth('date', 11)->whereYear('date', 2024);
                        })->orWhere(function($query) {
                            $query->whereMonth('date', 12)->whereYear('date', 2024);
                        })->orWhere(function($query) {
                            $query->whereMonth('date', 1)->whereYear('date', 2025);
                        });
                    })
                    ->groupBy(DB::raw('MONTH(date), YEAR(date)'))
                    ->orderBy(DB::raw('YEAR(date), MONTH(date)'))
                    ->get();

                foreach ($logins as $login) {
                    $results[] = [
                        'App' => $db,
                        'Mes' => $this->getMonthName($login->month) . ' ' . $login->year,
                        'Logins' => $login->logins,
                    ];
                }
            }
        }

        return view('reports.audit_logins', compact('results'));
    }

    public function specificTables()
    {
        $tables = [
            'mysql_37' => [
                'acta_responsabilidad' => ['actas'],
                'transporte2' => ['movilidad'],
            ],
            'mysql_51' => [
                'pallets' => ['pallets'],
                'inventario_activos_ti' => ['asignaciones', 'devoluciones'],
                'ensayos' => ['ensayos'],
            ],
            'mysql_54' => [
                'inventario_fisico' => ['inventario_fisicos'],
                'capacitaciones' => ['capacitaciones', 'asistencia', 'capacitacion_has_personal'],
                'evaluaciones_de_desempeno' => ['planes_de_accion', 'evaluador_has_evaluados'],
            ],
        ];

        $results = [];

        foreach ($tables as $connection => $dbs) {
            foreach ($dbs as $db => $tbls) {
                DB::purge($connection);
                config(["database.connections.$connection.database" => $db]);
                DB::reconnect($connection);

                foreach ($tbls as $table) {
                    if ($table == 'movilidad') {
                        $created = DB::connection($connection)->table($table)
                            ->select(DB::raw('COUNT(*) as registros, MONTH(created_date) as month, YEAR(created_date) as year'))
                            ->where(function($query) {
                                $query->where(function($query) {
                                    $query->whereMonth('created_date', 11)->whereYear('created_date', 2024);
                                })->orWhere(function($query) {
                                    $query->whereMonth('created_date', 12)->whereYear('created_date', 2024);
                                })->orWhere(function($query) {
                                    $query->whereMonth('created_date', 1)->whereYear('created_date', 2025);
                                });
                            })
                            ->groupBy(DB::raw('MONTH(created_date), YEAR(created_date)'))
                            ->orderBy(DB::raw('YEAR(created_date), MONTH(created_date)'))
                            ->get();

                        foreach ($created as $record) {
                            $results[] = [
                                'App' => $db,
                                'Modulo' => $table,
                                'Mes' => $this->getMonthName($record->month) . ' ' . $record->year,
                                'Registros' => $record->registros,
                            ];
                        }

                        $updated = DB::connection($connection)->table($table)
                            ->select(DB::raw('COUNT(*) as registros, MONTH(updated_date) as month, YEAR(updated_date) as year'))
                            ->where(function($query) {
                                $query->where(function($query) {
                                    $query->whereMonth('updated_date', 11)->whereYear('updated_date', 2024);
                                })->orWhere(function($query) {
                                    $query->whereMonth('updated_date', 12)->whereYear('updated_date', 2024);
                                })->orWhere(function($query) {
                                    $query->whereMonth('updated_date', 1)->whereYear('updated_date', 2025);
                                });
                            })
                            ->groupBy(DB::raw('MONTH(updated_date), YEAR(updated_date)'))
                            ->orderBy(DB::raw('YEAR(updated_date), MONTH(updated_date)'))
                            ->get();

                        foreach ($updated as $record) {
                            $results[] = [
                                'App' => $db,
                                'Modulo' => $table,
                                'Mes' => $this->getMonthName($record->month) . ' ' . $record->year,
                                'Registros' => $record->registros,
                            ];
                        }
                    } else {
                        $created = DB::connection($connection)->table($table)
                            ->select(DB::raw('COUNT(*) as registros, MONTH(created_at) as month, YEAR(created_at) as year'))
                            ->where(function($query) {
                                $query->where(function($query) {
                                    $query->whereMonth('created_at', 11)->whereYear('created_at', 2024);
                                })->orWhere(function($query) {
                                    $query->whereMonth('created_at', 12)->whereYear('created_at', 2024);
                                })->orWhere(function($query) {
                                    $query->whereMonth('created_at', 1)->whereYear('created_at', 2025);
                                });
                            })
                            ->groupBy(DB::raw('MONTH(created_at), YEAR(created_at)'))
                            ->orderBy(DB::raw('YEAR(created_at), MONTH(created_at)'))
                            ->get();

                        foreach ($created as $record) {
                            $results[] = [
                                'App' => $db,
                                'Modulo' => $table,
                                'Mes' => $this->getMonthName($record->month) . ' ' . $record->year,
                                'Registros' => $record->registros,
                            ];
                        }

                        if (in_array($table, ['planes_de_accion', 'evaluador_has_evaluados'])) {
                            $updated = DB::connection($connection)->table($table)
                                ->select(DB::raw('COUNT(*) as registros, MONTH(updated_at) as month, YEAR(updated_at) as year'))
                                ->where(function($query) {
                                    $query->where(function($query) {
                                        $query->whereMonth('updated_at', 11)->whereYear('updated_at', 2024);
                                    })->orWhere(function($query) {
                                        $query->whereMonth('updated_at', 12)->whereYear('updated_at', 2024);
                                    })->orWhere(function($query) {
                                        $query->whereMonth('updated_at', 1)->whereYear('updated_at', 2025);
                                    });
                                })
                                ->groupBy(DB::raw('MONTH(updated_at), YEAR(updated_at)'))
                                ->orderBy(DB::raw('YEAR(updated_at), MONTH(updated_at)'))
                                ->get();

                            foreach ($updated as $record) {
                                $results[] = [
                                    'App' => $db,
                                    'Modulo' => $table,
                                    'Mes' => $this->getMonthName($record->month) . ' ' . $record->year,
                                    'Registros' => $record->registros,
                                ];
                            }
                        }
                    }
                }
            }
        }

        return view('reports.specific_tables', compact('results'));
    }

    private function getMonthName($month)
    {
        $months = [
            1 => 'Enero',
            2 => 'Febrero',
            3 => 'Marzo',
            4 => 'Abril',
            5 => 'Mayo',
            6 => 'Junio',
            7 => 'Julio',
            8 => 'Agosto',
            9 => 'Septiembre',
            10 => 'Octubre',
            11 => 'Noviembre',
            12 => 'Diciembre',
        ];

        return $months[$month] ?? 'Desconocido';
    }
}