<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DocumentUpload extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     * These fields can be bulk-assigned using create() or fill().
     */
    protected $fillable = [
        'csmlbusi_id',      // Foreign key to the Csmlbusi model
        'document_title',   // The title of the document
        'file_path',        // Path to the uploaded document file
    ];

    /**
     * Define the relationship: 
     * Each document belongs to a Csmlbusi (business).
     */
    public function csmlbusi()
    {
        return $this->belongsTo(Csmlbusi::class, 'csmlbusi_id');
    }

    /**
     * Get the full URL for the document file.
     * Useful for serving documents stored in public or private directories.
     *
     * @return string
     */
    public function getFileUrlAttribute()
    {
        return asset('storage/' . $this->file_path); // Assumes storage:link is set up
    }
}
