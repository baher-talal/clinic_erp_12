<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'نظام إدارة العيادات')</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.rtl.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;600;700&display=swap" rel="stylesheet">
    <style>
        /* ─── Variables ─────────────────────────────────────── */
        :root {
            --primary:       #2563eb;
            --sidebar-bg:    #1e293b;
            --sidebar-hover: #334155;
            --sidebar-w:     260px;
            --topbar-h:      56px;
        }
        * { font-family: 'Cairo', sans-serif; box-sizing: border-box; }
        body { background: #f1f5f9; margin: 0; }

        /* ─── Sidebar shell ─────────────────────────────────── */
        #sidebar {
            position: absolute;
            top: 0; right: 0;
            width: var(--sidebar-w);
            height: 100%;           /* full viewport height */
            background: var(--sidebar-bg);
            z-index: 1040;
            display: flex;
            flex-direction: column;  /* brand | nav (scroll) | footer */
            transition: width .3s, transform .3s;
            overflow: hidden;        /* clips horizontal bleed */
        }

        /* Brand — fixed at top, never scrolls */
        #sidebar .sidebar-brand {
            flex-shrink: 0;
            padding: 1.1rem 1rem;
            border-bottom: 1px solid #334155;
        }

        /* Nav list — takes remaining space and scrolls vertically */
        #sidebar .sidebar-nav {
            flex: 1 1 auto;
            overflow-y: auto;
            overflow-x: hidden;
            padding-bottom: .5rem;
            /* custom thin scrollbar */
            scrollbar-width: thin;
            scrollbar-color: #334155 transparent;
        }
        #sidebar .sidebar-nav::-webkit-scrollbar       { width: 4px; }
        #sidebar .sidebar-nav::-webkit-scrollbar-thumb { background: #334155; border-radius: 4px; }

        /* User footer — fixed at bottom, never scrolls */
        #sidebar .sidebar-footer {
            flex-shrink: 0;
            padding: .75rem;
            border-top: 1px solid #334155;
        }

        /* Nav links */
        #sidebar .nav-link {
            color: #94a3b8;
            padding: .65rem 1.2rem;
            border-radius: 8px;
            margin: 2px 8px;
            font-size: .88rem;
            transition: background .15s, color .15s;
            display: flex;
            align-items: center;
            gap: 10px;
            white-space: nowrap;
        }
        #sidebar .nav-link:hover,
        #sidebar .nav-link.active {
            background: var(--sidebar-hover);
            color: #fff;
        }
        #sidebar .nav-link i { width: 18px; text-align: center; flex-shrink: 0; }

        #sidebar .section-label {
            display: block;
            font-size: .68rem;
            color: #64748b;
            padding: .9rem 1.5rem .3rem;
            letter-spacing: .06em;
            text-transform: uppercase;
        }

        /* Role accent colours */
        body.role-doctor #sidebar { background: #1a2744; }
        body.role-cs     #sidebar { background: #1a3344; }

        /* ─── Overlay (mobile) ──────────────────────────────── */
        #sidebar-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,.45);
            z-index: 1039;
        }
        #sidebar-overlay.show { display: block; }

        /* ─── Main content ──────────────────────────────────── */
        #main {
            margin-right: var(--sidebar-w);
            min-height: 100vh;
            transition: margin .3s;
        }

        /* Topbar */
        .topbar {
            height: var(--topbar-h);
            background: #fff;
            border-bottom: 1px solid #e2e8f0;
            padding: 0 1.5rem;
            position: sticky;
            top: 0;
            z-index: 999;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .page-content { padding: 1.5rem; }

        /* Cards */
        .stat-card  { border: none; border-radius: 12px; box-shadow: 0 1px 3px rgba(0,0,0,.08); }
        .stat-icon  { width: 50px; height: 50px; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 1.3rem; }
        .role-badge { font-size: .72rem; padding: .3em .6em; }

        /* ─── Mobile (<= 768 px) ────────────────────────────── */
        @media (max-width: 768px) {
            #sidebar {
                transform: translateX(100%); /* hidden off-screen to the right (RTL) */
                width: var(--sidebar-w);     /* keep width so animation is smooth */
            }
            #sidebar.show {
                transform: translateX(0);
            }
            #main {
                margin-right: 0;
            }
        }

        /* ─── Very small screens (<= 360 px) ───────────────── */
        @media (max-width: 360px) {
            :root { --sidebar-w: 240px; }
        }
    </style>
    @stack('styles')
</head>
<body class="role-{{ auth()->user()?->roles->first()?->name ?? 'admin' }}">

{{-- Mobile overlay — tap to close sidebar --}}
<div id="sidebar-overlay"></div>

