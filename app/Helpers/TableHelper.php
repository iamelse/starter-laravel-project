<?php

namespace App\Helpers;

use App\Constants\TableConstants;

class TableHelper
{
    public static function getColumnsForTable(string $tableName): array
    {
        $columns = [
            'users' => TableConstants::USER_TABLE_COLUMNS,
            // Add other tables here
        ];

        return $columns[$tableName] ?? [];
    }
}
