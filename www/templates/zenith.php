<?php
// تبدیل کد رنگ HEX به RGB برای استفاده در rgba()
function hexToRgb($hex) {
    $hex = ltrim($hex, '#');
    if (strlen($hex) === 3) {
        $r = hexdec($hex[0] . $hex[0]);
        $g = hexdec($hex[1] . $hex[1]);
        $b = hexdec($hex[2] . $hex[2]);
    } else {
        $r = hexdec(substr($hex, 0, 2));
        $g = hexdec(substr($hex, 2, 2));
        $b = hexdec(substr($hex, 4, 2));
    }
    return "$r, $g, $b";
}

$primaryRgb = hexToRgb($colors['primary']);
$secondaryRgb = hexToRgb($colors['secondary']);
?>
<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($siteName) ?></title>
    <meta name="description" content="<?= htmlspecialchars($siteDesc) ?>">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        /* --- Core Variables --- */
        :root {
            --primary: <?= htmlspecialchars($colors['primary']) ?>;
            --secondary: <?= htmlspecialchars($colors['secondary']) ?>;
            --primary-rgb: <?= $primaryRgb ?>;
            --secondary-rgb: <?= $secondaryRgb ?>;
            --font: 'Inter', system-ui, -apple-system, sans-serif;
            --radius: 20px;
            --radius-sm: 12px;
            --transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            --transition-fast: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        }

        /* --- Light Theme --- */
        [data-theme="light"] {
            --bg-base: #f5f7ff;
            --bg-gradient: 
                radial-gradient(at 0% 0%, rgba(var(--primary-rgb), 0.12) 0px, transparent 50%),
                radial-gradient(at 100% 0%, rgba(var(--secondary-rgb), 0.1) 0px, transparent 50%),
                radial-gradient(at 50% 100%, rgba(var(--primary-rgb), 0.08) 0px, transparent 50%);
            --surface: rgba(255, 255, 255, 0.75);
            --surface-hover: rgba(255, 255, 255, 0.95);
            --surface-solid: #ffffff;
            --border: rgba(15, 23, 42, 0.08);
            --border-hover: rgba(var(--primary-rgb), 0.3);
            --text: #0f172a;
            --text-secondary: #334155;
            --text-muted: #64748b;
            --shadow-sm: 0 2px 8px rgba(15, 23, 42, 0.04);
            --shadow-md: 0 8px 24px rgba(15, 23, 42, 0.08);
            --shadow-lg: 0 20px 48px rgba(15, 23, 42, 0.12);
            --glow: 0 0 0 rgba(var(--primary-rgb), 0);
            --card-bg: linear-gradient(135deg, rgba(255,255,255,0.9), rgba(255,255,255,0.7));
        }

        /* --- Dark Theme --- */
        [data-theme="dark"] {
            --bg-base: #08080f;
            --bg-gradient: 
                radial-gradient(at 0% 0%, rgba(var(--primary-rgb), 0.2) 0px, transparent 50%),
                radial-gradient(at 100% 0%, rgba(var(--secondary-rgb), 0.18) 0px, transparent 50%),
                radial-gradient(at 50% 100%, rgba(var(--primary-rgb), 0.12) 0px, transparent 50%);
            --surface: rgba(20, 20, 35, 0.6);
            --surface-hover: rgba(30, 30, 50, 0.85);
            --surface-solid: #14141f;
            --border: rgba(255, 255, 255, 0.08);
            --border-hover: rgba(var(--primary-rgb), 0.5);
            --text: #f1f5f9;
            --text-secondary: #cbd5e1;
            --text-muted: #94a3b8;
            --shadow-sm: 0 2px 8px rgba(0, 0, 0, 0.3);
            --shadow-md: 0 8px 24px rgba(0, 0, 0, 0.4);
            --shadow-lg: 0 20px 48px rgba(0, 0, 0, 0.6);
            --glow: 0 8px 32px rgba(var(--primary-rgb), 0.25);
            --card-bg: linear-gradient(135deg, rgba(30,30,50,0.7), rgba(20,20,35,0.5));
        }

        /* --- Reset & Base --- */
        * { margin: 0; padding: 0; box-sizing: border-box; }
        html { scroll-behavior: smooth; }
        
        body {
            font-family: var(--font);
            background-color: var(--bg-base);
            color: var(--text);
            line-height: 1.7;
            transition: background-color 0.6s ease, color 0.6s ease;
            overflow-x: hidden;
            min-height: 100vh;
            position: relative;
        }

        /* --- Animated Mesh Background --- */
        .mesh-bg {
            position: fixed;
            inset: 0;
            background: var(--bg-gradient);
            z-index: -2;
            transition: background 0.6s ease;
        }

        .mesh-bg::before {
            content: '';
            position: absolute;
            inset: 0;
            background-image: 
                radial-gradient(circle at 20% 30%, rgba(var(--primary-rgb), 0.15) 0%, transparent 40%),
                radial-gradient(circle at 80% 70%, rgba(var(--secondary-rgb), 0.15) 0%, transparent 40%);
            animation: meshMove 20s ease-in-out infinite;
        }

        @keyframes meshMove {
            0%, 100% { transform: translate(0, 0) scale(1); opacity: 0.6; }
            50% { transform: translate(-30px, 30px) scale(1.1); opacity: 1; }
        }

        /* Noise texture for depth */
        body::after {
            content: '';
            position: fixed;
            inset: 0;
            background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 200 200' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='n'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.9'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23n)' opacity='0.4'/%3E%3C/svg%3E");
            opacity: 0.03;
            pointer-events: none;
            z-index: -1;
            mix-blend-mode: overlay;
        }

        /* --- Layout --- */
        .container { max-width: 1200px; margin: 0 auto; padding: 0 24px; }
        
        h1, h2, h3 { line-height: 1.2; font-weight: 800; letter-spacing: -0.02em; }
        
        .section-header { text-align: center; margin-bottom: 60px; }
        .section-header h2 { 
            font-size: clamp(2rem, 5vw, 3rem); 
            margin-bottom: 16px;
            background: linear-gradient(135deg, var(--text) 0%, var(--text-secondary) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        .section-header p { color: var(--text-muted); font-size: 1.1rem; max-width: 600px; margin: 0 auto; }

        /* --- Header --- */
        header { padding: 100px 0 60px; text-align: center; position: relative; }
        header img { 
            max-height: 90px; 
            border-radius: var(--radius); 
            margin-bottom: 24px; 
            box-shadow: var(--shadow-md);
            border: 1px solid var(--border);
            background: var(--surface);
            backdrop-filter: blur(10px);
        }
        header h1 { 
            font-size: clamp(2.5rem, 7vw, 4.5rem); 
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin-bottom: 20px;
            font-weight: 900;
            letter-spacing: -0.03em;
        }
        header p { 
            color: var(--text-muted); 
            font-size: clamp(1rem, 2vw, 1.3rem); 
            max-width: 700px; 
            margin: 0 auto;
            line-height: 1.6;
        }

        /* --- Navigation --- */
        nav {
            position: sticky; 
            top: 20px; 
            z-index: 100;
            margin-bottom: 80px;
        }
        .nav-inner {
            background: var(--surface);
            backdrop-filter: blur(20px) saturate(180%);
            -webkit-backdrop-filter: blur(20px) saturate(180%);
            border: 1px solid var(--border);
            border-radius: 100px;
            padding: 8px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            max-width: 700px;
            margin: 0 auto;
            box-shadow: var(--shadow-md);
        }
        .nav-links { display: flex; list-style: none; gap: 4px; flex: 1; justify-content: center; }
        .nav-links a {
            color: var(--text-muted); 
            text-decoration: none; 
            padding: 10px 20px;
            border-radius: 100px; 
            font-weight: 500; 
            font-size: 0.95rem; 
            transition: var(--transition-fast);
            position: relative;
        }
        .nav-links a:hover { color: var(--text); background: var(--surface-hover); }
        .nav-links a.active { 
            color: var(--primary); 
            background: var(--surface-hover);
            box-shadow: inset 0 0 0 1px var(--border-hover);
        }
        
        .theme-toggle {
            background: var(--surface-hover);
            border: 1px solid var(--border);
            color: var(--text);
            width: 42px; height: 42px; 
            border-radius: 50%; 
            cursor: pointer;
            display: flex; 
            align-items: center; 
            justify-content: center; 
            transition: var(--transition);
            margin-right: 4px;
            position: relative;
            overflow: hidden;
        }
        .theme-toggle:hover { 
            transform: rotate(180deg) scale(1.05);
            border-color: var(--primary);
            color: var(--primary);
            box-shadow: var(--glow);
        }
        .theme-toggle svg { 
            width: 20px; height: 20px; 
            fill: currentColor;
            transition: transform 0.4s ease;
        }

        .mobile-menu-btn { 
            display: none; 
            background: none; 
            border: none; 
            color: var(--text); 
            padding: 10px; 
            cursor: pointer; 
        }
        .mobile-menu-btn svg { width: 24px; height: 24px; fill: currentColor; }

        /* --- About Section --- */
        .about-card {
            background: var(--card-bg);
            backdrop-filter: blur(20px) saturate(180%);
            -webkit-backdrop-filter: blur(20px) saturate(180%);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            padding: clamp(24px, 4vw, 48px);
            box-shadow: var(--shadow-md);
            font-size: 1.1rem;
            color: var(--text-secondary);
            max-width: 900px;
            margin: 0 auto 100px;
            text-align: center;
            line-height: 1.9;
            transition: var(--transition);
        }
        .about-card:hover {
            transform: translateY(-4px);
            box-shadow: var(--shadow-lg);
            border-color: var(--border-hover);
        }

        /* --- Portfolio Grid --- */
        .portfolio-grid {
            display: grid; 
            grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
            gap: 28px; 
            margin-bottom: 100px;
        }
        .portfolio-card {
            background: var(--card-bg);
            backdrop-filter: blur(20px) saturate(180%);
            -webkit-backdrop-filter: blur(20px) saturate(180%);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            overflow: hidden;
            box-shadow: var(--shadow-sm);
            transition: var(--transition);
            cursor: pointer;
            position: relative;
        }
        .portfolio-card::before {
            content: '';
            position: absolute;
            inset: 0;
            border-radius: var(--radius);
            padding: 1px;
            background: linear-gradient(135deg, transparent, rgba(var(--primary-rgb), 0.3), transparent);
            -webkit-mask: linear-gradient(#fff 0 0) content-box, linear-gradient(#fff 0 0);
            mask: linear-gradient(#fff 0 0) content-box, linear-gradient(#fff 0 0);
            -webkit-mask-composite: xor;
            mask-composite: exclude;
            opacity: 0;
            transition: var(--transition);
            pointer-events: none;
        }
        .portfolio-card:hover { 
            transform: translateY(-8px); 
            box-shadow: var(--shadow-lg);
        }
        .portfolio-card:hover::before { opacity: 1; }
        
        .card-media { 
            position: relative; 
            aspect-ratio: 16/10; 
            overflow: hidden; 
            background: var(--surface-solid);
        }
        .card-media img { 
            width: 100%; 
            height: 100%; 
            object-fit: cover; 
            transition: transform 0.7s cubic-bezier(0.4, 0, 0.2, 1); 
        }
        .portfolio-card:hover .card-media img { transform: scale(1.08); }
        
        .card-overlay {
            position: absolute; 
            inset: 0; 
            background: linear-gradient(135deg, rgba(var(--primary-rgb), 0.85), rgba(var(--secondary-rgb), 0.85));
            backdrop-filter: blur(4px);
            display: flex; 
            align-items: center; 
            justify-content: center; 
            opacity: 0; 
            transition: var(--transition);
        }
        .portfolio-card:hover .card-overlay { opacity: 1; }
        .card-overlay svg { 
            width: 56px; height: 56px; 
            fill: #fff; 
            transform: scale(0.7); 
            transition: var(--transition);
            filter: drop-shadow(0 4px 12px rgba(0,0,0,0.3));
        }
        .portfolio-card:hover .card-overlay svg { transform: scale(1); }

        .card-content { padding: 24px; }
        .card-content .category {
            display: inline-block; 
            font-size: 0.7rem; 
            font-weight: 700; 
            text-transform: uppercase;
            letter-spacing: 0.1em; 
            color: var(--primary);
            background: rgba(var(--primary-rgb), 0.1);
            padding: 4px 12px;
            border-radius: 100px;
            margin-bottom: 12px;
            border: 1px solid rgba(var(--primary-rgb), 0.2);
        }
        .card-content h3 { 
            font-size: 1.25rem; 
            margin-bottom: 10px; 
            color: var(--text);
            font-weight: 700;
        }
        .card-content p { 
            font-size: 0.95rem; 
            color: var(--text-muted); 
            display: -webkit-box; 
            -webkit-line-clamp: 2; 
            -webkit-box-orient: vertical; 
            overflow: hidden;
            line-height: 1.6;
        }

        /* --- Advanced Floating Modal --- */
        .modal-backdrop {
            position: fixed; 
            inset: 0; 
            background: rgba(0, 0, 0, 0.7);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            z-index: 1000; 
            display: none; 
            align-items: center; 
            justify-content: center; 
            padding: 24px;
            opacity: 0; 
            transition: opacity 0.3s ease;
        }
        .modal-backdrop.active { display: flex; opacity: 1; }
        
        .modal-window {
            width: 100%; 
            max-width: 950px; 
            max-height: 92vh;
            background: var(--card-bg);
            backdrop-filter: blur(30px) saturate(180%);
            -webkit-backdrop-filter: blur(30px) saturate(180%);
            border: 1px solid var(--border);
            border-radius: 28px;
            box-shadow: var(--shadow-lg), 0 0 60px rgba(var(--primary-rgb), 0.15);
            overflow: hidden; 
            display: flex; 
            flex-direction: column;
            transform: scale(0.92) translateY(30px); 
            transition: transform 0.5s cubic-bezier(0.34, 1.56, 0.64, 1);
            position: relative;
        }
        .modal-window::before {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0;
            height: 1px;
            background: linear-gradient(90deg, transparent, rgba(var(--primary-rgb), 0.5), transparent);
        }
        .modal-backdrop.active .modal-window { transform: scale(1) translateY(0); }

        .modal-media-container {
            width: 100%; 
            aspect-ratio: 16/9; 
            background: #000; 
            position: relative;
            border-bottom: 1px solid var(--border);
        }
        .modal-media-container img, 
        .modal-media-container video, 
        .modal-media-container iframe {
            width: 100%; 
            height: 100%; 
            object-fit: contain; 
            display: block;
        }

        .modal-close {
            position: absolute; 
            top: 16px; 
            right: 16px; 
            width: 44px; 
            height: 44px;
            background: rgba(0,0,0,0.6);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255,255,255,0.2);
            border-radius: 50%;
            color: #fff; 
            cursor: pointer; 
            display: flex; 
            align-items: center; 
            justify-content: center;
            transition: var(--transition); 
            z-index: 10;
        }
        .modal-close:hover { 
            background: #ef4444; 
            border-color: #ef4444; 
            transform: rotate(90deg) scale(1.1);
            box-shadow: 0 0 20px rgba(239, 68, 68, 0.5);
        }
        .modal-close svg { width: 20px; height: 20px; fill: currentColor; }

        .modal-body { 
            padding: clamp(24px, 4vw, 40px); 
            overflow-y: auto; 
        }
        .modal-body h2 { 
            font-size: clamp(1.5rem, 3vw, 2rem); 
            margin-bottom: 12px; 
            color: var(--text);
            background: linear-gradient(135deg, var(--text), var(--primary));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        .modal-meta { 
            display: flex; 
            gap: 12px; 
            margin-bottom: 24px; 
            flex-wrap: wrap;
            align-items: center;
        }
        .modal-meta span { 
            font-size: 0.85rem; 
            color: var(--text-muted); 
            display: flex; 
            align-items: center; 
            gap: 6px;
        }
        .modal-meta svg { width: 16px; height: 16px; fill: var(--primary); }
        .modal-meta .tag {
            background: rgba(var(--primary-rgb), 0.1);
            border: 1px solid rgba(var(--primary-rgb), 0.2);
            padding: 4px 12px;
            border-radius: 100px;
            font-size: 0.8rem;
            color: var(--primary);
            font-weight: 500;
        }
        .modal-desc { 
            font-size: 1.05rem; 
            color: var(--text-secondary); 
            line-height: 1.8; 
            margin-bottom: 32px; 
            white-space: pre-line;
        }
        
        .modal-actions { 
            display: flex; 
            gap: 12px; 
            flex-wrap: wrap;
        }
        .btn {
            display: inline-flex; 
            align-items: center; 
            gap: 10px; 
            padding: 12px 24px;
            border-radius: var(--radius-sm);
            font-weight: 600; 
            text-decoration: none; 
            font-size: 0.95rem;
            transition: var(--transition);
            border: 1px solid transparent;
            cursor: pointer;
        }
        .btn svg { width: 18px; height: 18px; fill: currentColor; }
        .btn-primary { 
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            color: #fff;
            box-shadow: 0 4px 16px rgba(var(--primary-rgb), 0.3);
        }
        .btn-primary:hover { 
            transform: translateY(-2px); 
            box-shadow: 0 8px 24px rgba(var(--primary-rgb), 0.5);
        }
        .btn-secondary { 
            background: var(--surface);
            backdrop-filter: blur(10px);
            color: var(--text);
            border-color: var(--border);
        }
        .btn-secondary:hover { 
            background: var(--surface-hover);
            border-color: var(--primary);
            color: var(--primary);
            transform: translateY(-2px);
        }

        /* --- Footer --- */
        footer { 
            border-top: 1px solid var(--border); 
            padding: 80px 0 40px; 
            text-align: center;
            position: relative;
            margin-top: 60px;
        }
        footer::before {
            content: '';
            position: absolute;
            top: 0; left: 50%;
            transform: translateX(-50%);
            width: 200px; height: 1px;
            background: linear-gradient(90deg, transparent, var(--primary), transparent);
        }
        .contact-grid { 
            display: flex; 
            justify-content: center; 
            gap: 14px; 
            flex-wrap: wrap; 
            margin-bottom: 40px;
        }
        .contact-link {
            display: inline-flex; 
            align-items: center; 
            gap: 10px; 
            padding: 12px 24px;
            background: var(--surface);
            backdrop-filter: blur(10px);
            border: 1px solid var(--border);
            border-radius: 100px;
            color: var(--text); 
            text-decoration: none; 
            font-weight: 500; 
            transition: var(--transition);
            font-size: 0.95rem;
        }
        .contact-link:hover { 
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            color: #fff;
            border-color: transparent;
            transform: translateY(-3px);
            box-shadow: 0 8px 24px rgba(var(--primary-rgb), 0.4);
        }
        .contact-link svg { width: 18px; height: 18px; fill: currentColor; }
        .copyright { color: var(--text-muted); font-size: 0.9rem; }

        /* --- Responsive --- */
        @media (max-width: 768px) {
            header { padding: 60px 0 40px; }
            .mobile-menu-btn { display: block; }
            .nav-links {
                display: none; 
                position: absolute; 
                top: 70px; 
                left: 0; right: 0;
                background: var(--surface);
                backdrop-filter: blur(20px);
                border: 1px solid var(--border);
                flex-direction: column; 
                padding: 16px; 
                border-radius: var(--radius); 
                box-shadow: var(--shadow-lg);
                gap: 4px;
            }
            .nav-links.active { display: flex; }
            .nav-links a { width: 100%; text-align: center; padding: 12px; }
            .portfolio-grid { grid-template-columns: 1fr; gap: 20px; }
            .modal-window { max-height: 95vh; border-radius: 20px; }
            .modal-body { padding: 24px; }
            .modal-actions { flex-direction: column; }
            .btn { width: 100%; justify-content: center; }
            .contact-grid { flex-direction: column; align-items: center; }
            .contact-link { width: 100%; max-width: 320px; justify-content: center; }
        }

        /* --- Scroll Animations --- */
        .reveal { 
            opacity: 0; 
            transform: translateY(30px); 
            transition: all 0.8s cubic-bezier(0.4, 0, 0.2, 1); 
        }
        .reveal.active { opacity: 1; transform: translateY(0); }

        /* --- Custom Scrollbar --- */
        ::-webkit-scrollbar { width: 10px; }
        ::-webkit-scrollbar-track { background: var(--bg-base); }
        ::-webkit-scrollbar-thumb { 
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            border-radius: 10px;
        }
        ::-webkit-scrollbar-thumb:hover { opacity: 0.8; }
    </style>
</head>
<body>

    <!-- Animated Mesh Background -->
    <div class="mesh-bg"></div>

    <!-- Header -->
    <header id="home" class="reveal">
        <div class="container">
            <?php if (!empty($logo) && file_exists(__DIR__ . '/../' . $logo)): ?>
                <img src="<?= '../' . htmlspecialchars($logo) ?>" alt="Logo">
            <?php elseif (!empty($logo) && file_exists(__DIR__ . '/' . $logo)): ?>
                <img src="<?= htmlspecialchars($logo) ?>" alt="Logo">
            <?php endif; ?>
            <h1><?= htmlspecialchars($siteName) ?></h1>
            <p><?= htmlspecialchars($siteDesc) ?></p>
        </div>
    </header>

    <!-- Navigation -->
    <nav class="container reveal">
        <div class="nav-inner">
            <button class="mobile-menu-btn" id="menuToggle" aria-label="Menu">
                <svg viewBox="0 0 24 24"><path d="M3 6h18v2H3V6m0 5h18v2H3v-2m0 5h18v2H3v-2z"/></svg>
            </button>
            <ul class="nav-links" id="navLinks">
                <li><a href="#home">Home</a></li>
                <li><a href="#about">About</a></li>
                <li><a href="#portfolio">Portfolio</a></li>
                <li><a href="#contact">Contact</a></li>
            </ul>
            <button class="theme-toggle" id="themeToggle" aria-label="Toggle Theme">
                <svg class="sun-icon" viewBox="0 0 24 24" style="display: none;">
                    <path d="M12 7c-2.76 0-5 2.24-5 5s2.24 5 5 5 5-2.24 5-5-2.24-5-5-5zM2 13h2c.55 0 1-.45 1-1s-.45-1-1-1H2c-.55 0-1 .45-1 1s.45 1 1 1zm18 0h2c.55 0 1-.45 1-1s-.45-1-1-1h-2c-.55 0-1 .45-1 1s.45 1 1 1zM11 2v2c0 .55.45 1 1 1s1-.45 1-1V2c0-.55-.45-1-1-1s-1 .45-1 1zm0 18v2c0 .55.45 1 1 1s1-.45 1-1v-2c0-.55-.45-1-1-1s-1 .45-1 1zM5.99 4.58a.996.996 0 00-1.41 0 .996.996 0 000 1.41l1.06 1.06c.39.39 1.03.39 1.41 0s.39-1.03 0-1.41L5.99 4.58zm12.37 12.37a.996.996 0 00-1.41 0 .996.996 0 000 1.41l1.06 1.06c.39.39 1.03.39 1.41 0a.996.996 0 000-1.41l-1.06-1.06zm1.06-10.96a.996.996 0 000-1.41.996.996 0 00-1.41 0l-1.06 1.06c-.39.39-.39 1.03 0 1.41s1.03.39 1.41 0l1.06-1.06zM7.05 18.36a.996.996 0 000 1.41.996.996 0 001.41 0l1.06-1.06c.39-.39.39-1.03 0-1.41s-1.03-.39-1.41 0l-1.06 1.06z"/>
                </svg>
                <svg class="moon-icon" viewBox="0 0 24 24">
                    <path d="M12 3c-4.97 0-9 4.03-9 9s4.03 9 9 9 9-4.03 9-9c0-.46-.04-.92-.1-1.36-.98 1.37-2.58 2.26-4.4 2.26-3.03 0-5.5-2.47-5.5-5.5 0-1.82.89-3.42 2.26-4.4-.44-.06-.9-.1-1.36-.1z"/>
                </svg>
            </button>
        </div>
    </nav><br><br>

    <main class="container">
        <!-- About Section -->
        <section id="about" class="reveal">
            <div class="section-header">
                <h2>About Me</h2>
                <p><?= nl2br(htmlspecialchars($aboutMe)) ?></p>
            </div>
        </section>

        <!-- Portfolio Section -->
        <section id="portfolio" class="reveal">
            <div class="section-header">
                <h2>Selected Works</h2>
                <p>A curated collection of projects that showcase my craft.</p>
            </div>
            
            <?php if (empty($portfolios)): ?>
                <div class="about-card">No projects have been added yet.</div>
            <?php else: ?>
                <div class="portfolio-grid">
                    <?php foreach ($portfolios as $index => $p): ?>
                        <article class="portfolio-card" data-index="<?= $index ?>">
                            <div class="card-media">
                                <img src="<?= htmlspecialchars($p['image']) ?>" alt="<?= htmlspecialchars($p['title']) ?>" loading="lazy">
                                <div class="card-overlay">
                                    <svg viewBox="0 0 24 24"><path d="M12 4.5C7 4.5 2.73 7.61 1 12c1.73 4.39 6 7.5 11 7.5s9.27-3.11 11-7.5c-1.73-4.39-6-7.5-11-7.5zM12 17c-2.76 0-5-2.24-5-5s2.24-5 5-5 5 2.24 5 5-2.24 5-5 5zm0-8c-1.66 0-3 1.34-3 3s1.34 3 3 3 3-1.34 3-3-1.34-3-3-3z"/></svg>
                                </div>
                            </div>
                            <div class="card-content">
                                <?php if (!empty($p['category'])): ?>
                                    <span class="category"><?= htmlspecialchars($p['category']) ?></span>
                                <?php endif; ?>
                                <h3><?= htmlspecialchars($p['title']) ?></h3>
                                <p><?= htmlspecialchars($p['description']) ?></p>
                            </div>
                        </article>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </section>
    </main>

    <!-- Footer -->
    <footer id="contact" class="reveal">
        <div class="container">
            <div class="section-header" style="margin-bottom: 40px;">
                <h2>Get In Touch</h2>
                <p>Let's create something extraordinary together.</p>
            </div>
            <div class="contact-grid">
                <?php foreach ($contacts as $c): 
                    $link = '#'; $target = '';
                    $val = htmlspecialchars($c['value']);
                    $label = htmlspecialchars($c['label'] ?: $c['value']);
                    
                    switch ($c['type']) {
                        case 'email': $link = 'mailto:' . $val; break;
                        case 'phone': $link = 'tel:' . $val; break;
                        case 'instagram': $link = 'https://instagram.com/' . ltrim($val, '@'); $target = '_blank'; break;
                        case 'telegram': $link = 'https://t.me/' . ltrim($val, '@'); $target = '_blank'; break;
                        case 'whatsapp': $link = 'https://wa.me/' . preg_replace('/\D/', '', $val); $target = '_blank'; break;
                        case 'linkedin': $link = 'https://linkedin.com/in/' . ltrim($val, '@'); $target = '_blank'; break;
                        case 'github': $link = 'https://github.com/' . ltrim($val, '@'); $target = '_blank'; break;
                        default: $link = $val; $target = '_blank';
                    }
                    
                    $icon = '<svg viewBox="0 0 24 24"><path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/></svg>';
                    if ($c['type'] === 'email') $icon = '<svg viewBox="0 0 24 24"><path d="M20 4H4c-1.1 0-1.99.9-1.99 2L2 18c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4l-8 5-8-5V6l8 5 8-5v2z"/></svg>';
                    elseif ($c['type'] === 'phone') $icon = '<svg viewBox="0 0 24 24"><path d="M6.62 10.79c1.44 2.83 3.76 5.14 6.59 6.59l2.2-2.2c.27-.27.67-.36 1.02-.24 1.12.37 2.33.57 3.57.57.55 0 1 .45 1 1V20c0 .55-.45 1-1 1-9.39 0-17-7.61-17-17 0-.55.45-1 1-1h3.5c.55 0 1 .45 1 1 0 1.25.2 2.45.57 3.57.11.35.03.74-.25 1.02l-2.2 2.2z"/></svg>';
                    elseif ($c['type'] === 'instagram') $icon = '<svg viewBox="0 0 24 24"><path d="M7.8 2h8.4C19.4 2 22 4.6 22 7.8v8.4a5.8 5.8 0 0 1-5.8 5.8H7.8C4.6 22 2 19.4 2 16.2V7.8A5.8 5.8 0 0 1 7.8 2m-.2 2A3.6 3.6 0 0 0 4 7.6v8.8C4 18.39 5.61 20 7.6 20h8.8a3.6 3.6 0 0 0 3.6-3.6V7.6C20 5.61 18.39 4 16.4 4H7.6m9.65 1.5a1.25 1.25 0 0 1 1.25 1.25A1.25 1.25 0 0 1 17.25 8 1.25 1.25 0 0 1 16 6.75a1.25 1.25 0 0 1 1.25-1.25M12 7a5 5 0 0 1 5 5 5 5 0 0 1-5 5 5 5 0 0 1-5-5 5 5 0 0 1 5-5m0 2a3 3 0 0 0-3 3 3 3 0 0 0 3 3 3 3 0 0 0 3-3 3 3 0 0 0-3-3z"/></svg>';
                    elseif ($c['type'] === 'telegram') $icon = '<svg viewBox="0 0 24 24"><path d="M9.78 18.65l.28-4.23 7.68-6.92c.34-.31-.07-.46-.52-.19L7.74 13.3 3.64 12c-.88-.25-.89-.86.2-1.3l15.97-6.16c.73-.33 1.43.18 1.15 1.3l-2.72 12.81c-.19.91-.74 1.13-1.5.71L12.6 16.3l-1.99 1.93c-.23.23-.42.42-.83.42z"/></svg>';
                    elseif ($c['type'] === 'whatsapp') $icon = '<svg viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/></svg>';
                    elseif ($c['type'] === 'linkedin') $icon = '<svg viewBox="0 0 24 24"><path d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.762 0 5-2.239 5-5v-14c0-2.761-2.238-5-5-5zm-11 19h-3v-11h3v11zm-1.5-12.268c-.966 0-1.75-.79-1.75-1.764s.784-1.764 1.75-1.764 1.75.79 1.75 1.764-.783 1.764-1.75 1.764zm13.5 12.268h-3v-5.604c0-3.368-4-3.113-4 0v5.604h-3v-11h3v1.765c1.396-2.586 7-2.777 7 2.476v6.759z"/></svg>';
                    elseif ($c['type'] === 'github') $icon = '<svg viewBox="0 0 24 24"><path d="M12 0c-6.626 0-12 5.373-12 12 0 5.302 3.438 9.8 8.207 11.387.599.111.793-.261.793-.577v-2.234c-3.338.726-4.033-1.416-4.033-1.416-.546-1.387-1.333-1.756-1.333-1.756-1.089-.745.083-.729.083-.729 1.205.084 1.839 1.237 1.839 1.237 1.07 1.834 2.807 1.304 3.492.997.107-.775.418-1.305.762-1.604-2.665-.305-5.467-1.334-5.467-5.931 0-1.311.469-2.381 1.236-3.221-.124-.303-.535-1.524.117-3.176 0 0 1.008-.322 3.301 1.23.957-.266 1.983-.399 3.003-.404 1.02.005 2.047.138 3.006.404 2.291-1.552 3.297-1.23 3.297-1.23.653 1.653.242 2.874.118 3.176.77.84 1.235 1.911 1.235 3.221 0 4.609-2.807 5.624-5.479 5.921.43.372.823 1.102.823 2.222v3.293c0 .319.192.694.801.576 4.765-1.589 8.199-6.086 8.199-11.386 0-6.627-5.373-12-12-12z"/></svg>';
                ?>
                    <a href="<?= $link ?>" class="contact-link" target="<?= $target ?>">
                        <?= $icon ?>
                        <span><?= $label ?></span>
                    </a>
                <?php endforeach; ?>
            </div>
            <p class="copyright"><?= htmlspecialchars($footerText ?: '© ' . date('Y') . ' All rights reserved.') ?></p>
        </div>
    </footer>

    <!-- Advanced Floating Modal -->
    <div class="modal-backdrop" id="projectModal">
        <div class="modal-window">
            <button class="modal-close" id="modalClose" aria-label="Close">
                <svg viewBox="0 0 24 24"><path d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12z"/></svg>
            </button>
            
            <div class="modal-media-container" id="modalMedia"></div>
            
            <div class="modal-body">
                <h2 id="modalTitle"></h2>
                <div class="modal-meta" id="modalMeta"></div>
                <div class="modal-desc" id="modalDesc"></div>
                <div class="modal-actions" id="modalActions"></div>
            </div>
        </div>
    </div>

    <script>
        // --- Theme Toggle Logic ---
        const themeToggle = document.getElementById('themeToggle');
        const html = document.documentElement;
        const sunIcon = document.querySelector('.sun-icon');
        const moonIcon = document.querySelector('.moon-icon');

        function updateIcons(theme) {
            if (theme === 'dark') {
                sunIcon.style.display = 'block';
                moonIcon.style.display = 'none';
            } else {
                sunIcon.style.display = 'none';
                moonIcon.style.display = 'block';
            }
        }

        // Initialize theme: check localStorage first, then system preference, default to light
        function initTheme() {
            const savedTheme = localStorage.getItem('zenith-theme');
            if (savedTheme) {
                html.setAttribute('data-theme', savedTheme);
            } else if (window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches) {
                html.setAttribute('data-theme', 'dark');
            } else {
                html.setAttribute('data-theme', 'light');
            }
            updateIcons(html.getAttribute('data-theme'));
        }
        initTheme();

        themeToggle.addEventListener('click', () => {
            const currentTheme = html.getAttribute('data-theme');
            const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
            html.setAttribute('data-theme', newTheme);
            localStorage.setItem('zenith-theme', newTheme);
            updateIcons(newTheme);
        });

        // Listen for system theme changes
        window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', (e) => {
            if (!localStorage.getItem('zenith-theme')) {
                html.setAttribute('data-theme', e.matches ? 'dark' : 'light');
                updateIcons(html.getAttribute('data-theme'));
            }
        });

        // --- Mobile Menu ---
        const menuToggle = document.getElementById('menuToggle');
        const navLinks = document.getElementById('navLinks');
        menuToggle.addEventListener('click', () => navLinks.classList.toggle('active'));
        document.querySelectorAll('.nav-links a').forEach(link => {
            link.addEventListener('click', () => navLinks.classList.remove('active'));
        });

        // --- Scroll Reveal ---
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) entry.target.classList.add('active');
            });
        }, { threshold: 0.1 });
        document.querySelectorAll('.reveal').forEach(el => observer.observe(el));

        // --- Smart Modal & Media Player ---
        const modal = document.getElementById('projectModal');
        const modalClose = document.getElementById('modalClose');
        const modalMedia = document.getElementById('modalMedia');
        const modalTitle = document.getElementById('modalTitle');
        const modalMeta = document.getElementById('modalMeta');
        const modalDesc = document.getElementById('modalDesc');
        const modalActions = document.getElementById('modalActions');

        const portfolios = <?= json_encode($portfolios, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) ?>;

        const icons = {
            category: '<svg viewBox="0 0 24 24"><path d="M12 2l-5.5 9h11z"/><path d="M17.5 13c-2.49 0-4.5 2.01-4.5 4.5s2.01 4.5 4.5 4.5 4.5-2.01 4.5-4.5-2.01-4.5-4.5-4.5z"/><path d="M5 21.5h8v-8H5v8z"/></svg>',
            link: '<svg viewBox="0 0 24 24"><path d="M3.9 12c0-1.71 1.39-3.1 3.1-3.1h4V7H7c-2.76 0-5 2.24-5 5s2.24 5 5 5h4v-1.9H7c-1.71 0-3.1-1.39-3.1-3.1zM8 13h8v-2H8v2zm9-6h-4v1.9h4c1.71 0 3.1 1.39 3.1 3.1s-1.39 3.1-3.1 3.1h-4V17h4c2.76 0 5-2.24 5-5s-2.24-5-5-5z"/></svg>',
            github: '<svg viewBox="0 0 24 24"><path d="M12 0c-6.626 0-12 5.373-12 12 0 5.302 3.438 9.8 8.207 11.387.599.111.793-.261.793-.577v-2.234c-3.338.726-4.033-1.416-4.033-1.416-.546-1.387-1.333-1.756-1.333-1.756-1.089-.745.083-.729.083-.729 1.205.084 1.839 1.237 1.839 1.237 1.07 1.834 2.807 1.304 3.492.997.107-.775.418-1.305.762-1.604-2.665-.305-5.467-1.334-5.467-5.931 0-1.311.469-2.381 1.236-3.221-.124-.303-.535-1.524.117-3.176 0 0 1.008-.322 3.301 1.23.957-.266 1.983-.399 3.003-.404 1.02.005 2.047.138 3.006.404 2.291-1.552 3.297-1.23 3.297-1.23.653 1.653.242 2.874.118 3.176.77.84 1.235 1.911 1.235 3.221 0 4.609-2.807 5.624-5.479 5.921.43.372.823 1.102.823 2.222v3.293c0 .319.192.694.801.576 4.765-1.589 8.199-6.086 8.199-11.386 0-6.627-5.373-12-12-12z"/></svg>',
            demo: '<svg viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>',
            download: '<svg viewBox="0 0 24 24"><path d="M19 9h-4V3H9v6H5l7 7 7-7zM5 18v2h14v-2H5z"/></svg>'
        };

        document.querySelectorAll('.portfolio-card').forEach(card => {
            card.addEventListener('click', () => {
                const p = portfolios[card.dataset.index];
                if (!p) return;

                // Smart Media Detection
                let mediaHTML = '';
                if (p.video) {
                    if (p.video.includes('youtube.com') || p.video.includes('youtu.be')) {
                        const videoId = p.video.match(/(?:v=|\/)([0-9A-Za-z_-]{11}).*/)?.[1] || '';
                        mediaHTML = `<iframe src="https://www.youtube.com/embed/${videoId}?rel=0&modestbranding=1" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>`;
                    } else if (p.video.includes('vimeo.com')) {
                        const vimeoId = p.video.match(/vimeo\.com\/(\d+)/)?.[1] || '';
                        mediaHTML = `<iframe src="https://player.vimeo.com/video/${vimeoId}?title=0&byline=0&portrait=0" frameborder="0" allow="autoplay; fullscreen" allowfullscreen></iframe>`;
                    } else if (p.video.includes('aparat.com')) {
                        const aparatId = p.video.match(/\/video\/([a-zA-Z0-9]+)/)?.[1] || '';
                        mediaHTML = `<iframe src="https://www.aparat.com/video/video/embed/videohash/${aparatId}/vt/frame" allowFullScreen="true"></iframe>`;
                    } else {
                        mediaHTML = `<video src="${p.video}" controls autoplay muted loop playsinline></video>`;
                    }
                } else if (p.image) {
                    mediaHTML = `<img src="${p.image}" alt="${p.title}">`;
                }
                modalMedia.innerHTML = mediaHTML;

                // Populate Text
                modalTitle.textContent = p.title;
                modalDesc.textContent = p.description;
                
                let metaHTML = '';
                if (p.category) metaHTML += `<span>${icons.category} ${p.category}</span>`;
                if (p.tags) {
                    const tags = p.tags.split(',').map(t => `<span class="tag">${t.trim()}</span>`).join(' ');
                    metaHTML += `<span style="display:flex; gap:6px; flex-wrap:wrap;">${tags}</span>`;
                }
                modalMeta.innerHTML = metaHTML;

                // Populate Actions
                let actionsHTML = '';
                if (p.link) actionsHTML += `<a href="${p.link}" target="_blank" class="btn btn-primary">${icons.link} View Project</a>`;
                if (p.demo) actionsHTML += `<a href="${p.demo}" target="_blank" class="btn btn-secondary">${icons.demo} Live Demo</a>`;
                if (p.github) actionsHTML += `<a href="${p.github}" target="_blank" class="btn btn-secondary">${icons.github} Source Code</a>`;
                if (p.image) actionsHTML += `<a href="${p.image}" download class="btn btn-secondary">${icons.download} Download Image</a>`;
                
                modalActions.innerHTML = actionsHTML;

                modal.classList.add('active');
                document.body.style.overflow = 'hidden';
            });
        });

        function closeModal() {
            modal.classList.remove('active');
            document.body.style.overflow = '';
            setTimeout(() => { modalMedia.innerHTML = ''; }, 400);
        }

        modalClose.addEventListener('click', closeModal);
        modal.addEventListener('click', (e) => { if (e.target === modal) closeModal(); });
        document.addEventListener('keydown', (e) => { if (e.key === 'Escape' && modal.classList.contains('active')) closeModal(); });
    </script>
</body>
</html>