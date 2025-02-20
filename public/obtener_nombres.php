<?php
require '../vendor/autoload.php'; // Cargar PhpSpreadsheet
use PhpOffice\PhpSpreadsheet\IOFactory;

// 📌 Configuración
$archivoExcel = "ASIGNADOS LAPTOPS.xlsx";
$urlBase = "https://apps.vanguardfresh.pe/sv/ivg/api/manager/capacitaciones/personal/";
$bearerToken = "eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJhdWQiOiIzIiwianRpIjoiNTgzY2M0YTQ3Mjc2MjE0NjQzNGI0ZmE3Y2ZhZGNmMjExMjhjZTBhYmMzYzRhZGQ2OTZhMmM1YmY3MTcwN2Q5NTAzOWU0OGZmY2ZjODAxNTEiLCJpYXQiOjE3MzEwMDIzNzUuNjQwOTc1LCJuYmYiOjE3MzEwMDIzNzUuNjQwOTc3LCJleHAiOjE3NjI1MzgzNzUuNjM0Nzc2LCJzdWIiOiIyIiwic2NvcGVzIjpbImRvY3VtZW50b3NwZXJzb25hbF9zaG93Il19.Fa38pZGyGXzrCD9TaqKxfAovllD5RaW2cUHceNVAWNwyCQjBdRvnDNP7vIl_FVsOKeHevU1fCfL1nrL68fvtBxDQpT6ZNM-yfBsz6hE-S-ckglvCDCW8sPAi2j3AWqABZZoNY3nz-w0M-8iiapNUfZKvH256ZkCk97Rx4DrgX4Qm62ICUzY2WBLJJHudnr4wiAAPFHSzovaTYjxaXGE-D4PwGZQCVznlMwbK3th3ytexQvH9f3BXw4tsKIUYF1Tixzyj7s9Q1r-Xzk6T8G0W4HkSyPKHeVXeVnqxTycMCav7nNvPs8OeKr8ZkUmPFn5rLV2epexbAee--ycPopNnzNoWh0lXf6UIux7K8WyiFyEZsYVmEBJ8XPCK2TODhdMLrN9HLd1otHoKLnE9FYkOLLxUgpZNiNQNSC20iGhDfrVrC2YEDfbaV22y8GScdQ8GxZw1qi4H2QHRa7mIEZ5GTCjVXh1ZGpWGZeCJX7_Wy5VvIUnwqr7N_JtIGiFa6RaHZE8uyQb7i8pWzqEmno3m-q1LfSe4ArTSnm-TBuxAlAEMwN78nBh9LCAbDGvtLb8G1xWYBLHJuPh3ojxfR3CtWtWnBFl3HcdZT_faXNbQzuNRTA2i2-JbIpz_AxXKjlO4gjv9DA8W5yVSciRy-tF2bmlmTuQc-3XIbxRcQSmmvlU"; // Coloca aquí tu token completo

// 📌 Cargar el archivo Excel
$spreadsheet = IOFactory::load($archivoExcel);
$hoja = $spreadsheet->getActiveSheet();

// 📌 Recorremos los DNIs en la columna A (desde la fila 2)
$filaInicial = 2;

while (true) {
    $dni = $hoja->getCell("F$filaInicial")->getValue();
    $nombre = $hoja->getCell("N$filaInicial")->getValue();
    // echo "⚠️ DNI $dni \n";

    if ($filaInicial == 238) break; // Si ya no hay más DNIs, terminamos
    if ($dni && $nombre === "No encontrado") { // Si ya no hay más DNIs, terminamos
        // echo "⚠️ DNI $dni \n";
        $url = $urlBase . trim($dni);
        // dd($url);
        echo "🔎 Buscando DNI $dni ";

        // 📌 Configurar la petición HTTP con el token
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "Authorization: Bearer $bearerToken",
            "Accept: application/json"
        ]);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // Deshabilitar la verificación SSL
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false); // Deshabilitar la verificación SSL

        $respuesta = curl_exec($ch);
        // if ($filaInicial == 3) {
        //     dd($ch);
        // }
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpCode == 200) {
            $data = json_decode($respuesta, true);
            if (!empty($data) && is_array($data)) {
                $nombreCompleto = $data[0]['nombrecompleto'] ?? "No encontrado";
                $hoja->setCellValue("N$filaInicial", $nombreCompleto); // Escribir en la columna B
                echo "✅ DNI $dni: $nombreCompleto\n<br>";
            } else {
                echo "⚠️ DNI $dni: No encontrado\n<br>";
            }
        } else {
            // echo $respuesta;
            echo "❌ Error en DNI $dni - Código HTTP: $httpCode\n<br>";
            echo "Respuesta de la API: $respuesta\n<br>";

            // break;
        }

        // Enviar la salida al navegador inmediatamente
        ob_flush();
        flush();

    }
    
    $filaInicial++;
    // sleep(1); // Pausa de 1 segundo entre solicitudes
}

// 📌 Guardar cambios en el Excel
$writer = IOFactory::createWriter($spreadsheet, "Xlsx");
$writer->save($archivoExcel);
echo "📂 Archivo actualizado correctamente.\n";
?>
