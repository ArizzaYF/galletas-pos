<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'GalletasPOS') 🍪</title>
    <link href="https://fonts.googleapis.com/css2?family=DM+Serif+Display:ital@0;1&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        :root {
            --cream: #FDF6EC;
            --brown-dark: #2C1A0E;
            --brown-mid: #6B3F1F;
            --brown-light: #C47A3A;
            --accent: #E8A045;
            --accent2: #D4621A;
            --soft: #F5E6D0;
            --border: #E2C89A;
            --green: #2E7D52;
            --green-light: #E8F5EE;
            --red: #C0392B;
            --red-light: #FDECEA;
            --text: #2C1A0E;
            --text-muted: #8B6B4A;
            --white: #FFFFFF;
            --shadow: 0 4px 24px rgba(44,26,14,0.10);
            --shadow-lg: 0 8px 40px rgba(44,26,14,0.16);
        }

        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'DM Sans', sans-serif;
            background: var(--cream);
            color: var(--text);
            min-height: 100vh;
            display: flex;
        }

        /* SIDEBAR */
        .sidebar {
            width: 220px;
            background: var(--brown-dark);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            position: fixed;
            top: 0; left: 0;
            z-index: 100;
        }
        .sidebar-logo {
            padding: 24px 20px 16px;
            border-bottom: 1px solid rgba(255,255,255,0.08);
        }
        .sidebar-logo .title {
            font-family: 'DM Serif Display', serif;
            color: var(--accent);
            font-size: 20px;
        }
        .sidebar-logo .sub {
            color: #888;
            font-size: 11px;
            margin-top: 2px;
        }
        .sidebar-menu { padding: 12px 0; flex: 1; }
        .menu-item {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 11px 20px;
            color: #999;
            font-size: 13px;
            font-weight: 500;
            text-decoration: none;
            border-left: 3px solid transparent;
            transition: all 0.15s;
        }
        .menu-item:hover { color: var(--accent); background: rgba(255,255,255,0.04); }
        .menu-item.active {
            color: var(--accent);
            border-left-color: var(--accent);
            background: rgba(232,160,69,0.08);
        }
        .menu-icon { font-size: 16px; }

        /* MAIN */
        .main-wrapper {
            margin-left: 220px;
            flex: 1;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }
        .topbar {
            background: var(--white);
            padding: 14px 28px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-bottom: 1px solid var(--border);
            position: sticky;
            top: 0;
            z-index: 50;
        }
        .topbar .page-title {
            font-family: 'DM Serif Display', serif;
            font-size: 20px;
            color: var(--brown-dark);
        }
        .page-body { padding: 28px; }

        /* CARDS */
        .card {
            background: var(--white);
            border-radius: 12px;
            box-shadow: var(--shadow);
            overflow: hidden;
        }
        .card-header {
            padding: 14px 20px;
            border-bottom: 1px solid var(--border);
            font-weight: 600;
            font-size: 14px;
            color: var(--brown-dark);
            background: var(--soft);
        }
        .card-body { padding: 20px; }

        /* STATS */
        .stats-row {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
            gap: 16px;
            margin-bottom: 24px;
        }
        .stat-card {
            background: var(--white);
            border-radius: 12px;
            padding: 20px;
            box-shadow: var(--shadow);
            border-left: 4px solid var(--accent);
        }
        .stat-card .label { font-size: 12px; color: var(--text-muted); margin-bottom: 6px; }
        .stat-card .value { font-size: 26px; font-weight: 700; color: var(--brown-dark); }
        .stat-card .sub { font-size: 11px; color: var(--text-muted); margin-top: 4px; }
        .stat-card.accent { border-left-color: var(--accent2); }
        .stat-card.green { border-left-color: var(--green); }
        .stat-card.red { border-left-color: var(--red); }

        /* ALERTS */
        .alert {
            padding: 12px 16px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 14px;
            display: flex;
            align-items: flex-start;
            gap: 10px;
        }
        .alert-success { background: var(--green-light); color: var(--green); border: 1px solid #b7dfc8; }
        .alert-danger { background: var(--red-light); color: var(--red); border: 1px solid #f5c0bb; }

        /* TABLES */
        .data-table { width: 100%; border-collapse: collapse; font-size: 13px; }
        .data-table th {
            background: var(--soft);
            padding: 10px 14px;
            text-align: left;
            font-weight: 600;
            color: var(--text-muted);
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .data-table td { padding: 11px 14px; border-bottom: 1px solid #f0e8dc; }
        .data-table tr:last-child td { border-bottom: none; }
        .data-table tr:hover td { background: #fdf8f3; }

        /* BADGES */
        .tag {
            display: inline-block;
            padding: 3px 10px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 600;
        }
        .tag-green { background: var(--green-light); color: var(--green); }
        .tag-red { background: var(--red-light); color: var(--red); }
        .tag-orange { background: #FFF3E0; color: #E65100; }
        .tag-purple { background: #F3E5F5; color: #7B1FA2; }
        .tag-blue { background: #E3F2FD; color: #1565C0; }
        .tag-gray { background: #F5F5F5; color: #616161; }

        /* FORMS */
        .form-group { margin-bottom: 16px; }
        .form-label { display: block; font-size: 13px; font-weight: 500; color: var(--text-muted); margin-bottom: 6px; }
        .input-field {
            width: 100%;
            padding: 9px 12px;
            border: 1px solid var(--border);
            border-radius: 8px;
            font-family: 'DM Sans', sans-serif;
            font-size: 13px;
            color: var(--text);
            background: var(--white);
            transition: border 0.2s;
        }
        .input-field:focus { outline: none; border-color: var(--accent); }

        /* BUTTONS */
        .btn {
            padding: 9px 18px;
            border-radius: 8px;
            border: none;
            font-family: 'DM Sans', sans-serif;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            transition: all 0.2s;
        }
        .btn-primary { background: var(--accent); color: var(--brown-dark); }
        .btn-primary:hover { background: var(--accent2); color: white; }
        .btn-danger { background: var(--red); color: white; }
        .btn-danger:hover { background: #a93226; }
        .btn-success { background: var(--green); color: white; }
        .btn-success:hover { background: #1d5c38; }
        .btn-outline {
            background: transparent;
            border: 1px solid var(--border);
            color: var(--text-muted);
        }
        .btn-outline:hover { border-color: var(--accent); color: var(--accent); }
        .btn-sm { padding: 6px 12px; font-size: 12px; }
    </style>
</head>
<body>

{{-- SIDEBAR --}}
<div class="sidebar">
    <div class="sidebar-logo">
        <div class="title">🍪 GalletasPOS</div>
        <div class="sub">Panel de control</div>
    </div>
    <nav class="sidebar-menu">
        <a href="/resumen" class="menu-item {{ request()->is('resumen*') ? 'active' : '' }}">
            <span class="menu-icon">📊</span> Resumen
        </a>
        <a href="/ventas/nueva" class="menu-item {{ request()->is('ventas*') ? 'active' : '' }}">
            <span class="menu-icon">🛒</span> Nueva Venta
        </a>
        <a href="/pagos" class="menu-item {{ request()->is('pagos*') ? 'active' : '' }}">
            <span class="menu-icon">💳</span> Créditos
        </a>
        <a href="/inventario" class="menu-item {{ request()->is('inventario*') ? 'active' : '' }}">
            <span class="menu-icon">📦</span> Inventario
        </a>
    </nav>
</div>

{{-- MAIN --}}
<div class="main-wrapper">
    <div class="topbar">
        <span class="page-title">@yield('page-title', 'GalletasPOS')</span>
        <span style="font-size:13px; color: var(--text-muted);">{{ now()->format('d/m/Y') }}</span>
    </div>
    <div class="page-body">

        @if(session('success'))
            <div class="alert alert-success">
                <i class="bi bi-check-circle-fill"></i>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger">
                <i class="bi bi-exclamation-triangle-fill"></i>
                <div>
                    @foreach($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                </div>
            </div>
        @endif

        @yield('content')
    </div>
</div>

@stack('scripts')
</body>
</html>