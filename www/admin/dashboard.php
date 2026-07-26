<?php
require_once '../includes/auth.php';
requireLogin();
require_once '../includes/functions.php';

$admin = getCurrentAdmin();
$totalPortfolios  = $pdo->query("SELECT COUNT(*) FROM portfolios")->fetchColumn();
$activePortfolios = $pdo->query("SELECT COUNT(*) FROM portfolios WHERE is_active = 1")->fetchColumn();
$totalContacts    = $pdo->query("SELECT COUNT(*) FROM contacts WHERE is_active = 1")->fetchColumn();
$siteName         = getSetting('site_name');
?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - My-Portfolio.Tool</title>
    <style>
        /* ============================================
           CSS CUSTOM PROPERTIES (Design Tokens)
           ============================================ */
        :root {
            /* Colors */
            --color-primary: #6366f1;
            --color-primary-light: #818cf8;
            --color-primary-dark: #4f46e5;
            --color-primary-bg: rgba(99, 102, 241, 0.08);
            --color-primary-bg-hover: rgba(99, 102, 241, 0.14);

            --color-success: #10b981;
            --color-success-bg: rgba(16, 185, 129, 0.1);
            --color-warning: #f59e0b;
            --color-warning-bg: rgba(245, 158, 11, 0.1);
            --color-danger: #ef4444;
            --color-danger-bg: rgba(239, 68, 68, 0.1);
            --color-info: #3b82f6;
            --color-info-bg: rgba(59, 130, 246, 0.1);

            --color-bg: #f1f5f9;
            --color-surface: #ffffff;
            --color-text: #0f172a;
            --color-text-secondary: #64748b;
            --color-text-muted: #94a3b8;
            --color-border: #e2e8f0;
            --color-border-light: #f1f5f9;

            /* Shadows */
            --shadow-sm: 0 1px 2px rgba(0, 0, 0, 0.04);
            --shadow-md: 0 4px 12px rgba(0, 0, 0, 0.06);
            --shadow-lg: 0 8px 30px rgba(0, 0, 0, 0.08);
            --shadow-xl: 0 20px 50px rgba(0, 0, 0, 0.12);
            --shadow-primary: 0 4px 20px rgba(99, 102, 241, 0.25);

            /* Spacing */
            --space-xs: 4px;
            --space-sm: 8px;
            --space-md: 16px;
            --space-lg: 24px;
            --space-xl: 32px;
            --space-2xl: 48px;

            /* Radius */
            --radius-sm: 8px;
            --radius-md: 12px;
            --radius-lg: 16px;
            --radius-xl: 20px;
            --radius-full: 9999px;

            /* Transitions */
            --transition-fast: 150ms cubic-bezier(0.4, 0, 0.2, 1);
            --transition-base: 250ms cubic-bezier(0.4, 0, 0.2, 1);
            --transition-slow: 350ms cubic-bezier(0.4, 0, 0.2, 1);

            /* Layout */
            --sidebar-width: 260px;
            --header-height: 64px;
        }

        /* ============================================
           RESET & BASE
           ============================================ */
        *, *::before, *::after {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        html {
            scroll-behavior: smooth;
            -webkit-text-size-adjust: 100%;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: var(--color-bg);
            color: var(--color-text);
            line-height: 1.6;
            min-height: 100vh;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }

        /* ============================================
           LAYOUT
           ============================================ */
        .admin-layout {
            display: flex;
            min-height: calc(100vh - var(--header-height));
        }

        /* ============================================
           SIDEBAR
           ============================================ */
        .sidebar {
            width: var(--sidebar-width);
            background: var(--color-surface);
            border-right: 1px solid var(--color-border);
            padding: var(--space-md) 0;
            position: sticky;
            top: var(--header-height);
            height: calc(100vh - var(--header-height));
            overflow-y: auto;
            flex-shrink: 0;
            transition: transform var(--transition-base);
            z-index: 40;
        }

        .sidebar::-webkit-scrollbar {
            width: 4px;
        }
        .sidebar::-webkit-scrollbar-thumb {
            background: var(--color-border);
            border-radius: var(--radius-full);
        }

        .sidebar__list {
            list-style: none;
            padding: 0 var(--space-sm);
        }

        .sidebar__link {
            display: flex;
            align-items: center;
            gap: var(--space-md);
            padding: 10px var(--space-md);
            color: var(--color-text-secondary);
            text-decoration: none;
            border-radius: var(--radius-sm);
            font-size: 14px;
            font-weight: 500;
            transition: all var(--transition-fast);
            margin-bottom: 2px;
        }

        .sidebar__link:hover {
            background: var(--color-primary-bg);
            color: var(--color-primary);
        }

        .sidebar__link--active {
            background: var(--color-primary-bg);
            color: var(--color-primary);
            font-weight: 600;
        }

        .sidebar__link--active .sidebar__icon {
            color: var(--color-primary);
        }

        .sidebar__icon {
            width: 20px;
            height: 20px;
            flex-shrink: 0;
            color: var(--color-text-muted);
            transition: color var(--transition-fast);
        }

        .sidebar__link:hover .sidebar__icon {
            color: var(--color-primary);
        }

        .sidebar__icon--sm {
            width: 14px;
            height: 14px;
            margin-left: auto;
            opacity: 0.5;
        }

        .sidebar__divider {
            height: 1px;
            background: var(--color-border);
            margin: var(--space-md) var(--space-md);
        }

        .sidebar__link--logout:hover {
            background: var(--color-danger-bg);
            color: var(--color-danger);
        }
        .sidebar__link--logout:hover .sidebar__icon {
            color: var(--color-danger);
        }

        /* ============================================
           MOBILE SIDEBAR OVERLAY
           ============================================ */
        .sidebar-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.4);
            z-index: 35;
            backdrop-filter: blur(4px);
            opacity: 0;
            transition: opacity var(--transition-base);
        }
        .sidebar-overlay.is-visible {
            opacity: 1;
        }

        /* ============================================
           HEADER
           ============================================ */
        .header {
            background: var(--color-surface);
            border-bottom: 1px solid var(--color-border);
            height: var(--header-height);
            position: sticky;
            top: 0;
            z-index: 50;
        }

        .header__container {
            display: flex;
            align-items: center;
            justify-content: space-between;
            height: 100%;
            padding: 0 var(--space-lg);
            max-width: 100%;
        }

        .header__brand {
            display: flex;
            align-items: center;
            gap: var(--space-md);
        }

        .header__logo-icon {
            width: 32px;
            height: 32px;
            color: var(--color-primary);
        }

        .header__title {
            font-size: 18px;
            font-weight: 700;
            color: var(--color-text);
            letter-spacing: -0.02em;
        }

        .header__subtitle {
            font-size: 12px;
            color: var(--color-text-muted);
            font-weight: 500;
        }

        .header__actions {
            display: flex;
            align-items: center;
            gap: var(--space-md);
        }

        .header__user {
            display: flex;
            align-items: center;
            gap: var(--space-sm);
            font-size: 14px;
            color: var(--color-text-secondary);
        }

        .header__icon {
            width: 18px;
            height: 18px;
        }

        .header__greeting {
            color: var(--color-text-muted);
        }

        .header__username {
            font-weight: 600;
            color: var(--color-text);
        }

        .header__btn {
            display: inline-flex;
            align-items: center;
            gap: var(--space-sm);
            padding: 8px 16px;
            border-radius: var(--radius-sm);
            font-size: 13px;
            font-weight: 500;
            text-decoration: none;
            transition: all var(--transition-fast);
        }

        .header__btn--ghost {
            color: var(--color-text-secondary);
            background: var(--color-bg);
        }
        .header__btn--ghost:hover {
            background: var(--color-primary-bg);
            color: var(--color-primary);
        }

        .header__btn--danger {
            color: var(--color-danger);
            background: var(--color-danger-bg);
        }
        .header__btn--danger:hover {
            background: var(--color-danger);
            color: white;
        }

        .hamburger-btn {
            display: none;
            background: none;
            border: none;
            padding: 8px;
            cursor: pointer;
            color: var(--color-text);
            border-radius: var(--radius-sm);
            transition: background var(--transition-fast);
        }
        .hamburger-btn:hover {
            background: var(--color-bg);
        }
        .hamburger-btn svg {
            width: 24px;
            height: 24px;
        }

        /* ============================================
           MAIN CONTENT
           ============================================ */
        .main-content {
            flex: 1;
            padding: var(--space-xl);
            min-width: 0;
            overflow-x: hidden;
        }

        /* ============================================
           WELCOME SECTION
           ============================================ */
        .welcome-section {
            background: linear-gradient(135deg, var(--color-primary) 0%, #8b5cf6 50%, #a78bfa 100%);
            border-radius: var(--radius-xl);
            padding: var(--space-xl) var(--space-2xl);
            color: white;
            margin-bottom: var(--space-xl);
            position: relative;
            overflow: hidden;
        }

        .welcome-section::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -20%;
            width: 400px;
            height: 400px;
            background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
            border-radius: 50%;
            pointer-events: none;
        }

        .welcome-section::after {
            content: '';
            position: absolute;
            bottom: -30%;
            left: 10%;
            width: 250px;
            height: 250px;
            background: radial-gradient(circle, rgba(255,255,255,0.06) 0%, transparent 70%);
            border-radius: 50%;
            pointer-events: none;
        }

        .welcome__greeting {
            font-size: 14px;
            font-weight: 500;
            opacity: 0.85;
            margin-bottom: var(--space-xs);
            display: flex;
            align-items: center;
            gap: var(--space-sm);
        }

        .welcome__wave {
            width: 20px;
            height: 20px;
            display: inline-block;
            animation: wave 2.5s ease-in-out infinite;
            transform-origin: 70% 70%;
        }

        @keyframes wave {
            0%, 100% { transform: rotate(0deg); }
            10% { transform: rotate(14deg); }
            20% { transform: rotate(-8deg); }
            30% { transform: rotate(14deg); }
            40% { transform: rotate(-4deg); }
            50% { transform: rotate(10deg); }
            60%, 100% { transform: rotate(0deg); }
        }

        .welcome__name {
            font-size: 28px;
            font-weight: 800;
            letter-spacing: -0.03em;
            position: relative;
            z-index: 1;
        }

        .welcome__date {
            font-size: 13px;
            opacity: 0.7;
            margin-top: var(--space-sm);
            position: relative;
            z-index: 1;
        }

        /* ============================================
           STATS GRID
           ============================================ */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: var(--space-lg);
            margin-bottom: var(--space-xl);
        }

        .stat-card {
            background: var(--color-surface);
            border-radius: var(--radius-lg);
            padding: var(--space-lg);
            border: 1px solid var(--color-border);
            transition: all var(--transition-base);
            position: relative;
            overflow: hidden;
        }

        .stat-card::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 3px;
            border-radius: var(--radius-lg) var(--radius-lg) 0 0;
            opacity: 0;
            transition: opacity var(--transition-base);
        }

        .stat-card:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-lg);
            border-color: transparent;
        }

        .stat-card:hover::after {
            opacity: 1;
        }

        .stat-card--primary::after { background: var(--color-primary); }
        .stat-card--success::after { background: var(--color-success); }
        .stat-card--info::after    { background: var(--color-info); }
        .stat-card--warning::after { background: var(--color-warning); }

        .stat-card__header {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            margin-bottom: var(--space-md);
        }

        .stat-card__icon-wrap {
            width: 44px;
            height: 44px;
            border-radius: var(--radius-md);
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .stat-card--primary .stat-card__icon-wrap { background: var(--color-primary-bg); color: var(--color-primary); }
        .stat-card--success .stat-card__icon-wrap { background: var(--color-success-bg); color: var(--color-success); }
        .stat-card--info    .stat-card__icon-wrap { background: var(--color-info-bg);    color: var(--color-info); }
        .stat-card--warning .stat-card__icon-wrap { background: var(--color-warning-bg); color: var(--color-warning); }

        .stat-card__icon {
            width: 22px;
            height: 22px;
        }

        .stat-card__value {
            font-size: 32px;
            font-weight: 800;
            letter-spacing: -0.03em;
            line-height: 1;
            color: var(--color-text);
        }

        .stat-card__label {
            font-size: 13px;
            color: var(--color-text-muted);
            font-weight: 500;
            margin-top: var(--space-xs);
        }

        /* ============================================
           QUICK ACTIONS
           ============================================ */
        .section-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: var(--space-lg);
        }

        .section-title {
            font-size: 18px;
            font-weight: 700;
            color: var(--color-text);
            letter-spacing: -0.02em;
        }

        .actions-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: var(--space-md);
        }

        .action-card {
            background: var(--color-surface);
            border: 1px solid var(--color-border);
            border-radius: var(--radius-lg);
            padding: var(--space-lg);
            text-decoration: none;
            color: var(--color-text);
            display: flex;
            align-items: center;
            gap: var(--space-md);
            transition: all var(--transition-base);
            position: relative;
            overflow: hidden;
        }

        .action-card::before {
            content: '';
            position: absolute;
            inset: 0;
            background: var(--color-primary-bg);
            opacity: 0;
            transition: opacity var(--transition-base);
        }

        .action-card:hover {
            border-color: var(--color-primary-light);
            transform: translateY(-2px);
            box-shadow: var(--shadow-md);
        }

        .action-card:hover::before {
            opacity: 1;
        }

        .action-card__icon-wrap {
            width: 44px;
            height: 44px;
            border-radius: var(--radius-md);
            background: var(--color-primary-bg);
            color: var(--color-primary);
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            position: relative;
            z-index: 1;
            transition: all var(--transition-base);
        }

        .action-card:hover .action-card__icon-wrap {
            background: var(--color-primary);
            color: white;
        }

        .action-card__icon {
            width: 20px;
            height: 20px;
        }

        .action-card__content {
            position: relative;
            z-index: 1;
        }

        .action-card__title {
            font-size: 14px;
            font-weight: 600;
            margin-bottom: 2px;
        }

        .action-card__desc {
            font-size: 12px;
            color: var(--color-text-muted);
        }

        .action-card__arrow {
            margin-left: auto;
            width: 16px;
            height: 16px;
            color: var(--color-text-muted);
            position: relative;
            z-index: 1;
            transition: all var(--transition-base);
            opacity: 0;
            transform: translateX(-8px);
        }

        .action-card:hover .action-card__arrow {
            opacity: 1;
            transform: translateX(0);
            color: var(--color-primary);
        }

        /* ============================================
           ALERTS
           ============================================ */
        .alert {
            padding: var(--space-md) var(--space-lg);
            border-radius: var(--radius-md);
            margin-bottom: var(--space-lg);
            font-size: 14px;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: var(--space-sm);
        }
        .alert-success { background: var(--color-success-bg); color: #065f46; border: 1px solid rgba(16,185,129,0.2); }
        .alert-error   { background: var(--color-danger-bg);  color: #991b1b; border: 1px solid rgba(239,68,68,0.2); }
        .alert-info    { background: var(--color-info-bg);    color: #1e40af; border: 1px solid rgba(59,130,246,0.2); }

        /* ============================================
           RESPONSIVE
           ============================================ */
        @media (max-width: 1200px) {
            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
            }
            .actions-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 768px) {
            .hamburger-btn {
                display: flex;
            }

            .sidebar {
                position: fixed;
                top: 0;
                left: 0;
                height: 100vh;
                transform: translateX(-100%);
                z-index: 45;
                padding-top: var(--space-xl);
                box-shadow: var(--shadow-xl);
            }

            .sidebar.is-open {
                transform: translateX(0);
            }

            .sidebar-overlay.is-visible {
                display: block;
                opacity: 1;
            }

            .main-content {
                padding: var(--space-md);
            }

            .welcome-section {
                padding: var(--space-lg);
            }

            .welcome__name {
                font-size: 22px;
            }

            .stats-grid {
                grid-template-columns: 1fr 1fr;
                gap: var(--space-md);
            }

            .stat-card {
                padding: var(--space-md);
            }

            .stat-card__value {
                font-size: 24px;
            }

            .actions-grid {
                grid-template-columns: 1fr;
            }

            .header__btn span {
                display: none;
            }

            .header__greeting {
                display: none;
            }
        }

        @media (max-width: 480px) {
            .stats-grid {
                grid-template-columns: 1fr;
            }

            .header__container {
                padding: 0 var(--space-md);
            }

            .header__subtitle {
                display: none;
            }
        }
    </style>
</head>
<body>

    <!-- ========== HEADER ========== -->
    <header class="header" role="banner">
        <div class="header__container">
            <div class="header__brand">
                <button class="hamburger-btn" id="hamburgerBtn" aria-label="Toggle navigation menu" aria-expanded="false">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="3" y1="6" x2="21" y2="6"/>
                        <line x1="3" y1="12" x2="21" y2="12"/>
                        <line x1="3" y1="18" x2="21" y2="18"/>
                    </svg>
                </button>
                <svg class="header__logo-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <polygon points="12 2 2 7 12 12 22 7 12 2"/>
                    <polyline points="2 17 12 22 22 17"/>
                    <polyline points="2 12 12 17 22 12"/>
                </svg>
                <div>
                    <div class="header__title">My-Portfolio.Tool</div>
                    <span class="header__subtitle">Admin Panel</span>
                </div>
            </div>

            <div class="header__actions">
                <div class="header__user">
                    <svg class="header__icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/>
                        <circle cx="12" cy="7" r="4"/>
                    </svg>
                    <span class="header__greeting">Hello,</span>
                    <span class="header__username"><?= htmlspecialchars($admin['name']) ?></span>
                </div>

                <a href="../index.php" target="_blank" rel="noopener" class="header__btn header__btn--ghost" title="View Site">
                    <svg class="header__icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M18 13v6a2 2 0 01-2 2H5a2 2 0 01-2-2V8a2 2 0 012-2h6"/>
                        <polyline points="15 3 21 3 21 9"/>
                        <line x1="10" y1="14" x2="21" y2="3"/>
                    </svg>
                    <span>View Site</span>
                </a>

                <a href="logout.php" class="header__btn header__btn--danger" title="Logout">
                    <svg class="header__icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M9 21H5a2 2 0 01-2-2V5a2 2 0 012-2h4"/>
                        <polyline points="16 17 21 12 16 7"/>
                        <line x1="21" y1="12" x2="9" y2="12"/>
                    </svg>
                    <span>Logout</span>
                </a>
            </div>
        </div>
    </header>

    <!-- ========== LAYOUT ========== -->
    <div class="admin-layout">

        <!-- Sidebar Overlay (Mobile) -->
        <div class="sidebar-overlay" id="sidebarOverlay"></div>

        <!-- ========== SIDEBAR ========== -->
        <aside class="sidebar" id="sidebar" aria-label="Admin navigation">
            <nav class="sidebar__nav">
                <ul class="sidebar__list">
                    <li class="sidebar__item">
                        <a href="dashboard.php" class="sidebar__link sidebar__link--active">
                            <svg class="sidebar__icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <rect x="3" y="3" width="7" height="7" rx="1"/>
                                <rect x="14" y="3" width="7" height="7" rx="1"/>
                                <rect x="3" y="14" width="7" height="7" rx="1"/>
                                <rect x="14" y="14" width="7" height="7" rx="1"/>
                            </svg>
                            <span>Dashboard</span>
                        </a>
                    </li>
                    <li class="sidebar__item">
                        <a href="portfolio.php" class="sidebar__link">
                            <svg class="sidebar__icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <path d="M22 19a2 2 0 01-2 2H4a2 2 0 01-2-2V5a2 2 0 012-2h5l2 3h9a2 2 0 012 2z"/>
                            </svg>
                            <span>Portfolio</span>
                        </a>
                    </li>
                    <li class="sidebar__item">
                        <a href="contacts.php" class="sidebar__link">
                            <svg class="sidebar__icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <path d="M22 16.92v3a2 2 0 01-2.18 2 19.79 19.79 0 01-8.63-3.07 19.5 19.5 0 01-6-6 19.79 19.79 0 01-3.07-8.67A2 2 0 014.11 2h3a2 2 0 012 1.72 12.84 12.84 0 00.7 2.81 2 2 0 01-.45 2.11L8.09 9.91a16 16 0 006 6l1.27-1.27a2 2 0 012.11-.45 12.84 12.84 0 002.81.7A2 2 0 0122 16.92z"/>
                            </svg>
                            <span>Contacts</span>
                        </a>
                    </li>
                    <li class="sidebar__item">
                        <a href="templates.php" class="sidebar__link">
                            <svg class="sidebar__icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <rect x="3" y="3" width="18" height="18" rx="2" ry="2"/>
                                <line x1="3" y1="9" x2="21" y2="9"/>
                                <line x1="9" y1="21" x2="9" y2="9"/>
                            </svg>
                            <span>Templates</span>
                        </a>
                    </li>
                    <li class="sidebar__item">
                        <a href="appearance.php" class="sidebar__link">
                            <svg class="sidebar__icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <circle cx="13.5" cy="6.5" r="2.5"/>
                                <circle cx="17.5" cy="10.5" r="2.5"/>
                                <circle cx="8.5" cy="7.5" r="2.5"/>
                                <circle cx="6.5" cy="12.5" r="2.5"/>
                                <path d="M12 2C6.5 2 2 6.5 2 12s4.5 10 10 10c.926 0 1.648-.746 1.648-1.688 0-.437-.18-.835-.437-1.125-.29-.289-.438-.652-.438-1.125a1.64 1.64 0 011.668-1.668h1.996c3.051 0 5.555-2.503 5.555-5.554C21.965 6.012 17.461 2 12 2z"/>
                            </svg>
                            <span>Appearance</span>
                        </a>
                    </li>
                    <li class="sidebar__item">
                        <a href="settings.php" class="sidebar__link">
                            <svg class="sidebar__icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <circle cx="12" cy="12" r="3"/>
                                <path d="M19.4 15a1.65 1.65 0 00.33 1.82l.06.06a2 2 0 010 2.83 2 2 0 01-2.83 0l-.06-.06a1.65 1.65 0 00-1.82-.33 1.65 1.65 0 00-1 1.51V21a2 2 0 01-2 2 2 2 0 01-2-2v-.09A1.65 1.65 0 009 19.4a1.65 1.65 0 00-1.82.33l-.06.06a2 2 0 01-2.83 0 2 2 0 010-2.83l.06-.06A1.65 1.65 0 004.6 15a1.65 1.65 0 00-1.51-1H3a2 2 0 01-2-2 2 2 0 012-2h.09A1.65 1.65 0 004.6 9a1.65 1.65 0 00-.33-1.82l-.06-.06a2 2 0 010-2.83 2 2 0 012.83 0l.06.06A1.65 1.65 0 009 4.6a1.65 1.65 0 001-1.51V3a2 2 0 012-2 2 2 0 012 2v.09a1.65 1.65 0 001 1.51 1.65 1.65 0 001.82-.33l.06-.06a2 2 0 012.83 0 2 2 0 010 2.83l-.06.06A1.65 1.65 0 0019.4 9a1.65 1.65 0 001.51 1H21a2 2 0 012 2 2 2 0 01-2 2h-.09a1.65 1.65 0 00-1.51 1z"/>
                            </svg>
                            <span>Settings</span>
                        </a>
                    </li>
                    <li class="sidebar__item">
                        <a href="profile.php" class="sidebar__link">
                            <svg class="sidebar__icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/>
                                <circle cx="12" cy="7" r="4"/>
                            </svg>
                            <span>Profile</span>
                        </a>
                    </li>
                    <li><div class="sidebar__divider" role="separator"></div></li>
                    <li class="sidebar__item">
                        <a href="../index.php" target="_blank" rel="noopener" class="sidebar__link">
                            <svg class="sidebar__icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                                <circle cx="12" cy="12" r="3"/>
                            </svg>
                            <span>View Site</span>
                            <svg class="sidebar__icon sidebar__icon--sm" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <path d="M18 13v6a2 2 0 01-2 2H5a2 2 0 01-2-2V8a2 2 0 012-2h6"/>
                                <polyline points="15 3 21 3 21 9"/>
                                <line x1="10" y1="14" x2="21" y2="3"/>
                            </svg>
                        </a>
                    </li>
                    <li class="sidebar__item">
                        <a href="logout.php" class="sidebar__link sidebar__link--logout">
                            <svg class="sidebar__icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <path d="M9 21H5a2 2 0 01-2-2V5a2 2 0 012-2h4"/>
                                <polyline points="16 17 21 12 16 7"/>
                                <line x1="21" y1="12" x2="9" y2="12"/>
                            </svg>
                            <span>Logout</span>
                        </a>
                    </li>
                </ul>
            </nav>
        </aside>

        <!-- ========== MAIN CONTENT ========== -->
        <main class="main-content">

            <!-- Welcome Banner -->
            <div class="welcome-section">
                <div class="welcome__greeting">
                    <svg class="welcome__wave" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                        <path d="M11.5 2C11.5 2 10 4 10 6.5C10 7.33 10.67 8 11.5 8C12.33 8 13 7.33 13 6.5C13 4 11.5 2 11.5 2ZM16.5 4C16.5 4 15 6 15 8.5C15 9.33 15.67 10 16.5 10C17.33 10 18 9.33 18 8.5C18 6 16.5 4 16.5 4ZM6.5 4C6.5 4 5 6 5 8.5C5 9.33 5.67 10 6.5 10C7.33 10 8 9.33 8 8.5C8 6 6.5 4 6.5 4ZM21 8C21 8 19.5 10 19.5 12.5C19.5 13.33 20.17 14 21 14C21.83 14 22.5 13.33 22.5 12.5C22.5 10 21 8 21 8ZM3 9C3 9 1.5 11 1.5 13.5C1.5 14.33 2.17 15 3 15C3.83 15 4.5 14.33 4.5 13.5C4.5 11 3 9 3 9ZM8 12C6.5 12 5 13.5 5 16C5 20 8 23 12 23C16 23 19 20 19 16C19 13.5 17.5 12 16 12H8Z"/>
                    </svg>
                    Welcome back,
                </div>
                <h1 class="welcome__name"><?= htmlspecialchars($admin['name']) ?></h1>
                <p class="welcome__date" id="currentDate"></p>
            </div>

            <!-- Stats Grid -->
            <div class="stats-grid">
                <!-- Total Portfolios -->
                <div class="stat-card stat-card--primary">
                    <div class="stat-card__header">
                        <div class="stat-card__icon-wrap">
                            <svg class="stat-card__icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <path d="M22 19a2 2 0 01-2 2H4a2 2 0 01-2-2V5a2 2 0 012-2h5l2 3h9a2 2 0 012 2z"/>
                            </svg>
                        </div>
                    </div>
                    <div class="stat-card__value"><?= $totalPortfolios ?></div>
                    <div class="stat-card__label">Total Portfolios</div>
                </div>

                <!-- Active Portfolios -->
                <div class="stat-card stat-card--success">
                    <div class="stat-card__header">
                        <div class="stat-card__icon-wrap">
                            <svg class="stat-card__icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <path d="M22 11.08V12a10 10 0 11-5.93-9.14"/>
                                <polyline points="22 4 12 14.01 9 11.01"/>
                            </svg>
                        </div>
                    </div>
                    <div class="stat-card__value"><?= $activePortfolios ?></div>
                    <div class="stat-card__label">Active Portfolios</div>
                </div>

                <!-- Contacts -->
                <div class="stat-card stat-card--info">
                    <div class="stat-card__header">
                        <div class="stat-card__icon-wrap">
                            <svg class="stat-card__icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <path d="M21 15a2 2 0 01-2 2H7l-4 4V5a2 2 0 012-2h14a2 2 0 012 2z"/>
                            </svg>
                        </div>
                    </div>
                    <div class="stat-card__value"><?= $totalContacts ?></div>
                    <div class="stat-card__label">Contact Methods</div>
                </div>

                <!-- Active Template -->
                <div class="stat-card stat-card--warning">
                    <div class="stat-card__header">
                        <div class="stat-card__icon-wrap">
                            <svg class="stat-card__icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <rect x="3" y="3" width="18" height="18" rx="2" ry="2"/>
                                <line x1="3" y1="9" x2="21" y2="9"/>
                                <line x1="9" y1="21" x2="9" y2="9"/>
                            </svg>
                        </div>
                    </div>
                    <div class="stat-card__value"><?= htmlspecialchars(getSetting('active_template')) ?></div>
                    <div class="stat-card__label">Active Template</div>
                </div>
            </div>

            <!-- Quick Actions -->
            <section>
                <div class="section-header">
                    <h2 class="section-title">Quick Actions</h2>
                </div>
                <div class="actions-grid">

                    <a href="portfolio.php?action=add" class="action-card">
                        <div class="action-card__icon-wrap">
                            <svg class="action-card__icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <line x1="12" y1="5" x2="12" y2="19"/>
                                <line x1="5" y1="12" x2="19" y2="12"/>
                            </svg>
                        </div>
                        <div class="action-card__content">
                            <div class="action-card__title">Add Portfolio</div>
                            <div class="action-card__desc">Create a new portfolio item</div>
                        </div>
                        <svg class="action-card__arrow" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <polyline points="9 18 15 12 9 6"/>
                        </svg>
                    </a>

                    <a href="portfolio.php" class="action-card">
                        <div class="action-card__icon-wrap">
                            <svg class="action-card__icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <line x1="8" y1="6" x2="21" y2="6"/>
                                <line x1="8" y1="12" x2="21" y2="12"/>
                                <line x1="8" y1="18" x2="21" y2="18"/>
                                <line x1="3" y1="6" x2="3.01" y2="6"/>
                                <line x1="3" y1="12" x2="3.01" y2="12"/>
                                <line x1="3" y1="18" x2="3.01" y2="18"/>
                            </svg>
                        </div>
                        <div class="action-card__content">
                            <div class="action-card__title">Manage Portfolios</div>
                            <div class="action-card__desc">Edit, delete, or reorder items</div>
                        </div>
                        <svg class="action-card__arrow" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <polyline points="9 18 15 12 9 6"/>
                        </svg>
                    </a>

                    <a href="contacts.php" class="action-card">
                        <div class="action-card__icon-wrap">
                            <svg class="action-card__icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <path d="M22 16.92v3a2 2 0 01-2.18 2 19.79 19.79 0 01-8.63-3.07 19.5 19.5 0 01-6-6A19.79 19.79 0 012.12 4.18 2 2 0 014.11 2h3a2 2 0 012 1.72c.127.96.361 1.903.7 2.81a2 2 0 01-.45 2.11L8.09 9.91a16 16 0 006 6l1.27-1.27a2 2 0 012.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0122 16.92z"/>
                            </svg>
                        </div>
                        <div class="action-card__content">
                            <div class="action-card__title">Contact Methods</div>
                            <div class="action-card__desc">Manage your contact links</div>
                        </div>
                        <svg class="action-card__arrow" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <polyline points="9 18 15 12 9 6"/>
                        </svg>
                    </a>

                    <a href="templates.php" class="action-card">
                        <div class="action-card__icon-wrap">
                            <svg class="action-card__icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <rect x="3" y="3" width="18" height="18" rx="2" ry="2"/>
                                <line x1="3" y1="9" x2="21" y2="9"/>
                                <line x1="9" y1="21" x2="9" y2="9"/>
                            </svg>
                        </div>
                        <div class="action-card__content">
                            <div class="action-card__title">Change Template</div>
                            <div class="action-card__desc">Switch your portfolio theme</div>
                        </div>
                        <svg class="action-card__arrow" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <polyline points="9 18 15 12 9 6"/>
                        </svg>
                    </a>

                    <a href="appearance.php" class="action-card">
                        <div class="action-card__icon-wrap">
                            <svg class="action-card__icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <circle cx="13.5" cy="6.5" r="2.5"/>
                                <circle cx="17.5" cy="10.5" r="2.5"/>
                                <circle cx="8.5" cy="7.5" r="2.5"/>
                                <circle cx="6.5" cy="12.5" r="2.5"/>
                                <path d="M12 2C6.5 2 2 6.5 2 12s4.5 10 10 10c.926 0 1.648-.746 1.648-1.688 0-.437-.18-.835-.437-1.125-.29-.289-.438-.652-.438-1.125a1.64 1.64 0 011.668-1.668h1.996c3.051 0 5.555-2.503 5.555-5.554C21.965 6.012 17.461 2 12 2z"/>
                            </svg>
                        </div>
                        <div class="action-card__content">
                            <div class="action-card__title">Appearance</div>
                            <div class="action-card__desc">Colors, logo, and branding</div>
                        </div>
                        <svg class="action-card__arrow" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <polyline points="9 18 15 12 9 6"/>
                        </svg>
                    </a>

                    <a href="settings.php" class="action-card">
                        <div class="action-card__icon-wrap">
                            <svg class="action-card__icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <circle cx="12" cy="12" r="3"/>
                                <path d="M19.4 15a1.65 1.65 0 00.33 1.82l.06.06a2 2 0 010 2.83 2 2 0 01-2.83 0l-.06-.06a1.65 1.65 0 00-1.82-.33 1.65 1.65 0 00-1 1.51V21a2 2 0 01-2 2 2 2 0 01-2-2v-.09A1.65 1.65 0 009 19.4a1.65 1.65 0 00-1.82.33l-.06.06a2 2 0 01-2.83-2.83l.06-.06A1.65 1.65 0 004.6 15a1.65 1.65 0 00-1.51-1H3a2 2 0 010-4h.09A1.65 1.65 0 004.6 9a1.65 1.65 0 00-.33-1.82l-.06-.06a2 2 0 012.83-2.83l.06.06A1.65 1.65 0 009 4.6a1.65 1.65 0 001-1.51V3a2 2 0 014 0v.09a1.65 1.65 0 001 1.51 1.65 1.65 0 001.82-.33l.06-.06a2 2 0 012.83 2.83l-.06.06A1.65 1.65 0 0019.4 9a1.65 1.65 0 001.51 1H21a2 2 0 010 4h-.09a1.65 1.65 0 00-1.51 1z"/>
                            </svg>
                        </div>
                        <div class="action-card__content">
                            <div class="action-card__title">Site Settings</div>
                            <div class="action-card__desc">General site configuration</div>
                        </div>
                        <svg class="action-card__arrow" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <polyline points="9 18 15 12 9 6"/>
                        </svg>
                    </a>

                </div>
            </section>
        </main>
    </div>

    <!-- ========== MOBILE SIDEBAR TOGGLE SCRIPT ========== -->
    <script>
        (function() {
            const hamburger = document.getElementById('hamburgerBtn');
            const sidebar   = document.getElementById('sidebar');
            const overlay   = document.getElementById('sidebarOverlay');
            const dateEl    = document.getElementById('currentDate');

            // Toggle sidebar
            function openSidebar() {
                sidebar.classList.add('is-open');
                overlay.style.display = 'block';
                requestAnimationFrame(() => overlay.classList.add('is-visible'));
                hamburger.setAttribute('aria-expanded', 'true');
                document.body.style.overflow = 'hidden';
            }

            function closeSidebar() {
                sidebar.classList.remove('is-open');
                overlay.classList.remove('is-visible');
                hamburger.setAttribute('aria-expanded', 'false');
                document.body.style.overflow = '';
                setTimeout(() => { overlay.style.display = 'none'; }, 250);
            }

            hamburger.addEventListener('click', () => {
                sidebar.classList.contains('is-open') ? closeSidebar() : openSidebar();
            });

            overlay.addEventListener('click', closeSidebar);

            // Close sidebar on Escape key
            document.addEventListener('keydown', (e) => {
                if (e.key === 'Escape' && sidebar.classList.contains('is-open')) {
                    closeSidebar();
                }
            });

            // Close sidebar if resized to desktop
            window.addEventListener('resize', () => {
                if (window.innerWidth > 768 && sidebar.classList.contains('is-open')) {
                    closeSidebar();
                }
            });

            // Display current date
            if (dateEl) {
                const now = new Date();
                dateEl.textContent = now.toLocaleDateString('en-US', {
                    weekday: 'long',
                    year: 'numeric',
                    month: 'long',
                    day: 'numeric'
                });
            }
        })();
    </script>

</body>
</html>