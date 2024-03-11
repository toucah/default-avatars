<?php
/**
 * Plugin class
 *
 * @package Default Avatars
 */

declare(strict_types=1);

namespace Default_Avatars;

/**
 * The Plugin class definition.
 *
 * @since 1.0.0
 */
class Plugin {

	/**
	 * Adds filter and deactivation hooks.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		add_action( 'wp_loaded', array( $this, 'instantiate' ) );
		register_deactivation_hook( plugin_dir_path( __DIR__ ) . 'default-avatars.php', array( $this, 'register_deactivation_hook' ) );
	}

	/**
	 * Instantiates the plugin.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function instantiate() {
		// Start the Config class.
		$config = new Config();
		// Start the Avatar class.
		$avatar = new Avatar();
	}

	/**
	 * Runs deactivation tasks.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function register_deactivation_hook() {
		// Get the user option.
		$avatar_default = get_option( 'avatar_default' );
		// Check the type of the user option.
		if ( is_string( $avatar_default ) ) {
			// Try to get the type from the option.
			$case = Default_Avatar_Type::tryFrom( $avatar_default );
			if ( ! is_null( $case ) ) {
				// Set option.
				update_option( 'avatar_default', 'mystery' );
			}
		}
	}
}
