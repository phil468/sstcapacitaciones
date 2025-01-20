<?php

namespace App\Exports;

use App\Models\Inspeccion;
use App\Models\InspeccionesGabinete;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;

class InspeccionGabineteExport
{
    protected $inspeccion;

    public function __construct(InspeccionesGabinete $inspeccion)
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
        $templatePath = storage_path('app/templates/template_inspeccion_de_gabinetes_de_emergencia.xlsx');
        if (!file_exists($templatePath)) {
            throw new \Exception("File \"$templatePath\" does not exist.");
        }
        $spreadsheet = IOFactory::load($templatePath);

        // Seleccionar la hoja de trabajo activa
        $sheet = $spreadsheet->getActiveSheet();

        // Obtener los datos desde la base de datos
        $inspecciones = InspeccionesGabinete::all();

        // Llenar datos en las celdas correspondientes
            // Ajustar las celdas de acuerdo al formato
            $sheet->setCellValue('O7', 'Lugar: '.$this->inspeccion->area->name ?? $this->inspeccion->lugar);
            $sheet->setCellValue('A7', 'Fecha y Hora de la inspección: '.Carbon::parse($this->inspeccion->fecha_inspeccion)->format('d/m/Y').' '.Carbon::parse($this->inspeccion->hora_inspeccion)->format('h:i a'));
            $sheet->setCellValue('A6', 'Inspector: '.$this->inspeccion->inspector->name);
            // $sheet->setCellValue('G14', $this->inspeccion->responsables_inspeccion()->pluck('name')->implode(', '));            
            // $sheet->setCellValue('A17', $this->inspeccion->hora_inspeccion);

            // if($this->inspeccion->tipo_inspeccion == 'Otro'){
            //     $sheet->setCellValue('I17', $this->inspeccion->tipo_inspeccion_otro);
            // }elseif ($this->inspeccion->tipo_inspeccion == 'Planeada') {
            //     $sheet->setCellValue('D17', 'X');
            // }elseif ($this->inspeccion->tipo_inspeccion == 'No Planeada') {
            //     $sheet->setCellValue('F17', 'X');
            // }

            // $sheet->setCellValue('A19', $this->inspeccion->objetivo);
            // $sheet->setCellValue('A26', $this->inspeccion->descripcion_causa);

            // $sheet->setCellValue('A29', $this->inspeccion->conclusiones_recomendaciones);

            $sheet->setCellValue('F12', $this->inspeccion->resultado);
            $sheet->mergeCells("F12:T12");

