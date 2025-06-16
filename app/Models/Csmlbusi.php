<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Csmlbusi extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     * These fields can be bulk-assigned using create() or fill().
     */
    protected $fillable = [
        'client_name',      // Name of the client
        'business_type',    // Type of business
        'year',             // Year of registration or document
        'amount',  
        'expiry_date',      // Expiry date of the business
        'bid_status',       // Status of the business bid (e.g., active, pending, completed)
        'file_path',        // Path to additional business-related files, if any
    ];

    /**
     * Define the relationship: 
     * A business (Csmlbusi) can have multiple document uploads.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function documents()
    {
        return $this->hasMany(DocumentUpload::class, 'csmlbusi_id');
    }

    /**
     * Get a formatted expiry date (e.g., 'Feb 13, 2025').
     *
     * @return string
     */
    public function getFormattedExpiryDateAttribute()
    {
        return $this->expiry_date ? \Carbon\Carbon::parse($this->expiry_date)->format('M d, Y') : 'N/A';
    }

    /**
     * Get the full URL for the file path, if applicable.
     * Useful for serving files stored in the storage or public directory.
     *
     * @return string|null
     */
    public function getFileUrlAttribute()
    {
        return $this->file_path ? asset('storage/' . $this->file_path) : null;
    }

    /**
     * Scope for filtering businesses by year.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $year
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeFilterByYear($query, $year)
    {
        return $query->where('year', $year);
    }

    /**
     * Scope for filtering businesses by bid status.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $status
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeFilterByBidStatus($query, $status)
    {
        return $query->where('bid_status', $status);
    }
}
