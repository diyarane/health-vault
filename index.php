<?php
/**
 * Root entry point. Health Vault's public site lives under /public/;
 * this simply forwards visitors there so http://localhost/health-vault/
 * works out of the box.
 */
require_once __DIR__ . '/includes/functions.php';
redirect(site_base_url() . '/public/index.php');
