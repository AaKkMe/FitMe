<?php
/**
 * Application constants
 */
define('ROLES', [
    'admin' => 1,
    'trainer' => 2,
    'user' => 3
]);

define('SESSION_LIFETIME', 3600); // 1 hour

// Start session if not started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
