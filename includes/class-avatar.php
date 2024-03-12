<?php
/**
 * Avatar class
 *
 * @package Default Avatars
 */

declare(strict_types=1);

namespace Default_Avatars;

/**
 * The Avatar class definition.
 *
 * @since 1.0.0
 */
class Avatar {

	/**
	 * Adds filter hooks.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		add_filter( 'pre_get_avatar_data', array( $this, 'pre_get_avatar_data' ), 10, 2 );
	}

	/**
	 * Filters the avatar data.
	 *
	 * @since 1.0.0
	 * @param mixed[] $args The arguments.
	 * @param mixed   $id_or_email The user identifier.
	 * @return mixed[]
	 */
	public function pre_get_avatar_data( $args, $id_or_email ) {
		// Check if the default image should be used.
		if ( true === $args['force_default'] ) {
			return $args;
		}
		// Get the user option.
		$avatar_default = get_option( 'avatar_default' );
		// Check the type of the user option.
		if ( ! is_string( $avatar_default ) ) {
			return $args;
		}
		// Try to get the type from the option.
		$case = Default_Avatar_Type::tryFrom( $avatar_default );
		if ( is_null( $case ) ) {
			return $args;
		}
		$user = new User();
		// Get a user email from the identifier.
		$email = $user->get_email_from_id_or_email( $id_or_email );
		// Check if a user email is available.
		if ( ! is_null( $email ) ) {
			// Check if a Gravatar image is available.
			$gravatar = new Gravatar();
			if ( true === $gravatar->read( $email ) ) {
				return $args;
			}
			// Create a new instance.
			$default_avatar = Factory::make( $case, $email );
			// Set the default.
			$args['default'] = $default_avatar->read();
			// Check if the file doesn't exist.
			if ( null === $args['default'] ) {
				if ( true === $default_avatar->create() ) {
					$args['default'] = $default_avatar->read();
					if ( null === $args['default'] ) {
						$args['default'] = 'blank';
					}
				} else {
					$args['default'] = 'blank';
				}
			}
		} else {
			$args['default'] = 'blank';
		}
		return $args;
	}
}
