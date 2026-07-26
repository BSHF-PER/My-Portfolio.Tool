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
        body { background: var(--bg); color: var(--text); line-height: 1.7; }
        
        /* Header مینیمال */
        header.minimal-header {
            padding: 100px 20px 80px;
            text-align: center;
            max-width: 800px;
            margin: 0 auto;
        }
        header.minimal-header img { max-height: 60px; margin-bottom: 30px; }
        header.minimal-header h1 {
            font-size: 3.5em;
            font-weight: 300;
            letter-spacing: -1px;
            margin-bottom: 20px;
        }
        header.minimal-header p {
            font-size: 1.2em;
            color: #888;
            font-weight: 300;
        }
        
        /* Nav مینیمال */
        nav.minimal-nav {
            border-top: 1px solid #eee;
            border-bottom: 1px solid #eee;
            padding: 20px;
            text-align: center;
            position: sticky;
            top: 0;
            background: var(--bg);
            z-index: 100;
        }
        nav.minimal-nav a {
            color: var(--text);
            text-decoration: none;
            margin: 0 20px;
            font-size: 0.95em;
            letter-spacing: 1px;
            text-transform: uppercase;
            transition: color 0.3s;
        }
        nav.minimal-nav a:hover { color: var(--primary); }
        
        /* Sections */
        section { padding: 80px 20px; max-width: 900px; margin: 0 auto; }
        .section-label {
            font-size: 0.85em;
            color: var(--primary);
            letter-spacing: 3px;
            text-transform: uppercase;
            margin-bottom: 15px;
            font-weight: 500;
        }
        .section-title {
            font-size: 2.2em;
            font-weight: 300;
            margin-bottom: 40px;
            letter-spacing: -0.5px;
        }
        
        /* About */
        .about-text {
            font-size: 1.15em;
            line-height: 2;
            color: #555;
            font-weight: 300;
        }
        
        /* Portfolio مینیمال */
        .portfolio-grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 60px;
        }
        .portfolio-card {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 40px;
            align-items: center;
        }
        .portfolio-card:nth-child(even) { direction: ltr; }
        .portfolio-card:nth-child(even) .portfolio-text { direction: rtl; }
        .portfolio-card img {
            width: 100%;
            aspect-ratio: 4/3;
            object-fit: cover;
            filter: grayscale(20%);
            transition: filter 0.5s;
        }
        .portfolio-card:hover img { filter: grayscale(0%); }
        .portfolio-text h3 {
            font-size: 1.8em;
            font-weight: 400;
            margin-bottom: 15px;
        }
        .portfolio-text .category {
            font-size: 0.85em;
            color: var(--primary);
            letter-spacing: 2px;
            text-transform: uppercase;
            margin-bottom: 15px;
        }
        .portfolio-text p {
            color: #666;
            margin-bottom: 20px;
            font-weight: 300;
        }
        .portfolio-text .links a {
            color: var(--primary);
            text-decoration: none;
            margin-left: 20px;
            font-size: 0.9em;
            border-bottom: 1px solid var(--primary);
            padding-bottom: 2px;
        }
        .portfolio-text .links a:hover { color: var(--secondary); border-color: var(--secondary); }
        
        /* Footer مینیمال */
        footer.minimal-footer {
            padding: 60px 20px 30px;
            border-top: 1px solid #eee;
            margin-top: 80px;
        }
        footer .footer-inner {
            max-width: 900px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 40px;
        }
        footer h4 {
            font-size: 0.85em;
            letter-spacing: 2px;
            text-transform: uppercase;
            margin-bottom: 20px;
            color: var(--primary);
        }
        footer ul { list-style: none; }
        footer li { margin-bottom: 10px; }
        footer a { color: var(--text); text-decoration: none; font-weight: 300; }
        footer a:hover { color: var(--primary); }
        footer .copyright {
            max-width: 900px;
            margin: 40px auto 0;
            padding-top: 20px;
            border-top: 1px solid #eee;
            text-align: center;
            color: #999;
            font-size: 0.9em;
        }
        
        @media (max-width: 768px) {
            header.minimal-header h1 { font-size: 2.2em; }
            .portfolio-card, .portfolio-card:nth-child(even) { grid-template-columns: 1fr; direction: rtl; }
            footer .footer-inner { grid-template-columns: 1fr; }
            nav.minimal-nav a { margin: 0 10px; font-size: 0.85em; }
        }
    </style>
</head>
<body>
    <header class="minimal-header" id="home">
        <?php if ($logo && file_exists(__DIR__ . '/' . $logo)): ?>
            <img src="<?= $logo ?>" alt="logo">
        <?php endif; ?>
        <h1><?= sanitize($siteName) ?></h1>
        <p><?= sanitize($siteDesc) ?></p>
    </header>

    <nav class="minimal-nav">
        <a href="#home">خانه</a>
        <a href="#about">درباره</a>
        <a href="#portfolio">کارها</a>
        <a href="#contact">تماس</a>
    </nav>

    <section id="about">
        <div class="section-label">— معرفی</div>
        <h2 class="section-title">درباره من</h2>
        <p class="about-text"><?= nl2br(sanitize($aboutMe)) ?></p>
    </section>

    <section id="portfolio">
        <div class="section-label">— پروژه‌ها</div>
        <h2 class="section-title">نمونه‌کارها</h2>
        <?php if (empty($portfolios)): ?>
            <p style="text-align:center;color:#888;">هنوز نمونه‌کاری ثبت نشده است.</p>
        <?php else: ?>
            <div class="portfolio-grid">
                <?php foreach ($portfolios as $p): ?>
                    <article class="portfolio-card">
                        <?php if ($p['image']): ?>
                            <img src="<?= $p['image'] ?>" alt="<?= sanitize($p['title']) ?>">
                        <?php endif; ?>
                        <div class="portfolio-text">
                            <?php if ($p['category']): ?>
                                <div class="category"><?= sanitize($p['category']) ?></div>
                            <?php endif; ?>
                            <h3><?= sanitize($p['title']) ?></h3>
                            <p><?= sanitize($p['description']) ?></p>
                            <div class="links">
                                <?php if ($p['link']): ?><a href="<?= $p['link'] ?>" target="_blank">مشاهده →</a><?php endif; ?>
                                <?php if ($p['github']): ?><a href="<?= $p['github'] ?>" target="_blank">کد →</a><?php endif; ?>
                                <?php if ($p['demo']): ?><a href="<?= $p['demo'] ?>" target="_blank">دمو →</a><?php endif; ?>
                            </div>
                        </div>
                    </article>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </section>

    <footer class="minimal-footer" id="contact">
        <div class="footer-inner">
            <div>
                <h4>درباره</h4>
                <p style="color:#666;font-weight:300;"><?= sanitize($siteDesc) ?></p>
            </div>
            <div>
                <h4>ارتباط</h4>
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