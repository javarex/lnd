<?php

namespace App\Traits;

use Spatie\Permission\Traits\HasRoles;

trait HasUserRole
{
    use HasRoles;

    public function isAdmin()
    {
        // dd($this->hasAnyRole(['system-admin', 'super_admin']));
        return $this->hasAnyRole(['super_admin', 'system-admin']);
    }
}
