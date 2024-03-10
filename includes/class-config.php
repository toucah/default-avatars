<?php
/**
 * Config class
 *
 * @package Default Avatars
 */

declare(strict_types=1);

namespace Default_Avatars;

/**
 * The Config class definition.
 *
 * @since 1.0.0
 */
class Config {

	/**
	 * Adds hooks.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		add_filter( 'default_avatar_select', array( $this, 'default_avatar_select' ) );
	}

	/**
	 * Filters the avatar default list.
	 *
	 * @since 1.0.0
	 * @param string $avatar_list The default list.
	 * @return string
	 */
	public function default_avatar_select( $avatar_list ) {
		foreach ( Default_Avatar_Type::cases() as $case ) {
			// Get the current user.
			$user = wp_get_current_user();
			// Get a hash from the user email.
			$hash = md5( strtolower( trim( $user->user_email ) ) );
			// Create a new instance.
			$default_avatar = Factory::make( $case, $hash );
			// Set the default.
			$default_value = $default_avatar->read();
			// Check if the file doesn't exist.
			if ( null === $default_value ) {
				if ( true === $default_avatar->create() ) {
					$default_value = $default_avatar->read();
					if ( null === $default_value ) {
						$default_value = 'blank';
					}
				} else {
					$default_value = 'blank';
				}
			}
			$avatar_list .= '<label>';
			$avatar_list .= sprintf(
				'<input type="radio" name="avatar_default" id="avatar_%s" value="%s"%s>',
				$case->value,
				$case->value,
				checked( get_option( 'avatar_default' ), $case->value, false )
			);
			$avatar_list .= ' ';
			// Build the data for the avatar.
			$args = array(
				'force_default' => true,
				'force_display' => true,
			);
			// Get the avatar.
			$avatar_list .= get_avatar(
				$user->ID,
				32,
				$default_value,
				'',
				$args
			);
			$avatar_list .= ' ';
			$avatar_list .= $case->label();
			$avatar_list .= '</label>';
		}
		return $avatar_list;
	}
}
