<?php

namespace App\Imports;

use App\Models\Area;
use App\Models\Cargo;
use App\Models\Personal;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

class PersonalFlexibleImport implements ToCollection
{
    protected bool $dryRun;
    protected array $errores = [];
    protected int $insertados = 0;
    protected int $actualizados = 0;

    protected int $simInsertados = 0;
    protected int $simActualizados = 0;

    protected array $areasPorCrear = [];
    protected array $cargosPorCrear = [];

    protected array $mapKeys = [
        'DNI'             => ['DNI'],
        'AREA'            => ['AREA'],
        'PUESTO'          => ['PUESTO','CARGO'],
        'EMPRESA'         => ['EMPRESA','IDEMPRESA','ID_EMPRESA','EMPRESA_ID'],
        'TIPO_DE_PUESTO'  => ['TIPO DE PUESTO','TIPO_PUESTO','TIPO PUESTO'],
        'DNI_SUPERIOR'    => ['DNI SUPERIOR','DNI_SUPERIOR','SUPERIOR DNI'],
        'CORREO'          => ['CORREO','CORREO EMPRESA','CORREO_EMPRESA','EMAIL','E-MAIL'],
        'APELLIDO_PATERNO' => ['APELLIDO PATERNO','APELLIDO_PATERNO','APELLIDO1'],
        'APELLIDO_MATERNO' => ['APELLIDO MATERNO','APELLIDO_MATERNO','APELLIDO2'],
        'NOMBRES'         => ['NOMBRES','NOMBRE'],
        'NOMBRES_COMPLETOS'   => ['NOMBRES COMPLETOS','NOMBRES_COMPLETOS','NOMBRES Y APELLIDOS','NOMBRES_Y_APELLIDOS'],

    ];

    protected array $headerIndex = [];

    public function __construct(bool $dryRun = false)
    {
        $this->dryRun = $dryRun;
    }

