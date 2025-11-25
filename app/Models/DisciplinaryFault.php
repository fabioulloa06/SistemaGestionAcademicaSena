<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DisciplinaryFault extends Model
{
    protected $fillable = ['codigo', 'description'];

    public function disciplinaryActions()
    {
        return $this->hasMany(DisciplinaryAction::class);
    }
}
