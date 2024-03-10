<?php
/**
 * Email class
 *
 * @package Default Avatars
 */

declare(strict_types=1);

namespace Default_Avatars;

/**
 * The Email class definition.
 *
 * @since 1.0.0
 */
class Email {

	/**
	 * Gets a user email from a user identifier.
	 *
	 * @since 1.0.0
	 * @param mixed $id_or_email The user identifier.
	 * @return string|null
	 */
	public function get_email_from_id_or_email( $id_or_email ): ?string {
		if ( is_numeric( $id_or_email ) ) {
			$user = get_user_by( 'id', absint( $id_or_email ) );
			if ( $user instanceof \WP_User ) {
				return $user->user_email;
			}
		} elseif ( $id_or_email instanceof \WP_User ) {
			return $id_or_email->user_email;
		} elseif ( $id_or_email instanceof \WP_Post ) {
			$user = get_user_by( 'id', (int) $id_or_email->post_author );
			if ( $user instanceof \WP_User ) {
				return $user->user_email;
			}
		} elseif ( $id_or_email instanceof \WP_Comment ) {
			if ( ! empty( $id_or_email->user_id ) ) {
				$user = get_user_by( 'id', (int) $id_or_email->user_id );
				if ( $user instanceof \WP_User ) {
					return $user->user_email;
				}
			}
			if ( ! empty( $id_or_email->comment_author_email ) ) {
				return $id_or_email->comment_author_email;
			}
		} elseif ( is_string( $id_or_email ) ) {
			if ( false === strpos( $id_or_email, '@md5.gravatar.com' ) ) {
				if ( is_email( $id_or_email ) ) {
					return $id_or_email;
				}
			}
		}
		return null;
	}
}
