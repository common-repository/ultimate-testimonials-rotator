<?php 

/**
 * 
 * Plugin Name: Ultimate Testimonials Rotator
 * Version: 1.0.1
 * Description: A powerful tool that enables you to display multiple testimonial grids and sliders on your website using shortcodes with categories.
 * Author: Bitcraftx
 * Author URI: https://www.bitcraftx.com
 * Requires at least: 6.0
 * Tested up to: 6.5.3
 * Text Domain: ultimate-testimonials-rotator
 * License: GPLv2 or later
 * 
 */

// Check if the constant 'ABSPATH' is not defined.
if (!defined('ABSPATH')) {
   // If not defined, exit the script.
   exit();
}

// Include the helper functions from the 'backend/helper/helper.php' file.
require_once plugin_dir_path(__FILE__) . '/includes/classes/class_register_post_type.php';
// // Include the main functionality from the 'includes/main.php' file.
require_once plugin_dir_path(__FILE__) . '/includes/main.php';