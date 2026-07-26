<?php
require_once '../includes/auth.php';
requireLogin();
require_once '../includes/functions.php';

$admin = getCurrentAdmin();
$message = '';
$messageType = ''; // 'success' or 'error'

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    
    if ($action === 'update_info') {
        $name = sanitize($_POST['name']);
        $pdo->prepare("UPDATE admins SET name = ? WHERE id = ?")->execute([$name, $admin['id']]);
        $_SESSION['admin_name'] = $name;
        $message = 'Profile information updated successfully.';
        $messageType = 'success';
    }
    
    if ($action === 'change_username') {
        $newUsername = sanitize($_POST['new_username']);
        if (strlen($newUsername) < 3) {
            $message = 'Username must be at least 3 characters long.';
            $messageType = 'error';
        } else {
            $check = $pdo->prepare("SELECT id FROM admins WHERE username = ? AND id != ?");
            $check->execute([$newUsername, $admin['id']]);
            if ($check->fetch()) {
                $message = 'This username is already taken.';
                $messageType = 'error';
            } else {
                $pdo->prepare("UPDATE admins SET username = ? WHERE id = ?")->execute([$newUsername, $admin['id']]);
                $message = 'Username changed successfully.';
                $messageType = 'success';
            }
        }
    }
    
    if ($action === 'change_password') {
        $currentPass = $_POST['current_password'];
        $newPass = $_POST['new_password'];
        $confirmPass = $_POST['confirm_password'];
        
        if (!password_verify($currentPass, $admin['password'])) {
            $message = 'Current password is incorrect.';
            $messageType = 'error';
        } elseif (strlen($newPass) < 6) {
            $message = 'New password must be at least 6 characters long.';
            $messageType = 'error';
        } elseif ($newPass !== $confirmPass) {
            $message = 'Passwords do not match.';
            $messageType = 'error';
        } else {
            $hashed = password_hash($newPass, PASSWORD_DEFAULT);
            $pdo->prepare("UPDATE admins SET password = ? WHERE id = ?")->execute([$hashed, $admin['id']]);
            $message = 'Password changed successfully.';
            $messageType = 'success';
        }
    }
    
    // Refresh admin data
    $admin = getCurrentAdmin();
}

