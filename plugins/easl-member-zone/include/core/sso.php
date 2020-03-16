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
            'redirect_url' => $this->redirect_url,
            'client_id' => $this->client_id
        ];
        $query_string = build_query($data);
        return $this->base_url . '/auth?' . $query_string;
    }

    public function handle_auth_code($code) {

        $api = EASL_MZ_API::get_instance();
        $session = EASL_MZ_Session_Handler::get_instance();

        if (isset($_GET['code'])) {
            $data = [
                'client_id' => $this->client_id,
                'redirect_url' => $this->redirect_url,
                'client_secret' => $this->client_secret,
                'code' => $code,
                'grant_type' => 'authorization_code',
                'scope' => 'profile email'
            ];

            $this->request->post('/token', $data, 'body', [], true, false);

            $response = $this->request->get_response_body();

            $redirect = get_field('member_dashboard_url', 'options');

            if (isset($response->access_token)) {
                $access_token = $response->access_token;

                $this->request->set_request_header('Authorization', 'Bearer ' . $access_token);
                $this->request->get('/userinfo');

                $response = $this->request->get_response_body();

                $response_data = json_decode(json_encode($response), true);

                $api->set_credentials(['access_token' => $response_data['sugarcrm_token']], true);

                $session->add_data('access_token', $response_data['sugarcrm_token']);

                // Member authenticated
                do_action( 'easl_mz_member_authenticated', $response->email, $response_data, $redirect );

                $member_id = $api->get_member_id();

                if ( $member_id ) {
                    $session->add_data( 'member_id', $member_id );
                    $session->save_session_data();
                }
            }
            wp_redirect($redirect);
        }
    }
}