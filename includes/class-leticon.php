<?php
/**
 * Leticon class
 *
 * @package Default Avatars
 */

declare(strict_types=1);

namespace Default_Avatars;

/**
 * The Leticon class definition.
 *
 * @since 1.0.0
 */
class Leticon implements Default_Avatar {

	/**
	 * The md5 hash of the user email.
	 *
	 * @since 1.0.0
	 * @var string
	 */
	private $hash;

	/**
	 * The first letter of the user.
	 *
	 * @since 1.0.0
	 * @var string
	 */
	private $letter;

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
	 * The font.
	 *
	 * @since 1.0.0
	 * @var string
	 */
	private $font;

	/**
	 * Sets data related to the image.
	 *
	 * @since 1.0.0
	 * @param string $email The user email.
	 */
	public function __construct( string $email ) {
		// Set the hash.
		$this->hash = md5( strtolower( trim( $email ) ) );
		// Set the letter.
		$this->letter = strtoupper( substr( $email, 0, 1 ) );
		// Get the upload directory.
		$wp_upload_dir = wp_upload_dir();
		// Set the file.
		$this->file = trailingslashit( $wp_upload_dir['basedir'] ) . trailingslashit( 'default-avatars' ) . trailingslashit( $this->hash ) . 'leticon.png';
		// Set the URL.
		$this->url = trailingslashit( $wp_upload_dir['baseurl'] ) . trailingslashit( 'default-avatars' ) . trailingslashit( $this->hash ) . 'leticon.png';
		// Set the font.
		$this->font = plugin_dir_path( __DIR__ ) . trailingslashit( 'fonts' ) . 'NotoSans-Regular.ttf';
	}

	/**
	 * Creates a leticon.
	 *
	 * @since 1.0.0
	 * @return bool
	 */
	public function create(): bool {
		// Check if the extension isn't loaded.
		if ( ! extension_loaded( 'gd' ) ) {
			return false;
		}
		// Create a new image.
		$image = imagecreatetruecolor(
			625,
			625
		);
		if ( false === $image ) {
			return false;
		}
		// Allocate the secondary.
		$secondary = imagecolorallocate(
			$image,
			(int) hexdec( substr( $this->hash, 0, 2 ) ),
			(int) hexdec( substr( $this->hash, 2, 2 ) ),
			(int) hexdec( substr( $this->hash, 4, 2 ) )
		);
		if ( false === $secondary ) {
			return false;
		}
		// Allocate the primary.
		$primary = imagecolorallocate(
			$image,
			255,
			255,
			255
		);
		if ( false === $primary ) {
			return false;
		}
		// Draw a filled rectangle in the image.
		$fill = imagefilledrectangle(
			$image,
			0,
			0,
			625,
			625,
			$secondary
		);
		if ( false === $fill ) {
			return false;
		}
		// Get the bounding box.
		$bounding_box = imagettfbbox(
			375,
			0,
			$this->font,
			$this->letter
		);
		if ( false === $bounding_box ) {
			return false;
		}
		// Draw text in the image.
		$bounding_box = imagettftext(
			$image,
			375,
			0,
			(int) floor( ( 625 - $bounding_box[2] ) / 2 ),
			500,
			$primary,
			$this->font,
			$this->letter
		);
		if ( false === $bounding_box ) {
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
	 * Reads a leticon.
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
	 * Updates a leticon.
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
	 * Deletes a leticon.
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
