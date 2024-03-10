<?php
/**
 * Default_Avatar_Type enum
 *
 * @package Default Avatars
 */

declare(strict_types=1);

namespace Default_Avatars;

/**
 * The Default_Avatar_Type enum definition.
 *
 * @since 1.0.0
 */
enum Default_Avatar_Type: string {

	/**
	 * Pixicon.
	 *
	 * @since 1.0.0
	 */
	case Pixicon = 'pixicon';

	/**
	 * Gets a label.
	 *
	 * @since 1.0.0
	 * @return string
	 */
	public function label(): string {
		return match ( $this ) {
			self::Pixicon => __( 'Pixicon (Generated)', 'default-avatars' )
		};
	}
}
