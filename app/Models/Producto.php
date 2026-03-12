<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    protected $table = 'productos';
    public $timestamps = false;
    
    protected $fillable = ['nombre', 'precio', 'stock', 'es_combo', 'activo'];

    protected $casts = [
        'es_combo' => 'boolean',
        'activo'   => 'boolean',
        'precio'   => 'decimal:2',
    ];

    public function detalleVentas()
    {
        return $this->hasMany(DetalleVenta::class);
    }

    public function scopeActivos($query)
    {
        return $query->where('activo', true);
    }
}