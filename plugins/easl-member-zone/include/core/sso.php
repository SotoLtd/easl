<?php

class EASL_MZ_SSO {
    
    private $client_id;
    private $client_secret;
    private $redirect_url;
    private $base_url;
    
    private $login_endpoint_url;
    private $user_info_endpoint_url;
    private $token_validation_endpoint_url;
    private $endsession_endpoint_url;
    private $scope;
    
    private static $_instance;
    
    /**
     * Constructor
     */
    protected function __construct() {
        $this->base_url = untrailingslashit( get_field( 'sso_base_url', 'option' ) );
        
        $this->client_id     = get_field( 'sso_client_id', 'option' );
        $this->client_secret = get_field( 'sso_client_secret', 'option' );
        $this->redirect_url  = get_field( 'sso_redirect_url', 'option' );
        
        $this->login_endpoint_url            = $this->base_url . '/auth';
        $this->user_info_endpoint_url        = $this->base_url . '/userinfo';
        $this->token_validation_endpoint_url = $this->base_url . '/token';
        $this->endsession_endpoint_url       = $this->base_url . '/logout';
        $this->scope                         = 'email profile openid';
        
        $this->logout_redirec_url = 'https://easl.eu/?mz_logout=1';
    }
    
    /**
     * get singleton instance
     * @return EASL_MZ_SSO
     */
    public static function get_instance() {
        if ( ! ( self::$_instance instanceof self ) ) {
            self::$_instance = new self();
        }
        
        return self::$_instance;
    }
    
    /**
     * Generate a new state, save it as a transient, and return the state hash.
     *
     * @param string $redirect_to The redirect URL to be used after IDP authentication.
     *
     * @return string
     */
    public function new_state( $redirect_to ) {
        // New state w/ timestamp.
        $state       = md5( mt_rand() . microtime( true ) );
        $state_value = array(
            $state => array(
                'redirect_to' => $redirect_to,
            ),
        );
        
        //set_transient( 'sso-state--' . $state, $state_value, 100 );
        
        return $state;
    }
    
    /**
     * @return mixed
     */
    public function get_current_page_url() {
        return $this->redirect_url;// TODO replace it with the current page
    }
    
    /**
     * get authentication url
     * @return string
     */
    public function get_login_url() {
        $login_url = sprintf(
            '%1$s%2$sresponse_type=code&scope=%3$s&client_id=%4$s&state=%5$s&redirect_uri=%6$s',
            $this->login_endpoint_url,
            '?',
            rawurlencode( $this->scope ),
            $this->client_id,
            $this->new_state( $this->get_current_page_url() ),
            rawurlencode( $this->redirect_url )
        );
        
        return $login_url;
    }
    
    /**
     * get logout url
     * @return string
     */
    public function get_logout_url() {
        $id_token = EASL_MZ_Session_Handler::get_instance()->get_data( 'id_token' );
        
        $logout_url = sprintf(
            '%s?id_token_hint=%s&post_logout_redirect_uri=%s',
            $this->endsession_endpoint_url,
            $id_token,
            urlencode( $this->logout_redirec_url )
        );
        
        return $logout_url;
    }
    
    
    /**
     * Handle openID return response
     *
     * @param $code
     */
    public function handle_auth_code( $code ) {
//        if ( ! isset( $_GET['state'] ) || ! $this->validate_state( $_GET['state'] ) ) {
//            easl_mz_redirect( add_query_arg(
//                [
//                    'sso_error' => 'no_state'
//                ],
//                $this->redirect_url
//            ) );
//        }
        
        $access_token_details = $this->get_access_token_details( $code );
        
        if ( ! $access_token_details ) {
            easl_mz_redirect( add_query_arg(
                [
                    'sso_error' => 'invalid_at'
                ],
                $this->redirect_url
            ) );
        }
        
        $crm_member_details = $this->get_crm_member_details( $access_token_details['access_token'], $access_token_details['id_token_claim'] );
        
        if ( ! $crm_member_details ) {
            easl_mz_redirect( add_query_arg(
                [
                    'sso_error' => 'member_not_found'
                ],
                $this->redirect_url
            ) );
        }
        
        // Member authenticated
        $session_details = array_merge( $access_token_details, $crm_member_details );
        /**
         *
         */
        do_action( 'easl_mz_member_authenticated', $crm_member_details['email'], $session_details, $this->redirect_url );
        
        easl_mz_redirect( $this->redirect_url );
    }
    
