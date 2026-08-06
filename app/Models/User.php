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
        'phone',
        'national_id',
        'role',
        'role_id',
        'telegram_chat_id',
        'profile_photo',
        'is_active',
        'last_login_at',
        'academic_qualification',
        'previous_experience',
        'years_of_experience',
        'job_title',
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

    private function getRoleNameValue()
    {
        if (array_key_exists('role', $this->attributes) && is_string($this->attributes['role'])) {
            return $this->attributes['role'];
        }

        $role = $this->getRoleObject();
        return $role?->name;
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
        $roleNameValue = $this->getRoleNameValue();
        
        if (!$roleNameValue) {
            return false;
        }

        if (is_string($roleName)) {
            return $roleNameValue === $roleName;
        }

        return $roleNameValue === $roleName->name;
    }

    // التحقق هل المستخدم يمتلك أياً من هذه الأدوار المحددة
    public function hasAnyRole(...$roles)
    {
        $roleNameValue = $this->getRoleNameValue();
        
        if (!$roleNameValue) {
            return false;
        }
        
        return in_array($roleNameValue, $roles);
    }

    // دالة سريعة للتحقق هل المستخدم هو مدير النظام
    public function isAdmin()
    {
        return $this->hasRole('admin') || $this->role_id == 1;
    }
}