<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MedTracker extends Model
{
    use HasFactory;
    protected $table = 'meds_data';

    protected $fillable = [
        'meds_taken',
        'last_dose',
        'next_dose',
    ];
}


