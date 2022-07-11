<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Tag extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title',
        'import_id',
    ];

    /**
     * Get streams for the tag.
     *
     * @return BelongsToMany
     */
    public function streams(): BelongsToMany
    {
        return $this->BelongsToMany(Stream::class, 'stream_tags');
    }
}
