<?php

namespace App\Exports;

use App\Models\Inspeccion;
use App\Models\InspeccionCheckList;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;

class InspeccionCheckListExport
{
    protected $inspeccion;

    public function __construct(InspeccionCheckList $inspeccion)
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
        $templatePath = storage_path('app/templates/template_check_list_sst.xlsx');
        if (!file_exists($templatePath)) {
            throw new \Exception("File \"$templatePath\" does not exist.");
        }
        $spreadsheet = IOFactory::load($templatePath);

        // Seleccionar la hoja de trabajo activa
        $sheet = $spreadsheet->getActiveSheet();

            // Obtener los datos desde la base de datos
            // $inspecciones = InspeccionCheckList::all();

            // Llenar datos en las celdas correspondientes
            // Ajustar las celdas de acuerdo al formato
            
            // $sheet->setCellValue('B8',  $this->inspeccion->numero_registro);
            $sheet->setCellValue('A9', $this->inspeccion->empresa->razon_social);
            $sheet->setCellValue('B9', $this->inspeccion->empresa->ruc);
            $sheet->setCellValue('C9', $this->inspeccion->empresa->domicilio);
            $sheet->setCellValue('G9', $this->inspeccion->empresa->actividad_economica);
            $sheet->setCellValue('H9', $this->inspeccion->num_trabajadores);

            $sheet->setCellValue('B10', ''.$this->inspeccion->area->name);
            $sheet->setCellValue('B11', ''.$this->inspeccion->lugar);
            $sheet->setCellValue('B12', ''.Carbon::parse($this->inspeccion->fecha_hora_inspeccion)->format('d/m/Y H:i'));
            $sheet->setCellValue('H10', ''.$this->inspeccion->inspector->name);
            if ($this->inspeccion->firma) {
                $drawing = new Drawing();
                $drawing->setName('Firma');
                $drawing->setDescription('Firma');
                $drawing->setPath($this->saveBase64Image($this->inspeccion->firma));
                $drawing->setCoordinates("H11");
                $drawing->setHeight(70); // Ajusta el tamaño según sea necesario
                $drawing->setWorksheet($sheet);
            }

            $sheet->setCellValue('A48', $this->inspeccion->observaciones);
            $sheet->mergeCells('A48','H51');

            $row = 16; // Comienza a llenar desde la fila 35 (según tu plantilla)
            $order = 1;
            $detalle = $this->inspeccion->detalles;

            // Zonas Seguras
            if ($detalle->zonas_seguras == 'SI') {
                $sheet->setCellValue("D$row", "X");
            } elseif ($detalle->zonas_seguras == 'NO') {
                $sheet->setCellValue("E$row", "X");
            } elseif ($detalle->zonas_seguras == 'N.A.') {
                $sheet->setCellValue("F$row", "X");
            }
            $sheet->setCellValue("G$row", $detalle->zonas_seguras_comentarios);
            if ($detalle->zonas_seguras_fotografias) {
                $drawing = new Drawing();
                $drawing->setName('Zonas Seguras');
                $drawing->setDescription('Zonas Seguras');
                $drawing->setPath($this->saveBase64Image($detalle->zonas_seguras_fotografias));
                $drawing->setCoordinates("H$row");
                $drawing->setHeight(70); // Ajusta el tamaño según sea necesario
                $drawing->setWorksheet($sheet);
            }         

            // Señalizaciones
            if ($detalle->senalizaciones == 'SI') {
                $sheet->setCellValue("D" . ($row + 1), "X");
            } elseif ($detalle->senalizaciones == 'NO') {
                $sheet->setCellValue("E" . ($row + 1), "X");
            } elseif ($detalle->senalizaciones == 'N.A.') {
                $sheet->setCellValue("F" . ($row + 1), "X");
            }
            $sheet->setCellValue("G" . ($row + 1), $detalle->senalizaciones_comentarios);
            if ($detalle->senalizaciones_fotografias) {
                $drawing = new Drawing();
                $drawing->setName('Señalizaciones');
                $drawing->setDescription('Señalizaciones');
                $drawing->setPath($this->saveBase64Image($detalle->senalizaciones_fotografias));
                $drawing->setCoordinates("H" . ($row + 1));
                $drawing->setHeight(70); // Ajusta el tamaño según sea necesario
                $drawing->setWorksheet($sheet);
            }

