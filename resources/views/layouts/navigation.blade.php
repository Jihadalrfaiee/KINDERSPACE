{{-- قسم إدارة النظام - يظهر فقط للسوبر أدمن --}}
@role('super-admin')
<div class="mt-4 px-4 text-xs font-semibold text-gray-400 uppercase tracking-wider">
    إدارة النظام
</div>

<nav class="mt-2 px-2 space-y-1">
    {{-- رابط إدارة المستخدمين --}}
    <a href="{{ route('users.index') }}" 
       class="group flex items-center px-2 py-2 text-sm font-medium rounded-md {{ request()->routeIs('users.*') ? 'bg-indigo-600 text-white' : 'text-gray-600 hover:bg-gray-100' }}">
        <svg class="ml-3 h-5 w-5 text-gray-400 group-hover:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
        </svg>
        إدارة المستخدمين
    </a>

    {{-- رابط إدارة الصلاحيات --}}
    <a href="{{ route('roles.index') }}" 
       class="group flex items-center px-2 py-2 text-sm font-medium rounded-md {{ request()->routeIs('roles.*') ? 'bg-indigo-600 text-white' : 'text-gray-600 hover:bg-gray-100' }}">
        <svg class="ml-3 h-5 w-5 text-gray-400 group-hover:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
        </svg>
        الصلاحيات والأدوار
    </a>
</nav>
@endrole