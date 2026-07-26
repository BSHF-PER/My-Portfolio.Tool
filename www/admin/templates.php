<?php
require_once '../includes/auth.php';
requireLogin();
require_once '../includes/functions.php';

// Activate template
if (isset($_GET['activate'])) {
    $template = sanitize($_GET['activate']);
    $allowed = ['classic', 'modern', 'minimal', 'creative', 'dark', 'glassmorphism', 'zenith'];
    if (in_array($template, $allowed)) {
        setSetting('active_template', $template);
        redirect('templates.php', 'Template activated successfully');
    }
}

$currentTemplate = getSetting('active_template', 'modern');

$templates = [
    'classic' => [
        'name' => 'Classic (PER)', 
        'desc' => 'Simple, clean, and professional layout suitable for corporate portfolios.', 
        'color' => '#3b82f6',
        'gradient' => 'linear-gradient(135deg, #3b82f6 0%, #2563eb 100%)'
    ],
    'modern' => [
        'name' => 'Modern (PER)', 
        'desc' => 'Contemporary design with smooth gradients and dynamic elements.', 
        'color' => '#6366f1',
        'gradient' => 'linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%)'
    ],
    'minimal' => [
        'name' => 'Minimal (PER)', 
        'desc' => 'Ultra-clean, spacious layout focusing purely on your content.', 
        'color' => '#10b981',
        'gradient' => 'linear-gradient(135deg, #10b981 0%, #059669 100%)'
    ],
    'creative' => [
        'name' => 'Creative (PER)', 
        'desc' => 'Artistic and unique layout with bold typography and asymmetric grids.', 
        'color' => '#f59e0b',
        'gradient' => 'linear-gradient(135deg, #f59e0b 0%, #d97706 100%)'
    ],
    'dark' => [
        'name' => 'Dark (PER)', 
        'desc' => 'Elegant dark theme that makes your visuals and colors pop.', 
        'color' => '#1f2937',
        'gradient' => 'linear-gradient(135deg, #1f2937 0%, #111827 100%)'
    ],
    'glassmorphism' => [
        'name' => 'Glassmorphism (PER)', 
        'desc' => 'A distinctive, luxurious glass design suitable for Golsamooni-style pieces and modern aesthetics.', 
        'color' => '#9dff00',
        'gradient' => 'linear-gradient(135deg, #27ff0b 1%, #d4af0a 100%)'
    ],
    'zenith' => [
        'name' => 'Zenith (EN)', 
        'desc' => 'The template is specifically designed for English-language portfolios, adheres to high UI/UX standards, is fully responsive, and features an intelligent Dark/Light Mode system that dynamically integrates with colors configured in the database.', 
        'color' => '#8400ff',
        'gradient' => 'linear-gradient(135deg, #0bfff3 1%, #0ad45e 100%)'
    ]
];

