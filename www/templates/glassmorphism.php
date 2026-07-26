<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($siteName) ?></title>
    <meta name="description" content="<?= htmlspecialchars($siteDesc) ?>">
    <link href="https://cdn.jsdelivr.net/gh/rastikerdar/vazirmatn@v33.003/Vazirmatn-font-face.css" rel="stylesheet">
    <style>
        :root {
            --primary: <?= htmlspecialchars($colors['primary']) ?>;
            --secondary: <?= htmlspecialchars($colors['secondary']) ?>;
            --bg: <?= htmlspecialchars($colors['bg']) ?>;
            --text: <?= htmlspecialchars($colors['text']) ?>;
            --font: '<?= htmlspecialchars($colors['font']) ?>', Tahoma, sans-serif;
            
            /* Premium Dark Glass Variables */
            --glass-bg: rgba(255, 255, 255, 0.03);
            --glass-bg-hover: rgba(255, 255, 255, 0.08);
            --glass-border: rgba(255, 255, 255, 0.1);
            --glass-border-hover: rgba(255, 255, 255, 0.25);
            --glass-shadow: 0 8px 32px 0 rgba(0, 0, 0, 0.3);
            --glass-blur: blur(20px);
            --transition: all 0.4s cubic-bezier(0.25, 0.46, 0.45, 0.94);
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }
        html { scroll-behavior: smooth; }
        
        body {
            font-family: var(--font);
            background-color: #0f172a; /* Fallback */
            background-image: 
                radial-gradient(at 0% 0%, rgba(99, 102, 241, 0.15) 0px, transparent 50%),
                radial-gradient(at 100% 0%, rgba(139, 92, 246, 0.15) 0px, transparent 50%),
                radial-gradient(at 100% 100%, rgba(236, 72, 153, 0.15) 0px, transparent 50%),
                radial-gradient(at 0% 100%, rgba(59, 130, 246, 0.15) 0px, transparent 50%);
            background-attachment: fixed;
            color: #e2e8f0;
            line-height: 1.8;
            overflow-x: hidden;
        }

        /* Animated Mesh Background */
        .mesh-bg {
            position: fixed;
            top: 0; left: 0; width: 100%; height: 100%;
            z-index: -1;
            overflow: hidden;
        }
        .mesh-orb {
            position: absolute;
            border-radius: 50%;
            filter: blur(80px);
            opacity: 0.4;
            animation: float 25s infinite ease-in-out;
        }
        .mesh-orb:nth-child(1) { width: 500px; height: 500px; background: var(--primary); top: -10%; right: -10%; animation-delay: 0s; }
        .mesh-orb:nth-child(2) { width: 400px; height: 400px; background: var(--secondary); bottom: -10%; left: -10%; animation-delay: -5s; }
        .mesh-orb:nth-child(3) { width: 300px; height: 300px; background: #ec4899; top: 40%; left: 40%; animation-delay: -10s; opacity: 0.2; }

        @keyframes float {
            0%, 100% { transform: translate(0, 0) scale(1); }
            33% { transform: translate(30px, -50px) scale(1.1); }
            66% { transform: translate(-30px, 30px) scale(0.9); }
        }

        /* Utility: Glass Card */
        .glass {
            background: var(--glass-bg);
            backdrop-filter: var(--glass-blur);
            -webkit-backdrop-filter: var(--glass-blur);
            border: 1px solid var(--glass-border);
            border-radius: 24px;
            box-shadow: var(--glass-shadow);
            transition: var(--transition);
        }
        .glass:hover {
            background: var(--glass-bg-hover);
            border-color: var(--glass-border-hover);
            transform: translateY(-4px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.4);
        }

        /* Header */
        header {
            text-align: center;
            padding: 60px 20px 40px;
            position: relative;
        }
        header img {
            max-height: 90px;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.3);
            margin-bottom: 20px;
            border: 1px solid var(--glass-border);
        }
        header h1 {
            font-size: 3rem;
            font-weight: 900;
            background: linear-gradient(135deg, #fff 0%, rgba(255,255,255,0.7) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            margin-bottom: 10px;
        }
        header p {
            font-size: 1.2rem;
            color: rgba(255,255,255,0.7);
            max-width: 600px;
            margin: 0 auto;
        }

        /* Navigation */
        nav {
            position: sticky;
            top: 20px;
            z-index: 100;
            margin: 0 auto 60px;
            max-width: 90%;
            padding: 0 10px;
        }
        .nav-inner {
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 10px;
        }
        .nav-links {
            display: flex;
            list-style: none;
            gap: 10px;
        }
        .nav-links a {
            color: rgba(255,255,255,0.8);
            text-decoration: none;
            padding: 12px 24px;
            border-radius: 16px;
            font-weight: 600;
            transition: var(--transition);
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .nav-links a:hover, .nav-links a.active {
            background: rgba(255,255,255,0.1);
            color: #fff;
        }
        .mobile-toggle {
            display: none;
            background: none;
            border: none;
            color: #fff;
            cursor: pointer;
            padding: 10px;
        }
        .mobile-toggle svg { width: 28px; height: 28px; fill: currentColor; }

        /* Container */
        .container { max-width: 1200px; margin: 0 auto; padding: 0 20px 80px; }

        /* Section Titles */
        .section-title {
            font-size: 2.5rem;
            font-weight: 800;
            text-align: center;
            margin-bottom: 50px;
            position: relative;
            display: inline-block;
            width: 100%;
        }
        .section-title span {
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        /* About */
        .about-content {
            padding: 40px;
            font-size: 1.1rem;
            line-height: 2;
            text-align: justify;
            color: rgba(255,255,255,0.85);
        }

        /* Portfolio Grid */
        .portfolio-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(340px, 1fr));
            gap: 30px;
        }
        .portfolio-card {
            cursor: pointer;
            overflow: hidden;
            position: relative;
            display: flex;
            flex-direction: column;
        }
        .portfolio-thumb {
            position: relative;
            height: 240px;
            overflow: hidden;
        }
        .portfolio-thumb img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.6s ease;
        }
        .portfolio-card:hover .portfolio-thumb img { transform: scale(1.1); }
        
        .play-overlay {
            position: absolute;
            inset: 0;
            background: rgba(0,0,0,0.4);
            display: flex;
            align-items: center;
            justify-content: center;
            opacity: 0;
            transition: var(--transition);
        }
        .portfolio-card:hover .play-overlay { opacity: 1; }
        .play-overlay svg {
            width: 60px;
            height: 60px;
            fill: #fff;
            filter: drop-shadow(0 4px 10px rgba(0,0,0,0.5));
            transform: scale(0.8);
            transition: var(--transition);
        }
        .portfolio-card:hover .play-overlay svg { transform: scale(1); }

        .portfolio-info { padding: 25px; flex-grow: 1; display: flex; flex-direction: column; }
        .portfolio-info h3 { font-size: 1.4rem; margin-bottom: 8px; color: #fff; }
        .portfolio-info .category {
            display: inline-block;
            font-size: 0.8rem;
            color: var(--primary);
            background: rgba(255,255,255,0.05);
            padding: 4px 12px;
            border-radius: 20px;
            margin-bottom: 12px;
            width: fit-content;
            border: 1px solid rgba(255,255,255,0.1);
        }
        .portfolio-info p {
            font-size: 0.95rem;
            color: rgba(255,255,255,0.6);
            margin-bottom: 20px;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
        .portfolio-tags { display: flex; flex-wrap: wrap; gap: 8px; margin-top: auto; }
        .portfolio-tags span {
            font-size: 0.75rem;
            color: rgba(255,255,255,0.5);
            background: rgba(255,255,255,0.03);
            padding: 4px 10px;
            border-radius: 6px;
        }

        /* Advanced Floating Modal */
        .modal-backdrop {
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.7);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            z-index: 2000;
            display: none;
            align-items: center;
            justify-content: center;
            padding: 20px;
            opacity: 0;
            transition: opacity 0.3s ease;
        }
        .modal-backdrop.active { display: flex; opacity: 1; }
        
        .modal-window {
            width: 100%;
            max-width: 900px;
            max-height: 90vh;
            background: rgba(20, 25, 40, 0.75);
            backdrop-filter: blur(30px);
            -webkit-backdrop-filter: blur(30px);
            border: 1px solid rgba(255, 255, 255, 0.15);
            border-radius: 32px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
            overflow-y: auto;
            transform: scale(0.9) translateY(20px);
            transition: transform 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
            position: relative;
        }
        .modal-backdrop.active .modal-window { transform: scale(1) translateY(0); }

        .modal-close {
            position: absolute;
            top: 20px;
            left: 20px;
            width: 44px;
            height: 44px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            color: #fff;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: var(--transition);
            z-index: 10;
        }
        .modal-close:hover { background: #ef4444; border-color: #ef4444; transform: rotate(90deg); }
        .modal-close svg { width: 20px; height: 20px; fill: currentColor; }

        .modal-media {
            width: 100%;
            background: #000;
            border-radius: 32px 32px 0 0;
            overflow: hidden;
            position: relative;
            aspect-ratio: 16/9;
        }
        .modal-media img, .modal-media video, .modal-media iframe {
            width: 100%;
            height: 100%;
            object-fit: contain; /* یا cover بسته به سلیقه */
            display: block;
        }

        .modal-body { padding: 40px; }
        .modal-body h2 {
            font-size: 2rem;
            font-weight: 800;
            margin-bottom: 10px;
            background: linear-gradient(135deg, #fff, rgba(255,255,255,0.7));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        .modal-body .meta {
            display: flex;
            gap: 15px;
            margin-bottom: 25px;
            flex-wrap: wrap;
        }
        .modal-body .meta span {
            font-size: 0.9rem;
            color: rgba(255,255,255,0.6);
            display: flex;
            align-items: center;
            gap: 6px;
        }
        .modal-body .meta svg { width: 16px; height: 16px; fill: var(--primary); }
        
        .modal-body .description {
            font-size: 1.05rem;
            line-height: 1.9;
            color: rgba(255,255,255,0.8);
            margin-bottom: 30px;
            white-space: pre-line;
        }

        .modal-actions {
            display: flex;
            gap: 15px;
            flex-wrap: wrap;
        }
        .action-btn {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            padding: 12px 24px;
            border-radius: 14px;
            font-weight: 600;
            text-decoration: none;
            transition: var(--transition);
            font-size: 0.95rem;
        }
        .action-btn svg { width: 18px; height: 18px; fill: currentColor; }
        
        .btn-primary {
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            color: #fff;
            border: none;
        }
        .btn-primary:hover { opacity: 0.9; transform: translateY(-2px); box-shadow: 0 10px 20px rgba(99, 102, 241, 0.3); }
        
        .btn-glass {
            background: rgba(255,255,255,0.05);
            border: 1px solid rgba(255,255,255,0.1);
            color: #fff;
        }
        .btn-glass:hover { background: rgba(255,255,255,0.1); border-color: rgba(255,255,255,0.3); }

        /* Footer */
        footer {
            text-align: center;
            padding: 40px 20px;
            border-top: 1px solid var(--glass-border);
            background: rgba(0,0,0,0.2);
            backdrop-filter: blur(10px);
        }
        .contact-grid {
            display: flex;
            justify-content: center;
            gap: 20px;
            flex-wrap: wrap;
            margin-bottom: 30px;
        }
        .contact-item {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 12px 24px;
            color: #fff;
            text-decoration: none;
            font-weight: 500;
        }
        .contact-item svg { width: 20px; height: 20px; fill: var(--primary); }
        .contact-item:hover { background: rgba(255,255,255,0.05); }

        /* Responsive */
        @media (max-width: 768px) {
            header h1 { font-size: 2.2rem; }
            .mobile-toggle { display: block; }
            .nav-links {
                display: none;
                position: absolute;
                top: 100%;
                left: 0; right: 0;
                background: rgba(15, 23, 42, 0.95);
                backdrop-filter: blur(20px);
                flex-direction: column;
                padding: 20px;
                border-radius: 20px;
                border: 1px solid var(--glass-border);
                margin-top: 10px;
            }
            .nav-links.active { display: flex; }
            .portfolio-grid { grid-template-columns: 1fr; }
            .modal-window { max-height: 95vh; border-radius: 24px; }
            .modal-media { border-radius: 24px 24px 0 0; }
            .modal-body { padding: 25px; }
            .modal-actions { flex-direction: column; }
            .action-btn { width: 100%; justify-content: center; }
        }

        /* Scroll Animation */
        .reveal {
            opacity: 0;
            transform: translateY(30px);
            transition: all 0.8s cubic-bezier(0.25, 0.46, 0.45, 0.94);
        }
        .reveal.active { opacity: 1; transform: translateY(0); }
    </style>
</head>
<body>

    <!-- Animated Background -->
    <div class="mesh-bg">
        <div class="mesh-orb"></div>
        <div class="mesh-orb"></div>
        <div class="mesh-orb"></div>
    </div>

    <!-- Header -->
    <header id="home">
        <?php if (!empty($logo) && file_exists(__DIR__ . '/../' . $logo)): ?>
            <img src="<?= '../' . htmlspecialchars($logo) ?>" alt="Logo">
        <?php elseif (!empty($logo) && file_exists(__DIR__ . '/' . $logo)): ?>
            <img src="<?= htmlspecialchars($logo) ?>" alt="Logo">
        <?php endif; ?>
        <h1><?= htmlspecialchars($siteName) ?></h1>
        <p><?= htmlspecialchars($siteDesc) ?></p>
    </header>

    <!-- Navigation -->
    <nav class="glass">
        <div class="nav-inner">
            <button class="mobile-toggle" id="menuToggle" aria-label="منو">
                <svg viewBox="0 0 24 24"><path d="M3 6h18v2H3V6m0 5h18v2H3v-2m0 5h18v2H3v-2z"/></svg>
            </button>
            <ul class="nav-links" id="navLinks">
                <li><a href="#home">خانه</a></li>
                <li><a href="#about">درباره من</a></li>
                <li><a href="#portfolio">نمونه‌کارها</a></li>
                <li><a href="#contact">تماس</a></li>
            </ul>
        </div>
    </nav>

    <div class="container">
        <!-- About Section -->
        <section id="about" class="reveal">
            <h2 class="section-title"><span>درباره من</span></h2>
            <div class="glass about-content">
                <?= nl2br(htmlspecialchars($aboutMe)) ?>
            </div>
        </section>

        <!-- Portfolio Section -->
        <section id="portfolio" class="reveal" style="margin-top: 80px;">
            <h2 class="section-title"><span>نمونه‌کارها</span></h2>
            
            <?php if (empty($portfolios)): ?>
                <div class="glass" style="padding: 40px; text-align: center; color: rgba(255,255,255,0.6);">
                    هنوز نمونه‌کاری ثبت نشده است.
                </div>
            <?php else: ?>
                <div class="portfolio-grid">
                    <?php foreach ($portfolios as $index => $p): ?>
                        <article class="glass portfolio-card" data-index="<?= $index ?>">
                            <div class="portfolio-thumb">
                                <img src="<?= htmlspecialchars($p['image']) ?>" alt="<?= htmlspecialchars($p['title']) ?>" loading="lazy">
                                <div class="play-overlay">
                                    <!-- SVG Eye/Play Icon -->
                                    <svg viewBox="0 0 24 24"><path d="M12 4.5C7 4.5 2.73 7.61 1 12c1.73 4.39 6 7.5 11 7.5s9.27-3.11 11-7.5c-1.73-4.39-6-7.5-11-7.5zM12 17c-2.76 0-5-2.24-5-5s2.24-5 5-5 5 2.24 5 5-2.24 5-5 5zm0-8c-1.66 0-3 1.34-3 3s1.34 3 3 3 3-1.34 3-3-1.34-3-3-3z"/></svg>
                                </div>
                            </div>
                            <div class="portfolio-info">
                                <h3><?= htmlspecialchars($p['title']) ?></h3>
                                <?php if (!empty($p['category'])): ?>
                                    <span class="category"><?= htmlspecialchars($p['category']) ?></span>
                                <?php endif; ?>
                                <p><?= htmlspecialchars($p['description']) ?></p>
                                <?php if (!empty($p['tags'])): ?>
                                    <div class="portfolio-tags">
                                        <?php foreach (explode(',', $p['tags']) as $tag): ?>
                                            <span>#<?= htmlspecialchars(trim($tag)) ?></span>
                                        <?php endforeach; ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </article>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </section>
    </div>

    <!-- Footer & Contact -->
    <footer id="contact">
        <div class="container">
            <h2 class="section-title" style="font-size: 2rem; margin-bottom: 30px;"><span>ارتباط با من</span></h2>
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
                        default: $link = $val; $target = '_blank';
                    }
                    
                    $icon = '<svg viewBox="0 0 24 24"><path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/></svg>';
                    if ($c['type'] === 'email') $icon = '<svg viewBox="0 0 24 24"><path d="M20 4H4c-1.1 0-1.99.9-1.99 2L2 18c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4l-8 5-8-5V6l8 5 8-5v2z"/></svg>';
                    elseif ($c['type'] === 'instagram') $icon = '<svg viewBox="0 0 24 24"><path d="M7.8 2h8.4C19.4 2 22 4.6 22 7.8v8.4a5.8 5.8 0 0 1-5.8 5.8H7.8C4.6 22 2 19.4 2 16.2V7.8A5.8 5.8 0 0 1 7.8 2m-.2 2A3.6 3.6 0 0 0 4 7.6v8.8C4 18.39 5.61 20 7.6 20h8.8a3.6 3.6 0 0 0 3.6-3.6V7.6C20 5.61 18.39 4 16.4 4H7.6m9.65 1.5a1.25 1.25 0 0 1 1.25 1.25A1.25 1.25 0 0 1 17.25 8 1.25 1.25 0 0 1 16 6.75a1.25 1.25 0 0 1 1.25-1.25M12 7a5 5 0 0 1 5 5 5 5 0 0 1-5 5 5 5 0 0 1-5-5 5 5 0 0 1 5-5m0 2a3 3 0 0 0-3 3 3 3 0 0 0 3 3 3 3 0 0 0 3-3 3 3 0 0 0-3-3z"/></svg>';
                    elseif ($c['type'] === 'telegram') $icon = '<svg viewBox="0 0 24 24"><path d="M9.78 18.65l.28-4.23 7.68-6.92c.34-.31-.07-.46-.52-.19L7.74 13.3 3.64 12c-.88-.25-.89-.86.2-1.3l15.97-6.16c.73-.33 1.43.18 1.15 1.3l-2.72 12.81c-.19.91-.74 1.13-1.5.71L12.6 16.3l-1.99 1.93c-.23.23-.42.42-.83.42z"/></svg>';
                ?>
                    <a href="<?= $link ?>" class="glass contact-item" target="<?= $target ?>">
                        <?= $icon ?>
                        <span><?= $label ?></span>
                    </a>
                <?php endforeach; ?>
            </div>
            <p style="color: rgba(255,255,255,0.4); font-size: 0.9rem;"><?= htmlspecialchars($footerText ?: '© ' . date('Y') . ' تمامی حقوق محفوظ است.') ?></p>
        </div>
    </footer>

    <!-- Advanced Floating Glass Modal -->
    <div class="modal-backdrop" id="projectModal">
        <div class="modal-window">
            <button class="modal-close" id="modalClose" aria-label="بستن">
                <svg viewBox="0 0 24 24"><path d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12z"/></svg>
            </button>
            
            <div class="modal-media" id="modalMediaContainer">
                <!-- Dynamic Content (Image, Video, or Iframe) will be injected here -->
            </div>
            
            <div class="modal-body">
                <h2 id="modalTitle"></h2>
                <div class="meta" id="modalMeta"></div>
                <div class="description" id="modalDescription"></div>
                <div class="modal-actions" id="modalActions"></div>
            </div>
        </div>
    </div>

    <script>
        // Mobile Menu
        const menuToggle = document.getElementById('menuToggle');
        const navLinks = document.getElementById('navLinks');
        menuToggle.addEventListener('click', () => navLinks.classList.toggle('active'));
        document.querySelectorAll('.nav-links a').forEach(link => {
            link.addEventListener('click', () => navLinks.classList.remove('active'));
        });

        // Scroll Reveal Animation
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('active');
                }
            });
        }, { threshold: 0.1 });
        document.querySelectorAll('.reveal').forEach(el => observer.observe(el));

        // Modal & Smart Media Player Logic
        const modal = document.getElementById('projectModal');
        const modalClose = document.getElementById('modalClose');
        const modalMedia = document.getElementById('modalMediaContainer');
        const modalTitle = document.getElementById('modalTitle');
        const modalMeta = document.getElementById('modalMeta');
        const modalDesc = document.getElementById('modalDescription');
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

                // 1. Smart Media Player Logic
                let mediaHTML = '';
                if (p.video) {
                    if (p.video.includes('youtube.com') || p.video.includes('youtu.be')) {
                        const videoId = p.video.split('v=')[1]?.split('&')[0] || p.video.split('/').pop();
                        mediaHTML = `<iframe src="https://www.youtube.com/embed/${videoId}?rel=0" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>`;
                    } else if (p.video.includes('aparat.com')) {
                        // Simple Aparat iframe extractor (assuming standard aparat link)
                        const aparatId = p.video.match(/\/video\/([a-zA-Z0-9]+)/)?.[1] || '';
                        mediaHTML = `<iframe src="https://www.aparat.com/video/video/embed/videohash/${aparatId}/vt/frame" allowFullScreen="true" webkitallowfullscreen="true" mozallowfullscreen="true"></iframe>`;
                    } else {
                        // Direct video file (mp4, webm)
                        mediaHTML = `<video src="${p.video}" controls autoplay muted loop playsinline></video>`;
                    }
                } else if (p.image) {
                    mediaHTML = `<img src="${p.image}" alt="${p.title}">`;
                }
                modalMedia.innerHTML = mediaHTML;

                // 2. Populate Text
                modalTitle.textContent = p.title;
                modalDesc.textContent = p.description;
                
                let metaHTML = '';
                if (p.category) metaHTML += `<span>${icons.category} ${p.category}</span>`;
                if (p.tags) {
                    const tags = p.tags.split(',').map(t => `<span style="background:rgba(255,255,255,0.1); padding:2px 8px; border-radius:4px; font-size:0.8rem;">${t.trim()}</span>`).join(' ');
                    metaHTML += `<span style="display:flex; gap:5px; align-items:center;">${tags}</span>`;
                }
                modalMeta.innerHTML = metaHTML;

                // 3. Populate Actions
                let actionsHTML = '';
                if (p.link) actionsHTML += `<a href="${p.link}" target="_blank" class="action-btn btn-primary">${icons.link} مشاهده پروژه</a>`;
                if (p.demo) actionsHTML += `<a href="${p.demo}" target="_blank" class="action-btn btn-glass">${icons.demo} دمو زنده</a>`;
                if (p.github) actionsHTML += `<a href="${p.github}" target="_blank" class="action-btn btn-glass">${icons.github} گیت‌هاب</a>`;
                if (p.image) actionsHTML += `<a href="${p.image}" download class="action-btn btn-glass">${icons.download} دانلود تصویر</a>`;
                
                modalActions.innerHTML = actionsHTML;

                // 4. Show Modal
                modal.classList.add('active');
                document.body.style.overflow = 'hidden';
            });
        });

        function closeModal() {
            modal.classList.remove('active');
            document.body.style.overflow = '';
            // Stop video playback when closing
            setTimeout(() => { modalMedia.innerHTML = ''; }, 300);
        }

        modalClose.addEventListener('click', closeModal);
        modal.addEventListener('click', (e) => { if (e.target === modal) closeModal(); });
        document.addEventListener('keydown', (e) => { if (e.key === 'Escape' && modal.classList.contains('active')) closeModal(); });

        // Parallax effect for background orbs on mouse move
        document.addEventListener('mousemove', (e) => {
            const orbs = document.querySelectorAll('.mesh-orb');
            const x = e.clientX / window.innerWidth;
            const y = e.clientY / window.innerHeight;
            orbs.forEach((orb, i) => {
                const speed = (i + 1) * 20;
                orb.style.transform = `translate(${(x - 0.5) * speed}px, ${(y - 0.5) * speed}px)`;
            });
        });
    </script>
</body>
</html>