{{-- ═══════════════════════════════════════════
     SIDEBAR
═══════════════════════════════════════════ --}}
<nav id="sidebar">

    {{-- Brand (never scrolls) --}}
    <div class="sidebar-brand d-flex align-items-center gap-2">
        <div class="bg-primary rounded-2 p-2">
            <i class="fas fa-clinic-medical text-white"></i>
        </div>
        <div>
            <div class="text-white fw-bold" style="font-size:.95rem">عيادتي ERP</div>
            <div style="font-size:.72rem">
                @auth
                    <span class="badge bg-{{ auth()->user()->role_color }} role-badge">
                        {{ auth()->user()->role_label }}
                    </span>
                @endauth
            </div>
        </div>
        {{-- Close button — mobile only --}}
        <button class="btn btn-sm btn-link text-secondary ms-auto d-md-none p-0" id="sidebar-close" title="إغلاق">
            <i class="fas fa-times"></i>
        </button>
    </div>

    {{-- Nav list (scrollable) --}}
    <div class="sidebar-nav">
        <ul class="nav flex-column mt-1">
            @auth
            @php $user = auth()->user(); @endphp

            <li><span class="section-label">الرئيسية</span></li>
            @if($user->isAdmin())
            <li><a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <i class="fas fa-tachometer-alt"></i> لوحة التحكم
            </a></li>
            @endif
            @if($user->isDoctor())
            <li><a href="{{ route('doctor.dashboard') }}" class="nav-link {{ request()->routeIs('doctor.*') ? 'active' : '' }}">
                <i class="fas fa-user-md"></i> لوحتي
            </a></li>
            @endif
            @if($user->isCustomerService())
            <li><a href="{{ route('cs.dashboard') }}" class="nav-link {{ request()->routeIs('cs.*') ? 'active' : '' }}">
                <i class="fas fa-headset"></i> خدمة العملاء
            </a></li>
            @endif

            @if($user->isAdmin() || $user->hasPermission('patients.view'))
            <li><span class="section-label">المرضى</span></li>
            <li><a href="{{ route('patients.index') }}" class="nav-link {{ request()->routeIs('patients.*') ? 'active' : '' }}">
                <i class="fas fa-users"></i> المرضى
            </a></li>
            @endif

            @if($user->isAdmin() || $user->hasPermission('appointments.view'))
            <li><span class="section-label">المواعيد</span></li>
            <li><a href="{{ route('appointments.index') }}" class="nav-link {{ request()->routeIs('appointments.index') || request()->routeIs('appointments.show') || request()->routeIs('appointments.create') ? 'active' : '' }}">
                <i class="fas fa-calendar-check"></i> المواعيد
            </a></li>
            <li><a href="{{ route('appointments.calendar') }}" class="nav-link {{ request()->routeIs('appointments.calendar') ? 'active' : '' }}">
                <i class="fas fa-calendar-alt"></i> التقويم
            </a></li>
            @endif

            @if($user->isAdmin())
            <li><span class="section-label">الإدارة</span></li>
            <li><a href="{{ route('admin.users.index') }}" class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                <i class="fas fa-user-cog"></i> المستخدمون
            </a></li>
            <li><a href="{{ route('admin.doctors.index') }}" class="nav-link {{ request()->routeIs('admin.doctors.*') ? 'active' : '' }}">
                <i class="fas fa-user-md"></i> الأطباء
            </a></li>
            <li><a href="{{ route('admin.roles.index') }}" class="nav-link {{ request()->routeIs('admin.roles.*') ? 'active' : '' }}">
                <i class="fas fa-shield-alt"></i> الأدوار والصلاحيات
            </a></li>

            <li><span class="section-label">التقارير</span></li>
            <li><a href="{{ route('admin.reports.appointments') }}" class="nav-link {{ request()->routeIs('admin.reports.appointments') ? 'active' : '' }}">
                <i class="fas fa-chart-bar"></i> تقارير المواعيد
            </a></li>
            <li><a href="{{ route('admin.reports.patients') }}" class="nav-link {{ request()->routeIs('admin.reports.patients') ? 'active' : '' }}">
                <i class="fas fa-chart-pie"></i> تقارير المرضى
            </a></li>
            <li><a href="{{ route('admin.reports.financial') }}" class="nav-link {{ request()->routeIs('admin.reports.financial') ? 'active' : '' }}">
                <i class="fas fa-chart-line"></i> التقارير المالية
            </a></li>
            @endif

            @if($user->isAdmin() || $user->hasPermission('pharmacy.view'))
            <li><span class="section-label">الصيدلية</span></li>
            <li><a href="{{ route('pharmacy.index') }}" class="nav-link {{ request()->routeIs('pharmacy.*') ? 'active' : '' }}">
                <i class="fas fa-pills"></i> الأدوية
            </a></li>
            <li><a href="{{ route('pharmacy.low-stock') }}" class="nav-link {{ request()->routeIs('pharmacy.low-stock') ? 'active' : '' }}">
                <i class="fas fa-exclamation-triangle"></i> تنبيهات المخزون
            </a></li>
            @endif

            @if($user->isAdmin() || $user->hasPermission('treasury.view'))
            <li><span class="section-label">المالية</span></li>
            <li><a href="{{ route('treasury.index') }}" class="nav-link {{ request()->routeIs('treasury.index') ? 'active' : '' }}">
                <i class="fas fa-cash-register"></i> الخزنة
            </a></li>
            <li><a href="{{ route('treasury.report') }}" class="nav-link {{ request()->routeIs('treasury.report') ? 'active' : '' }}">
                <i class="fas fa-file-invoice-dollar"></i> تقرير مالي
            </a></li>
            @endif

            @if($user->isAdmin() || $user->isCustomerService())
            <li><span class="section-label">خدمة العملاء</span></li>
            <li><a href="{{ route('cs.calls.index') }}" class="nav-link {{ request()->routeIs('cs.calls.*') ? 'active' : '' }}">
                <i class="fas fa-phone"></i> المكالمات
            </a></li>
            <li><a href="{{ route('cs.followups.index') }}" class="nav-link {{ request()->routeIs('cs.followups.*') ? 'active' : '' }}">
                <i class="fas fa-tasks"></i> المتابعات
            </a></li>
            @endif

            @endauth
        </ul>
    </div>

    {{-- User footer (never scrolls) --}}
    <div class="sidebar-footer">
        @auth
        <div class="d-flex align-items-center gap-2 p-2 rounded" style="background:#334155">
            <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center fw-bold text-white"
                 style="width:36px;height:36px;flex-shrink:0;font-size:.8rem">
                {{ mb_substr(auth()->user()->name, 0, 1) }}
            </div>
            <div style="min-width:0;overflow:hidden;flex:1">
                <div class="text-white text-truncate" style="font-size:.82rem">{{ auth()->user()->name }}</div>
                <div class="text-secondary text-truncate" style="font-size:.7rem">
                    {{ auth()->user()->job_title ?? auth()->user()->role_label }}
                </div>
            </div>
            <form method="POST" action="{{ route('logout') }}" class="flex-shrink-0">
                @csrf
                <button class="btn btn-sm btn-link text-secondary p-0" title="تسجيل الخروج">
                    <i class="fas fa-sign-out-alt"></i>
                </button>
            </form>
        </div>
        @endauth
    </div>

