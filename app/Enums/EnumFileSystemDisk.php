<?php

namespace App\Enums;

enum EnumFileSystemDisk: string
{
    case LOCAL = 'local';
    case PUBLIC = 'public';
    case PUBLIC_UPLOADS = 'public_uploads';
}