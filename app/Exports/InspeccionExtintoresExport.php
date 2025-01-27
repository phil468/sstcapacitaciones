<?php

namespace App\Exports;

use App\Models\Inspeccion;
use App\Models\InspeccionExtintor;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;

class InspeccionExtintoresExport
{
    protected $inspeccion;

    public function __construct(InspeccionExtintor $inspeccion)
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
        $templatePath = storage_path('app/templates/template_inspeccion_de_extintores.xlsx');
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
            
            
            $sheet->setCellValue('AF8', $this->inspeccion->area->name ?? $this->inspeccion->lugar);
            $sheet->setCellValue('E8', Carbon::parse($this->inspeccion->fecha_inspeccion)->format('d/m/Y').' '.Carbon::parse($this->inspeccion->hora_inspeccion)->format('h:i a'));
            $sheet->setCellValue('E7', $this->inspeccion->inspector->name);
            // $sheet->mergeCells('E7','AB7');

            if ($this->inspeccion->firma) {
                $drawing = new Drawing();
                $drawing->setName('Firma');
                $drawing->setDescription('Firma');
                $drawing->setPath($this->saveBase64Image($this->inspeccion->firma));
                $drawing->setCoordinates("AF7");
                $drawing->setHeight(70); // Ajusta el tamaño según sea necesario
                $drawing->setWorksheet($sheet);
            }
            
            $sheet->setCellValue('F15', $this->inspeccion->resultado);
            $sheet->mergeCells("F15:AJ15");

            $row = 13; // Comienza a llenar desde la fila 35 (según tu plantilla)
            $order = 1;
            foreach ($this->inspeccion->detalles as $detalle) {
                // Insertar una nueva fila antes de la fila actual
                $sheet->insertNewRowBefore($row, 1);
                // $sheet->getRowDimension($row)->setRowHeight(150);

                $sheet->setCellValue("A$row", $order.'');//ITEM
                $sheet->setCellValue("B$row", $detalle->numero_extintor );
                $sheet->setCellValue("C$row", $detalle->ubicacion);
                $sheet->mergeCells("C$row:E$row");
                $sheet->setCellValue("F$row", $detalle->tipo);
                $sheet->mergeCells("F$row:H$row");
                $sheet->setCellValue("I$row", $detalle->peso);
                $sheet->mergeCells("I$row:K$row");
                $sheet->setCellValue("L$row", $detalle->anio_fabricacion);
                $sheet->mergeCells("L$row:N$row");                
                $sheet->setCellValue("O$row", $detalle->serie);
                $sheet->mergeCells("O$row:P$row");
                $sheet->setCellValue("Q$row", $detalle->fecha_proxima_recarga);
                $sheet->setCellValue("R$row", $detalle->fecha_prueba_hidrostati);
                $sheet->setCellValue("S$row", $detalle->lugar_asignado);
                $sheet->setCellValue("T$row", $detalle->facil_acceso);
                $sheet->setCellValue("U$row", $detalle->senalizacion);
                $sheet->setCellValue("V$row", $detalle->pictograma);
                $sheet->setCellValue("W$row", $detalle->pasador);
                $sheet->setCellValue("X$row", $detalle->precinto);
                $sheet->setCellValue("Y$row", $detalle->colatin);
                $sheet->setCellValue("Z$row", $detalle->manometro);
                $sheet->setCellValue("AA$row", $detalle->presion_optima);
                $sheet->setCellValue("AB$row", $detalle->cuerpo_estado);
                $sheet->setCellValue("AC$row", $detalle->boquilla_tobera);
                $sheet->setCellValue("AD$row", $detalle->manguera);
                $sheet->setCellValue("AE$row", $detalle->manija_transporte);
                $sheet->setCellValue("AF$row", $detalle->palanca);
                $sheet->setCellValue("AG$row", $detalle->tarjeta_control);
                $sheet->setCellValue("AH$row", $detalle->colgador);
                $sheet->setCellValue("AI$row", $detalle->gabinete);
                
                $sheet->setCellValue("AJ$row", $detalle->observaciones);
                $order++;

                $row++;
            }
            
            // Eliminar la fila 23 que se utilizó como referencia
            $sheet->removeRow(12);

        // Guardar el archivo Excel generado
        $filePath = storage_path('app/template_inspeccion_de_extintores.xlsx');
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