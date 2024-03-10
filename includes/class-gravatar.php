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
	 * Checks if a Gravatar image exists for a given hash.
	 *
	 * @since 1.0.0
	 * @param string $hash The md5 hash.
	 * @return bool
	 */
	public function read( string $hash ): bool {
		// Build the path.
		$url = sprintf(
			'https://www.gravatar.com/avatar/%s',
			$hash
		);
		// Build the query string.
		$url = add_query_arg(
			array(
				'd' => '404',
			),
			$url
		);
		// Get data from the cache.
		$data = wp_cache_get( $hash );
		if ( false === $data ) {
			$response = wp_remote_head( $url );
			if ( ! is_wp_error( $response ) ) {
				$data = $response['response']['code'];
				wp_cache_add( $hash, $data, '', MINUTE_IN_SECONDS );
			}
		}
		if ( 200 === $data ) {
			return true;
		} else {
			return false;
		}
	}
}
