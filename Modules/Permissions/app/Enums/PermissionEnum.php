<?php

namespace Modules\Permissions\Enums;

enum PermissionEnum: string
{
    case VIEW_DASHBOARD = 'view_dashboard';
    case CREATE_POSTS = 'create_posts';
    case EDIT_POSTS = 'edit_posts';
    case DELETE_POSTS = 'delete_posts';
    case VIEW_USERS = 'view_users';
    case CREATE_USERS = 'create_users';

    public function label(): string
    {
        return match ($this) {
            static::VIEW_DASHBOARD => 'View Dashboard',
            static::CREATE_POSTS => 'Create Posts',
            static::EDIT_POSTS => 'Edit Posts',
            static::DELETE_POSTS => 'Delete Posts',
            static::VIEW_USERS => 'View Users',
            static::CREATE_USERS => 'Create Users',
        };
    }
}