            // Extintor Visible
            if ($detalle->extintor_visible == 'SI') {
                $sheet->setCellValue("D" . ($row + 2), "X");
            } elseif ($detalle->extintor_visible == 'NO') {
                $sheet->setCellValue("E" . ($row + 2), "X");
            } elseif ($detalle->extintor_visible == 'N.A.') {
                $sheet->setCellValue("F" . ($row + 2), "X");
            }
            $sheet->setCellValue("G" . ($row + 2), $detalle->extintor_visible_comentarios);
            if ($detalle->extintor_visible_fotografias) {
                $drawing = new Drawing();
                $drawing->setName('Extintor Visible');
                $drawing->setDescription('Extintor Visible');
                $drawing->setPath($this->saveBase64Image($detalle->extintor_visible_fotografias));
                $drawing->setCoordinates("H" . ($row + 2));
                $drawing->setHeight(70); // Ajusta el tamaño según sea necesario
                $drawing->setWorksheet($sheet);
            }

            // Botiquín
            if ($detalle->botiquin == 'SI') {
                $sheet->setCellValue("D" . ($row + 3), "X");
            } elseif ($detalle->botiquin == 'NO') {
                $sheet->setCellValue("E" . ($row + 3), "X");
            } elseif ($detalle->botiquin == 'N.A.') {
                $sheet->setCellValue("F" . ($row + 3), "X");
            }
            $sheet->setCellValue("G" . ($row + 3), $detalle->botiquin_comentarios);
            if ($detalle->botiquin_fotografias) {
                $drawing = new Drawing();
                $drawing->setName('Botiquín');
                $drawing->setDescription('Botiquín');
                $drawing->setPath($this->saveBase64Image($detalle->botiquin_fotografias));
                $drawing->setCoordinates("H" . ($row + 3));
                $drawing->setHeight(70); // Ajusta el tamaño según sea necesario
                $drawing->setWorksheet($sheet);
            }

            // Pisos Limpios
            if ($detalle->pisos_limpios == 'SI') {
                $sheet->setCellValue("D" . ($row + 5), "X");
            } elseif ($detalle->pisos_limpios == 'NO') {
                $sheet->setCellValue("E" . ($row + 5), "X");
            } elseif ($detalle->pisos_limpios == 'N.A.') {
                $sheet->setCellValue("F" . ($row + 5), "X");
            }
            $sheet->setCellValue("G" . ($row + 5), $detalle->pisos_limpios_comentarios);
            if ($detalle->pisos_limpios_fotografias) {
                $drawing = new Drawing();
                $drawing->setName('Pisos Limpios');
                $drawing->setDescription('Pisos Limpios');
                $drawing->setPath($this->saveBase64Image($detalle->pisos_limpios_fotografias));
                $drawing->setCoordinates("H" . ($row + 5));
                $drawing->setHeight(70); // Ajusta el tamaño según sea necesario
                $drawing->setWorksheet($sheet);
            }

            // Iluminación y Ventilación
            if ($detalle->iluminacion_ventilacion == 'SI') {
                $sheet->setCellValue("D" . ($row + 6), "X");
            } elseif ($detalle->iluminacion_ventilacion == 'NO') {
                $sheet->setCellValue("E" . ($row + 6), "X");
            } elseif ($detalle->iluminacion_ventilacion == 'N.A.') {
                $sheet->setCellValue("F" . ($row + 6), "X");
            }
            $sheet->setCellValue("G" . ($row + 6), $detalle->iluminacion_ventilacion_comentarios);
            if ($detalle->iluminacion_ventilacion_fotografias) {
                $drawing = new Drawing();
                $drawing->setName('Iluminación y Ventilación');
                $drawing->setDescription('Iluminación y Ventilación');
                $drawing->setPath($this->saveBase64Image($detalle->iluminacion_ventilacion_fotografias));
                $drawing->setCoordinates("H" . ($row + 6));
                $drawing->setHeight(70); // Ajusta el tamaño según sea necesario
                $drawing->setWorksheet($sheet);
            }

