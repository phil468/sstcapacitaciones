<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetallesCheckList extends Model
{
    use HasFactory;

    protected $table = 'detalles_check_list_inspeccion_sst';
    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'inspeccion_id',
        'zonas_seguras', // Existe identificación de zonas seguras, salidas de emergencia, zonas de riesgo
        'zonas_seguras_comentarios',
        'zonas_seguras_fotografias',
        'senalizaciones', // Señalizaciones en buen estado (no rotas, no deterioradas, no cubiertas)
        'senalizaciones_comentarios',
        'senalizaciones_fotografias',
        'extintor_visible', // Existe extintor en un lugar visible y señalizado (no cubierto, ni obstaculizado, con señalética)
        'extintor_visible_comentarios',
        'extintor_visible_fotografias',
        'botiquin', // Existe botiquín de emergencia con contenido mínimo y vigente en un área señalizada y visible
        'botiquin_comentarios',
        'botiquin_fotografias',
        'pisos_limpios', // Pisos limpios y secos sin riesgo de resbalones, en buen estado
        'pisos_limpios_comentarios',
        'pisos_limpios_fotografias',
        'iluminacion_ventilacion', // Áreas de trabajo bien iluminadas y ventiladas (fluorescentes completos, no quemados, con micas protectoras, suficientes para el espacio)
        'iluminacion_ventilacion_comentarios',
        'iluminacion_ventilacion_fotografias',
        'tableros_electricos', // Tableros eléctricos en buen estado, identificados y señalizados, lejos de fuentes de combustión.
        'tableros_electricos_comentarios',
        'tableros_electricos_fotografias',
        'pasillos_despejados', // Pasillos despejados, áreas de almacenamiento demarcadas.
        'pasillos_despejados_comentarios',
        'pasillos_despejados_fotografias',
        'escaleras_fijas', // Escaleras fijas se encuentran limpias y libres de obstáculos, cuentan con antideslizantes en los pasos, barandas y pasamanos.
        'escaleras_fijas_comentarios',
        'escaleras_fijas_fotografias',
        'estantes_almacenes', // Estantes de almacenes deben estar estables y aseguradas contra deslizamientos y caídas.
        'estantes_almacenes_comentarios',
        'estantes_almacenes_fotografias',
        'espacios_suficientes', // Existe espacios suficientes para realizar los trabajos.
        'espacios_suficientes_comentarios',
        'espacios_suficientes_fotografias',
        'escaleras_moviles_estado', // Las escaleras móviles están en buen estado de conservación (no hechizas)
        'escaleras_moviles_estado_comentarios',
        'escaleras_moviles_estado_fotografias',
        'escaleras_moviles_espacio', // Las escaleras móviles tienen el suficiente espacio para ser correctamente posicionadas, con un ángulo de inclinación de 60° a 75° y en una superficie nivelada.
        'escaleras_moviles_espacio_comentarios',
        'escaleras_moviles_espacio_fotografias',
        'proteccion_maquinas', // Las máquinas o equipos cuentan con protección (manijas de protección, guardas de protección, bloqueos)
        'proteccion_maquinas_comentarios',
        'proteccion_maquinas_fotografias',
        'materiales_almacenados', // Los materiales se encuentran almacenados en una zona establecida (delimitada) hasta una altura máxima de 1.80m.
        'materiales_almacenados_comentarios',
        'materiales_almacenados_fotografias',
        'epp_indumentaria', // Equipos de protección personal e indumentaria en buen estado y adecuado al personal
        'epp_indumentaria_comentarios',
        'epp_indumentaria_fotografias',
        'personal_funcion', // Personal conoce su función, la correcta operación de los equipos y herramientas que utiliza.
        'personal_funcion_comentarios',
        'personal_funcion_fotografias',
        'epp_uso_correcto', // El personal utiliza correctamente los equipos de protección personal e indumentaria.
        'epp_uso_correcto_comentarios',
        'epp_uso_correcto_fotografias',
        'area_limpia', // Área de trabajo limpia y ordenada
        'area_limpia_comentarios',
        'area_limpia_fotografias',
        'residuos_dispuestos', // Los residuos se encuentran correctamente dispuestos (clasificación y disposición)
        'residuos_dispuestos_comentarios',
        'residuos_dispuestos_fotografias'
    ];

    public function inspeccion()
    {
        return $this->belongsTo(InspeccionCheckList::class, 'inspeccion_id');
    }
    
}