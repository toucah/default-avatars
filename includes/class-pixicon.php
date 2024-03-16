<?php
/**
 * Pixicon class
 *
 * @package Default Avatars
 */

declare(strict_types=1);

namespace Default_Avatars;

/**
 * The Pixicon class definition.
 *
 * @since 1.0.0
 */
class Pixicon implements Default_Avatar {

	/**
	 * The md5 hash of the user email.
	 *
	 * @since 1.0.0
	 * @var string
	 */
	private $hash;

	/**
	 * The image file.
	 *
	 * @since 1.0.0
	 * @var string
	 */
	private $file;

	/**
	 * The image URL.
	 *
	 * @since 1.0.0
	 * @var string
	 */
	private $url;

	/**
	 * Sets data related to the image.
	 *
	 * @since 1.0.0
	 * @param string $email The user email.
	 */
	public function __construct( string $email ) {
		// Set the hash.
		$this->hash = md5( strtolower( trim( $email ) ) );
		// Get the upload directory.
		$wp_upload_dir = wp_upload_dir();
		// Set the file.
		$this->file = trailingslashit( $wp_upload_dir['basedir'] ) . trailingslashit( 'default-avatars' ) . trailingslashit( $this->hash ) . 'pixicon.png';
		// Set the URL.
		$this->url = trailingslashit( $wp_upload_dir['baseurl'] ) . trailingslashit( 'default-avatars' ) . trailingslashit( $this->hash ) . 'pixicon.png';
	}

	/**
	 * Creates a pixicon.
	 *
	 * @since 1.0.0
	 * @return bool
	 */
	public function create(): bool {
		// Check if the extension isn't loaded.
		if ( ! extension_loaded( 'gd' ) ) {
			return false;
		}
		// Create the left part of the image.
		$left = imagecreatetruecolor(
			375,
			625
		);
		if ( false === $left ) {
			return false;
		}
		// Create the right part of the image.
		$right = imagecreatetruecolor(
			250,
			625
		);
		if ( false === $right ) {
			return false;
		}
		// Create the final image.
		$image = imagecreatetruecolor(
			625,
			625
		);
		if ( false === $image ) {
			return false;
		}
		// Allocate the primary.
		$primary = imagecolorallocate(
			$left,
			(int) hexdec( substr( $this->hash, 0, 2 ) ),
			(int) hexdec( substr( $this->hash, 2, 2 ) ),
			(int) hexdec( substr( $this->hash, 4, 2 ) )
		);
		if ( false === $primary ) {
			return false;
		}
		// Allocate the secondary.
		$secondary = imagecolorallocate(
			$left,
			238,
			238,
			238
		);
		if ( false === $secondary ) {
			return false;
		}
		foreach ( range( 0, 2 ) as $x ) {
			foreach ( range( 0, 4 ) as $y ) {
				// Draw a filled rectangle in the image.
				$fill = imagefilledrectangle(
					$left,
					$x * 125,
					$y * 125,
					$x * 125 + 125,
					$y * 125 + 125,
					hexdec( substr( $this->hash, $x * 5 + $y + 6, 1 ) ) % 2 === 0 ? $primary : $secondary
				);
				if ( false === $fill ) {
					return false;
				}
			}
		}
		// Copy part of the left image.
		$copy = imagecopy(
			$right,
			$left,
			0,
			0,
			0,
			0,
			250,
			625
		);
		if ( false === $copy ) {
			return false;
		}
		// Flip the right image.
		$flip = imageflip(
			$right,
			IMG_FLIP_HORIZONTAL
		);
		if ( false === $flip ) {
			return false;
		}
		// Copy the left image.
		$copy = imagecopy(
			$image,
			$left,
			0,
			0,
			0,
			0,
			375,
			625
		);
		if ( false === $copy ) {
			return false;
		}
		// Copy the right image.
		$copy = imagecopy(
			$image,
			$right,
			375,
			0,
			0,
			0,
			250,
			625
		);
		if ( false === $copy ) {
			return false;
		}
		// Create a directory.
		wp_mkdir_p( dirname( $this->file ) );
		// Save the image to a file.
		return imagepng(
			$image,
			$this->file
		);
	}

	/**
	 * Reads a pixicon.
	 *
	 * @since 1.0.0
	 * @return string|null
	 */
	public function read(): ?string {
		// Check if the file exists.
		if ( file_exists( $this->file ) ) {
			return $this->url;
		} else {
			return null;
		}
	}

	/**
	 * Updates a pixicon.
	 *
	 * @since 1.0.0
	 * @param \GdImage $image The image.
	 * @return bool
	 */
	public function update( \GdImage $image ): bool {
		if ( extension_loaded( 'gd' ) ) {
			return imagepng( $image, $this->file );
		} else {
			return false;
		}
	}

	/**
	 * Deletes a pixicon.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function delete(): void {
		// Check if the file exists.
		if ( file_exists( $this->file ) ) {
			wp_delete_file( $this->file );
		}
	}
}
