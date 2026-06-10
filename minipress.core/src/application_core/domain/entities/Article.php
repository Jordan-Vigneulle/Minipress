<?php

namespace minipress\appli\application_core\domain\entities;

use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    protected $table = 'article';
    protected $primaryKey = 'id';
    public $incrementing = true;
    protected $keyType = 'int';
    public $timestamps = false;

    protected $fillable = [
        'titre',
        'resume',
        'contenu',
        'date',
        'id_categorie',
        'id_utilisateur',
    ];

    protected $casts = [
        'date' => 'date',
    ];

    public function categorie()
    {
        return $this->belongsTo(Categorie::class, 'id_categorie', 'id');
    }

    public function utilisateur()
    {
        return $this->belongsTo(Utilisateur::class, 'id_utilisateur', 'id');
    }

    public function images()
    {
        return $this->belongsToMany(Image::class, 'image_article', 'id_article', 'id_image');
    }
}