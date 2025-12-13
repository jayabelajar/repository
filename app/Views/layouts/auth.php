<!doctype html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $page_title ?? ($app_name ?? 'Auth') ?></title>
    <?php
      $cssPath = __DIR__ . '/../../../public/assets/css/tailwind.css';
      $cssVersion = file_exists($cssPath) ? filemtime($cssPath) : time();
    ?>
    <link rel="stylesheet" href="<?= $base_url ?>/assets/css/tailwind.css?v=<?= $cssVersion ?>">
</head>
<body class="bg-slate-50 min-h-screen flex items-center justify-center p-4">
    <?= $content ?>
</body>
</html>
