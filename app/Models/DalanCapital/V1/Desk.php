<?php

namespace App\Models\DalanCapital\V1;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Desk extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    const USER_ID = 'user_id';
    const TABLE = 'desks';
    protected $fillable = [
        'user_id', 'title', 'description', 'content', 'logo', 'cover',
        'aum_amount', 'aum_currency', 'is_public', 'status', 'synced_at'
    ];

    protected $casts = [
        'aum_amount' => 'double',
        'is_public' => 'boolean',
        'synced_at' => 'datetime'
    ];

    public function contract() : HasMany
    {
        return $this->hasMany(Contract::class);
    }
}
