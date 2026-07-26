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
        body { background: #f4f4f4; color: var(--text); line-height: 1.8; }
        
        /* Header کلاسیک */
        header.classic-header {
            background: #fff;
            border-bottom: 3px double var(--primary);
            padding: 30px 20px;
            text-align: center;
        }
        header.classic-header img { max-height: 70px; margin-bottom: 15px; }
        header.classic-header h1 {
            font-size: 2.5em;
            color: var(--primary);
            letter-spacing: 2px;
            font-weight: 700;
        }
        header.classic-header .divider {
            width: 80px;
            height: 3px;
            background: var(--primary);
            margin: 15px auto;
        }
        header.classic-header p { color: #666; font-size: 1.1em; }
        
        /* Navigation کلاسیک */
        nav.classic-nav {
            background: var(--primary);
            padding: 0;
            text-align: center;
            position: sticky;
            top: 0;
            z-index: 100;
        }
        nav.classic-nav ul { list-style: none; display: inline-flex; }
        nav.classic-nav a {
            display: block;
            padding: 15px 25px;
            color: white;
            text-decoration: none;
            border-left: 1px solid rgba(255,255,255,0.2);
            transition: background 0.3s;
        }
        nav.classic-nav li:first-child a { border-right: 1px solid rgba(255,255,255,0.2); }
        nav.classic-nav a:hover { background: var(--secondary); }
        
        /* Container */
        .container { max-width: 1000px; margin: 0 auto; padding: 50px 20px; }
        
        /* Section Title کلاسیک */
        .section-title {
            text-align: center;
            font-size: 2em;
            color: var(--primary);
            margin-bottom: 40px;
            position: relative;
            padding-bottom: 15px;
        }
        .section-title::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 60px;
            height: 2px;
            background: var(--primary);
        }
        
        /* About */
        .about-box {
            background: white;
            padding: 40px;
            border: 1px solid #ddd;
            border-top: 4px solid var(--primary);
            text-align: justify;
            font-size: 1.05em;
            line-height: 2;
        }
        
        /* Portfolio List کلاسیک */
        .portfolio-list { display: flex; flex-direction: column; gap: 30px; }
        .portfolio-item {
            background: white;
            border: 1px solid #ddd;
            display: grid;
            grid-template-columns: 300px 1fr;
            overflow: hidden;
            transition: box-shadow 0.3s;
        }
        .portfolio-item:hover { box-shadow: 0 5px 20px rgba(0,0,0,0.1); }
        .portfolio-item img { width: 100%; height: 100%; object-fit: cover; min-height: 200px; }
        .portfolio-content { padding: 25px; }
        .portfolio-content h3 {
            color: var(--primary);
            font-size: 1.5em;
            margin-bottom: 10px;
            border-bottom: 1px solid #eee;
            padding-bottom: 10px;
        }
        .portfolio-content .category {
            color: var(--secondary);
            font-size: 0.9em;
            margin-bottom: 15px;
            font-style: italic;
        }
        .portfolio-content p { margin-bottom: 15px; color: #555; }
        .portfolio-content .tags { margin-bottom: 15px; }
        .portfolio-content .tags span {
            display: inline-block;
            background: #f0f0f0;
            color: var(--primary);
            padding: 3px 10px;
            margin: 2px;
            font-size: 0.85em;
            border-radius: 3px;
        }
        .portfolio-content .links a {
            display: inline-block;
            padding: 8px 15px;
            background: var(--primary);
            color: white;
            text-decoration: none;
            margin-left: 5px;
            font-size: 0.9em;
            border-radius: 3px;
        }
        .portfolio-content .links a:hover { background: var(--secondary); }
        
        /* Footer کلاسیک */
        footer.classic-footer {
            background: #2c3e50;
            color: white;
            padding: 40px 20px 20px;
            margin-top: 50px;
        }
        footer .footer-inner {
            max-width: 1000px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 40px;
        }
        footer h3 {
            border-bottom: 2px solid var(--primary);
            padding-bottom: 10px;
            margin-bottom: 20px;
            display: inline-block;
        }
        footer ul { list-style: none; }
        footer li { padding: 8px 0; border-bottom: 1px dashed rgba(255,255,255,0.2); }
        footer a { color: white; text-decoration: none; }
        footer a:hover { color: var(--primary); }
        footer .copyright {
            text-align: center;
            padding-top: 25px;
            margin-top: 30px;
            border-top: 1px solid rgba(255,255,255,0.2);
            color: #bbb;
        }
        
        @media (max-width: 768px) {
            .portfolio-item { grid-template-columns: 1fr; }
            footer .footer-inner { grid-template-columns: 1fr; }
            header.classic-header h1 { font-size: 1.8em; }
        }
    </style>
</head>
<body>
    <header class="classic-header" id="home">
        <?php if ($logo && file_exists(__DIR__ . '/' . $logo)): ?>
            <img src="<?= $logo ?>" alt="logo">
        <?php endif; ?>
        <h1><?= sanitize($siteName) ?></h1>
        <div class="divider"></div>
        <p><?= sanitize($siteDesc) ?></p>
    </header>

    <nav class="classic-nav">
        <ul>
            <li><a href="#home">خانه</a></li>
            <li><a href="#about">درباره من</a></li>
            <li><a href="#portfolio">نمونه‌کارها</a></li>
            <li><a href="#contact">تماس</a></li>
        </ul>
    </nav>

    <div class="container">
        <section id="about">
            <h2 class="section-title">درباره من</h2>
            <div class="about-box">
                <?= nl2br(sanitize($aboutMe)) ?>
            </div>
        </section>

        <section id="portfolio">
            <h2 class="section-title">نمونه‌کارها</h2>
            <?php if (empty($portfolios)): ?>
                <p style="text-align:center;">هنوز نمونه‌کاری ثبت نشده است.</p>
            <?php else: ?>
                <div class="portfolio-list">
                    <?php foreach ($portfolios as $p): ?>
                        <article class="portfolio-item">
                            <?php if ($p['image']): ?>
                                <img src="<?= $p['image'] ?>" alt="<?= sanitize($p['title']) ?>">
                            <?php endif; ?>
                            <div class="portfolio-content">
                                <h3><?= sanitize($p['title']) ?></h3>
                                <?php if ($p['category']): ?>
                                    <div class="category">دسته: <?= sanitize($p['category']) ?></div>
                                <?php endif; ?>
                                <p><?= sanitize($p['description']) ?></p>
                                <?php if ($p['tags']): ?>
                                    <div class="tags">
                                        <?php foreach (explode(',', $p['tags']) as $tag): ?>
                                            <span><?= sanitize(trim($tag)) ?></span>
                                        <?php endforeach; ?>
                                    </div>
                                <?php endif; ?>
                                <div class="links">
                                    <?php if ($p['link']): ?><a href="<?= $p['link'] ?>" target="_blank">مشاهده پروژه</a><?php endif; ?>
                                    <?php if ($p['github']): ?><a href="<?= $p['github'] ?>" target="_blank">گیت‌هاب</a><?php endif; ?>
                                    <?php if ($p['demo']): ?><a href="<?= $p['demo'] ?>" target="_blank">دمو</a><?php endif; ?>
                                </div>
                            </div>
                        </article>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </section>
    </div>

    <footer class="classic-footer" id="contact">
        <div class="footer-inner">
            <div>
                <h3>درباره</h3>
                <p><?= sanitize($siteDesc) ?></p>
            </div>
            <div>
                <h3>ارتباط با من</h3>
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