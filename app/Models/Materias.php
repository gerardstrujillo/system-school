<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Materias extends Model
{
    use HasFactory;

    protected $table = 'materias';
    protected $primaryKey = 'id_materia';
    public $timestamps = false;

    protected $casts = [
        'es_submateria' => 'boolean',
    ];

    protected $fillable = [
        'nombre_materia',
        'id_grado',
        'es_submateria',
        'id_materia_padre',
        'porcentaje',
    ];

    // Relación con GradoAcademico
    public function grado()
    {
        return $this->belongsTo(Grado::class, 'id_grado');
    }

    // Relación con submaterias (si tiene)
    public function submaterias()
    {
        return $this->hasMany(Materias::class, 'id_materia_padre');
    }

    // Relación con materia padre (si es una submateria)
    public function materiaPadre()
    {
        return $this->belongsTo(Materias::class, 'id_materia_padre');
    }

    // Relación con Notas
    public function notas()
    {
        return $this->hasMany(Notas::class, 'id_materia');
    }
}
