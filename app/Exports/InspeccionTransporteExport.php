<?php

namespace App\Exports;

use App\Models\Inspeccion;
use App\Models\InspeccionTransporte;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;

class InspeccionTransporteExport
{
    protected $inspeccion;

    public function __construct(InspeccionTransporte $inspeccion)
    {
        $this->inspeccion = $inspeccion;
    }

    public function collection()
    {
        // Aquí puedes definir los datos que deseas exportar
        return collect([
            ['ID', 'Fecha'],
            [$this->inspeccion->id, $this->inspeccion->fecha_inspeccion]
        ]);
    }

    public function export()
    {
        // Cargar la plantilla desde el archivo
        $templatePath = storage_path('app/templates/template_inspeccion_de_transporte.xlsx');
        if (!file_exists($templatePath)) {
            throw new \Exception("File \"$templatePath\" does not exist.");
        }
        $spreadsheet = IOFactory::load($templatePath);

        // Seleccionar la hoja de trabajo activa
        $sheet = $spreadsheet->getActiveSheet();

            $sheet->setCellValue('A9', $this->inspeccion->empresa->razon_social);
            $sheet->setCellValue('D9', $this->inspeccion->empresa->ruc);
            $sheet->setCellValue('G9', $this->inspeccion->empresa->domicilio);
            $sheet->setCellValue('O9', $this->inspeccion->empresa->actividad_economica);
            $sheet->setCellValue('R9', $this->inspeccion->num_trabajadores);

            $sheet->setCellValue('A10', 'INSPECTOR: '.$this->inspeccion->inspector->name);
            $sheet->setCellValue('L10', 'LUGAR: '.$this->inspeccion->area->name ?? $this->inspeccion->lugar);

            $sheet->setCellValue('A60', 'OBSERVACIONES: '.$this->inspeccion->observaciones_1);
            $sheet->mergeCells('A60','R62');
            
            $sheet->setCellValue('A83', 'OBSERVACIONES: '.$this->inspeccion->observaciones_2);
            $sheet->mergeCells('A83','R91');
            // $sheet->setCellValue('B11', ''.$this->inspeccion->lugar);
            // $sheet->setCellValue('B12', ''.Carbon::parse($this->inspeccion->fecha_hora_inspeccion)->format('d/m/Y H:i'));

            $row = 99; // Comienza a llenar desde la fila 35 (según tu plantilla)
            foreach ($this->inspeccion->responsables as $responsable) {
                // Insertar una nueva fila antes de la fila actual
                $sheet->insertNewRowBefore($row, 1);
                $sheet->getRowDimension($row)->setRowHeight(70);

                $sheet->setCellValue("A$row", "Nombre: ".$responsable->personal->name);           // Descripción
                $sheet->mergeCells("A$row:N$row");
                $sheet->setCellValue("G$row", "Cargo: ".$responsable->cargo->name); // Registro Fotográfico
                $sheet->mergeCells("G$row:L$row");
                $sheet->setCellValue("M$row", "Fecha: ".Carbon::parse($responsable->fecha)->format('d/m/Y'));       // Acción a Tomar
                $sheet->mergeCells("M$row:N$row");
                $sheet->setCellValue("O$row", "Firma: ");          // Responsable
                $sheet->mergeCells("O$row:R$row");
                if ($responsable->firma) {
                    $drawing = new Drawing();
                    $drawing->setName('Firma');
                    $drawing->setDescription('Firma');
                    $drawing->setPath($this->saveBase64Image($responsable->firma));
                    $drawing->setCoordinates("P$row");
                    $drawing->setHeight(70); // Ajusta el tamaño según sea necesario
                    $drawing->setWorksheet($sheet);
                }
                $row++;
            }            
            // Eliminar la fila 34 que se utilizó como referencia
            $sheet->removeRow(98);

            if ($this->inspeccion->firma) {
                $drawing = new Drawing();
                $drawing->setName('Firma');
                $drawing->setDescription('Firma');
                $drawing->setPath($this->saveBase64Image($this->inspeccion->firma));
                $drawing->setCoordinates("K94");
                $drawing->setHeight(70); // Ajusta el tamaño según sea necesario
                $drawing->setWorksheet($sheet);
            }

            if ($this->inspeccion->firma_conductor) {
                $drawing = new Drawing();
                $drawing->setName('Firma');
                $drawing->setDescription('Firma');
                $drawing->setPath($this->saveBase64Image($this->inspeccion->firma_conductor));
                $drawing->setCoordinates("B94");
                $drawing->setHeight(70); // Ajusta el tamaño según sea necesario
                $drawing->setWorksheet($sheet);
            }

            $sheet->setCellValue('J95', 'NOMBRE: '.$this->inspeccion->inspector->name);
            $sheet->setCellValue('A95', 'EMPRESA DE TRANSPORTE: '.$this->inspeccion->empresa_de_transporte);

            $row = 16; // Comienza a llenar desde la fila 35 (según tu plantilla)
            $order = 1;
            $detalle = $this->inspeccion->detalles;

            $informacionConductor = $this->inspeccion->informacionConductor;
            $funcionamientoVehiculo = $this->inspeccion->funcionamientoVehiculo;
            $estadoVehiculo = $this->inspeccion->estadoVehiculo;
            $documentacionVehiculo = $this->inspeccion->documentacionVehiculo;
            $documentacionConductor = $this->inspeccion->documentacionConductor;
            $equipoSeguridad = $this->inspeccion->equipoSeguridad;
            $equipoPrimerosAuxilios = $this->inspeccion->equipoPrimerosAuxilios;

            // informacionConductor
            // funcionamientoVehiculo
            // estadoVehiculo
            // documentacionVehiculo
            // documentacionConductor
            // equipoSeguridad
            // equipoPrimerosAuxilios

            // Zonas Seguras
            $sheet->setCellValue('A13', 'CONDUCTOR: '.$informacionConductor->conductor);
            $sheet->setCellValue('L13', 'N° BREVETE: '.$informacionConductor->numero_brevete);
            $sheet->setCellValue('O13', 'CATEGORIA: '.$informacionConductor->categoria_brevete);
            $sheet->setCellValue('A14', 'PLACA: '.$informacionConductor->placa);
            $sheet->setCellValue('D14', 'N° ASIENTOS: '.$informacionConductor->numero_asientos);
            $sheet->setCellValue('L14', 'RUTA: '.$informacionConductor->ruta);
            if ($informacionConductor->omnibus) {
                $sheet->setCellValue('M15', 'X');
            } else {
                $sheet->setCellValue('O15', ''.$informacionConductor->otros);
            }
            $sheet->setCellValue('D15', 'HORA: '. Carbon::parse($informacionConductor->hora)->format('H:i'));
            $sheet->setCellValue('A15', 'FECHA: '.$informacionConductor->fecha);

            if ($funcionamientoVehiculo) {
                if ($funcionamientoVehiculo->luces_altas == 'SI') {
                    $sheet->setCellValue("Q19", "X");
                } elseif ($funcionamientoVehiculo->luces_altas == 'NO') {
                    $sheet->setCellValue("R19", "X");
                }            
                if ($funcionamientoVehiculo->luces_bajas == 'SI') {
                    $sheet->setCellValue("Q20", "X");
                } elseif ($funcionamientoVehiculo->luces_bajas == 'NO') {
                    $sheet->setCellValue("R20", "X");
                }
                if ($funcionamientoVehiculo->luces_direccionales_delanteras == 'SI') {
                    $sheet->setCellValue("Q21", "X");
                } elseif ($funcionamientoVehiculo->luces_direccionales_delanteras == 'NO') {
                    $sheet->setCellValue("R21", "X");
                }
                if ($funcionamientoVehiculo->luces_direccionales_posteriores == 'SI') {
                    $sheet->setCellValue("Q22", "X");
                } elseif ($funcionamientoVehiculo->luces_direccionales_posteriores == 'NO') {
                    $sheet->setCellValue("R22", "X");
                }
                if ($funcionamientoVehiculo->luces_emergencia == 'SI') {
                    $sheet->setCellValue("Q23", "X");
                } elseif ($funcionamientoVehiculo->luces_emergencia == 'NO') {
                    $sheet->setCellValue("R23", "X");
                }
                if ($funcionamientoVehiculo->luces_neblineros == 'SI') {
                    $sheet->setCellValue("Q24", "X");
                } elseif ($funcionamientoVehiculo->luces_neblineros == 'NO') {
                    $sheet->setCellValue("R24", "X");
                }
                if ($funcionamientoVehiculo->luces_alarma_retroceso == 'SI') {
                    $sheet->setCellValue("Q25", "X");
                } elseif ($funcionamientoVehiculo->luces_alarma_retroceso == 'NO') {
                    $sheet->setCellValue("R25", "X");
                }
                if ($funcionamientoVehiculo->velocimetro == 'SI') {
                    $sheet->setCellValue("Q26", "X");
                } elseif ($funcionamientoVehiculo->velocimetro == 'NO') {
                    $sheet->setCellValue("R26", "X");
                }
                if ($funcionamientoVehiculo->sistema_frenos == 'SI') {
                    $sheet->setCellValue("Q27", "X");
                } elseif ($funcionamientoVehiculo->sistema_frenos == 'NO') {
                    $sheet->setCellValue("R27", "X");
                }
                if ($funcionamientoVehiculo->tablero_combustible == 'SI') {
                    $sheet->setCellValue("Q28", "X");
                } elseif ($funcionamientoVehiculo->tablero_combustible == 'NO') {
                    $sheet->setCellValue("R28", "X");
                }
                if ($funcionamientoVehiculo->limpia_parabrisas == 'SI') {
                    $sheet->setCellValue("Q29", "X");
                } elseif ($funcionamientoVehiculo->limpia_parabrisas == 'NO') {
                    $sheet->setCellValue("R29", "X");
                }
                if ($funcionamientoVehiculo->puertas_acceso == 'SI') {
                    $sheet->setCellValue("Q30", "X");
                } elseif ($funcionamientoVehiculo->puertas_acceso == 'NO') {
                    $sheet->setCellValue("R30", "X");
                }
                if ($funcionamientoVehiculo->claxon == 'SI') {
                    $sheet->setCellValue("Q31", "X");
                } elseif ($funcionamientoVehiculo->claxon == 'NO') {
                    $sheet->setCellValue("R31", "X");
                }
                if ($funcionamientoVehiculo->luces_salon == 'SI') {
                    $sheet->setCellValue("Q32", "X");
                } elseif ($funcionamientoVehiculo->luces_salon == 'NO') {
                    $sheet->setCellValue("R32", "X");
                }
            }

            // Agregar el código para estadoVehiculo
            if ($estadoVehiculo) {
                if ($estadoVehiculo->parabrisas == 'SI') {
                    $sheet->setCellValue("Q33", "X");
                } elseif ($estadoVehiculo->parabrisas == 'NO') {
                    $sheet->setCellValue("R33", "X");
                }
                if ($estadoVehiculo->espejos_laterales == 'SI') {
                    $sheet->setCellValue("Q34", "X");
                } elseif ($estadoVehiculo->espejos_laterales == 'NO') {
                    $sheet->setCellValue("R34", "X");
                }
                if ($estadoVehiculo->espejo_central == 'SI') {
                    $sheet->setCellValue("Q35", "X");
                } elseif ($estadoVehiculo->espejo_central == 'NO') {
                    $sheet->setCellValue("R35", "X");
                }
                if ($estadoVehiculo->ventanas_integras == 'SI') {
                    $sheet->setCellValue("Q36", "X");
                } elseif ($estadoVehiculo->ventanas_integras == 'NO') {
                    $sheet->setCellValue("R36", "X");
                }
                if ($estadoVehiculo->ventanas_operativas == 'SI') {
                    $sheet->setCellValue("Q37", "X");
                } elseif ($estadoVehiculo->ventanas_operativas == 'NO') {
                    $sheet->setCellValue("R37", "X");
                }
                if ($estadoVehiculo->ventanas_cortinas == 'SI') {
                    $sheet->setCellValue("Q38", "X");
                } elseif ($estadoVehiculo->ventanas_cortinas == 'NO') {
                    $sheet->setCellValue("R38", "X");
                }
                if ($estadoVehiculo->neumaticos_delanteros == 'SI') {
                    $sheet->setCellValue("Q39", "X");
                } elseif ($estadoVehiculo->neumaticos_delanteros == 'NO') {
                    $sheet->setCellValue("R39", "X");
                }
                if ($estadoVehiculo->neumaticos_posteriores == 'SI') {
                    $sheet->setCellValue("Q40", "X");
                } elseif ($estadoVehiculo->neumaticos_posteriores == 'NO') {
                    $sheet->setCellValue("R40", "X");
                }
                if ($estadoVehiculo->asientos == 'SI') {
                    $sheet->setCellValue("Q41", "X");
                } elseif ($estadoVehiculo->asientos == 'NO') {
                    $sheet->setCellValue("R41", "X");
                }
                if ($estadoVehiculo->pasillo == 'SI') {
                    $sheet->setCellValue("Q42", "X");
                } elseif ($estadoVehiculo->pasillo == 'NO') {
                    $sheet->setCellValue("R42", "X");
                }
                if ($estadoVehiculo->cinturon_conductor == 'SI') {
                    $sheet->setCellValue("Q43", "X");
                } elseif ($estadoVehiculo->cinturon_conductor == 'NO') {
                    $sheet->setCellValue("R43", "X");
                }
                if ($estadoVehiculo->rotulo_ruta == 'SI') {
                    $sheet->setCellValue("Q44", "X");
                } elseif ($estadoVehiculo->rotulo_ruta == 'NO') {
                    $sheet->setCellValue("R44", "X");
                }
            }

            // // Agregar el código para estadoVehiculo
            // if ($estadoVehiculo) {
            //     if ($estadoVehiculo->parabrisas == 'SI') {
            //         $sheet->setCellValue("Q35", "X");
            //     } elseif ($estadoVehiculo->parabrisas == 'NO') {
            //         $sheet->setCellValue("R35", "X");
            //     }
            //     if ($estadoVehiculo->espejos_laterales == 'SI') {
            //         $sheet->setCellValue("Q36", "X");
            //     } elseif ($estadoVehiculo->espejos_laterales == 'NO') {
            //         $sheet->setCellValue("R36", "X");
            //     }
            //     if ($estadoVehiculo->espejo_central == 'SI') {
            //         $sheet->setCellValue("Q37", "X");
            //     } elseif ($estadoVehiculo->espejo_central == 'NO') {
            //         $sheet->setCellValue("R37", "X");
            //     }
            //     if ($estadoVehiculo->ventanas_estado == 'SI') {
            //         $sheet->setCellValue("Q38", "X");
            //     } elseif ($estadoVehiculo->ventanas_estado == 'NO') {
            //         $sheet->setCellValue("R38", "X");
            //     }
            //     if ($estadoVehiculo->ventanas_operativas == 'SI') {
            //         $sheet->setCellValue("Q39", "X");
            //     } elseif ($estadoVehiculo->ventanas_operativas == 'NO') {
            //         $sheet->setCellValue("R39", "X");
            //     }
            //     if ($estadoVehiculo->ventanas_cortinas == 'SI') {
            //         $sheet->setCellValue("Q40", "X");
            //     } elseif ($estadoVehiculo->ventanas_cortinas == 'NO') {
            //         $sheet->setCellValue("R40", "X");
            //     }
            //     if ($estadoVehiculo->neumaticos_delanteros == 'SI') {
            //         $sheet->setCellValue("Q41", "X");
            //     } elseif ($estadoVehiculo->neumaticos_delanteros == 'NO') {
            //         $sheet->setCellValue("R41", "X");
            //     }
            //     if ($estadoVehiculo->neumaticos_posteriores == 'SI') {
            //         $sheet->setCellValue("Q42", "X");
            //     } elseif ($estadoVehiculo->neumaticos_posteriores == 'NO') {
            //         $sheet->setCellValue("R42", "X");
            //     }
            //     if ($estadoVehiculo->asientos == 'SI') {
            //         $sheet->setCellValue("Q43", "X");
            //     } elseif ($estadoVehiculo->asientos == 'NO') {
            //         $sheet->setCellValue("R43", "X");
            //     }
            //     if ($estadoVehiculo->pasillo == 'SI') {
            //         $sheet->setCellValue("Q44", "X");
            //     } elseif ($estadoVehiculo->pasillo == 'NO') {
            //         $sheet->setCellValue("R44", "X");
            //     }
            //     if ($estadoVehiculo->cinturon_conductor == 'SI') {
            //         $sheet->setCellValue("Q45", "X");
            //     } elseif ($estadoVehiculo->cinturon_conductor == 'NO') {
            //         $sheet->setCellValue("R45", "X");
            //     }
            //     if ($estadoVehiculo->rotulo_ruta == 'SI') {
            //         $sheet->setCellValue("Q46", "X");
            //     } elseif ($estadoVehiculo->rotulo_ruta == 'NO') {
            //         $sheet->setCellValue("R46", "X");
            //     }
            // }

            // Agregar el código para documentacionVehiculo
            if ($documentacionVehiculo) {
                if ($documentacionVehiculo->soat_vigente == 'SI') {
                    $sheet->setCellValue("Q49", "X");
                } elseif ($documentacionVehiculo->soat_vigente == 'NO') {
                    $sheet->setCellValue("R49", "X");
                }               
                if ($documentacionVehiculo->revision_tecnica_vigente == 'SI') {
                    $sheet->setCellValue("Q50", "X");
                } elseif ($documentacionVehiculo->revision_tecnica_vigente == 'NO') {
                    $sheet->setCellValue("R50", "X");
                }
                if ($documentacionVehiculo->permiso_circulacion_vigente == 'SI') {
                    $sheet->setCellValue("Q51", "X");
                } elseif ($documentacionVehiculo->permiso_circulacion_vigente == 'NO') {
                    $sheet->setCellValue("R51", "X");
                }
                
                $sheet->setCellValue('L49', ''.Carbon::parse($documentacionVehiculo->fecha_vencimiento_soat)->format('d/m/Y'));
                $sheet->setCellValue('L50', ''.Carbon::parse($documentacionVehiculo->fecha_vencimiento_revision_tecnica)->format('d/m/Y'));
                $sheet->setCellValue('L51', ''.Carbon::parse($documentacionVehiculo->fecha_vencimiento_permiso_circulacion)->format('d/m/Y'));

                if ($documentacionVehiculo->tarjeta_identificacion_vehicular == 'SI') {
                    $sheet->setCellValue("Q52", "X");
                } elseif ($documentacionVehiculo->tarjeta_identificacion_vehicular == 'NO') {
                    $sheet->setCellValue("R52", "X");
                }

                $sheet->setCellValue('H49', 'N° asient. Cobert.: '.($documentacionVehiculo->num_asientos_soat));
                $sheet->setCellValue('H52', 'N° asient.: '.($documentacionVehiculo->num_asientos_tarjeta));
            }

            // Agregar el código para documentacionConductor
            if ($documentacionConductor) {
                if ($documentacionConductor->dni_vigente == 'SI') {
                    $sheet->setCellValue("Q55", "X");
                } elseif ($documentacionConductor->dni_vigente == 'NO') {
                    $sheet->setCellValue("R55", "X");
                }
                if ($documentacionConductor->brevete_validez == 'SI') {
                    $sheet->setCellValue("Q56", "X");
                } elseif ($documentacionConductor->brevete_validez == 'NO') {
                    $sheet->setCellValue("R56", "X");
                }

                $sheet->setCellValue('L55', ''.Carbon::parse($documentacionVehiculo->fecha_vencimiento_dni)->format('d/m/Y'));
                $sheet->setCellValue('L56', ''.Carbon::parse($documentacionVehiculo->fecha_vencimiento_brevete)->format('d/m/Y'));

                if ($documentacionConductor->brevete_categoria == 'SI') {
                    $sheet->setCellValue("Q57", "X");
                } elseif ($documentacionConductor->brevete_categoria == 'NO') {
                    $sheet->setCellValue("R57", "X");
                }
                if ($documentacionConductor->medidas_preventivas == 'SI') {
                    $sheet->setCellValue("Q58", "X");
                } elseif ($documentacionConductor->medidas_preventivas == 'NO') {
                    $sheet->setCellValue("R58", "X");
                }
            }

            // Agregar el código para equipoSeguridad
            if ($equipoSeguridad) {
                if ($equipoSeguridad->adhesivos_reflectivos == 'SI') {
                    $sheet->setCellValue("I70", "X");
                } elseif ($equipoSeguridad->adhesivos_reflectivos == 'NO') {
                    $sheet->setCellValue("J70", "X");
                }
                if ($equipoSeguridad->triangulos_seguridad == 'SI') {
                    $sheet->setCellValue("I71", "X");
                } elseif ($equipoSeguridad->triangulos_seguridad == 'NO') {
                    $sheet->setCellValue("J71", "X");
                }
                if ($equipoSeguridad->conos == 'SI') {
                    $sheet->setCellValue("I72", "X");
                } elseif ($equipoSeguridad->conos == 'NO') {
                    $sheet->setCellValue("J72", "X");
                }
                if ($equipoSeguridad->tacos_emergencia == 'SI') {
                    $sheet->setCellValue("I73", "X");
                } elseif ($equipoSeguridad->tacos_emergencia == 'NO') {
                    $sheet->setCellValue("J73", "X");
                }
                if ($equipoSeguridad->extintor_pqs == 'SI') {
                    $sheet->setCellValue("I74", "X");
                } elseif ($equipoSeguridad->extintor_pqs == 'NO') {
                    $sheet->setCellValue("J74", "X");
                }
                if ($equipoSeguridad->cable_baterias == 'SI') {
                    $sheet->setCellValue("I75", "X");
                } elseif ($equipoSeguridad->cable_baterias == 'NO') {
                    $sheet->setCellValue("J75", "X");
                }
                if ($equipoSeguridad->cadena_remolque == 'SI') {
                    $sheet->setCellValue("I76", "X");
                } elseif ($equipoSeguridad->cadena_remolque == 'NO') {
                    $sheet->setCellValue("J76", "X");
                }
                if ($equipoSeguridad->llave_palanza_rueda == 'SI') {
                    $sheet->setCellValue("I77", "X");
                } elseif ($equipoSeguridad->llave_palanza_rueda == 'NO') {
                    $sheet->setCellValue("J77", "X");
                }
                if ($equipoSeguridad->llanta_repuesto == 'SI') {
                    $sheet->setCellValue("I78", "X");
                } elseif ($equipoSeguridad->llanta_repuesto == 'NO') {
                    $sheet->setCellValue("J78", "X");
                }
                if ($equipoSeguridad->gata_hidraulica == 'SI') {
                    $sheet->setCellValue("I79", "X");
                } elseif ($equipoSeguridad->gata_hidraulica == 'NO') {
                    $sheet->setCellValue("J79", "X");
                }
                if ($equipoSeguridad->ventanas_emergencia == 'SI') {
                    $sheet->setCellValue("I80", "X");
                } elseif ($equipoSeguridad->ventanas_emergencia == 'NO') {
                    $sheet->setCellValue("J80", "X");
                }
                if ($equipoSeguridad->martillos_emergencia == 'SI') {
                    $sheet->setCellValue("I81", "X");
                } elseif ($equipoSeguridad->martillos_emergencia == 'NO') {
                    $sheet->setCellValue("J81", "X");
                }
            }

            // Agregar el código para equipoPrimerosAuxilios
            if ($equipoPrimerosAuxilios) {
                if ($equipoPrimerosAuxilios->botiquin == 'SI') {
                    $sheet->setCellValue("Q70", "X");
                } elseif ($equipoPrimerosAuxilios->botiquin == 'NO') {
                    $sheet->setCellValue("R70", "X");
                }
                if ($equipoPrimerosAuxilios->alcohol == 'SI') {
                    $sheet->setCellValue("Q72", "X");
                } elseif ($equipoPrimerosAuxilios->alcohol == 'NO') {
                    $sheet->setCellValue("R72", "X");
                }
                if ($equipoPrimerosAuxilios->agua_oxigenada == 'SI') {
                    $sheet->setCellValue("Q73", "X");
                } elseif ($equipoPrimerosAuxilios->agua_oxigenada == 'NO') {
                    $sheet->setCellValue("R73", "X");
                }
                if ($equipoPrimerosAuxilios->gasas == 'SI') {
                    $sheet->setCellValue("Q74", "X");
                } elseif ($equipoPrimerosAuxilios->gasas == 'NO') {
                    $sheet->setCellValue("R74", "X");
                }
                if ($equipoPrimerosAuxilios->aposito == 'SI') {
                    $sheet->setCellValue("Q75", "X");
                } elseif ($equipoPrimerosAuxilios->aposito == 'NO') {
                    $sheet->setCellValue("R75", "X");
                }
                if ($equipoPrimerosAuxilios->esparadrapo == 'SI') {
                    $sheet->setCellValue("Q76", "X");
                } elseif ($equipoPrimerosAuxilios->esparadrapo == 'NO') {
                    $sheet->setCellValue("R76", "X");
                }
                if ($equipoPrimerosAuxilios->venda_elastica == 'SI') {
                    $sheet->setCellValue("Q77", "X");
                } elseif ($equipoPrimerosAuxilios->venda_elastica == 'NO') {
                    $sheet->setCellValue("R77", "X");
                }
                if ($equipoPrimerosAuxilios->bandas_adhesivas == 'SI') {
                    $sheet->setCellValue("Q78", "X");
                } elseif ($equipoPrimerosAuxilios->bandas_adhesivas == 'NO') {
                    $sheet->setCellValue("R78", "X");
                }
                if ($equipoPrimerosAuxilios->tijera == 'SI') {
                    $sheet->setCellValue("Q79", "X");
                } elseif ($equipoPrimerosAuxilios->tijera == 'NO') {
                    $sheet->setCellValue("R79", "X");
                }
                if ($equipoPrimerosAuxilios->guantes_quirurgicos == 'SI') {
                    $sheet->setCellValue("Q80", "X");
                } elseif ($equipoPrimerosAuxilios->guantes_quirurgicos == 'NO') {
                    $sheet->setCellValue("R80", "X");
                }
                if ($equipoPrimerosAuxilios->algodon == 'SI') {
                    $sheet->setCellValue("Q81", "X");
                } elseif ($equipoPrimerosAuxilios->algodon == 'NO') {
                    $sheet->setCellValue("R81", "X");
                }
            }

        // Guardar el archivo Excel generado
        $filePath = storage_path('app/template_inspeccion_luces_de_emergencia.xlsx');
        $writer = new Xlsx($spreadsheet);
        $writer->save($filePath);

        return $filePath;
    }

    private function saveBase64Image($base64Image)
    {
        $imageData = explode(',', $base64Image);
        $imageType = explode(';', explode(':', $imageData[0])[1])[0];
        $imageExtension = explode('/', $imageType)[1];
        $imageBase64 = base64_decode($imageData[1]);
        $filePath = storage_path('app/temp_image' . uniqid() . '.' . $imageExtension);
        file_put_contents($filePath, $imageBase64);
        return $filePath;
    }
}
