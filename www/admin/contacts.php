<?php
require_once '../includes/auth.php';
requireLogin();
require_once '../includes/functions.php';

// Delete contact
if (isset($_GET['delete'])) {
    $pdo->prepare("DELETE FROM contacts WHERE id = ?")->execute([(int)$_GET['delete']]);
    redirect('contacts.php', 'Contact deleted successfully');
}

// Add / Edit contact
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = (int)($_POST['id'] ?? 0);
    $type = sanitize($_POST['type']);
    $value = sanitize($_POST['value']);
    $icon = sanitize($_POST['icon']);
    $label = sanitize($_POST['label']);
    $is_custom = $type === 'custom' ? 1 : 0;

    if ($id > 0) {
        $stmt = $pdo->prepare("UPDATE contacts SET type=?, value=?, icon=?, label=?, is_custom=? WHERE id=?");
        $stmt->execute([$type, $value, $icon, $label, $is_custom, $id]);
    } else {
        $stmt = $pdo->prepare("INSERT INTO contacts (type, value, icon, label, is_custom) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$type, $value, $icon, $label, $is_custom]);
    }
    redirect('contacts.php', 'Contact saved successfully');
}

$contacts = $pdo->query("SELECT * FROM contacts ORDER BY sort_order ASC, id DESC")->fetchAll();

// Contact types with high-quality SVG icons
$contactTypes = [
    'email' => [
        'name' => 'Email', 
        'svg' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>'
    ],
    'phone' => [
        'name' => 'Phone', 
        'svg' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 01-2.18 2 19.79 19.79 0 01-8.63-3.07 19.5 19.5 0 01-6-6A19.79 19.79 0 012.12 4.18 2 2 0 014.11 2h3a2 2 0 012 1.72c.127.96.361 1.903.7 2.81a2 2 0 01-.45 2.11L8.09 9.91a16 16 0 006 6l1.27-1.27a2 2 0 012.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0122 16.92z"/></svg>'
    ],
    'address' => [
        'name' => 'Address', 
        'svg' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0118 0z"/><circle cx="12" cy="10" r="3"/></svg>'
    ],
    'instagram' => [
        'name' => 'Instagram', 
        'svg' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="2" width="20" height="20" rx="5" ry="5"/><path d="M16 11.37A4 4 0 1112.63 8 4 4 0 0116 11.37z"/><line x1="17.5" y1="6.5" x2="17.51" y2="6.5"/></svg>'
    ],
    'telegram' => [
        'name' => 'Telegram', 
        'svg' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="22" y1="2" x2="11" y2="13"/><polygon points="22 2 15 22 11 13 2 9 22 2"/></svg>'
    ],
    'whatsapp' => [
        'name' => 'WhatsApp', 
        'svg' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 11.5a8.38 8.38 0 01-.9 3.8 8.5 8.5 0 01-7.6 4.7 8.38 8.38 0 01-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 01-.9-3.8 8.5 8.5 0 014.7-7.6 8.38 8.38 0 013.8-.9h.5a8.48 8.48 0 018 8v.5z"/></svg>'
    ],
    'linkedin' => [
        'name' => 'LinkedIn', 
        'svg' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 8a6 6 0 016 6v7h-4v-7a2 2 0 00-2-2 2 2 0 00-2 2v7h-4v-7a6 6 0 016-6z"/><rect x="2" y="9" width="4" height="12"/><circle cx="4" cy="4" r="2"/></svg>'
    ],
    'github' => [
        'name' => 'GitHub', 
        'svg' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 19c-5 1.5-5-2.5-7-3m14 6v-3.87a3.37 3.37 0 00-.94-2.61c3.14-.35 6.44-1.54 6.44-7A5.44 5.44 0 0020 4.77 5.07 5.07 0 0019.91 1S18.73.65 16 2.48a13.38 13.38 0 00-7 0C6.27.65 5.09 1 5.09 1A5.07 5.07 0 005 4.77a5.44 5.44 0 00-1.5 3.78c0 5.42 3.3 6.61 6.44 7A3.37 3.37 0 009 18.13V22"/></svg>'
    ],
    'twitter' => [
        'name' => 'Twitter / X', 
        'svg' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M23 3a10.9 10.9 0 01-3.14 1.53 4.48 4.48 0 00-7.86 3v1A10.66 10.66 0 013 4s-4 9 5 13a11.64 11.64 0 01-7 2c9 5 20 0 20-11.5a4.5 4.5 0 00-.08-.83A7.72 7.72 0 0023 3z"/></svg>'
    ],
    'youtube' => [
        'name' => 'YouTube', 
        'svg' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22.54 6.42a2.78 2.78 0 00-1.94-2C18.88 4 12 4 12 4s-6.88 0-8.6.46a2.78 2.78 0 00-1.94 2A29 29 0 001 11.75a29 29 0 00.46 5.33A2.78 2.78 0 003.4 19c1.72.46 8.6.46 8.6.46s6.88 0 8.6-.46a2.78 2.78 0 001.94-2 29 29 0 00.46-5.25 29 29 0 00-.46-5.33z"/><polygon points="9.75 15.02 15.5 11.75 9.75 8.48 9.75 15.02"/></svg>'
    ],
    'custom' => [
        'name' => 'Custom Link', 
        'svg' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M10 13a5 5 0 007.54.54l3-3a5 5 0 00-7.07-7.07l-1.72 1.71"/><path d="M14 11a5 5 0 00-7.54-.54l-3 3a5 5 0 007.07 7.07l1.71-1.71"/></svg>'
    ]
];

