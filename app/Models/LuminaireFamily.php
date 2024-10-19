<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LuminaireFamily extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'sheet_id',
        'html_filepath',
        'pdf_filepath',
        'visible',
    ];

    public function luminaires()
    {
        return $this->hasMany(Luminaire::class, 'luminaireFamily_id', 'id');
    }
}
