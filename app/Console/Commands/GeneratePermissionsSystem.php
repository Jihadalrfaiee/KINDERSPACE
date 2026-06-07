<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class GeneratePermissionsSystem extends Command
{
    protected $signature = 'make:permissions-system {--force : Overwrite existing files}';
    protected $description = 'Generate complete permissions system for kindergarten';

    private $bar;

    public function handle()
    {
        $this->info('🚀 جاري إنشاء نظام الصلاحيات المتكامل...');
        $this->newLine();

        $steps = [
            'createMigrations',
            'createModels',
            'createSeeders',
            'createController',
            'createMiddleware',
            'createViews',
            'createRoutes',
            'createTranslations',
            'updateProviders',
        ];

        $this->bar = $this->output->createProgressBar(count($steps));
        $this->bar->start();

        foreach ($steps as $step) {
            $this->$step();
            $this->bar->advance();
        }

        $this->bar->finish();
        $this->newLine(2);

        $this->displaySuccessMessage();
    }

    private function createMigrations()
    {
        $timestamp = date('Y_m_d_His');
        
        File::put(
            database_path("migrations/{$timestamp}_create_roles_table.php"),
            $this->getRolesMigration()
        );

        sleep(1);
        $timestamp = date('Y_m_d_His');

        File::put(
            database_path("migrations/{$timestamp}_create_permissions_table.php"),
            $this->getPermissionsMigration()
        );

        sleep(1);
        $timestamp = date('Y_m_d_His');

        File::put(
            database_path("migrations/{$timestamp}_create_role_permission_table.php"),
            $this->getRolePermissionMigration()
        );

        sleep(1);
        $timestamp = date('Y_m_d_His');

        File::put(
            database_path("migrations/{$timestamp}_add_role_id_to_users_table.php"),
            $this->getUsersRoleMigration()
        );
    }

    private function createModels()
    {
        File::put(app_path('Models/Role.php'), $this->getRoleModel());
        File::put(app_path('Models/Permission.php'), $this->getPermissionModel());
        File::put(app_path('Models/User.php'), $this->getUserModel());
    }

    private function createSeeders()
    {
        File::put(database_path('seeders/PermissionSeeder.php'), $this->getPermissionSeeder());
        File::put(database_path('seeders/RoleSeeder.php'), $this->getRoleSeeder());
    }

    private function createController()
    {
        File::put(app_path('Http/Controllers/RoleController.php'), $this->getRoleController());
    }

    private function createMiddleware()
    {
        File::put(app_path('Http/Middleware/CheckPermission.php'), $this->getCheckPermissionMiddleware());
        File::put(app_path('Http/Middleware/CheckRole.php'), $this->getCheckRoleMiddleware());
    }

    private function createViews()
    {
        $viewsPath = resource_path('views/roles');
        if (!File::exists($viewsPath)) {
            File::makeDirectory($viewsPath, 0755, true);
        }

        File::put($viewsPath . '/index.blade.php', $this->getIndexView());
        File::put($viewsPath . '/create.blade.php', $this->getCreateView());
        File::put($viewsPath . '/edit.blade.php', $this->getEditView());
        File::put($viewsPath . '/show.blade.php', $this->getShowView());
    }

    private function createRoutes()
    {
        $routesContent = File::get(base_path('routes/web.php'));
        
        if (!str_contains($routesContent, 'RoleController')) {
            $newRoutes = "\n\n// Roles & Permissions Routes\n";
            $newRoutes .= "Route::middleware(['auth'])->group(function () {\n";
            $newRoutes .= "    Route::resource('roles', App\Http\Controllers\RoleController::class);\n";
            $newRoutes .= "});\n";

            File::append(base_path('routes/web.php'), $newRoutes);
        }
    }

    private function createTranslations()
    {
        $langPath = resource_path('lang/ar');
        if (!File::exists($langPath)) {
            File::makeDirectory($langPath, 0755, true);
        }

        File::put($langPath . '/permissions.php', $this->getTranslations());
    }

    private function updateProviders()
    {
        $providerPath = app_path('Providers/AppServiceProvider.php');
        $content = File::get($providerPath);

        if (!str_contains($content, 'hasPermission')) {
            $useStatement = "use Illuminate\Support\Facades\Blade;";
            
            if (!str_contains($content, $useStatement)) {
                $content = str_replace(
                    'use Illuminate\Support\ServiceProvider;',
                    "use Illuminate\Support\ServiceProvider;\n{$useStatement}",
                    $content
                );
            }

            $bladeDirectives = <<<'BLADE'

        // Blade Directives for Permissions
        Blade::if('hasPermission', function ($permission) {
            return auth()->check() && auth()->user()->hasPermission($permission);
        });

        Blade::if('hasRole', function ($role) {
            return auth()->check() && auth()->user()->hasRole($role);
        });

        Blade::if('hasAnyRole', function (...$roles) {
            return auth()->check() && auth()->user()->hasAnyRole(...$roles);
        });
BLADE;

            $content = str_replace(
                'public function boot(): void',
                'public function boot(): void' . $bladeDirectives,
                $content
            );

            File::put($providerPath, $content);
        }
    }

    private function displaySuccessMessage()
    {
        $this->info('✅ تم إنشاء نظام الصلاحيات بنجاح!');
        $this->newLine();

        $this->components->twoColumnDetail('📁 Migrations', '4 files created');
        $this->components->twoColumnDetail('📦 Models', '3 files created');
        $this->components->twoColumnDetail('🌱 Seeders', '2 files created');
        $this->components->twoColumnDetail('🎮 Controllers', '1 file created');
        $this->components->twoColumnDetail('🛡️ Middleware', '2 files created');
        $this->components->twoColumnDetail('🎨 Views', '4 files created');
        $this->components->twoColumnDetail('🌐 Routes', 'Updated');
        $this->components->twoColumnDetail('🔤 Translations', '1 file created');

        $this->newLine();
        $this->warn('⚡ الخطوات التالية:');
        $this->line('1. php artisan migrate');
        $this->line('2. php artisan db:seed --class=PermissionSeeder');
        $this->line('3. php artisan db:seed --class=RoleSeeder');
        $this->newLine();
    }

    private function getRolesMigration()
    {
        return <<<'PHP'
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('display_name');
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('roles');
    }
};
PHP;
    }

    private function getPermissionsMigration()
    {
        return <<<'PHP'
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('permissions', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('display_name');
            $table->string('group');
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('permissions');
    }
};
PHP;
    }

    private function getRolePermissionMigration()
    {
        return <<<'PHP'
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('role_permission', function (Blueprint $table) {
            $table->id();
            $table->foreignId('role_id')->constrained()->onDelete('cascade');
            $table->foreignId('permission_id')->constrained()->onDelete('cascade');
            $table->timestamps();
            
            $table->unique(['role_id', 'permission_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('role_permission');
    }
};
PHP;
    }

    private function getUsersRoleMigration()
    {
        return <<<'PHP'
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('role_id')->nullable()->after('email')->constrained()->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['role_id']);
            $table->dropColumn('role_id');
        });
    }
};
PHP;
    }

    private function getRoleModel()
    {
        return <<<'PHP'
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
PHP;
    }

    private function getPermissionModel()
    {
        return <<<'PHP'
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'display_name', 'group', 'description'];

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'role_permission')->withTimestamps();
    }

    public static function grouped()
    {
        return static::all()->groupBy('group');
    }
}
PHP;
    }

    private function getUserModel()
    {
        return <<<'PHP'
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = ['name', 'email', 'password', 'role_id'];

    protected $hidden = ['password', 'remember_token'];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    protected $with = ['role'];

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function hasPermission($permission)
    {
        return $this->role && $this->role->hasPermission($permission);
    }

    public function hasRole($role)
    {
        if (is_string($role)) {
            return $this->role && $this->role->name === $role;
        }
        return $this->role && $this->role->id === $role->id;
    }

    public function hasAnyRole(...$roles)
    {
        return $this->role && in_array($this->role->name, $roles);
    }
}
PHP;
    }

    private function getPermissionSeeder()
    {
        return <<<'PHP'
<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    public function run()
    {
        $permissions = [
            ['name' => 'students.view', 'display_name' => 'عرض الطلاب', 'group' => 'students'],
            ['name' => 'students.create', 'display_name' => 'إضافة طالب', 'group' => 'students'],
            ['name' => 'students.edit', 'display_name' => 'تعديل طالب', 'group' => 'students'],
            ['name' => 'students.delete', 'display_name' => 'حذف طالب', 'group' => 'students'],
            
            ['name' => 'attendance.view', 'display_name' => 'عرض الحضور', 'group' => 'attendance'],
            ['name' => 'attendance.mark', 'display_name' => 'تسجيل الحضور', 'group' => 'attendance'],
            ['name' => 'attendance.edit', 'display_name' => 'تعديل الحضور', 'group' => 'attendance'],
            
            ['name' => 'roles.view', 'display_name' => 'عرض الأدوار', 'group' => 'roles'],
            ['name' => 'roles.create', 'display_name' => 'إضافة دور', 'group' => 'roles'],
            ['name' => 'roles.edit', 'display_name' => 'تعديل دور', 'group' => 'roles'],
            ['name' => 'roles.delete', 'display_name' => 'حذف دور', 'group' => 'roles'],
        ];

        foreach ($permissions as $permission) {
            Permission::create($permission);
        }
    }
}
PHP;
    }

    private function getRoleSeeder()
    {
        return <<<'PHP'
<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\Permission;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    public function run()
    {
        $admin = Role::create([
            'name' => 'admin',
            'display_name' => 'مدير النظام',
            'description' => 'صلاحيات كاملة',
            'is_active' => true
        ]);
        $admin->permissions()->attach(Permission::all());

        $teacher = Role::create([
            'name' => 'teacher',
            'display_name' => 'معلمة',
            'description' => 'متابعة الطلاب',
            'is_active' => true
        ]);
    }
}
PHP;
    }

    private function getRoleController()
    {
        return <<<'PHP'
<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\Permission;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    public function index()
    {
        $roles = Role::withCount(['users', 'permissions'])->get();
        return view('roles.index', compact('roles'));
    }

    public function create()
    {
        $permissions = Permission::grouped();
        return view('roles.create', compact('permissions'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|unique:roles',
            'display_name' => 'required',
            'description' => 'nullable',
            'permissions' => 'array',
        ]);

        $role = Role::create($validated);
        $role->permissions()->attach($validated['permissions'] ?? []);

        return redirect()->route('roles.index')->with('success', 'تم الإضافة بنجاح');
    }
}
PHP;
    }

    private function getCheckPermissionMiddleware()
    {
        return <<<'PHP'
<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckPermission
{
    public function handle(Request $request, Closure $next, $permission)
    {
        if (!auth()->check() || !auth()->user()->hasPermission($permission)) {
            abort(403, 'ليس لديك صلاحية');
        }
        return $next($request);
    }
}
PHP;
    }

    private function getCheckRoleMiddleware()
    {
        return <<<'PHP'
<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckRole
{
    public function handle(Request $request, Closure $next, ...$roles)
    {
        if (!auth()->check() || !auth()->user()->hasAnyRole(...$roles)) {
            abort(403, 'ليس لديك صلاحية');
        }
        return $next($request);
    }
}
PHP;
    }

    private function getIndexView()
    {
        return <<<'BLADE'
@extends('layouts.app')

@section('content')
<div class="container">
    <h2>الأدوار والصلاحيات</h2>
    
    <a href="{{ route('roles.create') }}" class="btn btn-primary mb-3">إضافة دور جديد</a>
    
    <div class="row">
        @foreach($roles as $role)
        <div class="col-md-4 mb-3">
            <div class="card">
                <div class="card-body">
                    <h5>{{ $role->display_name }}</h5>
                    <p class="text-muted">{{ $role->description }}</p>
                    <small>المستخدمين: {{ $role->users_count }}</small><br>
                    <small>الصلاحيات: {{ $role->permissions_count }}</small>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection
BLADE;
    }

    private function getCreateView()
    {
        return <<<'BLADE'
@extends('layouts.app')

@section('content')
<div class="container">
    <h2>إضافة دور جديد</h2>
    
    <form action="{{ route('roles.store') }}" method="POST">
        @csrf
        
        <div class="mb-3">
            <label>اسم الدور</label>
            <input type="text" name="name" class="form-control" required>
        </div>
        
        <div class="mb-3">
            <label>الاسم المعروض</label>
            <input type="text" name="display_name" class="form-control" required>
        </div>
        
        <div class="mb-3">
            <label>الوصف</label>
            <textarea name="description" class="form-control"></textarea>
        </div>
        
        <h4>الصلاحيات</h4>
        @foreach($permissions as $group => $perms)
        <div class="card mb-2">
            <div class="card-header">{{ $group }}</div>
            <div class="card-body">
                @foreach($perms as $perm)
                <div class="form-check">
                    <input type="checkbox" name="permissions[]" value="{{ $perm->id }}" class="form-check-input">
                    <label class="form-check-label">{{ $perm->display_name }}</label>
                </div>
                @endforeach
            </div>
        </div>
        @endforeach
        
        <button type="submit" class="btn btn-success">حفظ</button>
    </form>
</div>
@endsection
BLADE;
    }

    private function getEditView()
    {
        return '<!-- Edit View -->';
    }

    private function getShowView()
    {
        return '<!-- Show View -->';
    }

    private function getTranslations()
    {
        return <<<'PHP'
<?php

return [
    'groups' => [
        'students' => 'إدارة الطلاب',
        'attendance' => 'الحضور والغياب',
        'roles' => 'الأدوار والصلاحيات',
    ],
];
PHP;
    }
}