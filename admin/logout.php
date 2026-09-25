<?php
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/auth.php';

admin_logout();
// Start a fresh session only so we can show a one-time flash message on the login page.
session_start();
flash_set('success', 'You have been logged out successfully.');
redirect(admin_base_url() . '/login.php');
