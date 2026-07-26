<?php
$host = 'localhost';
$dbname = 'my_portfolio';
$user = 'root';
$pass = '';

try {
    $pdo = new PDO("mysql:host=$host", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->exec("CREATE DATABASE IF NOT EXISTS `$dbname` CHARACTER SET utf8mb4 COLLATE utf8mb4_persian_ci");
    $pdo->exec("USE `$dbname`");

    $pdo->exec("CREATE TABLE IF NOT EXISTS admins (
        id INT AUTO_INCREMENT PRIMARY KEY,
        username VARCHAR(50) UNIQUE NOT NULL,
        password VARCHAR(255) NOT NULL,
        name VARCHAR(100),
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )");

    $pdo->exec("CREATE TABLE IF NOT EXISTS portfolios (
        id INT AUTO_INCREMENT PRIMARY KEY,
        title VARCHAR(200) NOT NULL,
        description TEXT,
        category VARCHAR(100),
        image VARCHAR(255),
        video VARCHAR(255),
        link VARCHAR(500),
        github VARCHAR(500),
        demo VARCHAR(500),
        tags VARCHAR(500),
        is_active TINYINT DEFAULT 1,
        sort_order INT DEFAULT 0,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    )");

    $pdo->exec("CREATE TABLE IF NOT EXISTS settings (
        setting_key VARCHAR(100) PRIMARY KEY,
        setting_value TEXT,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    )");

    $pdo->exec("CREATE TABLE IF NOT EXISTS contacts (
        id INT AUTO_INCREMENT PRIMARY KEY,
        type VARCHAR(50) NOT NULL,
        value VARCHAR(500) NOT NULL,
        icon VARCHAR(100),
        label VARCHAR(100),
        is_custom TINYINT DEFAULT 0,
        sort_order INT DEFAULT 0,
        is_active TINYINT DEFAULT 1
    )");

    $pdo->exec("CREATE TABLE IF NOT EXISTS templates (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(100) NOT NULL,
        file VARCHAR(100) NOT NULL,
        preview VARCHAR(255),
        is_active TINYINT DEFAULT 0
    )");

    $defaultPass = password_hash('admin123', PASSWORD_DEFAULT);
    $pdo->exec("INSERT IGNORE INTO admins (username, password, name) 
                VALUES ('admin', '$defaultPass', 'Manager')");

    $pdo->exec("INSERT IGNORE INTO settings (setting_key, setting_value) VALUES
        ('site_name', 'My-Portfolio.Tool'),
        ('site_description', 'نمونه‌کارهای من'),
        ('logo', 'assets/images/default-logo.png'),
        ('active_template', 'modern'),
        ('primary_color', '#6366f1'),
        ('secondary_color', '#8b5cf6'),
        ('bg_color', '#ffffff'),
        ('text_color', '#1f2937'),
        ('font_family', 'Vazirmatn'),
        ('footer_text', '© 2026 My-Portfolio.Tool - تمامی حقوق محفوظ است'),
        ('about_me', 'درباره من را اینجا بنویسید...')
    ");

    $pdo->exec("INSERT IGNORE INTO templates (name, file, is_active) VALUES
        ('کلاسیک', 'classic', 0),
        ('مدرن', 'modern', 1),
        ('مینیمال', 'minimal', 0),
        ('خلاقانه', 'creative', 0),
        ('تاریک', 'dark', 0)
    ");

    @mkdir('assets/uploads', 0777, true);
    @mkdir('assets/uploads/portfolios', 0777, true);
    @mkdir('assets/uploads/logos', 0777, true);

    echo "<div style='font-family:Tahoma;text-align:center;padding:50px;'>
        <h1>Installed</h1>
        <p>Admin First Login Info:</p>
        <p><b>UserName:</b> admin</p>
        <p><b>Password:</b> admin123</p>
        <p><a href='index.php'>Go To</a> | <a href='admin/login.php'>Admin Panel</a></p>
        <p style='color:red;'>Please Delete Install.php File</p>
    </div>";

} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
