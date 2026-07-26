<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= sanitize($siteName) ?></title>
    <meta name="description" content="<?= sanitize($siteDesc) ?>">
    <link href="https://cdn.jsdelivr.net/gh/rastikerdar/vazirmatn@v33.003/Vazirmatn-font-face.css" rel="stylesheet">
    <style>
        :root {
            --primary: <?= $colors['primary'] ?>;
            --secondary: <?= $colors['secondary'] ?>;
            --bg: <?= $colors['bg'] ?>;
            --text: <?= $colors['text'] ?>;
        }
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: '<?= $colors['font'] ?>', Tahoma; }
        body { background: var(--bg); color: var(--text); line-height: 1.7; overflow-x: hidden; }
        
        /* Hero خلاقانه */
        .hero {
            min-height: 100vh;
            background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
        }
        .hero::before {
            content: '';
            position: absolute;
            width: 500px; height: 500px;
            background: rgba(255,255,255,0.1);
            border-radius: 50%;
            top: -200px; right: -200px;
            animation: float 8s ease-in-out infinite;
        }
        .hero::after {
            content: '';
            position: absolute;
            width: 300px; height: 300px;
            background: rgba(255,255,255,0.08);
            border-radius: 50%;
            bottom: -100px; left: -100px;
            animation: float 6s ease-in-out infinite reverse;
        }
        @keyframes float {
            0%, 100% { transform: translate(0, 0); }
            50% { transform: translate(30px, -30px); }
        }
        .hero-content {
            position: relative;
            z-index: 2;
            text-align: center;
            color: white;
            padding: 20px;
            animation: fadeInUp 1s ease;
        }
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .hero img { max-height: 100px; margin-bottom: 30px; border-radius: 50%; border: 4px solid white; box-shadow: 0 10px 30px rgba(0,0,0,0.3); }
        .hero h1 {
            font-size: 4em;
            font-weight: 900;
            margin-bottom: 20px;
            text-shadow: 2px 2px 10px rgba(0,0,0,0.3);
        }
        .hero p { font-size: 1.3em; opacity: 0.95; max-width: 600px; margin: 0 auto 30px; }
        .hero .scroll-hint {
            position: absolute;
            bottom: 30px;
            left: 50%;
            transform: translateX(-50%);
            color: white;
            font-size: 2em;
            animation: bounce 2s infinite;
        }
        @keyframes bounce {
            0%, 100% { transform: translateX(-50%) translateY(0); }
            50% { transform: translateX(-50%) translateY(-15px); }
        }
        
        /* Nav شناور */
        nav.floating-nav {
            position: fixed;
            top: 20px;
            left: 50%;
            transform: translateX(-50%);
            background: rgba(255,255,255,0.95);
            backdrop-filter: blur(10px);
            padding: 12px 30px;
            border-radius: 50px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            z-index: 1000;
        }
        nav.floating-nav ul { list-style: none; display: flex; gap: 25px; }
        nav.floating-nav a {
            color: var(--text);
            text-decoration: none;
            font-weight: 600;
            font-size: 0.9em;
            transition: color 0.3s;
        }
        nav.floating-nav a:hover { color: var(--primary); }
        
        /* Sections */
        section { padding: 100px 20px; }
        .container { max-width: 1200px; margin: 0 auto; }
        .section-title {
            font-size: 3em;
            font-weight: 900;
            text-align: center;
            margin-bottom: 60px;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        
        /* About */
        .about-content {
            max-width: 800px;
            margin: 0 auto;
            text-align: center;
            font-size: 1.2em;
            line-height: 2;
            color: #555;
        }
        
        /* Portfolio خلاقانه - Masonry */
        .portfolio-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
            gap: 30px;
        }
        .portfolio-card {
            position: relative;
            border-radius: 20px;
            overflow: hidden;
            aspect-ratio: 4/5;
            cursor: pointer;
            transition: transform 0.4s;
        }
        .portfolio-card:hover { transform: scale(1.02); }
        .portfolio-card img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.5s;
        }
        .portfolio-card:hover img { transform: scale(1.1); }
        .portfolio-overlay {
            position: absolute;
            inset: 0;
            background: linear-gradient(to top, rgba(0,0,0,0.9) 0%, rgba(0,0,0,0.3) 50%, transparent 100%);
            display: flex;
            flex-direction: column;
            justify-content: flex-end;
            padding: 30px;
            color: white;
        }
        .portfolio-overlay h3 { font-size: 1.5em; margin-bottom: 10px; }
        .portfolio-overlay p { font-size: 0.95em; opacity: 0.9; margin-bottom: 15px; max-height: 0; overflow: hidden; transition: max-height 0.5s; }
        .portfolio-card:hover .portfolio-overlay p { max-height: 200px; }
        .portfolio-overlay .links { display: flex; gap: 10px; flex-wrap: wrap; }
        .portfolio-overlay .links a {
            padding: 8px 15px;
            background: white;
            color: var(--primary);
            text-decoration: none;
            border-radius: 20px;
            font-size: 0.85em;
            font-weight: 600;
            transition: all 0.3s;
        }
        .portfolio-overlay .links a:hover { background: var(--primary); color: white; }
        
        /* Footer خلاقانه */
        footer.creative-footer {
            background: #111;
            color: white;
            padding: 80px 20px 30px;
            position: relative;
            overflow: hidden;
        }
        footer.creative-footer::before {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0;
            height: 5px;
            background: linear-gradient(90deg, var(--primary), var(--secondary));
        }
        footer .footer-inner {
            max-width: 1200px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 50px;
        }
        footer h3 {
            font-size: 1.5em;
            margin-bottom: 20px;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        footer ul { list-style: none; }
        footer li { margin-bottom: 12px; }
        footer a { color: #ccc; text-decoration: none; transition: color 0.3s; }
        footer a:hover { color: var(--primary); }
        footer .copyright {
            text-align: center;
            padding-top: 40px;
            margin-top: 50px;
            border-top: 1px solid #333;
            color: #888;
        }
        
        @media (max-width: 768px) {
            .hero h1 { font-size: 2.5em; }
            .section-title { font-size: 2em; }
            nav.floating-nav { padding: 10px 15px; }
            nav.floating-nav ul { gap: 15px; }
            nav.floating-nav a { font-size: 0.8em; }
            footer .footer-inner { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>
    <nav class="floating-nav">
        <ul>
            <li><a href="#home">خانه</a></li>
            <li><a href="#about">درباره</a></li>
            <li><a href="#portfolio">کارها</a></li>
            <li><a href="#contact">تماس</a></li>
        </ul>
    </nav>

    <section class="hero" id="home">
        <div class="hero-content">
            <?php if ($logo && file_exists(__DIR__ . '/' . $logo)): ?>
                <img src="<?= $logo ?>" alt="logo">
            <?php endif; ?>
            <h1><?= sanitize($siteName) ?></h1>
            <p><?= sanitize($siteDesc) ?></p>
        </div>
        <div class="scroll-hint">↓</div>
    </section>

    <section id="about">
        <div class="container">
            <h2 class="section-title">درباره من</h2>
            <p class="about-content"><?= nl2br(sanitize($aboutMe)) ?></p>
        </div>
    </section>

    <section id="portfolio" style="background: #f9f9f9;">
        <div class="container">
            <h2 class="section-title">نمونه‌کارها</h2>
            <?php if (empty($portfolios)): ?>
                <p style="text-align:center;color:#888;">هنوز نمونه‌کاری ثبت نشده است.</p>
            <?php else: ?>
                <div class="portfolio-grid">
                    <?php foreach ($portfolios as $p): ?>
                        <article class="portfolio-card">
                            <?php if ($p['image']): ?>
                                <img src="<?= $p['image'] ?>" alt="<?= sanitize($p['title']) ?>">
                            <?php else: ?>
                                <div style="width:100%;height:100%;background:linear-gradient(135deg,var(--primary),var(--secondary));"></div>
                            <?php endif; ?>
                            <div class="portfolio-overlay">
                                <?php if ($p['category']): ?>
                                    <small style="color:var(--primary);"><?= sanitize($p['category']) ?></small>
                                <?php endif; ?>
                                <h3><?= sanitize($p['title']) ?></h3>
                                <p><?= sanitize(mb_substr($p['description'], 0, 150)) ?></p>
                                <div class="links">
                                    <?php if ($p['link']): ?><a href="<?= $p['link'] ?>" target="_blank">مشاهده</a><?php endif; ?>
                                    <?php if ($p['github']): ?><a href="<?= $p['github'] ?>" target="_blank">کد</a><?php endif; ?>
                                    <?php if ($p['demo']): ?><a href="<?= $p['demo'] ?>" target="_blank">دمو</a><?php endif; ?>
                                </div>
                            </div>
                        </article>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </section>

    <footer class="creative-footer" id="contact">
        <div class="footer-inner">
            <div>
                <h3><?= sanitize($siteName) ?></h3>
                <p style="color:#aaa;line-height:2;"><?= sanitize($siteDesc) ?></p>
            </div>
            <div>
                <h3>ارتباط</h3>
                <ul>
                    <?php foreach ($contacts as $c): 
                        $link = '#'; $target = '';
                        switch ($c['type']) {
                            case 'email': $link = 'mailto:' . $c['value']; break;
                            case 'phone': $link = 'tel:' . $c['value']; break;
                            case 'instagram': $link = 'https://instagram.com/' . ltrim($c['value'], '@'); $target = '_blank'; break;
                            case 'telegram': $link = 'https://t.me/' . ltrim($c['value'], '@'); $target = '_blank'; break;
                            case 'whatsapp': $link = 'https://wa.me/' . preg_replace('/\D/', '', $c['value']); $target = '_blank'; break;
                            default: $link = $c['value']; $target = '_blank';
                        }
                    ?>
                        <li><a href="<?= $link ?>" target="<?= $target ?>"><?= $c['icon'] ?: '•' ?> <?= sanitize($c['label'] ?: $c['value']) ?></a></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
        <div class="copyright">
            <p><?= sanitize($footerText) ?></p>
        </div>
    </footer>
</body>
</html>