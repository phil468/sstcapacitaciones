<?php

namespace App\Exports;

use App\Models\Inspeccion;
use App\Models\Inspeccione;
use Maatwebsite\Excel\Concerns\FromCollection;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class InspeccionExport
{
    protected $inspeccion;

    public function __construct(Inspeccione $inspeccion)
    {
        $this->inspeccion = $inspeccion;
    }

    public function collection()
    {
        // Aquí puedes definir los datos que deseas exportar
        return collect([
            ['ID', 'Descripción', 'Fecha'],
            [$this->inspeccion->id, $this->inspeccion->tipo_inspeccion, $this->inspeccion->fecha_inspeccion]
        ]);
    }

    public function export()
    {
        // Cargar la plantilla desde el archivo
        $templatePath = storage_path('app/templates/template_inspeccion.xlsx');
        if (!file_exists($templatePath)) {
            throw new \Exception("File \"$templatePath\" does not exist.");
        }
        $spreadsheet = IOFactory::load($templatePath);

        // Seleccionar la hoja de trabajo activa
        $sheet = $spreadsheet->getActiveSheet();

        // Obtener los datos desde la base de datos
        $inspecciones = Inspeccione::all();

        // Llenar datos en las celdas correspondientes
        // foreach ($inspecciones as $index => $inspeccion) {
            // Ajustar las celdas de acuerdo al formato
            $sheet->setCellValue('A12', $this->inspeccion->empresa->razon_social);
            $sheet->setCellValue('D12', $this->inspeccion->empresa->ruc);
            $sheet->setCellValue('F12', $this->inspeccion->empresa->domicilio);
            $sheet->setCellValue('I12', $this->inspeccion->empresa->actividad_economica);
            $sheet->setCellValue('B8',  $this->inspeccion->numero_registro);
            $sheet->setCellValue('D14', $this->inspeccion->fecha_inspeccion);
            $sheet->setCellValue('A17', $this->inspeccion->hora_inspeccion);
            // Agrega más campos según sea necesario...
        // }

        // Guardar el archivo Excel generado
        $filePath = storage_path('app/inspecciones.xlsx');
        $writer = new Xlsx($spreadsheet);
        $writer->save($filePath);

        return $filePath;
    }
}