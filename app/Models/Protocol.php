<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Protocol extends Model
{
    use HasFactory;

    protected $fillable = [
        'people_id',
        'description',
        'date',
        'term'
    ];

    public function people()
    {
        return $this->belongsTo(People::class);
    }

}
