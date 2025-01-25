<?php

namespace Modules\RolesAndPermissions\Models;

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
}
