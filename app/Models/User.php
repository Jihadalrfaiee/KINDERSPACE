<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * الحقول القابلة للتعبئة
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role_id',
    ];

    /**
     * الحقول المخفية
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * تحويل نوع البيانات
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    /**
     * جلب الدور تلقائياً مع المستخدم
     */
    protected $with = ['role'];

    /**
     * -----------------------------------------------------------------
     * العلاقات (Relationships)
     * -----------------------------------------------------------------
     */
    
    // تم تحديد المفتاح الأجنبي 'role_id' صراحة لمنع أي تعارض
    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id');
    }

    /**
     * -----------------------------------------------------------------
     * دوال فحص الصلاحيات (RBAC Helpers) المحدثة والآمنة
     * -----------------------------------------------------------------
     */

    // جلب كائن الدور بشكل آمن لتجنب التعارض مع أي عمود نصي قديم يحمل اسم role
    private function getRoleObject()
    {
        $role = $this->getRelationValue('role');
        return is_object($role) ? $role : null;
    }

    // التحقق هل المستخدم يمتلك صلاحية معينة
    public function hasPermission($permission)
    {
        $role = $this->getRoleObject();
        
        if (!$role) {
            return false;
        }
        
        return $role->hasPermission($permission);
    }

    // التحقق هل المستخدم يمتلك دوراً معيناً
    public function hasRole($roleName)
    {
        $role = $this->getRoleObject();
        
        if (!$role) {
            return false;
        }

        if (is_string($roleName)) {
            return $role->name === $roleName;
        }
        
        return $role->id === $roleName->id;
    }

    // التحقق هل المستخدم يمتلك أياً من هذه الأدوار المحددة
    public function hasAnyRole(...$roles)
    {
        $role = $this->getRoleObject();
        
        if (!$role) {
            return false;
        }
        
        return in_array($role->name, $roles);
    }

    // دالة سريعة للتحقق هل المستخدم هو مدير النظام
    public function isAdmin()
    {
        return $this->hasRole('admin') || $this->role_id == 1;
    }
}