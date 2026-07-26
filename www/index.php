<?php
require_once 'includes/db.php';
require_once 'includes/functions.php';

$siteName = getSetting('site_name', 'My-Portfolio.Tool');
$siteDesc = getSetting('site_description');
$aboutMe = getSetting('about_me');
$logo = getSetting('logo');
$footerText = getSetting('footer_text');
$activeTemplate = getSetting('active_template', 'modern');

$colors = [
    'primary' => getSetting('primary_color', '#6366f1'),
    'secondary' => getSetting('secondary_color', '#8b5cf6'),
    'bg' => getSetting('bg_color', '#ffffff'),
    'text' => getSetting('text_color', '#1f2937'),
    'font' => getSetting('font_family', 'Vazirmatn')
];

$portfolios = $pdo->query("SELECT * FROM portfolios WHERE is_active = 1 ORDER BY sort_order ASC, created_at DESC")->fetchAll();

$contacts = $pdo->query("SELECT * FROM contacts WHERE is_active = 1 ORDER BY sort_order")->fetchAll();

$templateFile = __DIR__ . "/templates/$activeTemplate.php";
if (!file_exists($templateFile)) {
    $templateFile = __DIR__ . '/templates/modern.php';
}
include $templateFile;