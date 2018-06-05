<?php
namespace W4dev\Wpform\Api;

class Users
{
	protected $namespace = 'w4dev_wpform/v2';
	protected $rest_base = 'users';

	public function register_routes()
	{
		register_rest_route(
			$this->namespace, '/'. $this->rest_base, array(
				array(
					'methods'             => \WP_REST_Server::READABLE,
					'callback'            => [$this, 'get_items']
				)
			)
		);
	}

	public function get_items($request)
	{
		$params = $request->get_params();
		$args = [];
		if (isset($params['per_page'])) {
			$args['number'] = $params['per_page'];
		}
		if (isset($params['search'])) {
			$args['search'] = "*". $params['search'] ."*";
		}
		if (isset($params['page'])) {
			$args['offset'] = ($params['page']-1) * $args['number'];
		}
		if (isset($params['selected'])) {
			$args['include'] = wp_parse_id_list($params['selected']);
		}

		#if (is_user_logged_in()) {
			#die('Logged in ');
		#}

		$this->validate_cookie_user();

		#print_r(get_current_user_id());
		#die();
		$items = [];
		$total = 0;

		$query = new \WP_User_Query($args);
		if ($query->total_users > 0) {
			$total = $query->total_users;
			#print_r($query);
			#die();
	
			foreach ($query->get_results() as $user) {
				$items[] = [
					'id' 	=> $user->ID,
					'text' 	=> $user->display_name . ' ('. $user->ID .' # '. $user->user_login .')'
				];
			}
		}

		return [
			'total' => $total,
			'items' => $items
		];
	}

	protected function validate_cookie_user()
	{
		if (isset($_COOKIE[LOGGED_IN_COOKIE]) && $user_id = wp_validate_auth_cookie($_COOKIE[LOGGED_IN_COOKIE], 'logged_in')) {
			wp_set_current_user( $user_id );
		}
	}
}

