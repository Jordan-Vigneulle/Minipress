<?php

namespace gift\appli\application_core\domain\entities;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{

    protected $table = 'user';
    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = [
        'id',
        'user_id',
        'password',
        'role',
        'username',
        'chemin_acces_img'
    ];

    protected $hidden = [
        'password'
    ];

    public function boxes()
    {
        return $this->hasMany(Box::class, 'createur_id');
    }

    public function getId(): string
    {
        return $this->id;
    }
}