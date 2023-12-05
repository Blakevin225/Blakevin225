<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;

class Personne extends Model {

        protected $table = 'personne';

        protected $primaryKey = 'id';

        //protected $keyType = 'int';

        protected $fillable = [
                'id',
                'idtypepersonne',
                'nom',
                'prenom',
                'sexe',
                'photo',
                'datenaissance'
            ];
        public $timestamps = false;
    

        public function typepersonne()
    {
        return $this->belongsTo(TypePersonne::class, 'id');
    }


}