<?php

/**
 * Vercel PHP entry point.
 * This script overrides Laravel's default cache paths to work with 
 * Vercel's read-only filesystem.
 */

// Force Laravel to use /tmp for all service discovery and configuration caches
putenv('APP_SERVICES_CACHE=/tmp/services.php');
putenv('APP_PACKAGES_CACHE=/tmp/packages.php');
putenv('APP_CONFIG_CACHE=/tmp/config.php');
putenv('APP_ROUTES_CACHE=/tmp/routes.php');

// Redirect to the standard Laravel index
require __DIR__ . '/../public/index.php';