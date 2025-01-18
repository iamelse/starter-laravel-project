<?php

namespace Modules\Roles\Enums;

enum RoleEnum: string
{
    case MASTER = 'Master';
    case AUTHOR = 'Editor';

    public function label(): string
    {
        return match ($this) {
            static::MASTER => 'Master',
            static::AUTHOR => 'Editor',
        };
    }
}