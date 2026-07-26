<?php
require_once '../includes/auth.php';
requireLogin();
require_once '../includes/functions.php';

// Delete portfolio
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $stmt = $pdo->prepare("SELECT image, video FROM portfolios WHERE id = ?");
    $stmt->execute([$id]);
    $item = $stmt->fetch();
    
    if ($item) {
        if ($item['image'] && file_exists(BASE_PATH . '/' . $item['image'])) {
            @unlink(BASE_PATH . '/' . $item['image']);
        }
        if ($item['video'] && file_exists(BASE_PATH . '/' . $item['video'])) {
            @unlink(BASE_PATH . '/' . $item['video']);
        }
    }
    
    $pdo->prepare("DELETE FROM portfolios WHERE id = ?")->execute([$id]);
    redirect('portfolio.php', 'Portfolio deleted successfully');
}

// Toggle active/inactive status
if (isset($_GET['toggle'])) {
    $id = (int)$_GET['toggle'];
    $pdo->prepare("UPDATE portfolios SET is_active = NOT is_active WHERE id = ?")->execute([$id]);
    redirect('portfolio.php', 'Status updated successfully');
}

$portfolios = $pdo->query("SELECT * FROM portfolios ORDER BY sort_order ASC, created_at DESC")->fetchAll();
$pageTitle = 'Portfolio Management';
?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $pageTitle ?> - My-Portfolio.Tool</title>
    <style>
        /* ============================================
           SHARED DESIGN TOKENS (Exact match with dashboard)
           ============================================ */
        :root {
            --color-primary: #6366f1;
            --color-primary-light: #818cf8;
            --color-primary-dark: #4f46e5;
            --color-primary-bg: rgba(99, 102, 241, 0.08);
            --color-success: #10b981;
            --color-success-bg: rgba(16, 185, 129, 0.1);
            --color-success-text: #065f46;
            --color-warning: #f59e0b;
            --color-warning-bg: rgba(245, 158, 11, 0.1);
            --color-warning-text: #92400e;
            --color-danger: #ef4444;
            --color-danger-bg: rgba(239, 68, 68, 0.1);
            --color-danger-text: #991b1b;
            --color-info: #3b82f6;
            --color-info-bg: rgba(59, 130, 246, 0.1);
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
            margin-bottom: 24px; flex-wrap: wrap; gap: 16px;
        }
        .page-title {
            font-size: 24px; font-weight: 700; color: var(--color-text);
            display: flex; align-items: center; gap: 12px; letter-spacing: -0.02em;
        }
        .page-title svg { width: 28px; height: 28px; color: var(--color-primary); }

        .btn {
            display: inline-flex; align-items: center; gap: 8px; padding: 10px 20px;
            border: none; border-radius: var(--radius-sm); font-size: 14px; font-weight: 600;
            text-decoration: none; cursor: pointer; transition: all var(--transition-fast);
        }
        .btn svg { width: 18px; height: 18px; }
        .btn-primary { background: var(--color-primary); color: white; box-shadow: 0 2px 8px rgba(99, 102, 241, 0.3); }
        .btn-primary:hover { background: var(--color-primary-dark); transform: translateY(-1px); box-shadow: 0 4px 12px rgba(99, 102, 241, 0.4); }
        .btn-sm { padding: 6px 12px; font-size: 13px; }
        .btn-sm svg { width: 15px; height: 15px; }
        .btn-info { background: var(--color-info-bg); color: var(--color-info); }
        .btn-info:hover { background: var(--color-info); color: white; }
        .btn-danger { background: var(--color-danger-bg); color: var(--color-danger); }
        .btn-danger:hover { background: var(--color-danger); color: white; }

        .alert {
            padding: 14px 20px; border-radius: var(--radius-sm); margin-bottom: 24px;
            font-size: 14px; font-weight: 500; display: flex; align-items: center; gap: 10px;
            animation: slideDown 0.3s ease-out;
        }
        @keyframes slideDown { from { opacity: 0; transform: translateY(-10px); } to { opacity: 1; transform: translateY(0); } }
        .alert-success { background: var(--color-success-bg); color: var(--color-success-text); border: 1px solid rgba(16,185,129,0.2); }
        .alert-error { background: var(--color-danger-bg); color: var(--color-danger-text); border: 1px solid rgba(239,68,68,0.2); }

        .table-container {
            background: var(--color-surface); border-radius: var(--radius-lg);
            box-shadow: var(--shadow-sm); border: 1px solid var(--color-border); overflow: hidden;
        }
        .table-responsive { overflow-x: auto; -webkit-overflow-scrolling: touch; }
        .data-table { width: 100%; border-collapse: collapse; min-width: 800px; }
        .data-table th {
            background: #f8fafc; color: var(--color-text-secondary); font-size: 12px;
            font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em;
            padding: 16px 20px; text-align: left; border-bottom: 1px solid var(--color-border);
        }
        .data-table td {
            padding: 16px 20px; border-bottom: 1px solid var(--color-border);
            color: var(--color-text); font-size: 14px; vertical-align: middle;
        }
        .data-table tbody tr { transition: background var(--transition-fast); }
        .data-table tbody tr:hover { background: #f8fafc; }
        .data-table tbody tr:last-child td { border-bottom: none; }

        .portfolio-thumb { width: 56px; height: 56px; border-radius: var(--radius-sm); object-fit: cover; border: 1px solid var(--color-border); background: #f1f5f9; }
        .portfolio-thumb-placeholder {
            width: 56px; height: 56px; border-radius: var(--radius-sm); background: #f1f5f9;
            display: flex; align-items: center; justify-content: center; color: var(--color-text-muted); border: 1px dashed var(--color-border);
        }
        .portfolio-thumb-placeholder svg { width: 24px; height: 24px; }
        .portfolio-title { font-weight: 600; color: var(--color-text); }
        .portfolio-category { color: var(--color-text-secondary); font-size: 13px; }

        .badge {
            display: inline-flex; align-items: center; gap: 6px; padding: 4px 12px;
            border-radius: var(--radius-full); font-size: 12px; font-weight: 600;
            text-decoration: none; transition: all var(--transition-fast);
        }
        .badge svg { width: 14px; height: 14px; }
        .badge--success { background: var(--color-success-bg); color: var(--color-success-text); }
        .badge--success:hover { background: #bbf7d0; }
        .badge--warning { background: var(--color-warning-bg); color: var(--color-warning-text); }
        .badge--warning:hover { background: #fde68a; }
        .actions-cell { display: flex; gap: 8px; }

        .empty-state { padding: 60px 20px; text-align: center; color: var(--color-text-muted); }
        .empty-state svg { width: 48px; height: 48px; margin-bottom: 16px; opacity: 0.5; }
        .empty-state p { font-size: 15px; font-weight: 500; }

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
            .page-header { flex-direction: column; align-items: flex-start; }
            .btn { width: 100%; justify-content: center; }
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
                <h1 class="page-title">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M22 19a2 2 0 01-2 2H4a2 2 0 01-2-2V5a2 2 0 012-2h5l2 3h9a2 2 0 012 2z"/>
                    </svg>
                    Portfolio Management
                </h1>
                <a href="portfolio_edit.php" class="btn btn-primary">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <line x1="12" y1="5" x2="12" y2="19"/>
                        <line x1="5" y1="12" x2="19" y2="12"/>
                    </svg>
                    Add New Portfolio
                </a>
            </div>

            <?php if ($flash = getFlash()): ?>
                <div class="alert alert-<?= $flash['type'] === 'error' ? 'error' : 'success' ?>">
                    <?php if ($flash['type'] === 'success'): ?>
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 11-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                    <?php else: ?>
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>
                    <?php endif; ?>
                    <?= htmlspecialchars($flash['message']) ?>
                </div>
            <?php endif; ?>

            <div class="table-container">
                <div class="table-responsive">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Thumbnail</th>
                                <th>Title</th>
                                <th>Category</th>
                                <th>Status</th>
                                <th>Date Added</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($portfolios)): ?>
                                <tr>
                                    <td colspan="6">
                                        <div class="empty-state">
                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                                <rect x="3" y="3" width="18" height="18" rx="2" ry="2"/>
                                                <circle cx="8.5" cy="8.5" r="1.5"/>
                                                <polyline points="21 15 16 10 5 21"/>
                                            </svg>
                                            <p>No portfolios have been added yet.</p>
                                        </div>
                                    </td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($portfolios as $p): ?>
                                    <tr>
                                        <td>
                                            <?php if (!empty($p['image'])): ?>
                                                <img src="../<?= htmlspecialchars($p['image']) ?>" alt="Thumbnail" class="portfolio-thumb">
                                            <?php else: ?>
                                                <div class="portfolio-thumb-placeholder">
                                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                        <rect x="3" y="3" width="18" height="18" rx="2" ry="2"/>
                                                        <circle cx="8.5" cy="8.5" r="1.5"/>
                                                        <polyline points="21 15 16 10 5 21"/>
                                                    </svg>
                                                </div>
                                            <?php endif; ?>
                                        </td>
                                        <td><div class="portfolio-title"><?= htmlspecialchars($p['title']) ?></div></td>
                                        <td><span class="portfolio-category"><?= htmlspecialchars($p['category'] ?: 'Uncategorized') ?></span></td>
                                        <td>
                                            <a href="?toggle=<?= $p['id'] ?>" class="badge badge--<?= $p['is_active'] ? 'success' : 'warning' ?>" title="Click to toggle status">
                                                <?php if ($p['is_active']): ?>
                                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                                                    Active
                                                <?php else: ?>
                                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="4.93" y1="4.93" x2="19.07" y2="19.07"/></svg>
                                                    Inactive
                                                <?php endif; ?>
                                            </a>
                                        </td>
                                        <td style="color: var(--color-text-muted); font-size: 13px;"><?= date('M d, Y', strtotime($p['created_at'])) ?></td>
                                        <td>
                                            <div class="actions-cell">
                                                <a href="portfolio_edit.php?id=<?= $p['id'] ?>" class="btn btn-sm btn-info" title="Edit">
                                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                        <path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/>
                                                        <path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/>
                                                    </svg>
                                                    Edit
                                                </a>
                                                <a href="?delete=<?= $p['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this portfolio? This action cannot be undone.')" title="Delete">
                                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                        <polyline points="3 6 5 6 21 6"/>
                                                        <path d="M19 6v14a2 2 0 01-2 2H7a2 2 0 01-2-2V6m3 0V4a2 2 0 012-2h4a2 2 0 012 2v2"/>
                                                        <line x1="10" y1="11" x2="10" y2="17"/>
                                                        <line x1="14" y1="11" x2="14" y2="17"/>
                                                    </svg>
                                                    Delete
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>

    <script>
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