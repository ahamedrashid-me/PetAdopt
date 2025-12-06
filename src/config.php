<?php
/**
 * ============================================================================
 * CONFIGURATION FILE - Pet Adoption Portal
 * ============================================================================
 * 
 * This file contains all global configuration constants and paths used 
 * throughout the application. It serves as the central configuration hub 
 * for the entire project.
 * 
 * PURPOSE:
 * - Define application directory paths
 * - Configure environment settings (development/production)
 * - Set error reporting levels
 * - Initialize global constants
 * 
 * USAGE:
 * Include this file at the start of any PHP script that needs access to
 * configuration constants using: require_once __DIR__ . '/../src/config.php';
 * ============================================================================
 */

// ============================================================================
// 1. DIRECTORY PATHS - Organize the project structure
// ============================================================================
/** Base path to the project root directory */
define('BASE_PATH', dirname(__DIR__));

/** Path to the src directory containing classes and utilities */
define('SRC_PATH', BASE_PATH . '/src');

/** Path to the public directory containing web-accessible files */
define('PUBLIC_PATH', BASE_PATH . '/public');

/** Path to the data directory containing JSON data files */
define('DATA_PATH', BASE_PATH . '/data');

// ============================================================================
// 2. DATA FILE PATHS - Where persistent data is stored
// ============================================================================
/** 
 * Path to pets.json - Contains all pet records
 * Structure: [{ id, name, type, breed, age, size, image, status, ... }, ...]
 */
define('PETS_DATA_FILE', DATA_PATH . '/pets.json');

/** 
 * Path to adoptions.json - Contains all adoption requests
 * Structure: [{ id, petId, petName, adopterName, adopterEmail, ... }, ...]
 */
define('ADOPTIONS_DATA_FILE', DATA_PATH . '/adoptions.json');

// ============================================================================
// 3. ENVIRONMENT & DEBUG SETTINGS
// ============================================================================
/** 
 * Current environment: 'development' or 'production'
 * Set via APP_ENV environment variable or defaults to 'development'
 */
define('ENV', getenv('APP_ENV') ?: 'development');

/** 
 * Debug mode flag - true in development, false in production
 * Controls error display and logging verbosity
 */
define('DEBUG', ENV === 'development');

// ============================================================================
// 4. SITE CONFIGURATION
// ============================================================================
/** Official name of the application */
define('SITE_NAME', 'Pet Adoption Portal');

/** Base URL of the site - Update for production deployment */
define('SITE_URL', 'http://localhost:8000');

// ============================================================================
// 5. ERROR REPORTING & LOGGING CONFIGURATION
// ============================================================================
if (DEBUG) {
    // DEVELOPMENT: Show all errors for debugging
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
} else {
    // PRODUCTION: Log errors but don't display them to users
    error_reporting(E_ALL);
    ini_set('display_errors', 0);
    ini_set('log_errors', 1);
    // In production, set log_errors_max_len and error_log path as needed
}

// ============================================================================
// 6. TIMEZONE CONFIGURATION
// ============================================================================
/** 
 * Set default timezone for all date/time operations
 * Uses UTC for consistency across time zones
 */
date_default_timezone_set('UTC');
