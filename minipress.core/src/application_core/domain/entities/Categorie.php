<?php

namespace minipress\appli\application_core\domain\entities;

use Illuminate\Database\Eloquent\Model;

class Categorie extends Model
{
    protected $table = 'categorie';
    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = [
        'id',
        'titre',
    ];

    public function articles()
    {
        return $this->hasMany(Article::class, 'id_categorie', 'id');
    }
}