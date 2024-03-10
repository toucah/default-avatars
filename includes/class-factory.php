<?php
/**
 * Factory class
 *
 * @package Default Avatars
 */

declare(strict_types=1);

namespace Default_Avatars;

/**
 * The Factory class definition.
 *
 * @since 1.0.0
 */
class Factory {

	/**
	 * Creates a new instance.
	 *
	 * @since 1.0.0
	 * @param Default_Avatar_Type $type The type of avatar.
	 * @param string              $hash md5 hash of the user email.
	 * @return Default_Avatar
	 */
	public static function make( Default_Avatar_Type $type, string $hash ): Default_Avatar {
		$class = __NAMESPACE__ . '\\' . $type->name;
		return new $class( $hash );
	}
}