$pageTitle = 'Contact Methods';
?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $pageTitle ?> - My-Portfolio.Tool</title>
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
        .page-desc { color: var(--color-text-secondary); font-size: 14px; margin-top: 4px; }

        .btn {
            display: inline-flex; align-items: center; gap: 8px; padding: 10px 20px;
            border: none; border-radius: var(--radius-sm); font-size: 14px; font-weight: 600;
            text-decoration: none; cursor: pointer; transition: all var(--transition-fast);
        }
        .btn svg { width: 18px; height: 18px; }
        .btn-primary { background: var(--color-primary); color: white; box-shadow: 0 2px 8px rgba(99, 102, 241, 0.3); }
        .btn-primary:hover { background: var(--color-primary-dark); transform: translateY(-1px); box-shadow: 0 4px 12px rgba(99, 102, 241, 0.4); }
        .btn-secondary { background: var(--color-bg); color: var(--color-text-secondary); border: 1px solid var(--color-border); }
        .btn-secondary:hover { background: var(--color-border); color: var(--color-text); }
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

        /* Form Styles */
        .form-container {
            background: var(--color-surface);
            border-radius: var(--radius-lg);
            box-shadow: var(--shadow-sm);
            border: 1px solid var(--color-border);
            padding: 32px;
            margin-bottom: 32px;
        }
        .form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 24px; }
        .form-group { display: flex; flex-direction: column; gap: 6px; }
        .form-group.full-width { grid-column: 1 / -1; }
        .form-label { font-size: 14px; font-weight: 600; color: var(--color-text); }
        .form-input, .form-select {
            width: 100%; padding: 10px 14px; border: 1px solid var(--color-border);
            border-radius: var(--radius-sm); font-size: 14px; font-family: inherit;
            color: var(--color-text); background: var(--color-surface);
            transition: all var(--transition-fast);
        }
        .form-input:focus, .form-select:focus {
            outline: none; border-color: var(--color-primary);
            box-shadow: 0 0 0 3px var(--color-primary-bg);
        }
        .form-hint { font-size: 12px; color: var(--color-text-muted); margin-top: 2px; }
        .form-actions {
            grid-column: 1 / -1; display: flex; gap: 12px; margin-top: 16px;
            padding-top: 24px; border-top: 1px solid var(--color-border);
        }

        /* Table Styles */
        .table-container {
            background: var(--color-surface); border-radius: var(--radius-lg);
            box-shadow: var(--shadow-sm); border: 1px solid var(--color-border); overflow: hidden;
        }
        .table-responsive { overflow-x: auto; -webkit-overflow-scrolling: touch; }
        .data-table { width: 100%; border-collapse: collapse; min-width: 700px; }
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

        .contact-icon-wrap {
            width: 40px; height: 40px; border-radius: var(--radius-md);
            background: var(--color-primary-bg); color: var(--color-primary);
            display: flex; align-items: center; justify-content: center;
        }
        .contact-icon-wrap svg { width: 20px; height: 20px; }
        .contact-value { font-weight: 500; color: var(--color-text); word-break: break-all; }
        .contact-label { font-size: 13px; color: var(--color-text-muted); }
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
            .form-grid { grid-template-columns: 1fr; }
            .form-container { padding: 20px; }
            .form-actions { flex-direction: column-reverse; }
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
                <div>
                    <h1 class="page-title">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="M22 16.92v3a2 2 0 01-2.18 2 19.79 19.79 0 01-8.63-3.07 19.5 19.5 0 01-6-6A19.79 19.79 0 012.12 4.18 2 2 0 014.11 2h3a2 2 0 012 1.72c.127.96.361 1.903.7 2.81a2 2 0 01-.45 2.11L8.09 9.91a16 16 0 006 6l1.27-1.27a2 2 0 012.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0122 16.92z"/>
                        </svg>
                        Contact Methods
                    </h1>
                    <p class="page-desc">Manage the contact information displayed in your site's footer.</p>
                </div>
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

            <!-- Add / Edit Form -->
            <form method="POST" class="form-container" id="contactForm">
                <input type="hidden" name="id" id="edit_id" value="">
                <div class="form-grid">
                    <div class="form-group">
                        <label class="form-label">Contact Type</label>
                        <select name="type" id="type_select" class="form-select" required>
                            <?php foreach ($contactTypes as $key => $t): ?>
                                <option value="<?= $key ?>"><?= $t['name'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Value <span style="color:var(--color-danger)">*</span></label>
                        <input type="text" name="value" id="value_input" class="form-input" required placeholder="e.g., info@example.com">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Display Label</label>
                        <input type="text" name="label" id="label_input" class="form-input" placeholder="e.g., Work Email">
                        <span class="form-hint">Optional text shown next to the value</span>
                    </div>
                    <div class="form-group" id="custom_icon_group" style="display: none;">
                        <label class="form-label">Custom Icon (SVG or Class)</label>
                        <input type="text" name="icon" id="icon_input" class="form-input" placeholder="e.g., <svg>...</svg> or fa-icon">
                        <span class="form-hint">Only used for "Custom Link" type</span>
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary" id="submit_btn">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <path d="M19 21H5a2 2 0 01-2-2V5a2 2 0 012-2h11l5 5v11a2 2 0 01-2 2z"/>
                                <polyline points="17 21 17 13 7 13 7 21"/>
                                <polyline points="7 3 7 8 15 8"/>
                            </svg>
                            Save Contact
                        </button>
                        <button type="button" class="btn btn-secondary" onclick="resetForm()">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <polyline points="1 4 1 10 7 10"/>
                                <path d="M3.51 15a9 9 0 102.13-9.36L1 10"/>
                            </svg>
                            Reset Form
                        </button>
                    </div each="form-actions">
                </div>
            </form>

            <!-- Contacts List -->
            <div class="table-container">
                <div class="table-responsive">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th style="width: 60px;">Icon</th>
                                <th>Type</th>
                                <th>Value</th>
                                <th>Label</th>
                                <th style="width: 120px;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($contacts)): ?>
                                <tr>
                                    <td colspan="5">
                                        <div class="empty-state">
                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                                <path d="M22 16.92v3a2 2 0 01-2.18 2 19.79 19.79 0 01-8.63-3.07 19.5 19.5 0 01-6-6A19.79 19.79 0 012.12 4.18 2 2 0 014.11 2h3a2 2 0 012 1.72c.127.96.361 1.903.7 2.81a2 2 0 01-.45 2.11L8.09 9.91a16 16 0 006 6l1.27-1.27a2 2 0 012.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0122 16.92z"/>
                                            </svg>
                                            <p>No contact methods added yet.</p>
                                        </div>
                                    </td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($contacts as $c): ?>
                                    <tr>
                                        <td>
                                            <div class="contact-icon-wrap">
                                                <?php 
                                                // Render SVG: use DB icon if it looks like an SVG, otherwise fallback to type's SVG
                                                if (!empty($c['icon']) && strpos(trim($c['icon']), '<svg') === 0) {
                                                    echo $c['icon'];
                                                } elseif (!empty($c['icon']) && strlen($c['icon']) <= 2) {
                                                    // Legacy emoji support: just output it, but wrapped nicely
                                                    echo '<span style="font-size:20px;">' . htmlspecialchars($c['icon']) . '</span>';
                                                } else {
                                                    echo $contactTypes[$c['type']]['svg'] ?? $contactTypes['custom']['svg'];
                                                }
                                                ?>
                                            </div>
                                        </td>
                                        <td>
                                            <span style="font-weight: 600;"><?= htmlspecialchars($contactTypes[$c['type']]['name'] ?? 'Custom') ?></span>
                                        </td>
                                        <td>
                                            <div class="contact-value"><?= htmlspecialchars($c['value']) ?></div>
                                        </td>
                                        <td>
                                            <span class="contact-label"><?= htmlspecialchars($c['label']) ?: '—' ?></span>
                                        </td>
                                        <td>
                                            <div class="actions-cell">
                                                <button class="btn btn-sm btn-info" onclick='editContact(<?= htmlspecialchars(json_encode($c), ENT_QUOTES, 'UTF-8') ?>)' title="Edit">
                                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                        <path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/>
                                                        <path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/>
                                                    </svg>
                                                </button>
                                                <a href="?delete=<?= $c['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this contact method?')" title="Delete">
                                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                        <polyline points="3 6 5 6 21 6"/>
                                                        <path d="M19 6v14a2 2 0 01-2 2H7a2 2 0 01-2-2V6m3 0V4a2 2 0 012-2h4a2 2 0 012 2v2"/>
                                                        <line x1="10" y1="11" x2="10" y2="17"/>
                                                        <line x1="14" y1="11" x2="14" y2="17"/>
                                                    </svg>
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
        // Toggle custom icon field based on type selection
        const typeSelect = document.getElementById('type_select');
        const customIconGroup = document.getElementById('custom_icon_group');
        
        function toggleCustomIcon() {
            if (typeSelect.value === 'custom') {
                customIconGroup.style.display = 'flex';
            } else {
                customIconGroup.style.display = 'none';
                document.getElementById('icon_input').value = '';
            }
        }
        
        typeSelect.addEventListener('change', toggleCustomIcon);

        // Edit contact function
        function editContact(c) {
            document.getElementById('edit_id').value = c.id;
            document.getElementById('type_select').value = c.type;
            document.getElementById('value_input').value = c.value;
            document.getElementById('label_input').value = c.label;
            document.getElementById('icon_input').value = c.icon || '';
            
            toggleCustomIcon(); // Show/hide custom icon field based on loaded type
            
            // Change button text to indicate edit mode
            document.getElementById('submit_btn').innerHTML = `
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" style="width:18px;height:18px;">
                    <path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/>
                    <path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/>
                </svg>
                Update Contact
            `;
            
            // Scroll to form
            document.getElementById('contactForm').scrollIntoView({ behavior: 'smooth', block: 'center' });
        }

        // Reset form function
        function resetForm() {
            document.getElementById('contactForm').reset();
            document.getElementById('edit_id').value = '';
            toggleCustomIcon();
            document.getElementById('submit_btn').innerHTML = `
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" style="width:18px;height:18px;">
                    <path d="M19 21H5a2 2 0 01-2-2V5a2 2 0 012-2h11l5 5v11a2 2 0 01-2 2z"/>
                    <polyline points="17 21 17 13 7 13 7 21"/>
                    <polyline points="7 3 7 8 15 8"/>
                </svg>
                Save Contact
            `;
        }

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