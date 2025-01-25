<?php

namespace Modules\User\Models;

use App\Models\TableSettings;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Modules\User\Database\Factories\UserFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Permission\Traits\HasRoles;
use Yogameleniawan\SearchSortEloquent\Traits\Searchable;
use Yogameleniawan\SearchSortEloquent\Traits\Sortable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasRoles, Searchable, Sortable;

    protected static function newFactory(): UserFactory
    {
        return UserFactory::new();
    }

    protected $guarded = ['id'];

    public $timestamps = true;

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

    /**
     * Accessor for email_verified_at (human-readable format)
     */
    public function getEmailVerifiedAtAttribute($value)
    {
        return Carbon::parse($value)->format('F d, Y h:i A');
    }

    /**
     * Accessor for created_at (human-readable format)
     */
    public function getCreatedAtAttribute($value)
    {
        return Carbon::parse($value)->format('F d, Y h:i A');
    }

    /**
     * Accessor for updated_at (human-readable format)
     */
    public function getUpdatedAtAttribute($value)
    {
        return Carbon::parse($value)->format('F d, Y h:i A');
    }

    /**
     * Get the table settings for the user.
     */
    public function tableSettings()
    {
        return $this->hasOne(TableSettings::class);
    }
}
