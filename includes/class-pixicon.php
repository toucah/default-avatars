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
		// Create the image.
		$image = imagecreatetruecolor(
			625,
			625
		);
		if ( false === $image ) {
			return false;
		}
		foreach ( range( 0, 2 ) as $x ) {
			foreach ( range( 0, 4 ) as $y ) {
				// Get part of the hash.
				$part = substr( $this->hash, $x * 5 + $y + 6, 1 );
				// Set data.
				$data[ $x ][ $y ] = hexdec( $part ) % 2 === 0;
				// Get red.
				$red = $data[ $x ][ $y ] ? substr( $this->hash, 0, 2 ) : 'ee';
				// Get green.
				$green = $data[ $x ][ $y ] ? substr( $this->hash, 2, 2 ) : 'ee';
				// Get blue.
				$blue = $data[ $x ][ $y ] ? substr( $this->hash, 4, 2 ) : 'ee';
				// Allocate.
				$color = imagecolorallocate(
					$left,
					(int) hexdec( $red ),
					(int) hexdec( $green ),
					(int) hexdec( $blue )
				);
				if ( false === $color ) {
					return false;
				}
				$x1 = $x * 125;
				$y1 = $y * 125;
				$x2 = ( $x * 125 ) + 125;
				$y2 = ( $y * 125 ) + 125;
				// Draw a filled rectangle in the image.
				$fill = imagefilledrectangle(
					$left,
					$x1,
					$y1,
					$x2,
					$y2,
					$color
				);
				if ( false === $fill ) {
					return false;
				}
			}
		}
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
		$flip = imageflip(
			$right,
			IMG_FLIP_HORIZONTAL
		);
		if ( false === $flip ) {
			return false;
		}
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
		// Get the path to the parent directory.
		$dir = dirname( $this->file );
		// Create a directory.
		wp_mkdir_p( $dir );
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