            // Tableros Eléctricos
            if ($detalle->tableros_electricos == 'SI') {
                $sheet->setCellValue("D" . ($row + 7), "X");
            } elseif ($detalle->tableros_electricos == 'NO') {
                $sheet->setCellValue("E" . ($row + 7), "X");
            } elseif ($detalle->tableros_electricos == 'N.A.') {
                $sheet->setCellValue("F" . ($row + 7), "X");
            }
            $sheet->setCellValue("G" . ($row + 7), $detalle->tableros_electricos_comentarios);
            if ($detalle->tableros_electricos_fotografias) {
                $drawing = new Drawing();
                $drawing->setName('Tableros Eléctricos');
                $drawing->setDescription('Tableros Eléctricos');
                $drawing->setPath($this->saveBase64Image($detalle->tableros_electricos_fotografias));
                $drawing->setCoordinates("H" . ($row + 7));
                $drawing->setHeight(70); // Ajusta el tamaño según sea necesario
                $drawing->setWorksheet($sheet);
            }

            // Pasillos Despejados
            if ($detalle->pasillos_despejados == 'SI') {
                $sheet->setCellValue("D" . ($row + 8), "X");
            } elseif ($detalle->pasillos_despejados == 'NO') {
                $sheet->setCellValue("E" . ($row + 8), "X");
            } elseif ($detalle->pasillos_despejados == 'N.A.') {
                $sheet->setCellValue("F" . ($row + 8), "X");
            }
            $sheet->setCellValue("G" . ($row + 8), $detalle->pasillos_despejados_comentarios);
            if ($detalle->pasillos_despejados_fotografias) {
                $drawing = new Drawing();
                $drawing->setName('Pasillos Despejados');
                $drawing->setDescription('Pasillos Despejados');
                $drawing->setPath($this->saveBase64Image($detalle->pasillos_despejados_fotografias));
                $drawing->setCoordinates("H" . ($row + 8));
                $drawing->setHeight(70); // Ajusta el tamaño según sea necesario
                $drawing->setWorksheet($sheet);
            }

            // Escaleras Fijas
            if ($detalle->escaleras_fijas == 'SI') {
                $sheet->setCellValue("D" . ($row + 9), "X");
            } elseif ($detalle->escaleras_fijas == 'NO') {
                $sheet->setCellValue("E" . ($row + 9), "X");
            } elseif ($detalle->escaleras_fijas == 'N.A.') {
                $sheet->setCellValue("F" . ($row + 9), "X");
            }
            $sheet->setCellValue("G" . ($row + 9), $detalle->escaleras_fijas_comentarios);
            if ($detalle->escaleras_fijas_fotografias) {
                $drawing = new Drawing();
                $drawing->setName('Escaleras Fijas');
                $drawing->setDescription('Escaleras Fijas');
                $drawing->setPath($this->saveBase64Image($detalle->escaleras_fijas_fotografias));
                $drawing->setCoordinates("H" . ($row + 9));
                $drawing->setHeight(70); // Ajusta el tamaño según sea necesario
                $drawing->setWorksheet($sheet);
            }

            // Estantes de Almacenes
            if ($detalle->estantes_almacenes == 'SI') {
                $sheet->setCellValue("D" . ($row + 10), "X");
            } elseif ($detalle->estantes_almacenes == 'NO') {
                $sheet->setCellValue("E" . ($row + 10), "X");
            } elseif ($detalle->estantes_almacenes == 'N.A.') {
                $sheet->setCellValue("F" . ($row + 10), "X");
            }
            $sheet->setCellValue("G" . ($row + 10), $detalle->estantes_almacenes_comentarios);
            if ($detalle->estantes_almacenes_fotografias) {
                $drawing = new Drawing();
                $drawing->setName('Estantes de Almacenes');
                $drawing->setDescription('Estantes de Almacenes');
                $drawing->setPath($this->saveBase64Image($detalle->estantes_almacenes_fotografias));
                $drawing->setCoordinates("H" . ($row + 10));
                $drawing->setHeight(70); // Ajusta el tamaño según sea necesario
                $drawing->setWorksheet($sheet);
            }

