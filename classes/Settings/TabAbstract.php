<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

Abstract class AC_Settings_TabAbstract {

	/**
	 * Should this tab be displayed when no tab is selected
	 *
	 * @var bool
	 */
	private $default;

	/**
	 * @var string
	 */
	private $slug;

	/**
	 * @var string
	 */
	private $label;

	/**
	 * Is this the default to to display when no active tab is present
	 *
	 * @return bool
	 */
	public function is_default() {
		return (bool) $this->default;
	}

	public function set_default( $default ) {
		$this->default = (bool) $default;

		return $this;
	}

	public function get_slug() {
		return $this->slug;
	}

	public function set_slug( $slug ) {
		$this->slug = sanitize_key( $slug );

		return $this;
	}

	public function get_label() {
		return $this->label;
	}

	public function set_label( $label ) {
		$this->label = sanitize_title( $label );

		return $this;
	}

	/**
	 * Display tab
	 *
	 * @return void
	 */
	public abstract function display();

	/**
	 * Cast tab to an array
	 *
	 * @return array
	 */
	public function to_array() {
		return array(
			'slug'    => $this->get_slug(),
			'label'   => $this->get_label(),
			'default' => $this->is_default(),
		);
	}

	/**
	 * Show the label of the tab
	 *
	 * @return string
	 */
	public function __toString() {
		return $this->get_label();
	}

}