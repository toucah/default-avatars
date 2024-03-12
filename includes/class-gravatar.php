<?php
/**
 * Gravatar class
 *
 * @package Default Avatars
 */

declare(strict_types=1);

namespace Default_Avatars;

/**
 * The Gravatar class definition.
 *
 * @since 1.0.0
 */
class Gravatar {

	/**
	 * The md5 hash of the user email.
	 *
	 * @since 1.0.0
	 * @var string
	 */
	private $hash;

	/**
	 * Checks if a Gravatar image exists for a given hash.
	 *
	 * @since 1.0.0
	 * @param string $email The user email.
	 * @return bool
	 */
	public function read( string $email ): bool {
		// Set the hash.
		$this->hash = md5( strtolower( trim( $email ) ) );
		// Build the path.
		$url = sprintf(
			'https://www.gravatar.com/avatar/%s',
			$this->hash
		);
		// Build the query string.
		$url = add_query_arg(
			array(
				'd' => '404',
			),
			$url
		);
		// Get data from the cache.
		$data = wp_cache_get( $this->hash );
		if ( false === $data ) {
			$response = wp_remote_head( $url );
			if ( ! is_wp_error( $response ) ) {
				$data = $response['response']['code'];
				wp_cache_add( $this->hash, $data, '', MINUTE_IN_SECONDS );
			}
		}
		if ( 200 === $data ) {
			return true;
		} else {
			return false;
		}
	}
}
