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
        
        /* Header */
        .hero {
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            color: white;
            padding: 100px 20px;
            text-align: center;
            position: relative;
            overflow: hidden;
        }
        .hero::before {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0; bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><circle cx="50" cy="50" r="40" fill="none" stroke="white" stroke-width="0.5" opacity="0.1"/></svg>');
            background-size: 100px;
        }
        .hero-content { position: relative; z-index: 1; max-width: 800px; margin: 0 auto; }
        .hero img.logo { max-height: 80px; margin-bottom: 20px; }
        .hero h1 { font-size: 3em; margin-bottom: 15px; }
        .hero p { font-size: 1.2em; opacity: 0.95; }
        
        /* Navigation */
        nav {
            background: white;
            padding: 15px 20px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            position: sticky;
            top: 0;
            z-index: 100;
        }
        nav ul { list-style: none; display: flex; justify-content: center; gap: 30px; flex-wrap: wrap; }
        nav a { color: var(--text); text-decoration: none; font-weight: 500; transition: color 0.3s; }
        nav a:hover { color: var(--primary); }
        
        /* Sections */
        section { padding: 80px 20px; max-width: 1200px; margin: 0 auto; }
        .section-title { text-align: center; font-size: 2.5em; margin-bottom: 50px; color: var(--primary); }
        
        /* About */
        .about-content { max-width: 800px; margin: 0 auto; font-size: 1.1em; text-align: center; }
        
        /* Portfolio Grid */
        .portfolio-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 30px;
        }
        .portfolio-card {
            background: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 5px 20px rgba(0,0,0,0.08);
            transition: transform 0.3s, box-shadow 0.3s;
        }
        .portfolio-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 40px rgba(0,0,0,0.15);
        }
        .portfolio-card img { width: 100%; height: 220px; object-fit: cover; }
        .portfolio-info { padding: 20px; }
        .portfolio-info h3 { color: var(--primary); margin-bottom: 10px; }
        .portfolio-info p { color: #666; margin-bottom: 15px; }
        .portfolio-tags { display: flex; flex-wrap: wrap; gap: 5px; margin-bottom: 15px; }
        .portfolio-tags span { background: var(--primary); color: white; padding: 3px 10px; border-radius: 20px; font-size: 0.8em; }
        .portfolio-links { display: flex; gap: 10px; flex-wrap: wrap; }
        .portfolio-links a {
            padding: 8px 15px;
            background: var(--primary);
            color: white;
            text-decoration: none;
            border-radius: 8px;
            font-size: 0.9em;
            transition: background 0.3s;
        }
        .portfolio-links a:hover { background: var(--secondary); }
        
        /* Footer */
        footer {
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            color: white;
            padding: 50px 20px 20px;
            margin-top: 50px;
        }
        .footer-content { max-width: 1200px; margin: 0 auto; display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 30px; }
        .footer-section h3 { margin-bottom: 15px; }
        .footer-section ul { list-style: none; }
        .footer-section li { margin-bottom: 10px; }
        .footer-section a { color: white; text-decoration: none; opacity: 0.9; }
        .footer-section a:hover { opacity: 1; text-decoration: underline; }
        .footer-bottom { text-align: center; padding-top: 30px; margin-top: 30px; border-top: 1px solid rgba(255,255,255,0.2); opacity: 0.9; }
        
        @media (max-width: 768px) {
            .hero h1 { font-size: 2em; }
            .section-title { font-size: 1.8em; }
        }
    </style>
</head>
<body>
    <nav>
        <ul>
            <li><a href="#home">خانه</a></li>
            <li><a href="#about">درباره من</a></li>
            <li><a href="#portfolio">نمونه‌کارها</a></li>
            <li><a href="#contact">تماس</a></li>
        </ul>
    </nav>

    <section class="hero" id="home">
        <div class="hero-content">
            <?php if ($logo && file_exists(__DIR__ . '/' . $logo)): ?>
                <img src="<?= $logo ?>" alt="logo" class="logo">
            <?php endif; ?>
            <h1><?= sanitize($siteName) ?></h1>
            <p><?= sanitize($siteDesc) ?></p>
        </div>
    </section>

    <section id="about">
        <h2 class="section-title">درباره من</h2>
        <div class="about-content">
            <p><?= nl2br(sanitize($aboutMe)) ?></p>
        </div>
    </section>

    <section id="portfolio">
        <h2 class="section-title">نمونه‌کارها</h2>
        <?php if (empty($portfolios)): ?>
            <p style="text-align:center;">هنوز نمونه‌کاری ثبت نشده است.</p>
        <?php else: ?>
            <div class="portfolio-grid">
                <?php foreach ($portfolios as $p): ?>
                    <div class="portfolio-card">
                        <?php if ($p['image']): ?>
                            <img src="<?= $p['image'] ?>" alt="<?= sanitize($p['title']) ?>">
                        <?php endif; ?>
                        <div class="portfolio-info">
                            <h3><?= sanitize($p['title']) ?></h3>
                            <?php if ($p['category']): ?>
                                <small style="color:var(--primary);"><?= sanitize($p['category']) ?></small>
                            <?php endif; ?>
                            <p><?= sanitize(mb_substr($p['description'], 0, 150)) ?>...</p>
                            <?php if ($p['tags']): ?>
                                <div class="portfolio-tags">
                                    <?php foreach (explode(',', $p['tags']) as $tag): ?>
                                        <span><?= sanitize(trim($tag)) ?></span>
                                    <?php endforeach; ?>
                                </div>
                            <?php endif; ?>
                            <div class="portfolio-links">
                                <?php if ($p['link']): ?><a href="<?= $p['link'] ?>" target="_blank">🔗 مشاهده</a><?php endif; ?>
                                <?php if ($p['github']): ?><a href="<?= $p['github'] ?>" target="_blank">🐙 گیت‌هاب</a><?php endif; ?>
                                <?php if ($p['demo']): ?><a href="<?= $p['demo'] ?>" target="_blank">▶️ دمو</a><?php endif; ?>
                                <?php if ($p['video']): ?><a href="<?= $p['video'] ?>" target="_blank">🎥 ویدیو</a><?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </section>

    <footer id="contact">
        <div class="footer-content">
            <div class="footer-section">
                <h3><?= sanitize($siteName) ?></h3>
                <p><?= sanitize($siteDesc) ?></p>
            </div>
            <?php if (!empty($contacts)): ?>
                <div class="footer-section">
                    <h3>راه‌های ارتباطی</h3>
                    <ul>
                        <?php foreach ($contacts as $c): 
                            $link = '#';
                            $target = '';
                            switch ($c['type']) {
                                case 'email': $link = 'mailto:' . $c['value']; break;
                                case 'phone': $link = 'tel:' . $c['value']; break;
                                case 'instagram': $link = 'https://instagram.com/' . ltrim($c['value'], '@'); $target = '_blank'; break;
                                case 'telegram': $link = 'https://t.me/' . ltrim($c['value'], '@'); $target = '_blank'; break;
                                case 'whatsapp': $link = 'https://wa.me/' . preg_replace('/\D/', '', $c['value']); $target = '_blank'; break;
                                case 'linkedin': $link = $c['value']; $target = '_blank'; break;
                                case 'github': $link = 'https://github.com/' . ltrim($c['value'], '@'); $target = '_blank'; break;
                                case 'twitter': $link = 'https://twitter.com/' . ltrim($c['value'], '@'); $target = '_blank'; break;
                                case 'youtube': $link = $c['value']; $target = '_blank'; break;
                                default: $link = $c['value']; $target = '_blank';
                            }
                        ?>
                            <li>
                                <a href="<?= $link ?>" target="<?= $target ?>">
                                    <?= $c['icon'] ?: '🔗' ?> <?= sanitize($c['label'] ?: $c['value']) ?>
                                </a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>
        </div>
        <div class="footer-bottom">
            <p><?= sanitize($footerText) ?></p>
        </div>
    </footer>

    <script src="assets/js/main.js"></script>
</body>
</html>