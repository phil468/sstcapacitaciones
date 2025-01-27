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
            // $sheet->setCellValue('B12', ''.Carbon::parse($this->inspeccion->fecha_hora_inspeccion)->format('d/m/Y h:i a'));

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
            }
            $sheet->setCellValue('D15', 'HORA: '.$informacionConductor->hora);
            $sheet->setCellValue('A15', 'FECHA: '.$informacionConductor->fecha);
            $sheet->setCellValue('A13', 'OTROS:: '.$informacionConductor->otros);

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



            // 'luces_altas',
            // 'luces_bajas',
            // 'luces_direccionales_delanteras',
            // 'luces_direccionales_posteriores',
            // 'luces_emergencia',
            // 'luces_neblineros',
            // 'luces_alarma_retroceso',
            // 'velocimetro',
            // 'sistema_frenos',
            // 'tablero_combustible',
            // 'limpia_parabrisas',
            // 'puertas_acceso',
            // 'claxon',
            // 'luces_salon'
            // Eliminar la fila 23 que se utilizó como referencia
            // $sheet->removeRow(14);

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