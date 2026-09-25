<?php
/**
 * Admin panel header. Expects $pageTitle to be set, and that require_admin_login()
 * has already been called by the including page.
 */
$adminBase = admin_base_url();
$siteBase  = site_base_url();
$admin     = current_admin();
?><!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= h(($pageTitle ?? 'Admin') . ' — ' . APP_NAME . ' Admin') ?></title>
<link rel="stylesheet" href="<?= h($siteBase) ?>/assets/css/style.css">
</head>
<body class="admin-body">
<div class="admin-layout">
<?php include __DIR__ . '/admin-sidebar.php'; ?>
  <div class="admin-content">
    <header class="admin-topbar">
      <h1><?= h($pageTitle ?? 'Admin') ?></h1>
      <div class="admin-topbar-user">
        <span>Signed in as <strong><?= h($admin['name'] ?? '') ?></strong></span>
        <a href="<?= h($adminBase) ?>/logout.php" class="btn btn-sm btn-outline">Logout</a>
      </div>
    </header>
    <div class="admin-body-inner">
      <?php foreach (flash_get_all() as $msg): ?>
        <div class="alert alert-<?= h($msg['type']) ?>"><?= h($msg['message']) ?></div>
      <?php endforeach; ?>