            $row = 11; // Comienza a llenar desde la fila 35 (según tu plantilla)
            $order = 1;
            foreach ($this->inspeccion->detalles as $detalle) {
                // Insertar una nueva fila antes de la fila actual
                $sheet->insertNewRowBefore($row, 1);
                // $sheet->getRowDimension($row)->setRowHeight(150);

                $sheet->setCellValue("A$row", $order.'.-');//ITEM
                $sheet->setCellValue("B$row", $detalle->numero_gabinete );//N° DE GABINETE
                $sheet->setCellValue("C$row", $detalle->ubicacion );//N° DE GABINETE
                $sheet->mergeCells("C$row:E$row");
                // $sheet->setCellValue("C$row", $detalle->registro_fotografico);                       // Registro Fotográfico
                // $sheet->setCellValue("E$row", $detalle->nivel_riesgo);                               // Acción a Tomar
                
                 $sheet->setCellValue("F$row", $detalle->enrollada_correctamente);
                //  $sheet->setCellValue("F$row", $detalle->registro_fotografico_enrollada_correctamente);
                 $sheet->setCellValue("G$row", $detalle->acoples_estado);
                //  $sheet->setCellValue("F$row", $detalle->registro_fotografico_acoples_estado);
                 $sheet->setCellValue("H$row", $detalle->limpieza_manguera);
                //  $sheet->setCellValue("F$row", $detalle->registro_fotografico_limpieza_manguera);
                 $sheet->setCellValue("I$row", $detalle->empaques_estado);
                //  $sheet->setCellValue("F$row", $detalle->registro_fotografico_empaques_estado);
                 $sheet->setCellValue("J$row", $detalle->pintura_gabinete);
                //  $sheet->setCellValue("F$row", $detalle->registro_fotografico_pintura_gabinete);
                 $sheet->setCellValue("K$row", $detalle->limpieza_gabinete);
                //  $sheet->setCellValue("F$row", $detalle->registro_fotografico_limpieza_gabinete);
                 $sheet->setCellValue("L$row", $detalle->vidrio_estado);
                //  $sheet->setCellValue("F$row", $detalle->registro_fotografico_vidrio_estado);
                 $sheet->setCellValue("M$row", $detalle->senalizacion);
                //  $sheet->setCellValue("F$row", $detalle->registro_fotografico_senalizacion);
                 $sheet->setCellValue("N$row", $detalle->piton_obstruido);
                //  $sheet->setCellValue("F$row", $detalle->registro_fotografico_piton_obstruido);
                 $sheet->setCellValue("O$row", $detalle->piton_estado);
                //  $sheet->setCellValue("F$row", $detalle->registro_fotografico_piton_estado);
                 $sheet->setCellValue("P$row", $detalle->valvula_principal_estado);
                //  $sheet->setCellValue("F$row", $detalle->registro_fotografico_valvula_principal_estado);
                 $sheet->setCellValue("Q$row", $detalle->valvula_principal_abierta);
                //  $sheet->setCellValue("F$row", $detalle->registro_fotografico_valvula_principal_abierta);
                 $sheet->setCellValue("R$row", $detalle->manometro_estado);
                //  $sheet->setCellValue("F$row", $detalle->registro_fotografico_manometro_estado);
                 $sheet->setCellValue("S$row", $detalle->valvula_angular_estado);
                //  $sheet->setCellValue("F$row", $detalle->registro_fotografico_valvula_angular_estado);
                 $sheet->setCellValue("T$row", $detalle->observaciones);
                 $order++;

                // $sheet->setCellValue("F$row", $detalle->acciones_tomar);                                // Acción a Tomar
                // $sheet->setCellValue("G$row", $detalle->responsable->name);                             // Responsable
                // $sheet->setCellValue("H$row", $detalle->cargo->name);                                   // Responsable
                // $sheet->setCellValue("I$row", $detalle->estado);                                        // Estado
                // $sheet->setCellValue("J$row", Carbon::parse($detalle->fecha_cierre)->format('d/m/Y'));  // Fecha de Cierre
                    
                // if ($detalle->registro_fotografico) {
                //     $drawing = new Drawing();
                //     $drawing->setName('Registro');
                //     $drawing->setDescription('Registro');
                //     $drawing->setPath($this->saveBase64Image($detalle->registro_fotografico));
                //     $drawing->setCoordinates("C$row");
                //     $drawing->setOffsetX(5);    // Mover un poquito a la derecha
                //     $drawing->setOffsetY(5);    // Mover un poquito abajo
                //     $drawing->setHeight(150);   // Ajusta el tamaño según sea necesario
                //     $drawing->setWorksheet($sheet);
                // }

                // if ($detalle->levantamiento_ejecutado) {
                //     $drawing = new Drawing();
                //     $drawing->setName('Levantamiento');
                //     $drawing->setDescription('Levantamiento');
                //     $drawing->setPath($this->saveBase64Image($detalle->levantamiento_ejecutado->registro_fotografico));
                //     $drawing->setCoordinates("K$row");
                //     $drawing->setOffsetX(5);    // Mover un poquito a la derecha
                //     $drawing->setOffsetY(5);    // Mover un poquito abajo
                //     $drawing->setHeight(150);   // Ajusta el tamaño según sea necesario
                //     $drawing->setWorksheet($sheet);
                // }
                $row++;
            }
            
            // Eliminar la fila 23 que se utilizó como referencia
            $sheet->removeRow(10);

        // Guardar el archivo Excel generado
        $filePath = storage_path('app/inspeccion_de_gabinete_de_emergencia.xlsx');
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