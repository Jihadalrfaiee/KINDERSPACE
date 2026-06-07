<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'display_name', 'description', 'is_active'];

    protected $casts = ['is_active' => 'boolean'];

    public function permissions()
    {
        return $this->belongsToMany(Permission::class, 'role_permission')->withTimestamps();
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function hasPermission($permission)
    {
        if (is_string($permission)) {
            return $this->permissions->contains('name', $permission);
        }
        return $this->permissions->contains($permission);
    }

    public function givePermissionTo(...$permissions)
    {
        $permissionIds = Permission::whereIn('name', $permissions)->pluck('id');
        $this->permissions()->syncWithoutDetaching($permissionIds);
        return $this;
    }
}