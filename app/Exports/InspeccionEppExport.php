<?php

namespace App\Exports;

use App\Models\Inspeccion;
use App\Models\InspeccionesEpp;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;

class InspeccionEppExport
{
    protected $inspeccion;

    public function __construct(InspeccionesEpp $inspeccion)
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
        $templatePath = storage_path('app/templates/template_inspeccion_epp.xlsx');
        if (!file_exists($templatePath)) {
            throw new \Exception("File \"$templatePath\" does not exist.");
        }
        $spreadsheet = IOFactory::load($templatePath);

        // Seleccionar la hoja de trabajo activa
        $sheet = $spreadsheet->getActiveSheet();

        // Obtener los datos desde la base de datos
        $inspecciones = InspeccionesEpp::all();

        // Llenar datos en las celdas correspondientes
            // Ajustar las celdas de acuerdo al formato
            $sheet->setCellValue('A7', 'RAZON SOCIAL O DENOMINACION SOCIAL: '.$this->inspeccion->empresa->name);
            $sheet->setCellValue('A11', 'INSPECTOR: '.$this->inspeccion->inspector->name);
            $sheet->setCellValue('A8', 'INSPECCIÓN DE USO DE EPP POR AREAS: '.$this->inspeccion->area->name);
            $sheet->setCellValue('A14', 'ACTIVIDAD '.$this->inspeccion->actividad);
            $sheet->setCellValue('U12', 'Fecha: '.Carbon::parse($this->inspeccion->fecha)->format('d/m/Y'));
            $sheet->setCellValue('U11', 'Nº INSPECCIÓN:: '.$this->inspeccion->numero_inspeccion);
            $sheet->setCellValue('U14', 'RIESGO: '.$this->inspeccion->riesgo);
            
            if  ($this->inspeccion->turno == 'DIA') {
                $sheet->setCellValue('U12', 'DIA: X');
            }elseif ($this->inspeccion->turno == 'NOCHE') {
                $sheet->setCellValue('U13', 'NOCHE: X');
            }

            if ($this->inspeccion->condicion == 'BUENO') {
                $sheet->setCellValue('AB13', '√');
            }elseif ($this->inspeccion->condicion == 'MALO') {
                $sheet->setCellValue('AC13', 'X');
            }

            $sheet->setCellValue('G24',$this->inspeccion->resultado);

            if ($this->inspeccion->firma) {
                $drawing = new Drawing();
                $drawing->setName('Firma');
                $drawing->setDescription('Firma');
                $drawing->setPath($this->saveBase64Image($this->inspeccion->firma));
                $drawing->setCoordinates("N12");
                $drawing->setHeight(70); // Ajusta el tamaño según sea necesario
                $drawing->setWorksheet($sheet);
            }

            // Agregar "otros" a partir de la columna W
            $column = 'W';
            foreach ($this->inspeccion->otros as $otro) {
                // Combinar celdas para el título "OTROS: $otro->name"
                $sheet->mergeCells($column . '17:' . chr(ord($column) + 2) . '17');
                $sheet->setCellValue($column . '17', 'OTROS: ' . $otro->name);

                // Combinar celdas para "TIENE EPP"
                $sheet->mergeCells($column . '18:' . $column . '20');
                $sheet->setCellValue($column . '18', 'TIENE EPP');

                // Combinar celdas para "USO (SI/NO)"
                $sheet->mergeCells(chr(ord($column) + 1) . '18:' . chr(ord($column) + 1) . '20');
                $sheet->setCellValue(chr(ord($column) + 1) . '18', 'USO (SI/NO)');

                // Combinar celdas para "CONDICION"
                $sheet->mergeCells(chr(ord($column) + 2) . '18:' . chr(ord($column) + 2) . '20');
                $sheet->setCellValue(chr(ord($column) + 2) . '18', 'CONDICION');

                // Avanzar a la siguiente columna para el próximo "otro"
                $column = chr(ord($column) + 3);
            }

            $row = 23; // Comienza a llenar desde la fila 35 (según tu plantilla)
            $order = 1;
            foreach ($this->inspeccion->detalles as $detalle) {
                // Insertar una nueva fila antes de la fila actual
                $sheet->insertNewRowBefore($row, 1);
                // $sheet->getRowDimension($row)->setRowHeight(150);

                $sheet->setCellValue("A$row", $order.'.-');//ITEM
                $sheet->setCellValue("B$row", $detalle->personal->name ?? $detalle->nombre_trabajador );//N° DE GABINETE
                $sheet->setCellValue("D$row", $detalle->cargo->name ?? $detalle->cargo_personal );//N° DE GABINETE
                $sheet->setCellValue("C$row", $detalle->personal->dni ?? $detalle->dni_personal);//N° DE GABINETE

                $sheet->setCellValue("E$row", $detalle->casco_tiene);
                $sheet->setCellValue("F$row", $detalle->casco_uso);
                $sheet->setCellValue("G$row", $detalle->casco_condicion);
                $sheet->setCellValue("H$row", $detalle->zapatos_tiene);
                $sheet->setCellValue("I$row", $detalle->zapatos_uso);
                $sheet->setCellValue("J$row", $detalle->zapatos_condicion);
                $sheet->setCellValue("K$row", $detalle->lentes_tiene);
                $sheet->setCellValue("L$row", $detalle->lentes_uso);
                $sheet->setCellValue("M$row", $detalle->lentes_condicion);
                $sheet->setCellValue("N$row", $detalle->respirador_tiene);
                $sheet->setCellValue("O$row", $detalle->respirador_uso);
                $sheet->setCellValue("P$row", $detalle->respirador_condicion);
                $sheet->setCellValue("Q$row", $detalle->protector_auditivo_tiene);
                $sheet->setCellValue("R$row", $detalle->protector_auditivo_uso);
                $sheet->setCellValue("S$row", $detalle->protector_auditivo_condicion);
                $sheet->setCellValue("T$row", $detalle->guantes_tiene);
                $sheet->setCellValue("U$row", $detalle->guantes_uso);
                $sheet->setCellValue("V$row", $detalle->guantes_condicion);

                // ahora voy a llenar los otros de los cuales ya coloque las columnas
                $column = 'W';
                foreach ($this->inspeccion->otros as $otro) {
                    $detalleOtro = $detalle->detallesEppOtros->where('inspeccion_epp_otro_id', $otro->id)->first();
                    if ($detalleOtro) {
                        $sheet->setCellValue($column . $row, $detalleOtro->tiene);
                        $sheet->setCellValue(chr(ord($column) + 1) . $row, $detalleOtro->uso);
                        $sheet->setCellValue(chr(ord($column) + 2) . $row, $detalleOtro->condicion);
                    } else {
                        $sheet->setCellValue($column . $row, '');
                        $sheet->setCellValue(chr(ord($column) + 1) . $row, '');
                        $sheet->setCellValue(chr(ord($column) + 2) . $row, '');
                    }
                    $column = chr(ord($column) + 3);
                }

                $order++;

                $row++;
            }
            
            // Eliminar la fila 23 que se utilizó como referencia
            $sheet->removeRow(22);

        // Guardar el archivo Excel generado
        $filePath = storage_path('app/inspeccion_epp.xlsx');
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