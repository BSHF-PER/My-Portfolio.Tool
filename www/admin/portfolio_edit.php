<?php
require_once '../includes/auth.php';
requireLogin();
require_once '../includes/functions.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$isEdit = $id > 0;
$item = null;

if ($isEdit) {
    $stmt = $pdo->prepare("SELECT * FROM portfolios WHERE id = ?");
    $stmt->execute([$id]);
    $item = $stmt->fetch();
    if (!$item) {
        redirect('portfolio.php', 'Portfolio not found', 'error');
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = sanitize($_POST['title']);
    $description = sanitize($_POST['description']);
    $category = sanitize($_POST['category']);
    $link = sanitize($_POST['link']);
    $github = sanitize($_POST['github']);
    $demo = sanitize($_POST['demo']);
    $tags = sanitize($_POST['tags']);
    $sort_order = (int)($_POST['sort_order'] ?? 0);
    $is_active = isset($_POST['is_active']) ? 1 : 0;

    $image = $item['image'] ?? '';
    $video = $item['video'] ?? '';

    // Image upload
    if (!empty($_FILES['image']['name'])) {
        $uploaded = uploadFile($_FILES['image'], 'portfolios');
        if ($uploaded) {
            if ($image && file_exists(BASE_PATH . '/' . $image)) @unlink(BASE_PATH . '/' . $image);
            $image = $uploaded;
        }
    }

    // Video upload
    if (!empty($_FILES['video']['name'])) {
        $uploaded = uploadFile($_FILES['video'], 'portfolios');
        if ($uploaded) {
            if ($video && file_exists(BASE_PATH . '/' . $video)) @unlink(BASE_PATH . '/' . $video);
            $video = $uploaded;
        }
    }

    if ($isEdit) {
        $stmt = $pdo->prepare("UPDATE portfolios SET title=?, description=?, category=?, image=?, video=?, link=?, github=?, demo=?, tags=?, sort_order=?, is_active=? WHERE id=?");
        $stmt->execute([$title, $description, $category, $image, $video, $link, $github, $demo, $tags, $sort_order, $is_active, $id]);
        redirect('portfolio.php', 'Portfolio updated successfully');
    } else {
        $stmt = $pdo->prepare("INSERT INTO portfolios (title, description, category, image, video, link, github, demo, tags, sort_order, is_active) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$title, $description, $category, $image, $video, $link, $github, $demo, $tags, $sort_order, $is_active]);
        redirect('portfolio.php', 'Portfolio added successfully');
    }
}

$pageTitle = $isEdit ? 'Edit Portfolio' : 'Add New Portfolio';
?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $pageTitle ?> - My-Portfolio.Tool</title>
    <style>
        /* ============================================
           SHARED DESIGN TOKENS (Exact match)
           ============================================ */
        :root {
            --color-primary: #6366f1;
            --color-primary-light: #818cf8;
            --color-primary-dark: #4f46e5;
            --color-primary-bg: rgba(99, 102, 241, 0.08);
            --color-success: #10b981;
            --color-success-bg: rgba(16, 185, 129, 0.1);
            --color-bg: #f1f5f9;
            --color-surface: #ffffff;
            --color-text: #0f172a;
            --color-text-secondary: #64748b;
            --color-text-muted: #94a3b8;
            --color-border: #e2e8f0;
            --color-border-light: #f1f5f9;
            --shadow-sm: 0 1px 2px rgba(0, 0, 0, 0.04);
            --shadow-md: 0 4px 12px rgba(0, 0, 0, 0.06);
            --radius-sm: 8px;
            --radius-md: 12px;
            --radius-lg: 16px;
            --radius-full: 9999px;
            --transition-fast: 150ms cubic-bezier(0.4, 0, 0.2, 1);
            --sidebar-width: 260px;
            --header-height: 64px;
        }

        /* ============================================
           RESET & BASE
           ============================================ */
        *, *::before, *::after { margin: 0; padding: 0; box-sizing: border-box; }
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
           LAYOUT & SHARED COMPONENTS (Header/Sidebar)
           ============================================ */
        .admin-layout { display: flex; min-height: calc(100vh - var(--header-height)); }
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
        .btn-secondary { background: var(--color-bg); color: var(--color-text-secondary); border: 1px solid var(--color-border); }
        .btn-secondary:hover { background: var(--color-border); color: var(--color-text); }

        .alert {
            padding: 14px 20px; border-radius: var(--radius-sm); margin-bottom: 24px;
            font-size: 14px; font-weight: 500; display: flex; align-items: center; gap: 10px;
        }
        .alert-error { background: var(--color-danger-bg, #fee2e2); color: #991b1b; border: 1px solid rgba(239,68,68,0.2); }

        /* ============================================
           FORM STYLES
           ============================================ */
        .form-container {
            background: var(--color-surface);
            border-radius: var(--radius-lg);
            box-shadow: var(--shadow-sm);
            border: 1px solid var(--color-border);
            padding: 32px;
        }

        .form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 24px;
        }

        .form-group {
            display: flex;
            flex-direction: column;
            gap: 6px;
        }

        .form-group.full-width {
            grid-column: 1 / -1;
        }

        .form-label {
            font-size: 14px;
            font-weight: 600;
            color: var(--color-text);
        }

        .form-label .required {
            color: var(--color-danger, #ef4444);
            margin-left: 2px;
        }

        .form-input, .form-textarea, .form-select {
            width: 100%;
            padding: 10px 14px;
            border: 1px solid var(--color-border);
            border-radius: var(--radius-sm);
            font-size: 14px;
            font-family: inherit;
            color: var(--color-text);
            background: var(--color-surface);
            transition: all var(--transition-fast);
        }

        .form-input:focus, .form-textarea:focus, .form-select:focus {
            outline: none;
            border-color: var(--color-primary);
            box-shadow: 0 0 0 3px var(--color-primary-bg);
        }

        .form-textarea {
            resize: vertical;
            min-height: 120px;
        }

        .form-hint {
            font-size: 12px;
            color: var(--color-text-muted);
            margin-top: 2px;
        }

        /* File Input Styling */
        .file-input-wrapper {
            position: relative;
        }
        .file-input {
            width: 100%;
            padding: 8px;
            border: 1px dashed var(--color-border);
            border-radius: var(--radius-sm);
            background: var(--color-bg);
            font-size: 13px;
            color: var(--color-text-secondary);
            cursor: pointer;
            transition: all var(--transition-fast);
        }
        .file-input:hover {
            border-color: var(--color-primary);
            background: var(--color-primary-bg);
        }

        .media-preview {
            margin-top: 12px;
            padding: 8px;
            background: var(--color-bg);
            border-radius: var(--radius-sm);
            border: 1px solid var(--color-border);
            display: inline-block;
        }
        .media-preview img, .media-preview video {
            max-width: 200px;
            border-radius: var(--radius-sm);
            display: block;
        }

        /* Custom Toggle Switch */
        .toggle-wrapper {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-top: 8px;
        }
        .toggle-switch {
            position: relative;
            width: 44px;
            height: 24px;
        }
        .toggle-switch input {
            opacity: 0;
            width: 0;
            height: 0;
        }
        .toggle-slider {
            position: absolute;
            cursor: pointer;
            top: 0; left: 0; right: 0; bottom: 0;
            background-color: var(--color-border);
            transition: var(--transition-fast);
            border-radius: 24px;
        }
        .toggle-slider:before {
            position: absolute;
            content: "";
            height: 18px;
            width: 18px;
            left: 3px;
            bottom: 3px;
            background-color: white;
            transition: var(--transition-fast);
            border-radius: 50%;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }
        .toggle-switch input:checked + .toggle-slider {
            background-color: var(--color-primary);
        }
        .toggle-switch input:checked + .toggle-slider:before {
            transform: translateX(20px);
        }
        .toggle-label {
            font-size: 14px;
            font-weight: 500;
            color: var(--color-text);
        }

        .form-actions {
            grid-column: 1 / -1;
            display: flex;
            gap: 12px;
            margin-top: 16px;
            padding-top: 24px;
            border-top: 1px solid var(--color-border);
        }

        /* ============================================
           RESPONSIVE
           ============================================ */
        @media (max-width: 768px) {
            .main-content { padding: 20px; }
            .form-grid { grid-template-columns: 1fr; }
            .form-container { padding: 20px; }
            .form-actions { flex-direction: column-reverse; }
            .btn { width: 100%; justify-content: center; }
        }
    </style>
</head>
<body>

    <?php include '../includes/header_admin.php'; ?>
    
    <div class="admin-layout">
        <?php include '../includes/sidebar_admin.php'; ?>
        
        <main class="main-content">
            <div class="page-header">
                <h1 class="page-title">
                    <?php if ($isEdit): ?>
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/>
                            <path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/>
                        </svg>
                        Edit Portfolio
                    <?php else: ?>
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <circle cx="12" cy="12" r="10"/>
                            <line x1="12" y1="8" x2="12" y2="16"/>
                            <line x1="8" y1="12" x2="16" y2="12"/>
                        </svg>
                        Add New Portfolio
                    <?php endif; ?>
                </h1>
                <a href="portfolio.php" class="btn btn-secondary">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <line x1="19" y1="12" x2="5" y2="12"/>
                        <polyline points="12 19 5 12 12 5"/>
                    </svg>
                    Back to List
                </a>
            </div>

            <?php if (isset($_GET['error'])): ?>
                <div class="alert alert-error">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>
                    An error occurred. Please try again.
                </div>
            <?php endif; ?>

            <form method="POST" enctype="multipart/form-data" class="form-container">
                <div class="form-grid">
                    
                    <!-- Title & Category -->
                    <div class="form-group">
                        <label class="form-label">Title <span class="required">*</span></label>
                        <input type="text" name="title" class="form-input" required value="<?= htmlspecialchars($item['title'] ?? '') ?>" placeholder="e.g., E-commerce Dashboard">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Category</label>
                        <input type="text" name="category" class="form-input" value="<?= htmlspecialchars($item['category'] ?? '') ?>" placeholder="e.g., Web Design">
                    </div>

                    <!-- Description -->
                    <div class="form-group full-width">
                        <label class="form-label">Description</label>
                        <textarea name="description" class="form-textarea" placeholder="Describe the project, technologies used, and your role..."><?= htmlspecialchars($item['description'] ?? '') ?></textarea>
                    </div>

                    <!-- Media Uploads -->
                    <div class="form-group">
                        <label class="form-label">Cover Image</label>
                        <input type="file" name="image" accept="image/*" class="file-input">
                        <span class="form-hint">Recommended: 1200x800px, JPG or PNG</span>
                        <?php if (!empty($item['image'])): ?>
                            <div class="media-preview">
                                <img src="../<?= htmlspecialchars($item['image']) ?>" alt="Current image">
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Video (Optional)</label>
                        <input type="file" name="video" accept="video/*" class="file-input">
                        <span class="form-hint">MP4 or WebM format</span>
                        <?php if (!empty($item['video'])): ?>
                            <div class="media-preview">
                                <video src="../<?= htmlspecialchars($item['video']) ?>" controls></video>
                            </div>
                        <?php endif; ?>
                    </div>

                    <!-- Links -->
                    <div class="form-group">
                        <label class="form-label">
                            <svg style="width:14px;height:14px;display:inline;vertical-align:middle;margin-right:4px;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M10 13a5 5 0 007.54.54l3-3a5 5 0 00-7.07-7.07l-1.72 1.71"/></svg>
                            Project Link
                        </label>
                        <input type="url" name="link" class="form-input" value="<?= htmlspecialchars($item['link'] ?? '') ?>" placeholder="https://...">
                    </div>
                    <div class="form-group">
                        <label class="form-label">
                            <svg style="width:14px;height:14px;display:inline;vertical-align:middle;margin-right:4px;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 19c-5 1.5-5-2.5-7-3m14 6v-3.87a3.37 3.37 0 00-.94-2.61c3.14-.35 6.44-1.54 6.44-7A5.44 5.44 0 0020 4.77 5.07 5.07 0 0019.91 1S18.73.65 16 2.48a13.38 13.38 0 00-7 0C6.27.65 5.09 1 5.09 1A5.07 5.07 0 005 4.77a5.44 5.44 0 00-1.5 3.78c0 5.42 3.3 6.61 6.44 7A3.37 3.37 0 009 18.13V22"/></svg>
                            GitHub Repository
                        </label>
                        <input type="url" name="github" class="form-input" value="<?= htmlspecialchars($item['github'] ?? '') ?>" placeholder="https://github.com/...">
                    </div>

                    <div class="form-group">
                        <label class="form-label">Live Demo Link</label>
                        <input type="url" name="demo" class="form-input" value="<?= htmlspecialchars($item['demo'] ?? '') ?>" placeholder="https://...">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Tags</label>
                        <input type="text" name="tags" class="form-input" value="<?= htmlspecialchars($item['tags'] ?? '') ?>" placeholder="php, mysql, javascript (comma separated)">
                        <span class="form-hint">Separate tags with commas</span>
                    </div>

                    <!-- Settings -->
                    <div class="form-group">
                        <label class="form-label">Sort Order</label>
                        <input type="number" name="sort_order" class="form-input" value="<?= (int)($item['sort_order'] ?? 0) ?>" min="0">
                        <span class="form-hint">Lower numbers appear first</span>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Visibility</label>
                        <div class="toggle-wrapper">
                            <label class="toggle-switch">
                                <input type="checkbox" name="is_active" value="1" <?= ($item['is_active'] ?? 1) ? 'checked' : '' ?>>
                                <span class="toggle-slider"></span>
                            </label>
                            <span class="toggle-label">Publish and make visible on the site</span>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <path d="M19 21H5a2 2 0 01-2-2V5a2 2 0 012-2h11l5 5v11a2 2 0 01-2 2z"/>
                                <polyline points="17 21 17 13 7 13 7 21"/>
                                <polyline points="7 3 7 8 15 8"/>
                            </svg>
                            <?= $isEdit ? 'Save Changes' : 'Create Portfolio' ?>
                        </button>
                        <a href="portfolio.php" class="btn btn-secondary">Cancel</a>
                    </div>

                </div>
            </form>
        </main>
    </div>

</body>
</html>