<?php
/**
 * Handles flatsome option upgrades
 *
 * @author     UX Themes
 * @category   Class
 * @package    Flatsome/Classes
 * @since      3.4.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Flatsome_Upgrade
 */
class Flatsome_Upgrade {

	/**
	 * Holds flatsome DB version
	 *
	 * @var string
	 */
	private $db_version;

	/**
	 * Holds flatsome current running parent theme version
	 *
	 * @var string
	 */
	private $running_version;

	/**
	 * Holds is upgrade completed
	 *
	 * @var bool
	 */
	private $is_upgrade_completed = false;

	/**
	 * Holds update callback that need to be run per version
	 *
	 * @var array
	 */
	private $updates = array(
		'3.4.0' => array(
			'update_340',
		),
	);

	/**
	 * Flatsome_Upgrade Class constructor
	 */
	public function __construct() {

		add_action( 'init', array( $this, 'check_version' ), 5, 0 );
	}

	/**
	 * Check Flatsome version and run the updater if required.
	 */
	public function check_version() {

		$theme = wp_get_theme( get_template() );
		$this->db_version = get_theme_mod( 'flatsome_db_version', '3.0.0' );
		$this->running_version = $theme->version;

		// If current version is new and current version has any update run it.
		if ( version_compare( $this->db_version, $this->running_version, '<' ) && version_compare( $this->db_version, max( array_keys( $this->updates ) ), '<' ) ) {
			$this->update();
			if ( $this->is_upgrade_completed ) {
				$this->update_db_version();
			}
		}
	}

	/**
	 * Push all needed updates
	 */
	private function update() {

		foreach ( $this->updates as $version => $update_callbacks ) {
			if ( version_compare( $this->db_version, $version, '<' ) ) {

				// Run all callbacks.
				foreach ( $update_callbacks as $update_callback ) {
					if ( method_exists( $this, $update_callback ) ) {
						$this->$update_callback();
					} elseif ( function_exists( $update_callback ) ) {
						$update_callback();
					}
				}
			}
		}
		$this->is_upgrade_completed = true;
	}

	/**
	 * Performs upgrades to Flatsome 3.4.0
	 */
	private function update_340() {
		$portfolio_archive_filter = get_theme_mod( 'portfolio_archive_filter' );
		if ( empty( $portfolio_archive_filter ) ) {
			set_theme_mod( 'portfolio_archive_filter', 'left' );
		}
	}

	/**
	 * Set the DB version to the current running version.
	 * Should only be called when all upgrades are performed.
	 */
	private function update_db_version() {
		set_theme_mod( 'flatsome_db_version', $this->running_version );
	}
}

new Flatsome_Upgrade();
