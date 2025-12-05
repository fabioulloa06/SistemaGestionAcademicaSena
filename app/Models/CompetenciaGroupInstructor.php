<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompetenciaGroupInstructor extends Model
{
    use HasFactory;

    protected $table = 'competencia_group_instructor';

    protected $fillable = [
        'competencia_id',
        'group_id',
        'instructor_id',
    ];

    public function competencia()
    {
        return $this->belongsTo(Competencia::class);
    }

    public function group()
    {
        return $this->belongsTo(Group::class);
    }

    public function instructor()
    {
        return $this->belongsTo(User::class, 'instructor_id');
    }
}
