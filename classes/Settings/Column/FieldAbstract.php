<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

abstract class AC_Settings_Column_FieldAbstract {

	/**
	 * @var AC_Settings_Column
	 */
	protected $settings;

	/**
	 * @var array
	 */
	private $args;

	public function __construct( array $args = array() ) {
		$this->args = array(
			'label'       => '', // empty label will apply colspan 2
			'description' => '',
		);
	}

	/**
	 * Display the field
	 */
	public abstract function display_field();

	/**
	 * @param AC_Settings_Column $settings
	 *
	 * @since NEWVERSION
	 * @return $this
	 */
	public function set_settings( AC_Settings_Column $settings ) {
		$this->settings = $settings;

		return $this;
	}

	public function get_arg( $key ) {
		if ( ! isset( $this->args[ $key ] ) ) {
			return false;
		}

		return $this->args[ $key ];
	}

	protected function set_arg( $key, $value ) {
		$this->args[ $key ] = $value;

		return $this;
	}

	/**
	 * Merge a set of arg with the current args
	 *
	 * @param array $args
	 */
	public function merge_args( array $args ) {
		$this->args = wp_parse_args( $args, $this->args );
	}

	/**
	 * Formats value based on stored field settings
	 *
	 * @param array|string $value
	 *
	 * @return string mixed
	 */
	public function format( $value ) {
		return $value;
	}

	/**
	 * @param string|int $default
	 *
	 * @return AC_Settings_Column_FieldAbstract
	 */
	public function set_default( $default ) {
		return $this->set_arg( 'default', $default );
	}

	/**
	 * Return the stored value
	 *
	 * @return string|array
	 */
	public function get_value() {
		$value = $this->settings->get_value( $this->get_arg( 'name' ) );

		if ( false === $value ) {
			$value = $this->get_arg( 'default' );
		}

		return $value;
	}

	public function get_attribute( $key, $value = null ) {
		if ( null === $value ) {
			$value = $this->get_arg( 'name' );
		}

		$column = $this->settings->get_column();

		switch ( $key ) {
			case 'id':
				return sprintf( 'cpac-%s-%s', $column->get_name(), $value );
			case 'name':
				return sprintf( '%s[%s]', $column->get_name(), $value );
		}

		return false;
	}

	/**
	 * @since NEWVERSION
	 */
	public function display() {
		$class = sprintf( '%s column-%s', $this->get_arg( 'type' ), $this->get_arg( 'name' ) );

		if ( $this->get_arg( 'hidden' ) ) {
			$class .= ' hide';
		}

		if ( $this->get_arg( 'section' ) ) {
			$class .= ' section';
		}

		$data_handle = $this->get_arg( 'toggle_handle' ) ? $this->get_attribute( 'id', $this->get_arg( 'toggle_handle' ) ) : '';
		$data_refresh = $this->get_arg( 'refresh_column' ) ? 1 : 0;

		?>
		<tr class="<?php echo esc_attr( $class ); ?>" data-handle="<?php echo esc_attr( $data_handle ); ?>" data-refresh="<?php echo esc_attr( $data_refresh ); ?>">
			<?php $this->display_label(); ?>

			<?php

			$data_trigger = $this->get_arg( 'toggle_trigger' ) ? $this->get_attribute( 'id', $this->get_arg( 'toggle_trigger' ) ) : '';
			$colspan = trim( $this->get_arg( 'label' ) ) ? 1 : 2;

			?>

			<td class="input" data-trigger="<?php echo $data_trigger; ?>" colspan="<?php echo $colspan; ?>">
				<?php $this->display_field(); ?>

				<?php if ( $this->get_arg( 'help' ) ) : ?>
					<p class="help-msg">
						<?php echo $this->get_arg( 'help' ); ?>
					</p>
				<?php endif; ?>
			</td>
		</tr>
		<?php
	}

	protected function display_label() {
		if ( ! $this->get_arg( 'label' ) ) {
			return;
		}

		$class = 'label';

		if ( $this->get_arg( 'description' ) ) {
			$class .= ' description';
		}

		?>

		<td class="<?php echo esc_attr( $class ); ?>">
			<label for="<?php esc_attr( $this->get_attribute( 'id' ) ); ?>">
				<span class="label"><?php echo stripslashes( $this->get_arg( 'label' ) ); ?></span>
				<?php if ( $this->get_arg( 'more_link' ) ) : ?>
					<a target="_blank" class="more-link" title="<?php esc_attr_e( 'View more' ); ?>" href="<?php echo esc_url( $this->get_arg( 'more_link' ) ); ?>">
						<span class="dashicons dashicons-external"></span>
					</a>
				<?php endif; ?>
				<?php if ( $this->get_arg( 'description' ) ) : ?>
					<p class="description"><?php echo $this->get_arg( 'description' ); ?></p>
				<?php endif; ?>
			</label>
		</td>

		<?php
	}

	/**
	 * Converts object to array that is suitable for using with formfields
	 *
	 */
	public function to_formfield() {
		return wp_parse_args( $this->args, array(
			'attr_name' => $this->get_attribute( 'name' ),
			'attr_id'   => $this->get_attribute( 'id' ),
			'current'   => $this->get_value(),
		) );
	}

	/**
	 * Convert field to object
	 *
	 * @return object
	 */
	public function to_object() {
		return (object) $this->args;
	}

}