$pageTitle = 'Profile & Security';
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
            --color-danger-text: #991b1b;
            --color-warning: #f59e0b;
            --color-warning-bg: rgba(245, 158, 11, 0.1);
            --color-warning-text: #92400e;
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
        .main-content { flex: 1; padding: 32px; min-width: 0; max-width: 800px; margin: 0 auto; }
        
        .page-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 32px; flex-wrap: wrap; gap: 16px; }
        .page-title { font-size: 24px; font-weight: 700; color: var(--color-text); display: flex; align-items: center; gap: 12px; letter-spacing: -0.02em; }
        .page-title svg { width: 28px; height: 28px; color: var(--color-primary); }
        .page-desc { color: var(--color-text-secondary); font-size: 15px; margin-top: 6px; }

        .alert { padding: 14px 20px; border-radius: var(--radius-sm); margin-bottom: 24px; font-size: 14px; font-weight: 500; display: flex; align-items: center; gap: 10px; animation: slideDown 0.3s ease-out; }
        @keyframes slideDown { from { opacity: 0; transform: translateY(-10px); } to { opacity: 1; transform: translateY(0); } }
        .alert-success { background: var(--color-success-bg); color: var(--color-success-text); border: 1px solid rgba(16,185,129,0.2); }
        .alert-error { background: var(--color-danger-bg); color: var(--color-danger-text); border: 1px solid rgba(239,68,68,0.2); }

        /* Profile Sections */
        .profile-section {
            background: var(--color-surface);
            border-radius: var(--radius-lg);
            box-shadow: var(--shadow-sm);
            border: 1px solid var(--color-border);
            padding: 24px;
            margin-bottom: 24px;
        }

        .section-header {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 20px;
            padding-bottom: 16px;
            border-bottom: 1px solid var(--color-border);
        }

        .section-header svg { width: 20px; height: 20px; color: var(--color-primary); }
        .section-title { font-size: 16px; font-weight: 700; color: var(--color-text); }
        .section-desc { font-size: 13px; color: var(--color-text-muted); margin-left: auto; }

        .form-group { display: flex; flex-direction: column; gap: 6px; margin-bottom: 20px; }
        .form-group:last-of-type { margin-bottom: 0; }
        
        .form-label { font-size: 14px; font-weight: 600; color: var(--color-text); }
        .form-hint { font-size: 12px; color: var(--color-text-muted); margin-top: 2px; }

        .form-input {
            width: 100%; padding: 10px 14px; border: 1px solid var(--color-border);
            border-radius: var(--radius-sm); font-size: 14px; font-family: inherit;
            color: var(--color-text); background: var(--color-surface);
            transition: all var(--transition-fast);
        }
        .form-input:focus {
            outline: none; border-color: var(--color-primary);
            box-shadow: 0 0 0 3px var(--color-primary-bg);
        }
        .form-input:read-only {
            background: var(--color-bg);
            color: var(--color-text-muted);
            cursor: not-allowed;
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
            .main-content { padding: 20px; max-width: 100%; }
            .header__btn span, .header__greeting { display: none; }
            .section-desc { display: none; }
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
                            <path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/>
                            <circle cx="12" cy="7" r="4"/>
                        </svg>
                        Profile & Security
                    </h1>
                    <p class="page-desc">Manage your account details, username, and password.</p>
                </div>
            </div>

            <?php if ($message): ?>
                <div class="alert alert-<?= $messageType === 'error' ? 'error' : 'success' ?>">
                    <?php if ($messageType === 'success'): ?>
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 11-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                    <?php else: ?>
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>
                    <?php endif; ?>
                    <?= htmlspecialchars($message) ?>
                </div>
            <?php endif; ?>

            <!-- Section 1: Profile Information -->
            <form method="POST" class="profile-section">
                <input type="hidden" name="action" value="update_info">
                <div class="section-header">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                    <h2 class="section-title">Profile Information</h2>
                    <span class="section-desc">Update your display name</span>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Username</label>
                    <input type="text" class="form-input" value="<?= htmlspecialchars($admin['username']) ?>" readonly>
                    <span class="form-hint">Your unique login identifier (cannot be changed here).</span>
                </div>

                <div class="form-group">
                    <label class="form-label" for="name">Display Name</label>
                    <input type="text" id="name" name="name" class="form-input" value="<?= htmlspecialchars($admin['name']) ?>" required>
                </div>

                <button type="submit" class="btn btn-primary">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21H5a2 2 0 01-2-2V5a2 2 0 012-2h11l5 5v11a2 2 0 01-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
                    Save Changes
                </button>
            </form>

            <!-- Section 2: Change Username -->
            <form method="POST" class="profile-section">
                <input type="hidden" name="action" value="change_username">
                <div class="section-header">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/><circle cx="12" cy="7" r="4"/><line x1="12" y1="11" x2="12" y2="11"/></svg>
                    <h2 class="section-title">Change Username</h2>
                    <span class="section-desc">Update your login ID</span>
                </div>
                
                <div class="form-group">
                    <label class="form-label" for="new_username">New Username</label>
                    <input type="text" id="new_username" name="new_username" class="form-input" required minlength="3" placeholder="Enter new username">
                    <span class="form-hint">Must be at least 3 characters long and unique.</span>
                </div>

                <button type="submit" class="btn btn-primary">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21H5a2 2 0 01-2-2V5a2 2 0 012-2h11l5 5v11a2 2 0 01-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
                    Update Username
                </button>
            </form>

            <!-- Section 3: Change Password -->
            <form method="POST" class="profile-section">
                <input type="hidden" name="action" value="change_password">
                <div class="section-header">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0110 0v4"/></svg>
                    <h2 class="section-title">Change Password</h2>
                    <span class="section-desc">Keep your account secure</span>
                </div>
                
                <div class="form-group">
                    <label class="form-label" for="current_password">Current Password</label>
                    <input type="password" id="current_password" name="current_password" class="form-input" required placeholder="••••••••">
                </div>

                <div class="form-group">
                    <label class="form-label" for="new_password">New Password</label>
                    <input type="password" id="new_password" name="new_password" class="form-input" required minlength="6" placeholder="Minimum 6 characters">
                </div>

                <div class="form-group">
                    <label class="form-label" for="confirm_password">Confirm New Password</label>
                    <input type="password" id="confirm_password" name="confirm_password" class="form-input" required minlength="6" placeholder="Re-enter new password">
                </div>

                <button type="submit" class="btn btn-primary">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21H5a2 2 0 01-2-2V5a2 2 0 012-2h11l5 5v11a2 2 0 01-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
                    Change Password
                </button>
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