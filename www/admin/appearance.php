<?php
require_once '../includes/auth.php';
requireLogin();
require_once '../includes/functions.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    setSetting('primary_color', sanitize($_POST['primary_color']));
    setSetting('secondary_color', sanitize($_POST['secondary_color']));
    setSetting('bg_color', sanitize($_POST['bg_color']));
    setSetting('text_color', sanitize($_POST['text_color']));
    setSetting('font_family', sanitize($_POST['font_family']));
    redirect('appearance.php', 'Appearance settings saved successfully');
}

// Fetch current settings
$primaryColor = getSetting('primary_color', '#6366f1');
$secondaryColor = getSetting('secondary_color', '#8b5cf6');
$bgColor = getSetting('bg_color', '#ffffff');
$textColor = getSetting('text_color', '#1f2937');
$fontFamily = getSetting('font_family', 'Inter');

$pageTitle = 'Appearance & Branding';
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
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 40px;
        }

        .form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 24px; }
        .form-group { display: flex; flex-direction: column; gap: 8px; }
        .form-group.full-width { grid-column: 1 / -1; }
        .form-label { font-size: 14px; font-weight: 600; color: var(--color-text); }
        
        .form-select {
            width: 100%; padding: 10px 14px; border: 1px solid var(--color-border);
            border-radius: var(--radius-sm); font-size: 14px; font-family: inherit;
            color: var(--color-text); background: var(--color-surface);
            transition: all var(--transition-fast); cursor: pointer;
        }
        .form-select:focus { outline: none; border-color: var(--color-primary); box-shadow: 0 0 0 3px var(--color-primary-bg); }

        /* Modern Color Input */
        .color-input-wrapper {
            display: flex; align-items: center; gap: 12px;
            padding: 6px; border: 1px solid var(--color-border);
            border-radius: var(--radius-sm); background: var(--color-surface);
            transition: all var(--transition-fast);
        }
        .color-input-wrapper:focus-within {
            border-color: var(--color-primary);
            box-shadow: 0 0 0 3px var(--color-primary-bg);
        }
        .color-input-wrapper input[type="color"] {
            border: none; width: 36px; height: 36px; cursor: pointer;
            background: none; padding: 0; border-radius: 6px; overflow: hidden;
        }
        .color-input-wrapper input[type="color"]::-webkit-color-swatch-wrapper { padding: 0; }
        .color-input-wrapper input[type="color"]::-webkit-color-swatch { border: 1px solid rgba(0,0,0,0.1); border-radius: 6px; }
        .color-input-wrapper input[type="color"]::-moz-color-swatch { border: 1px solid rgba(0,0,0,0.1); border-radius: 6px; }
        
        .color-hex {
            font-family: 'SF Mono', 'Fira Code', monospace;
            font-size: 13px; color: var(--color-text-secondary);
            text-transform: uppercase; flex: 1;
        }

        /* Preview Card */
        .preview-card {
            background: var(--color-bg);
            border-radius: var(--radius-md);
            padding: 24px;
            border: 1px solid var(--color-border);
            position: sticky;
            top: 96px;
        }
        .preview-title { font-size: 14px; font-weight: 600; color: var(--color-text); margin-bottom: 16px; display: flex; align-items: center; gap: 8px; }
        .preview-title svg { width: 16px; height: 16px; color: var(--color-text-muted); }
        
        .preview-swatch {
            display: flex; align-items: center; gap: 12px;
            padding: 10px 0; border-bottom: 1px solid var(--color-border);
        }
        .preview-swatch:last-child { border-bottom: none; }
        .swatch-dot { width: 24px; height: 24px; border-radius: 50%; border: 2px solid white; box-shadow: 0 0 0 1px var(--color-border); flex-shrink: 0; }
        .swatch-info { flex: 1; }
        .swatch-name { font-size: 12px; color: var(--color-text-muted); font-weight: 500; }
        .swatch-value { font-size: 13px; font-weight: 600; color: var(--color-text); font-family: monospace; }

        .form-actions {
            grid-column: 1 / -1; display: flex; gap: 12px; margin-top: 16px;
            padding-top: 24px; border-top: 1px solid var(--color-border);
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
        @media (max-width: 900px) {
            .form-container { grid-template-columns: 1fr; }
            .preview-card { position: static; order: -1; margin-bottom: 24px; }
        }
        @media (max-width: 768px) {
            .hamburger-btn { display: flex; }
            .sidebar {
                position: fixed; top: 0; left: 0; height: 100vh; transform: translateX(-100%);
                z-index: 45; padding-top: 80px; box-shadow: var(--shadow-xl);
            }
            .sidebar.is-open { transform: translateX(0); }
            .main-content { padding: 20px; }
            .form-grid { grid-template-columns: 1fr; }
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
                            <circle cx="13.5" cy="6.5" r="2.5"/>
                            <circle cx="17.5" cy="10.5" r="2.5"/>
                            <circle cx="8.5" cy="7.5" r="2.5"/>
                            <circle cx="6.5" cy="12.5" r="2.5"/>
                            <path d="M12 2C6.5 2 2 6.5 2 12s4.5 10 10 10c.926 0 1.648-.746 1.648-1.688 0-.437-.18-.835-.437-1.125-.29-.289-.438-.652-.438-1.125a1.64 1.64 0 011.668-1.668h1.996c3.051 0 5.555-2.503 5.555-5.554C21.965 6.012 17.461 2 12 2z"/>
                        </svg>
                        Appearance & Branding
                    </h1>
                    <p class="page-desc">Customize the colors and typography of your public-facing portfolio site.</p>
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

            <form method="POST" class="form-container" id="appearanceForm">
                
                <!-- Left: Form Inputs -->
                <div>
                    <div class="form-grid">
                        <div class="form-group">
                            <label class="form-label">Primary Color</label>
                            <div class="color-input-wrapper">
                                <input type="color" name="primary_color" id="primary_color" value="<?= htmlspecialchars($primaryColor) ?>">
                                <span class="color-hex"><?= htmlspecialchars($primaryColor) ?></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Secondary Color</label>
                            <div class="color-input-wrapper">
                                <input type="color" name="secondary_color" id="secondary_color" value="<?= htmlspecialchars($secondaryColor) ?>">
                                <span class="color-hex"><?= htmlspecialchars($secondaryColor) ?></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Background Color</label>
                            <div class="color-input-wrapper">
                                <input type="color" name="bg_color" id="bg_color" value="<?= htmlspecialchars($bgColor) ?>">
                                <span class="color-hex"><?= htmlspecialchars($bgColor) ?></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Text Color</label>
                            <div class="color-input-wrapper">
                                <input type="color" name="text_color" id="text_color" value="<?= htmlspecialchars($textColor) ?>">
                                <span class="color-hex"><?= htmlspecialchars($textColor) ?></span>
                            </div>
                        </div>

                        <div class="form-group full-width">
                            <label class="form-label">Typography (Font Family)</label>
                            <select name="font_family" class="form-select">
                                <option value="Inter" <?= $fontFamily === 'Inter' ? 'selected' : '' ?>>Inter (Modern Global)</option>
                                <option value="Vazirmatn" <?= $fontFamily === 'Vazirmatn' ? 'selected' : '' ?>>Vazirmatn (Modern Persian/Arabic)</option>
                                <option value="Estedad" <?= $fontFamily === 'Estedad' ? 'selected' : '' ?>>Estedad (Bold Persian/Arabic)</option>
                                <option value="Shabnam" <?= $fontFamily === 'Shabnam' ? 'selected' : '' ?>>Shabnam (Friendly Persian/Arabic)</option>
                                <option value="IranSans" <?= $fontFamily === 'IranSans' ? 'selected' : '' ?>>IranSans (Standard Persian/Arabic)</option>
                                <option value="Tahoma" <?= $fontFamily === 'Tahoma' ? 'selected' : '' ?>>Tahoma (Fallback)</option>
                            </select>
                            <span style="font-size: 12px; color: var(--color-text-muted);">This font will be applied to the public-facing site.</span>
                        </div>

                        <div class="form-actions">
                            <button type="submit" class="btn btn-primary">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                    <path d="M19 21H5a2 2 0 01-2-2V5a2 2 0 012-2h11l5 5v11a2 2 0 01-2 2z"/>
                                    <polyline points="17 21 17 13 7 13 7 21"/>
                                    <polyline points="7 3 7 8 15 8"/>
                                </svg>
                                Save Appearance
                            </button>
                            <a href="dashboard.php" class="btn btn-secondary">Cancel</a>
                        </div>
                    </div>
                </div>

                <!-- Right: Live Preview -->
                <div class="preview-card">
                    <div class="preview-title">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                        Live Preview
                    </div>
                    
                    <div class="preview-swatch">
                        <div class="swatch-dot" id="preview-primary" style="background: <?= htmlspecialchars($primaryColor) ?>;"></div>
                        <div class="swatch-info">
                            <div class="swatch-name">Primary</div>
                            <div class="swatch-value" id="text-primary"><?= htmlspecialchars($primaryColor) ?></div>
                        </div>
                    </div>
                    <div class="preview-swatch">
                        <div class="swatch-dot" id="preview-secondary" style="background: <?= htmlspecialchars($secondaryColor) ?>;"></div>
                        <div class="swatch-info">
                            <div class="swatch-name">Secondary</div>
                            <div class="swatch-value" id="text-secondary"><?= htmlspecialchars($secondaryColor) ?></div>
                        </div>
                    </div>
                    <div class="preview-swatch">
                        <div class="swatch-dot" id="preview-bg" style="background: <?= htmlspecialchars($bgColor) ?>;"></div>
                        <div class="swatch-info">
                            <div class="swatch-name">Background</div>
                            <div class="swatch-value" id="text-bg"><?= htmlspecialchars($bgColor) ?></div>
                        </div>
                    </div>
                    <div class="preview-swatch">
                        <div class="swatch-dot" id="preview-text" style="background: <?= htmlspecialchars($textColor) ?>;"></div>
                        <div class="swatch-info">
                            <div class="swatch-name">Text</div>
                            <div class="swatch-value" id="text-text"><?= htmlspecialchars($textColor) ?></div>
                        </div>
                    </div>
                </div>

            </form>
        </main>
    </div>

    <script>
        // Live Preview & Hex Text Update Logic
        const colorInputs = ['primary_color', 'secondary_color', 'bg_color', 'text_color'];
        
        colorInputs.forEach(id => {
            const input = document.getElementById(id);
            const hexSpan = input.nextElementSibling; // The span with class 'color-hex'
            const previewDot = document.getElementById('preview-' + id.replace('_color', ''));
            const previewText = document.getElementById('text-' + id.replace('_color', ''));

            if (input && hexSpan && previewDot && previewText) {
                // Update on input (while dragging color picker)
                input.addEventListener('input', function() {
                    const val = this.value.toUpperCase();
                    hexSpan.textContent = val;
                    previewDot.style.background = val;
                    previewText.textContent = val;
                });
            }
        });

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