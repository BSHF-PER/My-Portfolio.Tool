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
            --bg: #0a0a0f;
            --card-bg: #15151f;
            --text: #e5e5e5;
            --muted: #888;
        }
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: '<?= $colors['font'] ?>', Tahoma; }
        body { background: var(--bg); color: var(--text); line-height: 1.7; }
        
        /* Header تاریک */
        header.dark-header {
            min-height: 100vh;
            background: radial-gradient(circle at 20% 50%, rgba(99, 102, 241, 0.15) 0%, transparent 50%),
                        radial-gradient(circle at 80% 80%, rgba(139, 92, 246, 0.15) 0%, transparent 50%),
                        var(--bg);
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow: hidden;
        }
        header.dark-header::before {
            content: '';
            position: absolute;
            inset: 0;
            background-image: 
                linear-gradient(rgba(99, 102, 241, 0.03) 1px, transparent 1px),
                linear-gradient(90deg, rgba(99, 102, 241, 0.03) 1px, transparent 1px);
            background-size: 50px 50px;
        }
        .hero-content {
            position: relative;
            z-index: 2;
            text-align: center;
            padding: 20px;
        }
        header.dark-header img {
            max-height: 80px;
            margin-bottom: 30px;
            filter: drop-shadow(0 0 20px var(--primary));
        }
        header.dark-header h1 {
            font-size: 4em;
            font-weight: 800;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin-bottom: 20px;
        }
        header.dark-header p {
            font-size: 1.3em;
            color: var(--muted);
            max-width: 600px;
            margin: 0 auto;
        }
        
        /* Nav تاریک */
        nav.dark-nav {
            background: rgba(10, 10, 15, 0.9);
            backdrop-filter: blur(10px);
            padding: 15px 20px;
            border-bottom: 1px solid rgba(99, 102, 241, 0.2);
            position: sticky;
            top: 0;
            z-index: 100;
            text-align: center;
        }
        nav.dark-nav a {
            color: var(--text);
            text-decoration: none;
            margin: 0 20px;
            font-weight: 500;
            transition: color 0.3s;
            position: relative;
        }
        nav.dark-nav a::after {
            content: '';
            position: absolute;
            bottom: -5px;
            left: 0;
            width: 0;
            height: 2px;
            background: var(--primary);
            transition: width 0.3s;
        }
        nav.dark-nav a:hover { color: var(--primary); }
        nav.dark-nav a:hover::after { width: 100%; }
        
        /* Sections */
        section { padding: 100px 20px; }
        .container { max-width: 1200px; margin: 0 auto; }
        .section-title {
            font-size: 2.5em;
            font-weight: 700;
            text-align: center;
            margin-bottom: 60px;
            color: white;
        }
        .section-title span {
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
            font-size: 1.15em;
            color: var(--muted);
            line-height: 2;
        }
        
        /* Portfolio تاریک */
        .portfolio-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
            gap: 25px;
        }
        .portfolio-card {
            background: var(--card-bg);
            border: 1px solid rgba(255,255,255,0.05);
            border-radius: 15px;
            overflow: hidden;
            transition: all 0.4s;
            position: relative;
        }
        .portfolio-card::before {
            content: '';
            position: absolute;
            inset: 0;
            border-radius: 15px;
            padding: 1px;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            -webkit-mask: linear-gradient(#fff 0 0) content-box, linear-gradient(#fff 0 0);
            -webkit-mask-composite: xor;
            mask-composite: exclude;
            opacity: 0;
            transition: opacity 0.4s;
        }
        .portfolio-card:hover::before { opacity: 1; }
        .portfolio-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 40px rgba(99, 102, 241, 0.2);
        }
        .portfolio-card img {
            width: 100%;
            height: 220px;
            object-fit: cover;
        }
        .portfolio-info { padding: 25px; }
        .portfolio-info .category {
            display: inline-block;
            padding: 4px 12px;
            background: rgba(99, 102, 241, 0.15);
            color: var(--primary);
            border-radius: 20px;
            font-size: 0.8em;
            margin-bottom: 12px;
        }
        .portfolio-info h3 {
            color: white;
            font-size: 1.4em;
            margin-bottom: 12px;
        }
        .portfolio-info p {
            color: var(--muted);
            margin-bottom: 15px;
            font-size: 0.95em;
        }
        .portfolio-info .tags { margin-bottom: 15px; }
        .portfolio-info .tags span {
            display: inline-block;
            background: rgba(255,255,255,0.05);
            color: var(--muted);
            padding: 3px 10px;
            margin: 2px;
            font-size: 0.8em;
            border-radius: 4px;
        }
        .portfolio-info .links { display: flex; gap: 10px; flex-wrap: wrap; }
        .portfolio-info .links a {
            padding: 8px 15px;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            color: white;
            text-decoration: none;
            border-radius: 8px;
            font-size: 0.85em;
            font-weight: 500;
            transition: all 0.3s;
        }
        .portfolio-info .links a:hover {
            transform: scale(1.05);
            box-shadow: 0 5px 15px rgba(99, 102, 241, 0.4);
        }
        
        /* Footer تاریک */
        footer.dark-footer {
            background: #05050a;
            padding: 60px 20px 20px;
            border-top: 1px solid rgba(99, 102, 241, 0.2);
        }
        footer .footer-inner {
            max-width: 1200px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 40px;
        }
        footer h3 {
            font-size: 1.3em;
            margin-bottom: 20px;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        footer ul { list-style: none; }
        footer li { margin-bottom: 10px; }
        footer a { color: var(--muted); text-decoration: none; transition: color 0.3s; }
        footer a:hover { color: var(--primary); }
        footer .copyright {
            text-align: center;
            padding-top: 30px;
            margin-top: 40px;
            border-top: 1px solid rgba(255,255,255,0.05);
            color: var(--muted);
        }
        
        @media (max-width: 768px) {
            header.dark-header h1 { font-size: 2.5em; }
            .section-title { font-size: 1.8em; }
            footer .footer-inner { grid-template-columns: 1fr; }
            nav.dark-nav a { margin: 0 10px; font-size: 0.9em; }
        }
    </style>
</head>
<body>
    <header class="dark-header" id="home">
        <div class="hero-content">
            <?php if ($logo && file_exists(__DIR__ . '/' . $logo)): ?>
                <img src="<?= $logo ?>" alt="logo">
            <?php endif; ?>
            <h1><?= sanitize($siteName) ?></h1>
            <p><?= sanitize($siteDesc) ?></p>
        </div>
    </header>

    <nav class="dark-nav">
        <a href="#home">خانه</a>
        <a href="#about">درباره</a>
        <a href="#portfolio">کارها</a>
        <a href="#contact">تماس</a>
    </nav>

    <section id="about">
        <div class="container">
            <h2 class="section-title"><span>درباره من</span></h2>
            <p class="about-content"><?= nl2br(sanitize($aboutMe)) ?></p>
        </div>
    </section>

    <section id="portfolio">
        <div class="container">
            <h2 class="section-title"><span>نمونه‌کارها</span></h2>
            <?php if (empty($portfolios)): ?>
                <p style="text-align:center;color:var(--muted);">هنوز نمونه‌کاری ثبت نشده است.</p>
            <?php else: ?>
                <div class="portfolio-grid">
                    <?php foreach ($portfolios as $p): ?>
                        <article class="portfolio-card">
                            <?php if ($p['image']): ?>
                                <img src="<?= $p['image'] ?>" alt="<?= sanitize($p['title']) ?>">
                            <?php endif; ?>
                            <div class="portfolio-info">
                                <?php if ($p['category']): ?>
                                    <span class="category"><?= sanitize($p['category']) ?></span>
                                <?php endif; ?>
                                <h3><?= sanitize($p['title']) ?></h3>
                                <p><?= sanitize(mb_substr($p['description'], 0, 150)) ?></p>
                                <?php if ($p['tags']): ?>
                                    <div class="tags">
                                        <?php foreach (explode(',', $p['tags']) as $tag): ?>
                                            <span><?= sanitize(trim($tag)) ?></span>
                                        <?php endforeach; ?>
                                    </div>
                                <?php endif; ?>
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

    <footer class="dark-footer" id="contact">
        <div class="footer-inner">
            <div>
                <h3><?= sanitize($siteName) ?></h3>
                <p style="color:var(--muted);line-height:2;"><?= sanitize($siteDesc) ?></p>
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
