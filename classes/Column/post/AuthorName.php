<?php
defined( 'ABSPATH' ) or die();

/**
 * Column displaying information about the author of a post, such as the
 * author's display name, user ID and email address.
 *
 * @since 2.0
 */
class AC_Column_Post_AuthorName extends CPAC_Column {

	public function init() {
		parent::init();

		$this->properties['type'] = 'column-author_name';
		$this->properties['label'] = __( 'Display Author As', 'codepress-admin-columns' );
		$this->properties['is_cloneable'] = true;
		$this->properties['object_property'] = 'post_author';
	}

	public function get_value( $post_id ) {
		$value = '';

		if ( $user_id = $this->get_raw_value( $post_id ) ) {
			$value = $this->get_user_formatted( $user_id );
		}

		switch ( $this->get_option( 'user_link_to' ) ) {
			case 'edit_user' :
				$link = get_edit_user_link( $user_id );
				break;
			case 'view_user_posts' :
				$link = add_query_arg( array(
					'post_type' => get_post_field( 'post_type', $post_id ),
					'author'    => get_the_author_meta( 'ID' )
				), 'edit.php' );
				break;
			case 'view_author' :
				$link = get_author_posts_url( $user_id );
				break;
			default:
				$link = '';
		}

		if ( $link ) {
			$value = '<a href="' . esc_url( $link ) . '">' . $value . '</a>';
		}

		return $value;
	}

	public function get_raw_value( $post_id ) {
		return get_post_field( 'post_author', $post_id );
	}

	public function display_settings() {
		$this->display_field_user_format();
		$this->display_field_user_link_to();
	}

	public function display_field_user_link_to() {
		$this->form_field( array(
			'type'        => 'select',
			'name'        => 'user_link_to',
			'label'       => __( 'Link To', 'codepress-admin-columns' ),
			'options'     => array(
				''                => __( 'None' ),
				'edit_user'       => __( 'Edit User Profile' ),
				'view_user_posts' => __( 'View User Posts' ),
				'view_author'     => __( 'View Public Author Page', 'codepress-admin-columns' )
			),
			'description' => __( 'Page the author name should link to.', 'codepress-admin-columns' )
		) );
	}

}