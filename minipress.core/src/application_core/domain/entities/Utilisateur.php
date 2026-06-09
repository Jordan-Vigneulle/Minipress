<?php

namespace minipress\appli\application_core\domain\entities;

use Illuminate\Database\Eloquent\Model;

class Utilisateur extends Model
{

    protected $table = 'utilisateur';
    protected $primaryKey = 'email';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = [
        'email',
        'motdepasse',
        'role',
        'pseudo',
        'chemin_acces_img'
    ];

    protected $hidden = [
        'motdepasse'
    ];

    public function articles()
    {
        return $this->hasMany(Article::class, 'id_utilisateur', 'id');
    }
}