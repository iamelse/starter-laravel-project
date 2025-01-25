<?php

namespace App\Constants;

class TableConstants
{
    public const USER_TABLE_COLUMNS = [
        'id', 
        'name', 
        'roles', 
        'username', 
        'email', 
        'email_verified_at', 
        'created_at', 
        'updated_at',
    ];

    public const ROLE_AND_PERMISSION_TABLE_COLUMNS = [
        'id', 
        'name',
        'created_at', 
        'updated_at',
    ];

    // Add more table columns as needed
}