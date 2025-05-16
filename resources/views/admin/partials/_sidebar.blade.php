<div class="sidebar border-start">
    <h4 class="px-3">لوحة التحكم</h4>
    <ul class="nav flex-column">
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">
                <i class="fas fa-tachometer-alt me-2"></i>لوحة القيادة
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('admin.faculties.*') ? 'active' : '' }}" href="{{ route('admin.faculties.index') }}">
                <i class="fas fa-university me-2"></i>إدارة الكليات
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('admin.specializations.*') ? 'active' : '' }}" href="{{ route('admin.specializations.index') }}">
                <i class="fas fa-sitemap me-2"></i>إدارة الاختصاصات
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('admin.instructors.*') ? 'active' : '' }}" href="{{ route('admin.instructors.index') }}">
                <i class="fas fa-chalkboard-teacher me-2"></i>إدارة المدرسين
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('admin.courses.*') ? 'active' : '' }}" href="{{ route('admin.courses.index') }}">
                <i class="fas fa-book-open me-2"></i>إدارة المقررات
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('admin.projects.*') ? 'active' : '' }}" href="{{ route('admin.projects.index') }}">
                <i class="fas fa-project-diagram me-2"></i>إدارة المشاريع
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('admin.university-facilities.*') ? 'active' : '' }}" href="{{ route('admin.university-facilities.index') }}">
                <i class="fas fa-photo-video me-2"></i>إدارة وسائط الجامعة
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('admin.events.*') ? 'active' : '' }}" href="{{ route('admin.events.index') }}">
                <i class="fas fa-calendar-alt me-2"></i>إدارة الفعاليات
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('admin.event-registrations.*') ? 'active' : '' }}" href="{{ route('admin.event-registrations.index') }}">
                <i class="fas fa-clipboard-check me-2"></i>طلبات تسجيل الفعاليات
            </a>
        </li>
         <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('admin.students.*') ? 'active' : '' }}" href="{{ route('admin.students.index') }}">
                <i class="fas fa-user-graduate me-2"></i>إدارة الطلاب
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('admin.notifications.*') ? 'active' : '' }}" href="{{ route('admin.notifications.index') }}">
                <i class="fas fa-bell me-2"></i>إدارة التنبيهات
            </a>
        </li>
        @auth('admin_web') {{-- تحقق أولاً أن المدير مسجل دخوله --}}
        @if(Auth::guard('admin_web')->user()->role === 'superadmin') {{-- مثال لتقييد حسب الدور --}}
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('admin.admin-users.*') ? 'active' : '' }}" href="{{ route('admin.admin-users.index') }}">
                <i class="fas fa-users-cog me-2"></i>إدارة مديري النظام
            </a>
        </li>
        @endif
        <li class="nav-item mt-auto">
             <form method="POST" action="{{ route('admin.logout') }}">
                @csrf
                <button type="submit" class="nav-link text-danger">
                    <i class="fas fa-sign-out-alt me-2"></i>تسجيل الخروج
                </button>
            </form>
        </li>
        @endauth
    </ul>

</div>