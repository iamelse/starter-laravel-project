<?php

namespace Modules\RolesAndPermissions\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Permission\Models\Role as SpatieModelRole;
use Yogameleniawan\SearchSortEloquent\Traits\Searchable;
use Yogameleniawan\SearchSortEloquent\Traits\Sortable;

// use Modules\RolesAndPermissions\Database\Factories\RoleFactory;

class Role extends SpatieModelRole
{
    use HasFactory, Searchable, Sortable;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [];

    // protected static function newFactory(): RoleFactory
    // {
    //     // return RoleFactory::new();
    // }

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
}
