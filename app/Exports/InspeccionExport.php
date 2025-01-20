<?php

namespace App\Exports;

use App\Models\Inspeccion;
use App\Models\Inspeccione;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;

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
        // $inspecciones = Inspeccione::all();

        // Llenar datos en las celdas correspondientes
            // Ajustar las celdas de acuerdo al formato
            $sheet->setCellValue('B8',  $this->inspeccion->numero_registro);
            $sheet->setCellValue('A12', $this->inspeccion->empresa->razon_social);
            $sheet->setCellValue('D12', $this->inspeccion->empresa->ruc);
            $sheet->setCellValue('F12', $this->inspeccion->empresa->domicilio);
            $sheet->setCellValue('I12', $this->inspeccion->empresa->actividad_economica);
            $sheet->setCellValue('A14', $this->inspeccion->areas()->pluck('name')->implode(', '));
            $sheet->setCellValue('D14', Carbon::parse($this->inspeccion->fecha_inspeccion)->format('d/m/Y'));
            $sheet->setCellValue('G14', $this->inspeccion->responsables_area()->pluck('name')->implode(', '));
            $sheet->setCellValue('J14', $this->inspeccion->responsables_inspeccion()->pluck('name')->implode(', '));            
            $sheet->setCellValue('A17', $this->inspeccion->hora_inspeccion);

            if($this->inspeccion->tipo_inspeccion == 'Otro'){
                $sheet->setCellValue('I17', $this->inspeccion->tipo_inspeccion_otro);
            }elseif ($this->inspeccion->tipo_inspeccion == 'Planeada') {
                $sheet->setCellValue('D17', 'X');
            }elseif ($this->inspeccion->tipo_inspeccion == 'No Planeada') {
                $sheet->setCellValue('F17', 'X');
            }

            $sheet->setCellValue('A19', $this->inspeccion->objetivo);
            $sheet->setCellValue('A26', $this->inspeccion->descripcion_causa);

            $sheet->setCellValue('A29', $this->inspeccion->conclusiones_recomendaciones);

            $sheet->setCellValue('A32', $this->inspeccion->adjuntar);

            $row = 35; // Comienza a llenar desde la fila 35 (según tu plantilla)
            foreach ($this->inspeccion->responsables_registro as $responsable) {
                // Insertar una nueva fila antes de la fila actual
                $sheet->insertNewRowBefore($row, 1);
                $sheet->getRowDimension($row)->setRowHeight(70);

                $sheet->setCellValue("A$row", "Nombre: ".$responsable->personal->name);           // Descripción
                $sheet->mergeCells("A$row:D$row");
                $sheet->setCellValue("E$row", "Cargo: ".$responsable->cargo->name); // Registro Fotográfico
                $sheet->mergeCells("E$row:G$row");
                $sheet->setCellValue("H$row", "Fecha: ".Carbon::parse($responsable->fecha)->format('d/m/Y'));       // Acción a Tomar
                $sheet->mergeCells("H$row:I$row");
                $sheet->setCellValue("J$row", "Firma: ");          // Responsable
                $sheet->mergeCells("J$row:K$row");
                if ($responsable->firma) {
                    $drawing = new Drawing();
                    $drawing->setName('Firma');
                    $drawing->setDescription('Firma');
                    $drawing->setPath($this->saveBase64Image($responsable->firma));
                    $drawing->setCoordinates("K$row");
                    $drawing->setHeight(70); // Ajusta el tamaño según sea necesario
                    $drawing->setWorksheet($sheet);
                }
                $row++;
            }
            
            // Eliminar la fila 34 que se utilizó como referencia
            $sheet->removeRow(34);
            //eliminar fila 38
            
            $row = 24; // Comienza a llenar desde la fila 24 (según tu plantilla)
            foreach ($this->inspeccion->detalles as $detalle) {
                // Insertar una nueva fila antes de la fila actual
                $sheet->insertNewRowBefore($row, 1);
                $sheet->getRowDimension($row)->setRowHeight(150);

                $sheet->setCellValue("A$row", $detalle->descripcion);                                   // Descripción
                $sheet->mergeCells("A$row:B$row");
                $sheet->mergeCells("C$row:D$row");
                // $sheet->setCellValue("C$row", $detalle->registro_fotografico);                       // Registro Fotográfico
                $sheet->setCellValue("E$row", $detalle->nivel_riesgo);                                  // Acción a Tomar
                $sheet->setCellValue("F$row", $detalle->accion_a_tomar);                                // Acción a Tomar
                $sheet->setCellValue("G$row", $detalle->responsable->name);                             // Responsable
                $sheet->setCellValue("H$row", $detalle->cargo->name);                                   // Responsable
                $sheet->setCellValue("I$row", $detalle->estado);                                        // Estado
                $sheet->setCellValue("J$row", Carbon::parse($detalle->fecha_cierre)->format('d/m/Y'));  // Fecha de Cierre
                    
                if ($detalle->registro_fotografico) {
                    $drawing = new Drawing();
                    $drawing->setName('Registro');
                    $drawing->setDescription('Registro');
                    $drawing->setPath($this->saveBase64Image($detalle->registro_fotografico));
                    $drawing->setCoordinates("C$row");
                    $drawing->setOffsetX(5);    // Mover un poquito a la derecha
                    $drawing->setOffsetY(5);    // Mover un poquito abajo
                    $drawing->setHeight(150);   // Ajusta el tamaño según sea necesario
                    $drawing->setWorksheet($sheet);
                }

                if ($detalle->levantamiento_ejecutado) {
                    $drawing = new Drawing();
                    $drawing->setName('Levantamiento');
                    $drawing->setDescription('Levantamiento');
                    $drawing->setPath($this->saveBase64Image($detalle->levantamiento_ejecutado->registro_fotografico));
                    $drawing->setCoordinates("K$row");
                    $drawing->setOffsetX(5);    // Mover un poquito a la derecha
                    $drawing->setOffsetY(5);    // Mover un poquito abajo
                    $drawing->setHeight(150);   // Ajusta el tamaño según sea necesario
                    $drawing->setWorksheet($sheet);
                }
                $row++;
            }
            
            // Eliminar la fila 23 que se utilizó como referencia
            $sheet->removeRow(23);

        // Guardar el archivo Excel generado
        $filePath = storage_path('app/inspecciones.xlsx');
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