    public function collection(Collection $rows)
    {
        if ($rows->isEmpty()) {
            $this->errores[] = 'Archivo vacío.';
            return;
        }

        $headerRow = $rows->shift()->toArray();
        $this->buildHeaderIndex($headerRow);

        if (!isset($this->headerIndex['DNI'])) {
            $this->errores[] = 'La cabecera DNI es obligatoria.';
            return;
        }

        $linea = 2;
        foreach ($rows as $row) {
            $rowArray = is_array($row) ? $row : $row->toArray();
            if ($this->rowIsEmpty($rowArray)) { $linea++; continue; }

            $dni = trim((string)$this->getCell($rowArray,'DNI'));
            if (!$dni || !preg_match('/^\d{8}$/',$dni)) {
                $this->errores[] = "Línea $linea: DNI inválido o vacío.";
                $linea++; continue;
            }

            try {
                $personal = Personal::where('dni',$dni)->first();

                $areaNombre       = $this->clean($this->getCell($rowArray,'AREA'));
                $puestoNombre     = $this->clean($this->getCell($rowArray,'PUESTO'));
                $empresaValor     = $this->clean($this->getCell($rowArray,'EMPRESA'));
                $tipoPuestoNombre = $this->clean($this->getCell($rowArray,'TIPO_DE_PUESTO'));
                $dniSuperior      = $this->clean($this->getCell($rowArray,'DNI_SUPERIOR'));
                $correoEmpresa    = $this->clean($this->getCell($rowArray,'CORREO'));
                $apellidoPaterno   = $this->clean($this->getCell($rowArray,'APELLIDO_PATERNO'));
                $apellidoMaterno   = $this->clean($this->getCell($rowArray,'APELLIDO_MATERNO'));
                $nombres           = $this->clean($this->getCell($rowArray,'NOMBRES'));
                $nombresCompletos  = $this->clean($this->getCell($rowArray,'NOMBRES_COMPLETOS'));

                $area = null;
                $empresaId = null;
                if ($empresaValor) {
                    if (is_numeric($empresaValor)) {
                        $empresa = \App\Models\Empresa::where('id', (int)$empresaValor)->first();
                        if (!$empresa) {
                            $empresa = \App\Models\Empresa::firstOrCreate(['name' => $empresaValor], ['estado' => 1]);
                        }
                    } else {
                        $empresa = \App\Models\Empresa::where('name', $empresaValor)->first();
                        if (!$empresa) {
                            $empresa = \App\Models\Empresa::create(['name' => $empresaValor, 'estado' => 1]);
                        }
                    }
                    $empresaId = $empresa->id ?? null;
                }
                if ($areaNombre) {
                    $area = Area::where('name',$areaNombre)->where('empresa_id', $empresaId)->first();
                    if (!$area) {
                        if ($this->dryRun) {
                            $this->areasPorCrear[$areaNombre] = true;
                        } else {
                            $data = ['name' => $areaNombre];
                            if ($empresaId) $data['empresa_id'] = $empresaId;
                            $area = Area::create($data);
                        }
                    }
                }

                $cargo = null;
                if ($puestoNombre) {
                    $cargo = Cargo::where('name',$puestoNombre)->where('empresa_id', $empresaId)->first();
                    if (!$cargo) {
                        if ($this->dryRun) {
                            $this->cargosPorCrear[$puestoNombre] = true;
                        } else {
                            $data = ['name' => $puestoNombre];
                            if ($empresaId) $data['empresa_id'] = $empresaId;
                            $cargo = Cargo::create($data);
                        }
                    }
                }

                $superior = null;
                if ($dniSuperior && preg_match('/^\d{8}$/',$dniSuperior)) {
                    $superior = Personal::where('dni',$dniSuperior)->first();
                }

                if ($personal) {
                    $update = [];
                    if ($empresaId) $update['empresa_id'] = $empresaId;
                    if ($correoEmpresa) $update['correo_empresa'] = $correoEmpresa;
                    if ($area && !$this->dryRun) $update['area_id'] = $area->id;
                    elseif ($areaNombre && !$area && $this->dryRun) { /* ya registrado en areasPorCrear */ }
                    if ($cargo && !$this->dryRun) $update['cargo_id'] = $cargo->id;
                    if ($superior && !$this->dryRun) $update['reporta_a'] = $superior->id;

                    if (!empty($update)) {
                        if ($this->dryRun) {
                            $this->simActualizados++;
                        } else {
                            $personal->update($update);
                            $this->actualizados++;
                        }
                    }
                } else {
                    if ($this->dryRun) {
                        $this->simInsertados++;
                    } else {
                        $create = [
                            'dni'=>$dni,
                            'name'=>$dni,
                            'nombres'=>'',
                            'apellido_paterno'=>'',
                            'apellido_materno'=>'',
                            'estado'=>1,
                            'seleccionado'=>0,
                            'apellido_paterno' => $apellidoPaterno,
                            'apellido_materno' => $apellidoMaterno,
                            'nombres' => $nombres,
                            'name' => $nombresCompletos ?: $dni,
                        ];
                        if ($empresaId) $create['empresa_id'] = $empresaId;
                        if ($correoEmpresa) $create['correo_empresa']=$correoEmpresa;
                        if ($area) $create['area_id']=$area->id;
                        if ($cargo) $create['cargo_id']=$cargo->id;
                        if ($superior) $create['reporta_a']=$superior->id;
                        Personal::create($create);
                        $this->insertados++;
                    }
                }
            } catch (\Throwable $e) {
                $this->errores[] = "Línea $linea: error inesperado ({$e->getMessage()})";
            }
            $linea++;
        }
    }

    protected function buildHeaderIndex(array $headerRow): void
    {
        foreach ($headerRow as $idx=>$rawHeader) {
            $normalized = $this->normalizeHeader((string)$rawHeader);
            if ($normalized==='') continue;
            foreach ($this->mapKeys as $clave=>$variantes) {
                foreach ($variantes as $var) {
                    if ($this->normalizeHeader($var)===$normalized) {
                        $this->headerIndex[$clave]=$idx;
                    }
                }
            }
        }
    }
    protected function getCell(array $row,string $key){ if(!isset($this->headerIndex[$key])) return null; $i=$this->headerIndex[$key]; return $row[$i]??null; }
    protected function normalizeHeader(string $h):string{
        $h=trim(mb_strtoupper($h));
        $h=str_replace(['Á','É','Í','Ó','Ú','Ü'],['A','E','I','O','U','U'],$h);
        $h=preg_replace('/[^A-Z0-9 ]/',' ',$h);
        return preg_replace('/\s+/',' ',$h);
    }
    protected function clean($v){
        if($v===null) return null;
        $v=trim((string)$v);
        return $v===''?null:$v;
    }
    protected function rowIsEmpty(array $row):bool{
        foreach($row as $v){ if(trim((string)$v)!=='') return false; }
        return true;
    }

    // Getters
    public function tieneErrores():bool { return !empty($this->errores); }
    public function getErrores():array { return $this->errores; }
    public function getInsertados():int { return $this->insertados; }
    public function getActualizados():int { return $this->actualizados; }
    public function getSimInsertados():int { return $this->simInsertados; }
    public function getSimActualizados():int { return $this->simActualizados; }
    public function getAreasPorCrear():array { return array_keys($this->areasPorCrear); }
    public function getCargosPorCrear():array { return array_keys($this->cargosPorCrear); }
}
