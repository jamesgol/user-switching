<?php

class WP_Stream_Connector_User_Switching extends WP_Stream_Connector {

	/**
	 * Connector slug
	 *
	 * @var string
	 */
	public static $name = 'user-switching';

	/**
	 * Actions registered for this connector
	 *
	 * @var array
	 */
	public static $actions = array(
		'pre_switch_to_user',
		'pre_switch_back_user',
		'pre_switch_off_user',
	);

	/**
	 * Return translated connector label
	 *
	 * @return string Translated connector label
	 */
	public static function get_label() {
		return __( 'User Switching', 'user-switching' );
	}

	/**
	 * Return translated action labels
	 *
	 * @return array Action label translations
	 */
	public static function get_action_labels() {
		return array(
			'switch_to_user' => __( 'Switch&nbsp;To', 'user-switching' ),
			'switch_off'     => __( 'Switch Off', 'user-switching' ),
		);
	}

	/**
	 * Return translated context labels
	 *
	 * @return array Context label translations
	 */
	public static function get_context_labels() {
		return array();
	}

	/**
	 * Add action links to Stream drop row in admin list screen
	 *
	 * @filter wp_stream_action_links_{connector}
	 * @param  array $links      Previous links registered
	 * @param  object $record    Stream record
	 * @return array             Action links
	 */
	public static function action_links( $links, $record ) {
		if ( $record->object_id ) {
			if ( $link = get_edit_user_link( $record->object_id ) ) {
				$links [ __( 'Edit User', 'default' ) ] = $link;
			}
		}

		return $links;
	}

	public static function callback_pre_switch_to_user( $new_user_id, $old_user_id ) {

		foreach ( array( 'clear_auth_cookie', 'set_logged_in_cookie' ) as $action ) {
			remove_action( $action, array( 'WP_Stream_Connector_Users', 'callback' ), null );
		}

		$new_user = get_userdata( $new_user_id );

		self::log(
			_x(
				'Switched to %1$s (%2$s).',
				'1: User display name, 2: Username',
				'user-switching'
			),
			array(
				$new_user->display_name,
				$new_user->user_login,
			),
			$new_user_id,
			array( 'sessions' => 'switch_to_user' ),
			$old_user_id
		);
	}

	public static function callback_pre_switch_back_user( $new_user_id, $old_user_id ) {

		foreach ( array( 'clear_auth_cookie', 'set_logged_in_cookie' ) as $action ) {
			remove_action( $action, array( 'WP_Stream_Connector_Users', 'callback' ), null );
		}

		$new_user = get_userdata( $new_user_id );

		if ( ! $old = get_current_user_id() ) {
			$old = $new_user_id;
		}

		self::log(
			_x(
				'Switched back to %1$s (%2$s).',
				'1: User display name, 2: Username',
				'user-switching'
			),
			array(
				$new_user->display_name,
				$new_user->user_login,
			),
			$new_user_id,
			array( 'sessions' => 'switch_to_user' ),
			$old
		);
	}

	public static function callback_pre_switch_off_user( $old_user_id ) {

		remove_action( 'clear_auth_cookie', array( 'WP_Stream_Connector_Users', 'callback' ), null );

		self::log(
			_x(
				'Switched off',
				'',
				'user-switching'
			),
			array(),
			$old_user_id,
			array( 'sessions' => 'switch_off' ),
			$old_user_id
		);
	}

}