$pageTitle = 'Template Selection';
?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $pageTitle ?> - My-Portfolio.Tool</title> <!--
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet"> -->
    <style>
        /* ============================================
           DESIGN TOKENS (Shared)
           ============================================ */
        :root {
            --color-primary: #6366f1;
            --color-primary-light: #818cf8;
            --color-primary-dark: #4f46e5;
            --color-primary-bg: rgba(99, 102, 241, 0.08);
            --color-success: #10b981;
            --color-success-bg: rgba(16, 185, 129, 0.1);
            --color-success-text: #065f46;
            --color-danger: #ef4444;
            --color-danger-bg: rgba(239, 68, 68, 0.1);
            --color-bg: #f1f5f9;
            --color-surface: #ffffff;
            --color-text: #0f172a;
            --color-text-secondary: #64748b;
            --color-text-muted: #94a3b8;
            --color-border: #e2e8f0;
            --shadow-sm: 0 1px 2px rgba(0, 0, 0, 0.04);
            --shadow-md: 0 4px 12px rgba(0, 0, 0, 0.06);
            --shadow-lg: 0 8px 30px rgba(0, 0, 0, 0.08);
            --shadow-xl: 0 20px 50px rgba(0, 0, 0, 0.12);
            --radius-sm: 8px;
            --radius-md: 12px;
            --radius-lg: 16px;
            --radius-xl: 20px;
            --radius-full: 9999px;
            --transition-fast: 150ms cubic-bezier(0.4, 0, 0.2, 1);
            --transition-base: 250ms cubic-bezier(0.4, 0, 0.2, 1);
            --sidebar-width: 260px;
            --header-height: 64px;
        }

        /* ============================================
           RESET & BASE
           ============================================ */
        *, *::before, *::after { margin: 0; padding: 0; box-sizing: border-box; }
        html { scroll-behavior: smooth; -webkit-text-size-adjust: 100%; }
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: var(--color-bg);
            color: var(--color-text);
            line-height: 1.6;
            min-height: 100vh;
            -webkit-font-smoothing: antialiased;
        }

        /* ============================================
           HEADER (Shared)
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
            padding: 0 24px;
            max-width: 100%;
        }
        .header__brand { display: flex; align-items: center; gap: 16px; }
        .header__logo-icon { width: 32px; height: 32px; color: var(--color-primary); }
        .header__title { font-size: 18px; font-weight: 700; color: var(--color-text); letter-spacing: -0.02em; }
        .header__subtitle { font-size: 12px; color: var(--color-text-muted); font-weight: 500; }
        .header__actions { display: flex; align-items: center; gap: 16px; }
        .header__user { display: flex; align-items: center; gap: 8px; font-size: 14px; color: var(--color-text-secondary); }
        .header__icon { width: 18px; height: 18px; }
        .header__greeting { color: var(--color-text-muted); }
        .header__username { font-weight: 600; color: var(--color-text); }
        .header__btn {
            display: inline-flex; align-items: center; gap: 8px; padding: 8px 16px;
            border-radius: var(--radius-sm); font-size: 13px; font-weight: 500;
            text-decoration: none; transition: all var(--transition-fast);
        }
        .header__btn--ghost { color: var(--color-text-secondary); background: var(--color-bg); }
        .header__btn--ghost:hover { background: var(--color-primary-bg); color: var(--color-primary); }
        .header__btn--danger { color: var(--color-danger); background: var(--color-danger-bg); }
        .header__btn--danger:hover { background: var(--color-danger); color: white; }
        .hamburger-btn {
            display: none; background: none; border: none; padding: 8px; cursor: pointer;
            color: var(--color-text); border-radius: var(--radius-sm); transition: background var(--transition-fast);
        }
        .hamburger-btn:hover { background: var(--color-bg); }
        .hamburger-btn svg { width: 24px; height: 24px; }

        /* ============================================
           SIDEBAR (Shared)
           ============================================ */
        .admin-layout { display: flex; min-height: calc(100vh - var(--header-height)); }
        .sidebar {
            width: var(--sidebar-width); background: var(--color-surface);
            border-right: 1px solid var(--color-border); padding: 16px 0;
            position: sticky; top: var(--header-height); height: calc(100vh - var(--header-height));
            overflow-y: auto; flex-shrink: 0; transition: transform var(--transition-base); z-index: 40;
        }
        .sidebar__list { list-style: none; padding: 0 8px; }
        .sidebar__link {
            display: flex; align-items: center; gap: 16px; padding: 10px 16px;
            color: var(--color-text-secondary); text-decoration: none; border-radius: var(--radius-sm);
            font-size: 14px; font-weight: 500; transition: all var(--transition-fast); margin-bottom: 2px;
        }
        .sidebar__link:hover { background: var(--color-primary-bg); color: var(--color-primary); }
        .sidebar__link--active { background: var(--color-primary-bg); color: var(--color-primary); font-weight: 600; }
        .sidebar__icon { width: 20px; height: 20px; flex-shrink: 0; color: var(--color-text-muted); transition: color var(--transition-fast); }
        .sidebar__link:hover .sidebar__icon, .sidebar__link--active .sidebar__icon { color: var(--color-primary); }
        .sidebar__icon--sm { width: 14px; height: 14px; margin-left: auto; opacity: 0.5; }
        .sidebar__divider { height: 1px; background: var(--color-border); margin: 16px; }
        .sidebar__link--logout:hover { background: var(--color-danger-bg); color: var(--color-danger); }
        .sidebar__link--logout:hover .sidebar__icon { color: var(--color-danger); }
        .sidebar-overlay {
            display: none; position: fixed; inset: 0; background: rgba(0, 0, 0, 0.4);
            z-index: 35; backdrop-filter: blur(4px); opacity: 0; transition: opacity var(--transition-base);
        }
        .sidebar-overlay.is-visible { display: block; opacity: 1; }

        /* ============================================
           MAIN CONTENT & PAGE SPECIFIC
           ============================================ */
        .main-content { flex: 1; padding: 32px; min-width: 0; }
        
        .page-header {
            display: flex; justify-content: space-between; align-items: center;
            margin-bottom: 32px; flex-wrap: wrap; gap: 16px;
        }
        .page-title {
            font-size: 24px; font-weight: 700; color: var(--color-text);
            display: flex; align-items: center; gap: 12px; letter-spacing: -0.02em;
        }
        .page-title svg { width: 28px; height: 28px; color: var(--color-primary); }
        .page-desc { color: var(--color-text-secondary); font-size: 15px; margin-top: 6px; max-width: 600px; }

        .alert {
            padding: 14px 20px; border-radius: var(--radius-sm); margin-bottom: 24px;
            font-size: 14px; font-weight: 500; display: flex; align-items: center; gap: 10px;
            animation: slideDown 0.3s ease-out;
        }
        @keyframes slideDown { from { opacity: 0; transform: translateY(-10px); } to { opacity: 1; transform: translateY(0); } }
        .alert-success { background: var(--color-success-bg); color: var(--color-success-text); border: 1px solid rgba(16,185,129,0.2); }

        /* Templates Grid */
        .templates-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 24px;
        }

        .template-card {
            background: var(--color-surface);
            border-radius: var(--radius-lg);
            border: 2px solid var(--color-border);
            overflow: hidden;
            transition: all var(--transition-base);
            display: flex;
            flex-direction: column;
        }

        .template-card:hover {
            transform: translateY(-4px);
            box-shadow: var(--shadow-lg);
            border-color: var(--color-primary-light);
        }

        .template-card.active {
            border-color: var(--color-primary);
            box-shadow: 0 0 0 4px var(--color-primary-bg), var(--shadow-md);
        }

        .template-preview {
            height: 180px;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow: hidden;
        }

        .template-preview svg {
            width: 64px;
            height: 64px;
            color: rgba(255, 255, 255, 0.9);
            filter: drop-shadow(0 4px 6px rgba(0,0,0,0.1));
            transition: transform var(--transition-base);
        }

        .template-card:hover .template-preview svg {
            transform: scale(1.1);
        }

        .template-info {
            padding: 24px;
            flex: 1;
            display: flex;
            flex-direction: column;
        }

        .template-name {
            font-size: 18px;
            font-weight: 700;
            color: var(--color-text);
            margin-bottom: 8px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .template-desc {
            font-size: 14px;
            color: var(--color-text-secondary);
            line-height: 1.6;
            margin-bottom: 20px;
            flex: 1;
        }

        .template-actions {
            margin-top: auto;
        }

        .btn {
            display: inline-flex; align-items: center; justify-content: center; gap: 8px; 
            padding: 10px 20px; border: none; border-radius: var(--radius-sm); 
            font-size: 14px; font-weight: 600; text-decoration: none; cursor: pointer; 
            transition: all var(--transition-fast); width: 100%;
        }
        .btn svg { width: 18px; height: 18px; }
        .btn-primary { background: var(--color-primary); color: white; box-shadow: 0 2px 8px rgba(99, 102, 241, 0.3); }
        .btn-primary:hover { background: var(--color-primary-dark); transform: translateY(-1px); box-shadow: 0 4px 12px rgba(99, 102, 241, 0.4); }
        
        .badge {
            display: inline-flex; align-items: center; gap: 6px; padding: 6px 14px;
            border-radius: var(--radius-full); font-size: 13px; font-weight: 600;
            width: 100%; justify-content: center;
        }
        .badge--success { background: var(--color-success-bg); color: var(--color-success-text); }
        .badge--success svg { width: 16px; height: 16px; }

        /* ============================================
           RESPONSIVE
           ============================================ */
        @media (max-width: 768px) {
            .hamburger-btn { display: flex; }
            .sidebar {
                position: fixed; top: 0; left: 0; height: 100vh; transform: translateX(-100%);
                z-index: 45; padding-top: 80px; box-shadow: var(--shadow-xl);
            }
            .sidebar.is-open { transform: translateX(0); }
            .main-content { padding: 20px; }
            .templates-grid { grid-template-columns: 1fr; }
            .header__btn span, .header__greeting { display: none; }
        }
        @media (max-width: 480px) {
            .header__container { padding: 0 16px; }
            .header__subtitle { display: none; }
        }
    </style>
</head>
<body>

    <?php include '../includes/header_admin.php'; ?>
    
    <div class="admin-layout">
        <div class="sidebar-overlay" id="sidebarOverlay"></div>
        <?php include '../includes/sidebar_admin.php'; ?>
        
        <main class="main-content">
            <div class="page-header">
                <div>
                    <h1 class="page-title">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <rect x="3" y="3" width="18" height="18" rx="2" ry="2"/>
                            <line x1="3" y1="9" x2="21" y2="9"/>
                            <line x1="9" y1="21" x2="9" y2="9"/>
                        </svg>
                        Template Selection
                    </h1>
                    <p class="page-desc">Choose one of the professional layouts below to instantly change the look and feel of your public portfolio.</p>
                </div>
            </div>

            <?php if ($flash = getFlash()): ?>
                <div class="alert alert-<?= $flash['type'] === 'error' ? 'error' : 'success' ?>">
                    <?php if ($flash['type'] === 'success'): ?>
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 11-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                    <?php endif; ?>
                    <?= htmlspecialchars($flash['message']) ?>
                </div>
            <?php endif; ?>

            <div class="templates-grid">
                <?php foreach ($templates as $key => $t): ?>
                    <div class="template-card <?= $currentTemplate === $key ? 'active' : '' ?>">
                        <div class="template-preview" style="background: <?= $t['gradient'] ?>;">
                            <!-- Generic Layout SVG Icon -->
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <rect x="3" y="3" width="18" height="18" rx="2" ry="2"/>
                                <line x1="3" y1="9" x2="21" y2="9"/>
                                <line x1="9" y1="21" x2="9" y2="9"/>
                            </svg>
                        </div>
                        <div class="template-info">
                            <div class="template-name">
                                <?= htmlspecialchars($t['name']) ?>
                            </div>
                            <p class="template-desc"><?= htmlspecialchars($t['desc']) ?></p>
                            
                            <div class="template-actions">
                                <?php if ($currentTemplate === $key): ?>
                                    <div class="badge badge--success">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                            <polyline points="20 6 9 17 4 12"/>
                                        </svg>
                                        Active Template
                                    </div>
                                <?php else: ?>
                                    <a href="?activate=<?= $key ?>" class="btn btn-primary">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <polyline points="20 6 9 17 4 12"/>
                                        </svg>
                                        Select This Template
                                    </a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </main>
    </div>

    <script>
        // Sidebar toggle logic (Shared)
        (function() {
            const hamburger = document.getElementById('hamburgerBtn');
            const sidebar   = document.getElementById('sidebar');
            const overlay   = document.getElementById('sidebarOverlay');
            if (!hamburger || !sidebar || !overlay) return;

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
            hamburger.addEventListener('click', () => sidebar.classList.contains('is-open') ? closeSidebar() : openSidebar());
            overlay.addEventListener('click', closeSidebar);
            document.addEventListener('keydown', (e) => { if (e.key === 'Escape' && sidebar.classList.contains('is-open')) closeSidebar(); });
            window.addEventListener('resize', () => { if (window.innerWidth > 768 && sidebar.classList.contains('is-open')) closeSidebar(); });
        })();
    </script>
</body>
</html>