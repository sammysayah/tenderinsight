<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Csmldoc extends Model
{
    use HasFactory;

    protected $fillable = [
        'document_name',
        'document_type',
        'year',
        'expiry_date',
        'document_title', // Ensure this is fillable
        'file_path',
    ];
}