            // Espacios Suficientes
            if ($detalle->espacios_suficientes == 'SI') {
                $sheet->setCellValue("D" . ($row + 17), "X");
            } elseif ($detalle->espacios_suficientes == 'NO') {
                $sheet->setCellValue("E" . ($row + 17), "X");
            } elseif ($detalle->espacios_suficientes == 'N.A.') {
                $sheet->setCellValue("F" . ($row + 17), "X");
            }
            $sheet->setCellValue("G" . ($row + 17), $detalle->espacios_suficientes_comentarios);
            if ($detalle->espacios_suficientes_fotografias) {
                $drawing = new Drawing();
                $drawing->setName('Espacios Suficientes');
                $drawing->setDescription('Espacios Suficientes');
                $drawing->setPath($this->saveBase64Image($detalle->espacios_suficientes_fotografias));
                $drawing->setCoordinates("H" . ($row + 17));
                $drawing->setHeight(70); // Ajusta el tamaño según sea necesario
                $drawing->setWorksheet($sheet);
            }

            // Escaleras Móviles Estado
            if ($detalle->escaleras_moviles_estado == 'SI') {
                $sheet->setCellValue("D" . ($row + 19), "X");
            } elseif ($detalle->escaleras_moviles_estado == 'NO') {
                $sheet->setCellValue("E" . ($row + 19), "X");
            } elseif ($detalle->escaleras_moviles_estado == 'N.A.') {
                $sheet->setCellValue("F" . ($row + 19), "X");
            }
            $sheet->setCellValue("G" . ($row + 19), $detalle->escaleras_moviles_estado_comentarios);
            if ($detalle->escaleras_moviles_estado_fotografias) {
                $drawing = new Drawing();
                $drawing->setName('Escaleras Móviles Estado');
                $drawing->setDescription('Escaleras Móviles Estado');
                $drawing->setPath($this->saveBase64Image($detalle->escaleras_moviles_estado_fotografias));
                $drawing->setCoordinates("H" . ($row + 19));
                $drawing->setHeight(70); // Ajusta el tamaño según sea necesario
                $drawing->setWorksheet($sheet);
            }

            // Escaleras Móviles Espacio
            if ($detalle->escaleras_moviles_espacio == 'SI') {
                $sheet->setCellValue("D" . ($row + 20), "X");
            } elseif ($detalle->escaleras_moviles_espacio == 'NO') {
                $sheet->setCellValue("E" . ($row + 20), "X");
            } elseif ($detalle->escaleras_moviles_espacio == 'N.A.') {
                $sheet->setCellValue("F" . ($row + 20), "X");
            }
            $sheet->setCellValue("G" . ($row + 20), $detalle->escaleras_moviles_espacio_comentarios);
            if ($detalle->escaleras_moviles_espacio_fotografias) {
                $drawing = new Drawing();
                $drawing->setName('Escaleras Móviles Espacio');
                $drawing->setDescription('Escaleras Móviles Espacio');
                $drawing->setPath($this->saveBase64Image($detalle->escaleras_moviles_espacio_fotografias));
                $drawing->setCoordinates("H" . ($row + 20));
                $drawing->setHeight(70); // Ajusta el tamaño según sea necesario
                $drawing->setWorksheet($sheet);
            }

            // Protección de Máquinas
            if ($detalle->proteccion_maquinas == 'SI') {
                $sheet->setCellValue("D" . ($row + 21), "X");
            } elseif ($detalle->proteccion_maquinas == 'NO') {
                $sheet->setCellValue("E" . ($row + 21), "X");
            } elseif ($detalle->proteccion_maquinas == 'N.A.') {
                $sheet->setCellValue("F" . ($row + 21), "X");
            }
            $sheet->setCellValue("G" . ($row + 21), $detalle->proteccion_maquinas_comentarios);
            if ($detalle->proteccion_maquinas_fotografias) {
                $drawing = new Drawing();
                $drawing->setName('Protección de Máquinas');
                $drawing->setDescription('Protección de Máquinas');
                $drawing->setPath($this->saveBase64Image($detalle->proteccion_maquinas_fotografias));
                $drawing->setCoordinates("H" . ($row + 21));
                $drawing->setHeight(70); // Ajusta el tamaño según sea necesario
                $drawing->setWorksheet($sheet);
            }

