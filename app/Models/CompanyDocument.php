<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompanyDocument extends Model
{
    use HasFactory;

    protected $fillable = [
        'document_name', 
        'document_type', 
        'year', 
        'amount', 
        'expiry_date', 
        'file_path'
    ];
}
