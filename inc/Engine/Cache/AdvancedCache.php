<?php

namespace WP_Rocket\Engine\Cache;

class AdvancedCache {
	/**
	 * Absolute path to template files
	 *
	 * @var string
	 */
	private $template_path;

	/**
	 * WP Content directory path
	 *
	 * @var string
	 */
	private $content_dir;

	/**
	 * WP Filesystem Direct instance
	 *
	 * @var WP_Filesystem_Direct
	 */
	private $filesystem;

	/**
	 * Instantiate the class
	 *
	 * @param string $template_path Absolute path to template files.
	 */
	public function __construct( $template_path ) {
		$this->template_path = $template_path;
		$this->content_dir   = rocket_get_constant( 'WP_CONTENT_DIR' );
		$this->filesystem    = rocket_direct_filesystem();
	}

	/**
	 * Gets the content for the advanced-cache.php file
	 *
	 * @since 3.6
	 *
	 * @return string
	 */
	public function get_advanced_cache_content() {
		$content = $this->filesystem->get_contents( $this->template_path . '/advanced-cache.php' );
		$mobile  = is_rocket_generate_caching_mobile_files() ? '$2' : '';
		$content = preg_replace( "/('{{MOBILE_CACHE}}';)(\X*)('{{\/MOBILE_CACHE}}';)/", $mobile, $content );

		$replacements = [
			'{{WP_ROCKET_PHP_VERSION}}' => rocket_get_constant( 'WP_ROCKET_PHP_VERSION' ),
			'{{WP_ROCKET_PATH}}'        => rocket_get_constant( 'WP_ROCKET_PATH' ),
			'{{VENDOR_PATH}}'           => rocket_get_constant( 'WP_ROCKET_VENDORS_PATH' ),
			'{{WP_ROCKET_CONFIG_PATH}}' => rocket_get_constant( 'WP_ROCKET_CONFIG_PATH' ),
			'{{WP_ROCKET_CACHE_PATH}}'  => rocket_get_constant( 'WP_ROCKET_CACHE_PATH' ),
		];

		foreach ( $replacements as $key => $value ) {
			$content = str_replace( $key, $value, $content );
		}

		/**
		 * Filter the content of advanced-cache.php file.
		 *
		 * @since 2.1
		 *
		 * @param string $content The content that will be printed in advanced-cache.php.
		*/
		return (string) apply_filters( 'rocket_advanced_cache_file', $content );
	}

	/**
	 * This warning is displayed when the advanced-cache.php file isn't writeable
	 *
	 * @since 3.6 Moved to a method in AdvancedCache
	 * @since 2.0
	 *
	 * @return void
	 */
	public function notice_permissions() {
		if ( ! $this->is_user_allowed() ) {
			return;
		}

		if (
			$this->filesystem->is_writable( "{$this->content_dir}/advanced-cache.php" )
			|| rocket_get_constant( 'WP_ROCKET_ADVANCED_CACHE' )
		) {
			return;
		}

		$notice_name = 'rocket_warning_advanced_cache_permissions';

		if (
			in_array(
				$notice_name,
				(array) get_user_meta( get_current_user_id(), 'rocket_boxes', true ),
				true
			)
		) {
			return;
		}

		rocket_notice_html(
			[
				'status'           => 'error',
				'dismissible'      => '',
				'message'          => $this->get_notice_message(),
				'dismiss_button'   => $notice_name,
				'readonly_content' => $this->get_advanced_cache_content(),
			]
		);
	}

	/**
	 * This warning is displayed when the advanced-cache.php file isn't ours
	 *
	 * @since 3.6 Moved to a method in AdvancedCache
	 * @since 2.2
	 *
	 * @return void
	 */
	public function notice_content_not_ours() {
		global $pagenow;

		if (
			'plugins.php' === $pagenow
			&& isset( $_GET['activate'] ) // phpcs:ignore WordPress.Security.NonceVerification.Recommended
		) {
			return;
		}

		if ( ! $this->is_user_allowed() ) {
			return;
		}

		if ( rocket_get_constant( 'WP_ROCKET_ADVANCED_CACHE' ) ) {
			return;
		}

		rocket_notice_html(
			[
				'status'      => 'error',
				'dismissible' => '',
				'message'     => $this->get_notice_message(),
			]
		);
	}

	/**
	 * Checks if current user can see the notices
	 *
	 * @since 3.6
	 *
	 * @return bool
	 */
	private function is_user_allowed() {
		return current_user_can( 'rocket_manage_options' ) && rocket_valid_key();
	}

	/**
	 * Gets the message to display in the notice
	 *
	 * @since 3.6
	 *
	 * @return string
	 */
	private function get_notice_message() {
		return rocket_notice_writing_permissions( basename( $this->content_dir ) . '/advanced-cache.php' );
	}
}