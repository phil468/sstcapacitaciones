<?php

namespace App\Exports;

use App\Models\Inspeccion;
use App\Models\Inspecciones\Luces\InspeccionLuzEmergencia;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;

class InspeccionLucesExport
{
    protected $inspeccion;

    public function __construct(InspeccionLuzEmergencia $inspeccion)
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
        $templatePath = storage_path('app/templates/template_inspeccion_luces_de_emergencia.xlsx');
        if (!file_exists($templatePath)) {
            throw new \Exception("File \"$templatePath\" does not exist.");
        }
        $spreadsheet = IOFactory::load($templatePath);

        // Seleccionar la hoja de trabajo activa
        $sheet = $spreadsheet->getActiveSheet();

        // Obtener los datos desde la base de datos
        // $inspecciones = InspeccionLuzEmergencia::all();

        // Llenar datos en las celdas correspondientes
            // Ajustar las celdas de acuerdo al formato
            
            // $sheet->setCellValue('B8',  $this->inspeccion->numero_registro);
            $sheet->setCellValue('A9', $this->inspeccion->empresa->razon_social);
            $sheet->setCellValue('H9', $this->inspeccion->empresa->ruc);
            $sheet->setCellValue('L9', $this->inspeccion->empresa->domicilio);
            $sheet->setCellValue('T9', $this->inspeccion->empresa->actividad_economica);
            $sheet->setCellValue('W9', $this->inspeccion->num_trabajadores);

            $sheet->setCellValue('Q11', 'Lugar: '.$this->inspeccion->area->name ?? $this->inspeccion->lugar);
            $sheet->setCellValue('A11', 'Fecha y Hora de la inspección: '.Carbon::parse($this->inspeccion->fecha_hora_inspeccion)->format('d/m/Y H:i'));
            $sheet->setCellValue('A10', 'Inspector: '.$this->inspeccion->inspector->name);
            if ($this->inspeccion->firma) {
                $drawing = new Drawing();
                $drawing->setName('Firma');
                $drawing->setDescription('Firma');
                $drawing->setPath($this->saveBase64Image($this->inspeccion->firma));
                $drawing->setCoordinates("R10");
                $drawing->setHeight(70); // Ajusta el tamaño según sea necesario
                $drawing->setWorksheet($sheet);
            }

            $row = 29; // Comienza a llenar desde la fila 35 (según tu plantilla)
            foreach ($this->inspeccion->responsables as $responsable) {
                // Insertar una nueva fila antes de la fila actual
                $sheet->insertNewRowBefore($row, 1);
                $sheet->getRowDimension($row)->setRowHeight(70);

                $sheet->setCellValue("A$row", "Nombre: ".$responsable->personal->name);           // Descripción
                $sheet->mergeCells("A$row:N$row");
                $sheet->setCellValue("O$row", "Cargo: ".$responsable->cargo->name); // Registro Fotográfico
                $sheet->mergeCells("O$row:S$row");
                $sheet->setCellValue("T$row", "Fecha: ".Carbon::parse($responsable->fecha)->format('d/m/Y'));       // Acción a Tomar
                $sheet->mergeCells("T$row:V$row");
                $sheet->setCellValue("W$row", "Firma: ");          // Responsable
                $sheet->mergeCells("W$row:X$row");
                if ($responsable->firma) {
                    $drawing = new Drawing();
                    $drawing->setName('Firma');
                    $drawing->setDescription('Firma');
                    $drawing->setPath($this->saveBase64Image($responsable->firma));
                    $drawing->setCoordinates("X$row");
                    $drawing->setHeight(70); // Ajusta el tamaño según sea necesario
                    $drawing->setWorksheet($sheet);
                }
                $row++;
            }
            
            // Eliminar la fila 34 que se utilizó como referencia
            $sheet->removeRow(28);

            $row = 15; // Comienza a llenar desde la fila 35 (según tu plantilla)
            $order = 1;
            foreach ($this->inspeccion->detalles as $detalle) {
                // Insertar una nueva fila antes de la fila actual
                $sheet->insertNewRowBefore($row, 1);
                // $sheet->getRowDimension($row)->setRowHeight(150);

                // dd($this->inspeccion);
                // dd($detalle->area);
                // dd($detalle->area->name);

                $sheet->setCellValue("A$row", $order.'');//ITEM
                $sheet->setCellValue("B$row", $detalle->area ? $detalle->area->name : '');//N° DE GABINETE
                $sheet->setCellValue("I$row", ($detalle->enciende=="1" ? "X":""));            
                $sheet->setCellValue("J$row", ($detalle->enciende=="0" ? "X":""));
                $sheet->setCellValue("K$row", ($detalle->buen_estado=="1" ? "X":""));
                $sheet->setCellValue("L$row", ($detalle->buen_estado=="0" ? "X":""));
                $sheet->setCellValue("M$row", ($detalle->buena_iluminacion=="1" ? "X":""));
                $sheet->setCellValue("N$row", ($detalle->buena_iluminacion=="0" ? "X":""));                
                $sheet->setCellValue("O$row", ($detalle->buena_ubicacion=="1" ? "X":""));
                $sheet->setCellValue("P$row", ($detalle->buena_ubicacion=="0" ? "X":""));
                $sheet->setCellValue("Q$row", ($detalle->conectado=="1" ? "X":""));
                $sheet->setCellValue("R$row", ($detalle->conectado=="0" ? "X":""));
                $sheet->setCellValue("S$row", ($detalle->senalizado=="1" ? "X":""));
                $sheet->setCellValue("T$row", ($detalle->senalizado=="0" ? "X":""));
                $sheet->setCellValue("U$row", ($detalle->partes()->pluck('name')->implode(', ') ));
                $order++;

                $row++;
            }
            
            // Eliminar la fila 23 que se utilizó como referencia
            $sheet->removeRow(14);

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
