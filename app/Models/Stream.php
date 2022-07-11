<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Stream extends Model
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
        'game_id',
        'game_name',
        'user_id',
        'user_name',
        'viewer_count',
        'started_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'started_at' => 'datetime',
    ];

    /**
     * Get tags for the stream.
     *
     * @return BelongsToMany
     */
    public function tags(): BelongsToMany
    {
        return $this->BelongsToMany(Tag::class, 'stream_tags');
    }
}