            // Materiales Almacenados
            if ($detalle->materiales_almacenados == 'SI') {
                $sheet->setCellValue("D" . ($row + 23), "X");
            } elseif ($detalle->materiales_almacenados == 'NO') {
                $sheet->setCellValue("E" . ($row + 23), "X");
            } elseif ($detalle->materiales_almacenados == 'N.A.') {
                $sheet->setCellValue("F" . ($row + 23), "X");
            }
            $sheet->setCellValue("G" . ($row + 23), $detalle->materiales_almacenados_comentarios);
            if ($detalle->materiales_almacenados_fotografias) {
                $drawing = new Drawing();
                $drawing->setName('Materiales Almacenados');
                $drawing->setDescription('Materiales Almacenados');
                $drawing->setPath($this->saveBase64Image($detalle->materiales_almacenados_fotografias));
                $drawing->setCoordinates("H" . ($row + 23));
                $drawing->setHeight(70); // Ajusta el tamaño según sea necesario
                $drawing->setWorksheet($sheet);
            }

            // EPP e Indumentaria
            if ($detalle->epp_indumentaria == 'SI') {
                $sheet->setCellValue("D" . ($row + 24), "X");
            } elseif ($detalle->epp_indumentaria == 'NO') {
                $sheet->setCellValue("E" . ($row + 24), "X");
            } elseif ($detalle->epp_indumentaria == 'N.A.') {
                $sheet->setCellValue("F" . ($row + 24), "X");
            }
            $sheet->setCellValue("G" . ($row + 24), $detalle->epp_indumentaria_comentarios);
            if ($detalle->epp_indumentaria_fotografias) {
                $drawing = new Drawing();
                $drawing->setName('EPP e Indumentaria');
                $drawing->setDescription('EPP e Indumentaria');
                $drawing->setPath($this->saveBase64Image($detalle->epp_indumentaria_fotografias));
                $drawing->setCoordinates("H" . ($row + 24));
                $drawing->setHeight(70); // Ajusta el tamaño según sea necesario
                $drawing->setWorksheet($sheet);
            }

            // Personal Función
            if ($detalle->personal_funcion == 'SI') {
                $sheet->setCellValue("D" . ($row + 26), "X");
            } elseif ($detalle->personal_funcion == 'NO') {
                $sheet->setCellValue("E" . ($row + 26), "X");
            } elseif ($detalle->personal_funcion == 'N.A.') {
                $sheet->setCellValue("F" . ($row + 26), "X");
            }
            $sheet->setCellValue("G" . ($row + 26), $detalle->personal_funcion_comentarios);
            if ($detalle->personal_funcion_fotografias) {
                $drawing = new Drawing();
                $drawing->setName('Personal Función');
                $drawing->setDescription('Personal Función');
                $drawing->setPath($this->saveBase64Image($detalle->personal_funcion_fotografias));
                $drawing->setCoordinates("H" . ($row + 26));
                $drawing->setHeight(70); // Ajusta el tamaño según sea necesario
                $drawing->setWorksheet($sheet);
            }

            // EPP Uso Correcto
            if ($detalle->epp_uso_correcto == 'SI') {
                $sheet->setCellValue("D" . ($row + 27), "X");
            } elseif ($detalle->epp_uso_correcto == 'NO') {
                $sheet->setCellValue("E" . ($row + 27), "X");
            } elseif ($detalle->epp_uso_correcto == 'N.A.') {
                $sheet->setCellValue("F" . ($row + 27), "X");
            }
            $sheet->setCellValue("G" . ($row + 27), $detalle->epp_uso_correcto_comentarios);
            if ($detalle->epp_uso_correcto_fotografias) {
                $drawing = new Drawing();
                $drawing->setName('EPP Uso Correcto');
                $drawing->setDescription('EPP Uso Correcto');
                $drawing->setPath($this->saveBase64Image($detalle->epp_uso_correcto_fotografias));
                $drawing->setCoordinates("H" . ($row + 27));
                $drawing->setHeight(70); // Ajusta el tamaño según sea necesario
                $drawing->setWorksheet($sheet);
            }