    /**
     * @param $code
     *
     * @return array|false
     */
    public function get_access_token_details( $code ) {
        $request = array(
            'body' => array(
                'code'          => $code,
                'client_id'     => $this->client_id,
                'client_secret' => $this->client_secret,
                'redirect_uri'  => $this->redirect_url,
                'grant_type'    => 'authorization_code',
                'scope'         => $this->scope,
            ),
        );
        // Call the server and ask for a token.
        $response = wp_remote_post( $this->token_validation_endpoint_url, $request );
        
        if ( is_wp_error( $response ) ) {
            return false;
        }
        $response = json_decode( $response['body'], true );
        if ( is_null( $response ) || isset( $response['error'] ) ) {
            return false;
        }
        if ( ! isset( $response['id_token'] ) ||
             ! isset( $response['token_type'] ) || strcasecmp( $response['token_type'], 'Bearer' )
        ) {
            return false;
        }
        
        $id_token_claim = $this->get_id_token_claim( $response );
        if ( ! $id_token_claim || empty( $id_token_claim['sub'] ) ) {
            return false;
        }
        
        return array(
            'access_token'       => $response['access_token'],
            'refresh_token'      => $response['refresh_token'],
            'expires_in'         => intval( $response['expires_in'] ),
            'refresh_expires_in' => intval( $response['refresh_expires_in'] ),
            'login'              => time(),
            'authorization_code' => $code,
            'id_token'           => $response['id_token'],
            'id_token_claim'     => $id_token_claim
        );
    }
    
    /**
     * get id token
     *
     * @param $token_response
     *
     * @return false|mixed
     */
    public function get_id_token_claim( $token_response ) {
        // Validate there is an id_token.
        if ( ! isset( $token_response['id_token'] ) ) {
            return false;
        }
        
        // Break apart the id_token in the response for decoding.
        $tmp = explode( '.', $token_response['id_token'] );
        
        if ( ! isset( $tmp[1] ) ) {
            return false;
        }
        
        // Extract the id_token's claims from the token.
        $id_token_claim = json_decode(
            base64_decode(
                str_replace( // Because token is encoded in base64 URL (and not just base64).
                    array( '-', '_' ),
                    array( '+', '/' ),
                    $tmp[1]
                )
            ),
            true
        );
        
        return $id_token_claim;
    }
    
    /**
     * get crm members details
     *
     * @param $access_token
     *
     * @return array|false
     */
    public function get_crm_member_details( $access_token, $id_token_claim ) {
        $request = [
            'headers' => [
                'Authorization' => 'Bearer ' . $access_token,
            ]
        ];
        
        $response = wp_remote_post( $this->user_info_endpoint_url, $request );
        
        if ( is_wp_error( $response ) || ! isset( $response['body'] ) ) {
            return false;
        }
        $user_claim = json_decode( $response['body'], true );
        
        if ( ! is_array( $user_claim ) || isset( $user_claim['error'] ) ) {
            return false;
        }
        if ( $id_token_claim['sub'] !== $user_claim['sub'] ) {
            return false;
        }
        if ( empty( $user_claim['email'] ) ) {
            return false;
        }
        $crm_api = easl_mz_get_manager()->getApi();
        $crm_api->get_user_auth_token();
        $member_details = $crm_api->get_member_by_email( $user_claim['email'], false );
        
        if ( ! $member_details ) {
            return false;
        }
        
        $member_data = array(
            'member_id'         => $member_details->id,
            'email'             => $user_claim['email'],
            'title'             => $member_details->salutation,
            'first_name'        => $member_details->first_name,
            'last_name'         => $member_details->last_name,
            'membership_status' => $member_details->dotb_mb_current_status,
        );
        
        return $member_data;
    }
}