<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Luminaire extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'luminaireFamily_id',
        'values',
        'html_filepath',
        'pdf_filepath',
        'visible',
    ];


    protected function casts(): array
{
    return [
        'values' => 'array',
    ];
}



    public function family()
    {
        return $this->belongsTo(LuminaireFamily::class, 'luminaireFamily_id', 'id');
    }
}