</nav>

{{-- ═══════════════════════════════════════════
     MAIN CONTENT
═══════════════════════════════════════════ --}}
<div id="main">

    {{-- Topbar --}}
    <div class="topbar">
        <div class="d-flex align-items-center gap-3">
            {{-- Mobile menu toggle --}}
            <button class="btn btn-sm btn-outline-secondary d-md-none" id="sidebar-toggle" title="القائمة">
                <i class="fas fa-bars"></i>
            </button>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 small">@yield('breadcrumb')</ol>
            </nav>
        </div>
        <div class="d-flex align-items-center gap-3">
            <span class="text-muted small d-none d-lg-block">{{ now()->format('l، d F Y') }}</span>
            @auth
            @if(auth()->user()->isAdmin() || auth()->user()->isCustomerService())
                @php
                    $overdueCount = \App\Models\Followup::where('status','!=','completed')
                        ->where('due_date','<', now())->count();
                @endphp
                @if($overdueCount > 0)
                <a href="{{ route('cs.followups.index') }}?overdue=1"
                   class="btn btn-sm btn-outline-danger position-relative" title="متابعات متأخرة">
                    <i class="fas fa-bell"></i>
                    <span class="position-absolute top-0 start-0 translate-middle badge rounded-pill bg-danger"
                          style="font-size:.6rem">{{ $overdueCount }}</span>
                </a>
                @endif
            @endif
            @endauth
        </div>
    </div>

    {{-- Page content --}}
    <div class="page-content">

        @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm">
            <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @endif

        @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm">
            <i class="fas fa-exclamation-circle me-2"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @endif

        @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm">
            <i class="fas fa-exclamation-triangle me-2"></i>
            <ul class="mb-0 mt-1">
                @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @endif

        @yield('content')
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
(function () {
    const sidebar  = document.getElementById('sidebar');
    const overlay  = document.getElementById('sidebar-overlay');
    const toggleBtn = document.getElementById('sidebar-toggle');
    const closeBtn  = document.getElementById('sidebar-close');

    function openSidebar() {
        sidebar.classList.add('show');
        overlay.classList.add('show');
        document.body.style.overflow = 'hidden'; // prevent background scroll on mobile
    }

    function closeSidebar() {
        sidebar.classList.remove('show');
        overlay.classList.remove('show');
        document.body.style.overflow = '';
    }

    if (toggleBtn) toggleBtn.addEventListener('click', openSidebar);
    if (closeBtn)  closeBtn.addEventListener('click',  closeSidebar);
    if (overlay)   overlay.addEventListener('click',   closeSidebar);

    // Close sidebar on nav-link click (mobile)
    sidebar.querySelectorAll('.nav-link').forEach(function (link) {
        link.addEventListener('click', function () {
            if (window.innerWidth <= 768) closeSidebar();
        });
    });

    // Highlight active link by scroll position (optional — already handled server-side)
    // Re-open sidebar if viewport grows above breakpoint
    window.addEventListener('resize', function () {
        if (window.innerWidth > 768) closeSidebar();
    });
})();
</script>
@stack('scripts')
</body>
</html>
