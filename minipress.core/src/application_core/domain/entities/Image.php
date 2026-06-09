<?php

namespace minipress\appli\application_core\domain\entities;

use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    protected $table = 'image';
    public $timestamps = false;

    protected $fillable = [
        'url',
    ];

    public function articles()
    {
        return $this->belongsToMany(Article::class, 'image_article', 'id_image', 'id_article');
    }
}