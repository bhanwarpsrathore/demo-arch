<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Authenticatable;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Spatie\Permission\Traits\HasRoles;

class Admin extends Model implements AuthenticatableContract, AuthorizableContract, CanResetPasswordContract {
    use Authenticatable,
        Authorizable,
        CanResetPassword,
        HasRoles,
        HasFactory;

    public const STATUS_INACTIVE = 0;
    public const STATUS_ACTIVE = 1;
    public const STATUS_INVITED = 2;

    public function getFirstname(): string {
        return trim($this->firstname);
    }
    public function getName(): string {
        return trim($this->firstname . " " . $this->lastname);
    }

    public function getRoles(): string {
        return implode(", ", $this->getRoleNames()->toArray());
    }

    public function getUrl(): string {
        return route('admins.show', $this->id);
    }

    public function showStatusBadge(): string {
        return match ($this->status) {
            self::STATUS_ACTIVE => '<span class="badge badge-green">Active</span>',
            self::STATUS_INVITED => '<span class="badge badge-warning">Invited</span>',
            default => '<span class="badge badge-danger">Inactive</span>',
        };
    }

    public function getImage(bool $placeholder = false): string {
        if (!empty($this->image) && !$placeholder) {
            return asset($this->image);
        }

        return asset('images/bckpnl/blank-user.png');
    }
}