            // Área Limpia
            if ($detalle->area_limpia == 'SI') {
                $sheet->setCellValue("D" . ($row + 29), "X");
            } elseif ($detalle->area_limpia == 'NO') {
                $sheet->setCellValue("E" . ($row + 29), "X");
            } elseif ($detalle->area_limpia == 'N.A.') {
                $sheet->setCellValue("F" . ($row + 29), "X");
            }
            $sheet->setCellValue("G" . ($row + 29), $detalle->area_limpia_comentarios);
            if ($detalle->area_limpia_fotografias) {
                $drawing = new Drawing();
                $drawing->setName('Área Limpia');
                $drawing->setDescription('Área Limpia');
                $drawing->setPath($this->saveBase64Image($detalle->area_limpia_fotografias));
                $drawing->setCoordinates("H" . ($row + 29));
                $drawing->setHeight(70); // Ajusta el tamaño según sea necesario
                $drawing->setWorksheet($sheet);
            }

            // Residuos Dispuestos
            if ($detalle->residuos_dispuestos == 'SI') {
                $sheet->setCellValue("D" . ($row + 30), "X");
            } elseif ($detalle->residuos_dispuestos == 'NO') {
                $sheet->setCellValue("E" . ($row + 30), "X");
            } elseif ($detalle->residuos_dispuestos == 'N.A.') {
                $sheet->setCellValue("F" . ($row + 30), "X");
            }
            $sheet->setCellValue("G" . ($row + 30), $detalle->residuos_dispuestos_comentarios);
            if ($detalle->residuos_dispuestos_fotografias) {
                $drawing = new Drawing();
                $drawing->setName('Residuos Dispuestos');
                $drawing->setDescription('Residuos Dispuestos');
                $drawing->setPath($this->saveBase64Image($detalle->residuos_dispuestos_fotografias));
                $drawing->setCoordinates("H" . ($row + 30));
                $drawing->setHeight(70); // Ajusta el tamaño según sea necesario
                $drawing->setWorksheet($sheet);
            }

            // foreach ($this->inspeccion->detalles as $detalle) {
            //     // Insertar una nueva fila antes de la fila actual
            //     $sheet->insertNewRowBefore($row, 1);

            //     $sheet->setCellValue("A$row", $order.'');//ITEM
            //     $sheet->setCellValue("B$row", $detalle->area ? $detalle->area->name : '');//N° DE GABINETE
            //     $sheet->setCellValue("I$row", ($detalle->enciende=="1" ? "X":""));            
            //     $sheet->setCellValue("J$row", ($detalle->enciende=="0" ? "X":""));
            //     $sheet->setCellValue("K$row", ($detalle->buen_estado=="1" ? "X":""));
            //     $sheet->setCellValue("L$row", ($detalle->buen_estado=="0" ? "X":""));
            //     $sheet->setCellValue("M$row", ($detalle->buena_iluminacion=="1" ? "X":""));
            //     $sheet->setCellValue("N$row", ($detalle->buena_iluminacion=="0" ? "X":""));                
            //     $sheet->setCellValue("O$row", ($detalle->buena_ubicacion=="1" ? "X":""));
            //     $sheet->setCellValue("P$row", ($detalle->buena_ubicacion=="0" ? "X":""));
            //     $sheet->setCellValue("Q$row", ($detalle->conectado=="1" ? "X":""));
            //     $sheet->setCellValue("R$row", ($detalle->conectado=="0" ? "X":""));
            //     $sheet->setCellValue("S$row", ($detalle->senalizado=="1" ? "X":""));
            //     $sheet->setCellValue("T$row", ($detalle->senalizado=="0" ? "X":""));
            //     $sheet->setCellValue("U$row", ($detalle->partes()->pluck('name')->implode(', ') ));
            //     $order++;

            //     $row++;
            // }
            
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