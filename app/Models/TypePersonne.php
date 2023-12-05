<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;

class TypePersonne extends Model
{
    protected $table = 'typepersonne';

    protected $primaryKey = 'id';

    //protected $keyType = 'int';

    protected $fillable = [
            'id',
            'libelle'
        ];
    public $timestamps = false;

    public function personnes()
    {
        return $this->hasMany(Personne::class, 'idtypepersonne');
    }

}
