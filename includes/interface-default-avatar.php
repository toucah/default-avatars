<?php
/**
 * Default_Avatar interface
 *
 * @package Default Avatars
 */

declare(strict_types=1);

namespace Default_Avatars;

/**
 * The Default_Avatar interface definition.
 *
 * @since 1.0.0
 */
interface Default_Avatar {

	/**
	 * Creates an indention.
	 *
	 * @since 1.0.0
	 * @return bool
	 */
	public function create(): bool;

	/**
	 * Reads an indention.
	 *
	 * @since 1.0.0
	 * @return string|null
	 */
	public function read(): ?string;

	/**
	 * Updates a default avatar.
	 *
	 * @since 1.0.0
	 * @param \GdImage $image The image.
	 * @return bool
	 */
	public function update( \GdImage $image ): bool;

	/**
	 * Deletes a default avatar.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function delete(): void;
}
