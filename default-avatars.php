<?php
/**
 * Default Avatars
 *
 * @package Default Avatars
 *
 * @wordpress-plugin
 * Plugin Name: Default Avatars
 * Plugin URI: https://github.com/toucah/default-avatars
 * Description: Fun default avatars for your WordPress site.
 * Version: 1.0.0
 * Requires at least: 6.4
 * Requires PHP: 8.1
 * Author: toucah
 * Author URI: https://github.com/toucah
 * Text Domain: default-avatars
 * License: GPLv3
 * License URI: https://www.gnu.org/licenses/gpl-3.0.html
 */

declare(strict_types=1);

namespace Default_Avatars;

/**
 * Require the autoload.php file.
 */
require plugin_dir_path( __FILE__ ) . 'vendor/autoload.php';

new Plugin();
