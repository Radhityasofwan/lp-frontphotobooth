<?php
// header.php
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= h($page_title ?? 'Front Photobooth') ?> | <?= h(get_setting('seo_title', 'Front Photobooth')) ?></title>
    <meta name="description" content="<?= h(get_setting('seo_desc', 'Photobooth modern untuk event Anda.')) ?>">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Phosphor Icons -->
    <script src="https://unpkg.com/@phosphor-icons/web"></script>

    <!-- Google Fonts (dari index.php) -->
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;700;800&display=swap" rel="stylesheet">

    <!-- Anda bisa membuat file CSS kustom di sini -->
    <!-- <link rel="stylesheet" href="<?= asset('assets/css/style.css') ?>"> -->
</head>
<body>