<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <meta name="description" content="PHP script for monitoring Docker Containers running on a remote server" />

    <link href="https://cdn.jsdelivr.net/npm/modern-normalize@v2.0.0/modern-normalize.min.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:ital,wght@0,100..800;1,100..800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= $base_url ?>/css/style.css">

    <link rel="icon" href="<?= $base_url ?>/img/favicon.png">

    <title><?= $name ?></title>
</head>

<body>
<nav class="nav" id="nav">
    <a href="<?= $base_url ?>" class="logo">
        <img src="<?= $base_url ?>/img/logo.png" alt="Durbin logo" height="40">
        Durbin
    </a">
    <a href="<?= $base_url ?>/stats" <?= $page == 'stats' ? 'class="active"' : '' ?> >Stats</a>
    <a href="<?= $base_url ?>/all" <?= $page == 'all' ? 'class="active"' : '' ?> >All</a>
    <a href="<?= $base_url ?>" <?= $page == 'running' ? 'class="active"' : '' ?> >Running</a>
    <a href="javascript:void(0);" id="menu-toggle">
        <div class="menu-toggle__bar1"></div>
        <div class="menu-toggle__bar2"></div>
        <div class="menu-toggle__bar3"></div>
    </a>
</nav>

<main>
    <h2><?= $title ?></h2>
    <div class="container">

        <form action="<?= $base_url ?>/action" method="post" id="action-form">
            <input type="hidden" name="_csrf" value="<?= $csrfToken ?? 'no-csrf-token' ?>">
            <input type="hidden" name="container_id" value="">
            <input type="hidden" name="action" value="">

            <div class="content"><?= $content ?></div>

        </form>

    </div>
</main>

<!--<div style="width: 100%; position: fixed; z-index: 1000; top: 0; left: 0; background: lightgray;"><p>This is an announcemet.</p><div onClick="parentNode.remove()">Close [X]</div></div>-->
<footer>
    <small><?= $copyright ?></small>
</footer>

<script type="text/javascript" src="<?= $base_url ?>/js/app.js"></script>
</body>
</html>