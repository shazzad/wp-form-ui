<?php
namespace Shazzad\WpFormUi\Api;

use WP_REST_Server;
use WP_User_Query;

class Users {

	protected $namespace = 'swpfu/v2';

	protected $rest_base = 'users';

	public function register_routes() {
		register_rest_route(
			$this->namespace,
			$this->rest_base,
			array(
				array(
					'methods'             => WP_REST_Server::READABLE,
					'callback'            => [ $this, 'get_items' ],
					'callback_permission' => [ $this, 'get_items_permissions_check' ],
				)
			)
		);
	}

	public function get_items( $request ) {
		$params = $request->get_params();

		$args = [];
		if ( isset( $params['per_page'] ) ) {
			$args['number'] = $params['per_page'];
		}
		if ( isset( $params['search'] ) ) {
			$args['search'] = "*" . $params['search'] . "*";
		}
		if ( isset( $params['page'] ) ) {
			$args['offset'] = ( $params['page'] - 1 ) * $args['number'];
		}
		if ( isset( $params['selected'] ) ) {
			$args['include'] = wp_parse_id_list( $params['selected'] );
		}

		$items = [];
		$total = 0;

		$query = new WP_User_Query( $args );
		if ( $query->total_users > 0 ) {
			$total = $query->total_users;

			foreach ( $query->get_results() as $user ) {
				$items[] = [ 
					'id'   => $user->ID,
					'text' => $user->display_name . ' (' . $user->ID . ' # ' . $user->user_login . ')'
				];
			}
		}

		return [ 
			'total' => $total,
			'items' => $items
		];
	}

	public function get_items_permissions_check() {
		return current_user_can( 'list_users' );
	}
}
