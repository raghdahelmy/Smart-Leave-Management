<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Smart Leave System')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
        :root {
            --sidebar-width: 260px;
            --primary: #4361ee;
            --primary-dark: #3a50d9;
            --sidebar-bg: #1e293b;
            --sidebar-hover: #334155;
            --sidebar-active: #4361ee;
        }
        body { background: #f1f5f9; font-family: 'Segoe UI', sans-serif; }
        .sidebar {
            width: var(--sidebar-width);
            min-height: 100vh;
            background: var(--sidebar-bg);
            position: fixed;
            top: 0; left: 0;
            z-index: 1000;
            transition: transform .3s;
        }
        .sidebar .brand {
            padding: 1.25rem;
            border-bottom: 1px solid rgba(255,255,255,.08);
        }
        .sidebar .brand h5 { color: #fff; margin: 0; font-weight: 700; }
        .sidebar .brand small { color: #94a3b8; font-size: .75rem; }
        .sidebar .nav-link {
            color: #cbd5e1;
            padding: .65rem 1.25rem;
            border-radius: .5rem;
            margin: .15rem .75rem;
            font-size: .875rem;
            transition: all .2s;
        }
        .sidebar .nav-link:hover { background: var(--sidebar-hover); color: #fff; }
        .sidebar .nav-link.active { background: var(--sidebar-active); color: #fff; font-weight: 600; }
        .sidebar .nav-link i { width: 22px; margin-right: .5rem; }
        .sidebar .nav-section {
            color: #64748b;
            font-size: .7rem;
            text-transform: uppercase;
            letter-spacing: .08em;
            padding: 1rem 1.25rem .35rem;
            font-weight: 700;
        }
        .main-content {
            margin-left: var(--sidebar-width);
            min-height: 100vh;
        }
        .top-navbar {
            background: #fff;
            border-bottom: 1px solid #e2e8f0;
            padding: .75rem 1.5rem;
        }
        .content-area { padding: 1.5rem; }
        .stat-card {
            border: none;
            border-radius: .75rem;
            box-shadow: 0 1px 3px rgba(0,0,0,.06);
            transition: transform .2s;
        }
        .stat-card:hover { transform: translateY(-2px); }
        .stat-card .icon-box {
            width: 48px; height: 48px;
            border-radius: .75rem;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.25rem;
        }
        .table th { font-weight: 600; font-size: .8rem; text-transform: uppercase; color: #64748b; letter-spacing: .05em; }
        .badge-pending { background: #fef3c7; color: #92400e; }
        .badge-approved { background: #d1fae5; color: #065f46; }
        .badge-rejected { background: #fee2e2; color: #991b1b; }
        .badge-open { background: #dbeafe; color: #1e40af; }
        .badge-in-progress { background: #fef3c7; color: #92400e; }
        .badge-resolved { background: #d1fae5; color: #065f46; }
        @media (max-width: 991.98px) {
            .sidebar { transform: translateX(-100%); }
            .sidebar.show { transform: translateX(0); }
            .main-content { margin-left: 0; }
        }
    </style>
    @stack('styles')
</head>
<body>
    {{-- Sidebar --}}
    <aside class="sidebar" id="sidebar">
        <div class="brand">
            <h5><i class="bi bi-calendar2-check"></i> SLS</h5>
            <small>Smart Leave System</small>
        </div>
        <nav class="mt-2">
            @if(auth()->user()->role === 'admin')
                <div class="nav-section">Main</div>
                <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <i class="bi bi-grid-1x2-fill"></i> Dashboard
                </a>

                <div class="nav-section">Management</div>
                <a href="{{ route('admin.employees.index') }}" class="nav-link {{ request()->routeIs('admin.employees.*') ? 'active' : '' }}">
                    <i class="bi bi-people-fill"></i> Employees
                </a>
                <a href="{{ route('admin.departments.index') }}" class="nav-link {{ request()->routeIs('admin.departments.*') ? 'active' : '' }}">
                    <i class="bi bi-building"></i> Departments
                </a>
                <a href="{{ route('admin.leave-types.index') }}" class="nav-link {{ request()->routeIs('admin.leave-types.*') ? 'active' : '' }}">
                    <i class="bi bi-tags-fill"></i> Leave Types
                </a>

                <div class="nav-section">Leaves</div>
                <a href="{{ route('admin.leaves.index') }}" class="nav-link {{ request()->routeIs('admin.leaves.*') ? 'active' : '' }}">
                    <i class="bi bi-envelope-paper-fill"></i> Leave Requests
                </a>
                <a href="{{ route('admin.balances.index') }}" class="nav-link {{ request()->routeIs('admin.balances.*') ? 'active' : '' }}">
                    <i class="bi bi-wallet2"></i> Leave Balances
                </a>
                <a href="{{ route('admin.calendar') }}" class="nav-link {{ request()->routeIs('admin.calendar') ? 'active' : '' }}">
                    <i class="bi bi-calendar3"></i> Calendar
                </a>

                <div class="nav-section">Reports</div>
                <a href="{{ route('admin.reports.index') }}" class="nav-link {{ request()->routeIs('admin.reports.*') ? 'active' : '' }}">
                    <i class="bi bi-file-earmark-bar-graph"></i> Reports & Export
                </a>

                <div class="nav-section">Support</div>
                <a href="{{ route('admin.complaints.index') }}" class="nav-link {{ request()->routeIs('admin.complaints.*') ? 'active' : '' }}">
                    <i class="bi bi-chat-dots-fill"></i> Complaints
                </a>
                <a href="{{ route('admin.activity-logs') }}" class="nav-link {{ request()->routeIs('admin.activity-logs') ? 'active' : '' }}">
                    <i class="bi bi-clock-history"></i> Activity Logs
                </a>
            @else
                <div class="nav-section">Main</div>
                <a href="{{ route('employee.dashboard') }}" class="nav-link {{ request()->routeIs('employee.dashboard') ? 'active' : '' }}">
                    <i class="bi bi-grid-1x2-fill"></i> Dashboard
                </a>

                <div class="nav-section">Leaves</div>
                <a href="{{ route('employee.leaves.create') }}" class="nav-link {{ request()->routeIs('employee.leaves.create') ? 'active' : '' }}">
                    <i class="bi bi-plus-circle-fill"></i> Apply Leave
                </a>
                <a href="{{ route('employee.leaves.index') }}" class="nav-link {{ request()->routeIs('employee.leaves.index') ? 'active' : '' }}">
                    <i class="bi bi-clock-history"></i> My Leaves
                </a>
                <a href="{{ route('employee.balances') }}" class="nav-link {{ request()->routeIs('employee.balances') ? 'active' : '' }}">
                    <i class="bi bi-wallet2"></i> My Balance
                </a>

                <div class="nav-section">Support</div>
                <a href="{{ route('employee.complaints.index') }}" class="nav-link {{ request()->routeIs('employee.complaints.*') ? 'active' : '' }}">
                    <i class="bi bi-chat-dots-fill"></i> Complaints
                </a>
            @endif
        </nav>
    </aside>

    {{-- Main Content --}}
    <div class="main-content">
        {{-- Top Navbar --}}
        <div class="top-navbar d-flex justify-content-between align-items-center">
            <div class="d-flex align-items-center gap-3">
                <button class="btn btn-sm btn-light d-lg-none" onclick="document.getElementById('sidebar').classList.toggle('show')">
                    <i class="bi bi-list"></i>
                </button>
                <h6 class="mb-0">@yield('page-title', 'Dashboard')</h6>
            </div>
            <div class="d-flex align-items-center gap-3">
                <span class="text-muted small">{{ auth()->user()->name }}</span>
                <div class="dropdown">
                    <button class="btn btn-sm btn-light rounded-circle" data-bs-toggle="dropdown">
                        <i class="bi bi-person-fill"></i>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><span class="dropdown-item-text fw-semibold">{{ auth()->user()->name }}</span></li>
                        <li><span class="dropdown-item-text text-muted small">{{ auth()->user()->email }}</span></li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button class="dropdown-item text-danger"><i class="bi bi-box-arrow-right me-2"></i>Logout</button>
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        {{-- Content --}}
        <div class="content-area">
            {{-- Flash Messages --}}
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="bi bi-check-circle me-1"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="bi bi-exclamation-circle me-1"></i> {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @yield('content')
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        $.ajaxSetup && $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content } });
    </script>
    @stack('scripts')
</body>
</html>
