<?php

class EASL_MZ_SSO {

    private $request;
    private $client_id;
    private $client_secret;
    private $redirect_url;
    private $base_url;

    private static $_instance;

    protected function __construct() {
        $this->base_url = untrailingslashit(get_field('sso_base_url', 'option'));

        $this->client_id = get_field('sso_client_id', 'option');
        $this->client_secret  = get_field('sso_client_secret', 'option');
        $this->redirect_url = get_field('sso_redirect_url', 'option');

        $this->request = new EASL_MZ_Request($this->base_url);
    }

    public static function get_instance() {
        if ( ! ( self::$_instance instanceof self ) ) {
            self::$_instance = new self();
        }

        return self::$_instance;
    }

    public function get_login_url() {
        $data = [
            'response_type' => 'code',
            'redirect_uri' => $this->redirect_url,
            'client_id' => $this->client_id
        ];
        $query_string = build_query($data);
        return $this->base_url . '/auth?' . $query_string;
    }

    public function get_logout_url() {
        $data = [
            //'redirect_uri' => 'https://slo.easl.eu/?slo_app_id=easldev',
            'redirect_uri' => 'https://easldev.websitestage.co.uk/?mz_logout=1'
        ];
        $query_string = build_query($data);
        return $this->base_url . '/logout?' . $query_string;
    }

    public function handle_auth_code($code) {
        $session = EASL_MZ_Session_Handler::get_instance();

	    $redirect = 'https://' . $_SERVER['HTTP_HOST'] . rtrim(strtok($_SERVER['REQUEST_URI'], '?'), '/');
	    $redirect = trailingslashit($redirect);

        $access_token_details = $this->get_access_token_details($code);
        // Access code is denied
        if(!$access_token_details) {
	        $redirect = add_query_arg(array(
	        	'ec' => 401 //@todo some error code can be introduce to display messages on frontend  so user can know what happened
	        ), $redirect);
	        easl_mz_redirect($redirect);
        }
        // Access code accepted, access token returned
	    $crm_member_details = $this->get_crm_member_details($access_token_details['access_token']);

        if(!$crm_member_details) {
	        $redirect = add_query_arg(array(
		        'ec' => 404 //@todo some error code can be introduce to display messages on frontend  so user can know what happened
	        ), $redirect);
	        easl_mz_redirect($redirect);
        }

	    // Member authenticated
	    $session_details = array_merge($access_token_details, $crm_member_details);
	    do_action( 'easl_mz_member_authenticated', $crm_member_details['email'], $session_details, $redirect );

	    easl_mz_redirect($redirect);
    }

    public function get_access_token_details($code) {
	    $redirect_url = 'https://' . $_SERVER['HTTP_HOST'] . rtrim(strtok($_SERVER['REQUEST_URI'], '?'), '/');
	    $data = [
		    'client_id' => $this->client_id,
		    'redirect_uri' => $redirect_url,
		    'client_secret' => $this->client_secret,
		    'code' => $code,
		    'grant_type' => 'authorization_code',
		    'scope' => 'profile email'
	    ];

	    $this->request->post('/token', $data, 'body', [], true, false);

	    $response = $this->request->get_response_body();

	    if (empty($response->access_token)) {
	    	return false;
	    }
	    return array(
		    'access_token'       => $response->access_token,
		    'refresh_token'      => $response->refresh_token,
		    'expires_in'         => intval( $response->expires_in ),
		    'refresh_expires_in' => intval( $response->refresh_expires_in ),
		    'login'              => time(),
	    );
    }
    public function get_crm_member_details($access_token) {
	    $this->request->set_request_header('Authorization', 'Bearer ' . $access_token);
	    $this->request->get('/userinfo');

	    $response = $this->request->get_response_body();
	    if(empty($response->email)) {
	    	return false;
	    }
        $crm_api = easl_mz_get_manager()->getApi();
        $crm_api->get_user_auth_token();
        $member_details = $crm_api->get_member_by_email($response->email, false);
        if(!$member_details) {
            return false;
        }

	    $member_data = array(
	    	'member_id' => $member_details->id,
	    	'email' => $response->email,
	    	'title' => $response->title,
	    	'first_name' => $response->given_name,
	    	'last_name' => $response->family_name,
	    	'membership_status' => $response->membership_status,
	    );

	    return $member_data;
    }
}