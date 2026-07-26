<?php
require_once '../includes/auth.php';
requireLogin();
require_once '../includes/functions.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    setSetting('site_name', sanitize($_POST['site_name']));
    setSetting('site_description', sanitize($_POST['site_description']));
    setSetting('about_me', sanitize($_POST['about_me']));
    setSetting('footer_text', sanitize($_POST['footer_text']));
    
    // Logo upload
    if (!empty($_FILES['logo']['name'])) {
        $uploaded = uploadFile($_FILES['logo'], 'logos');
        if ($uploaded) {
            setSetting('logo', $uploaded);
        }
    }

    redirect('settings.php', 'Settings saved successfully');
}

$settings = [
    'site_name' => getSetting('site_name', 'My-Portfolio.Tool'),
    'site_description' => getSetting('site_description', 'Professional Portfolio Website'),
    'about_me' => getSetting('about_me', ''),
    'footer_text' => getSetting('footer_text', '© ' . date('Y') . ' All rights reserved.'),
    'logo' => getSetting('logo', '')
];

$pageTitle = 'Site Settings';
?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $pageTitle ?> - My-Portfolio.Tool</title><!--
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
            --radius-sm: 8px;
            --radius-md: 12px;
            --radius-lg: 16px;
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
        .header { background: var(--color-surface); border-bottom: 1px solid var(--color-border); height: var(--header-height); position: sticky; top: 0; z-index: 50; }
        .header__container { display: flex; align-items: center; justify-content: space-between; height: 100%; padding: 0 24px; max-width: 100%; }
        .header__brand { display: flex; align-items: center; gap: 16px; }
        .header__logo-icon { width: 32px; height: 32px; color: var(--color-primary); }
        .header__title { font-size: 18px; font-weight: 700; color: var(--color-text); letter-spacing: -0.02em; }
        .header__subtitle { font-size: 12px; color: var(--color-text-muted); font-weight: 500; }
        .header__actions { display: flex; align-items: center; gap: 16px; }
        .header__user { display: flex; align-items: center; gap: 8px; font-size: 14px; color: var(--color-text-secondary); }
        .header__icon { width: 18px; height: 18px; }
        .header__greeting { color: var(--color-text-muted); }
        .header__username { font-weight: 600; color: var(--color-text); }
        .header__btn { display: inline-flex; align-items: center; gap: 8px; padding: 8px 16px; border-radius: var(--radius-sm); font-size: 13px; font-weight: 500; text-decoration: none; transition: all var(--transition-fast); }
        .header__btn--ghost { color: var(--color-text-secondary); background: var(--color-bg); }
        .header__btn--ghost:hover { background: var(--color-primary-bg); color: var(--color-primary); }
        .header__btn--danger { color: var(--color-danger); background: var(--color-danger-bg); }
        .header__btn--danger:hover { background: var(--color-danger); color: white; }
        .hamburger-btn { display: none; background: none; border: none; padding: 8px; cursor: pointer; color: var(--color-text); border-radius: var(--radius-sm); transition: background var(--transition-fast); }
        .hamburger-btn:hover { background: var(--color-bg); }
        .hamburger-btn svg { width: 24px; height: 24px; }

        /* ============================================
           SIDEBAR (Shared)
           ============================================ */
        .admin-layout { display: flex; min-height: calc(100vh - var(--header-height)); }
        .sidebar { width: var(--sidebar-width); background: var(--color-surface); border-right: 1px solid var(--color-border); padding: 16px 0; position: sticky; top: var(--header-height); height: calc(100vh - var(--header-height)); overflow-y: auto; flex-shrink: 0; transition: transform var(--transition-base); z-index: 40; }
        .sidebar__list { list-style: none; padding: 0 8px; }
        .sidebar__link { display: flex; align-items: center; gap: 16px; padding: 10px 16px; color: var(--color-text-secondary); text-decoration: none; border-radius: var(--radius-sm); font-size: 14px; font-weight: 500; transition: all var(--transition-fast); margin-bottom: 2px; }
        .sidebar__link:hover { background: var(--color-primary-bg); color: var(--color-primary); }
        .sidebar__link--active { background: var(--color-primary-bg); color: var(--color-primary); font-weight: 600; }
        .sidebar__icon { width: 20px; height: 20px; flex-shrink: 0; color: var(--color-text-muted); transition: color var(--transition-fast); }
        .sidebar__link:hover .sidebar__icon, .sidebar__link--active .sidebar__icon { color: var(--color-primary); }
        .sidebar__icon--sm { width: 14px; height: 14px; margin-left: auto; opacity: 0.5; }
        .sidebar__divider { height: 1px; background: var(--color-border); margin: 16px; }
        .sidebar__link--logout:hover { background: var(--color-danger-bg); color: var(--color-danger); }
        .sidebar__link--logout:hover .sidebar__icon { color: var(--color-danger); }
        .sidebar-overlay { display: none; position: fixed; inset: 0; background: rgba(0, 0, 0, 0.4); z-index: 35; backdrop-filter: blur(4px); opacity: 0; transition: opacity var(--transition-base); }
        .sidebar-overlay.is-visible { display: block; opacity: 1; }

        /* ============================================
           MAIN CONTENT & PAGE SPECIFIC
           ============================================ */
        .main-content { flex: 1; padding: 32px; min-width: 0; }
        
        .page-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 32px; flex-wrap: wrap; gap: 16px; }
        .page-title { font-size: 24px; font-weight: 700; color: var(--color-text); display: flex; align-items: center; gap: 12px; letter-spacing: -0.02em; }
        .page-title svg { width: 28px; height: 28px; color: var(--color-primary); }
        .page-desc { color: var(--color-text-secondary); font-size: 15px; margin-top: 6px; max-width: 600px; }

        .alert { padding: 14px 20px; border-radius: var(--radius-sm); margin-bottom: 24px; font-size: 14px; font-weight: 500; display: flex; align-items: center; gap: 10px; animation: slideDown 0.3s ease-out; }
        @keyframes slideDown { from { opacity: 0; transform: translateY(-10px); } to { opacity: 1; transform: translateY(0); } }
        .alert-success { background: var(--color-success-bg); color: var(--color-success-text); border: 1px solid rgba(16,185,129,0.2); }

        /* Form Layout */
        .form-container {
            background: var(--color-surface);
            border-radius: var(--radius-lg);
            box-shadow: var(--shadow-sm);
            border: 1px solid var(--color-border);
            padding: 32px;
        }

        .settings-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 24px; }
        .form-group { display: flex; flex-direction: column; gap: 6px; }
        .form-group.full-width { grid-column: 1 / -1; }
        
        .form-label { font-size: 14px; font-weight: 600; color: var(--color-text); }
        .form-hint { font-size: 12px; color: var(--color-text-muted); margin-top: 2px; }

        .form-input, .form-textarea {
            width: 100%; padding: 10px 14px; border: 1px solid var(--color-border);
            border-radius: var(--radius-sm); font-size: 14px; font-family: inherit;
            color: var(--color-text); background: var(--color-surface);
            transition: all var(--transition-fast);
        }
        .form-input:focus, .form-textarea:focus {
            outline: none; border-color: var(--color-primary);
            box-shadow: 0 0 0 3px var(--color-primary-bg);
        }
        .form-textarea { resize: vertical; min-height: 100px; }

        /* Logo Upload Area */
        .logo-upload-area {
            border: 2px dashed var(--color-border);
            border-radius: var(--radius-md);
            padding: 24px;
            text-align: center;
            background: var(--color-bg);
            transition: all var(--transition-fast);
            cursor: pointer;
        }
        .logo-upload-area:hover {
            border-color: var(--color-primary);
            background: var(--color-primary-bg);
        }
        .logo-upload-area svg {
            width: 32px; height: 32px; color: var(--color-text-muted); margin-bottom: 8px;
        }
        .logo-upload-text { font-size: 14px; font-weight: 500; color: var(--color-text); }
        .logo-upload-hint { font-size: 12px; color: var(--color-text-muted); margin-top: 4px; }
        
        .logo-preview {
            margin-top: 16px;
            padding: 16px;
            background: var(--color-surface);
            border: 1px solid var(--color-border);
            border-radius: var(--radius-sm);
            display: inline-block;
        }
        .logo-preview img {
            max-height: 64px;
            width: auto;
            object-fit: contain;
            display: block;
        }

        .form-actions {
            grid-column: 1 / -1;
            display: flex;
            gap: 12px;
            margin-top: 16px;
            padding-top: 24px;
            border-top: 1px solid var(--color-border);
        }

        .btn {
            display: inline-flex; align-items: center; justify-content: center; gap: 8px; 
            padding: 10px 20px; border: none; border-radius: var(--radius-sm); 
            font-size: 14px; font-weight: 600; text-decoration: none; cursor: pointer; 
            transition: all var(--transition-fast);
        }
        .btn svg { width: 18px; height: 18px; }
        .btn-primary { background: var(--color-primary); color: white; box-shadow: 0 2px 8px rgba(99, 102, 241, 0.3); }
        .btn-primary:hover { background: var(--color-primary-dark); transform: translateY(-1px); box-shadow: 0 4px 12px rgba(99, 102, 241, 0.4); }
        .btn-secondary { background: var(--color-bg); color: var(--color-text-secondary); border: 1px solid var(--color-border); }
        .btn-secondary:hover { background: var(--color-border); color: var(--color-text); }

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
            .settings-grid { grid-template-columns: 1fr; }
            .form-container { padding: 20px; }
            .form-actions { flex-direction: column-reverse; }
            .btn { width: 100%; }
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
                            <circle cx="12" cy="12" r="3"/>
                            <path d="M19.4 15a1.65 1.65 0 00.33 1.82l.06.06a2 2 0 010 2.83 2 2 0 01-2.83 0l-.06-.06a1.65 1.65 0 00-1.82-.33 1.65 1.65 0 00-1 1.51V21a2 2 0 01-2 2 2 2 0 01-2-2v-.09A1.65 1.65 0 009 19.4a1.65 1.65 0 00-1.82.33l-.06.06a2 2 0 01-2.83 0 2 2 0 010-2.83l.06-.06A1.65 1.65 0 004.6 15a1.65 1.65 0 00-1.51-1H3a2 2 0 010-4h.09A1.65 1.65 0 004.6 9a1.65 1.65 0 00-.33-1.82l-.06-.06a2 2 0 012.83-2.83l.06.06A1.65 1.65 0 009 4.6a1.65 1.65 0 001-1.51V3a2 2 0 014 0v.09a1.65 1.65 0 001 1.51 1.65 1.65 0 001.82-.33l.06-.06a2 2 0 012.83 2.83l-.06.06A1.65 1.65 0 0019.4 9a1.65 1.65 0 001.51 1H21a2 2 0 010 4h-.09a1.65 1.65 0 00-1.51 1z"/>
                        </svg>
                        Site Settings
                    </h1>
                    <p class="page-desc">Configure the general information, branding, and metadata of your public portfolio.</p>
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

            <form method="POST" enctype="multipart/form-data" class="form-container">
                <div class="settings-grid">
                    
                    <!-- Site Name -->
                    <div class="form-group">
                        <label class="form-label" for="site_name">Site Name</label>
                        <input type="text" id="site_name" name="site_name" class="form-input" value="<?= htmlspecialchars($settings['site_name']) ?>" placeholder="My-Portfolio.Tool">
                        <span class="form-hint">Displayed in the browser tab and header.</span>
                    </div>

                    <!-- Site Description (Meta) -->
                    <div class="form-group">
                        <label class="form-label" for="site_description">Meta Description</label>
                        <input type="text" id="site_description" name="site_description" class="form-input" value="<?= htmlspecialchars($settings['site_description']) ?>" placeholder="Professional portfolio of...">
                        <span class="form-hint">Used for SEO and search engine results.</span>
                    </div>

                    <!-- About Me -->
                    <div class="form-group full-width">
                        <label class="form-label" for="about_me">About Me</label>
                        <textarea id="about_me" name="about_me" class="form-textarea" rows="5" placeholder="Write a brief introduction about yourself, your skills, and your experience..."><?= htmlspecialchars($settings['about_me']) ?></textarea>
                        <span class="form-hint">This will be displayed in the hero or about section of your site.</span>
                    </div>

                    <!-- Logo Upload -->
                    <div class="form-group full-width">
                        <label class="form-label">Site Logo</label>
                        <label class="logo-upload-area" for="logo_input">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4"/>
                                <polyline points="17 8 12 3 7 8"/>
                                <line x1="12" y1="3" x2="12" y2="15"/>
                            </svg>
                            <div class="logo-upload-text">Click to upload a new logo</div>
                            <div class="logo-upload-hint">SVG, PNG, or JPG (max. 2MB, recommended: 200x60px)</div>
                            <input type="file" id="logo_input" name="logo" accept="image/*" style="display: none;">
                        </label>
                        
                        <?php if ($settings['logo']): ?>
                            <div class="logo-preview">
                                <span class="form-hint" style="margin-bottom: 8px; display: block;">Current Logo:</span>
                                <img src="../<?= htmlspecialchars($settings['logo']) ?>" alt="Current Site Logo">
                            </div>
                        <?php endif; ?>
                    </div>

                    <!-- Footer Text -->
                    <div class="form-group full-width">
                        <label class="form-label" for="footer_text">Footer Text</label>
                        <input type="text" id="footer_text" name="footer_text" class="form-input" value="<?= htmlspecialchars($settings['footer_text']) ?>" placeholder="© 2024 All rights reserved.">
                        <span class="form-hint">Supports basic text. Displayed at the very bottom of the site.</span>
                    </div>

                    <!-- Actions -->
                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <path d="M19 21H5a2 2 0 01-2-2V5a2 2 0 012-2h11l5 5v11a2 2 0 01-2 2z"/>
                                <polyline points="17 21 17 13 7 13 7 21"/>
                                <polyline points="7 3 7 8 15 8"/>
                            </svg>
                            Save Settings
                        </button>
                        <a href="dashboard.php" class="btn btn-secondary">Cancel</a>
                    </div>

                </div>
            </form>
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