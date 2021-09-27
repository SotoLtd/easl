<?php
// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

class EASL_Survey_Monkey {
	protected $base_uri;
	protected $access_token;
	protected $survey_id = 308458560;
	protected $question_ids = '673906712';
	protected $api_client_id = 'lW1o62IFRMe3uePe2cCqyQ';
	protected $api_secret = '248530796968050673612927412265364637108';
	protected $version = '1.1';
	protected $commulative_survey_responses = [];
	protected $db_option_key = 'easl_survey_monkey_names';
	
	/**
	 * Core singleton class
	 * @var self - pattern realization
	 */
	private static $_instance;
	
	/**
	 * Get the instance of EASL_Survey_Monkey
	 *
	 * @return self
	 */
	public static function get_instance() {
		if ( ! ( self::$_instance instanceof self ) ) {
			self::$_instance = new self();
		}
		
		return self::$_instance;
	}
	
	protected function __construct() {
		$this->base_uri     = 'https://api.surveymonkey.net/v3';
		//$this->version      = time();
		$this->access_token = get_field( 'easl_sm_access_token', 'option' );
		
		add_action( 'load-easl-settings_page_survey-monkey-settings', [ $this, 'admin_sync_assets' ] );
		add_action( 'easl-settings_page_survey-monkey-settings', [ $this, 'admin_sync_content' ], 11 );
		
		add_action( 'wp_ajax_easl_sm_sync', [ $this, 'sync_survey_responses' ] );
		
		add_shortcode( 'easl_sm_response_data', [ $this, 'response_data' ] );
		
		add_action( 'init', [ $this, 'init' ] );
	}
	
	public function init() {
		if ( isset( $_REQUEST['easl_sm_webhook_cb'] ) ) {
			$this->process_webhook_callback();
		}
		if(isset($_GET['mhm_sm_db'])) {
		    //var_dump(get_option('easl_sm_callback_raw_header'));
		    //var_dump(get_option('easl_sm_callback_raw_body'));
		    //var_dump(get_option('easl_sm_callback_new_name'));
		    //die();
        }
	}
	
	public function process_webhook_callback() {
		$headers = apache_request_headers();
		$body    = file_get_contents( 'php://input' );
		
		//update_option( 'easl_sm_callback_raw_header', $headers );
		//update_option( 'easl_sm_callback_raw_body', $body );
		
		if ( empty( $headers['Sm-Signature'] ) || ( $headers['Sm-Signature'] != base64_encode( hex2bin( hash_hmac( 'sha1', $body, $this->api_client_id . '&' . $this->api_secret ) ) ) ) ) {
			die();
		}
		$body = json_decode( $body );
		if ( ! $body ) {
			die();
		}
		
		$response_name_field = $this->get_response_details_by_id( $body->object_id );
		
		if($response_name_field) {
		    $this->add_respondent_to_db($response_name_field);
        }
		
		die();
	}
	
	protected function add_respondent_to_db($new_name) {
	    $existing_names = get_option($this->db_option_key, array());
	    if(!is_array($existing_names)) {
	        $existing_names = [];
        }
	    $existing_names[] = $new_name;
	    update_option($this->db_option_key, $existing_names);
    }
	
	public function admin_sync_assets() {
		wp_enqueue_style( 'easl-sm-style', get_stylesheet_directory_uri() . '/assets/css/admin/survey-monkey.css', [], $this->version );
		wp_enqueue_script( 'easl-sm-scripts', get_stylesheet_directory_uri() . '/assets/js/admin/survey-monkey.js', [ 'jquery' ], $this->version, true );
	}
	
	public function admin_sync_content() {
		?>
        <div class="easl-sm-sync">
            <p>
                <button id="easl-sm-sync-button" data-nonce="<?php echo wp_create_nonce( 'easl-sm-sync' ); ?>">Synchronise
                    <em>Open Letter to the EU Institutions on Liver Cancer Care</em>
                    <span>
                        <i class="fa fa-spinner fa-spin"></i>
                        <i class="fa fa-check" aria-hidden="true"></i>
                        <i class="fa fa-times" aria-hidden="true"></i>
                    </span>
                </button>
            </p>
        </div>
		<?php
	}
	
	public function sync_survey_responses() {
		if ( empty( $_POST['_smnonce'] ) || ! wp_verify_nonce( $_POST['_smnonce'], 'easl-sm-sync' ) ) {
			wp_send_json( [
				'Status' => 'Failed',
				'Msg'    => "You're not allowed to do this action!"
			] );
		}
		$status = $this->get_responses();
		if ( ! $status || empty( $this->commulative_survey_responses ) ) {
			wp_send_json( [
				'Status' => 'Failed',
				'Msg'    => "Responses could not be retrieved from Survey Monkey",
			] );
		}
		update_option( $this->db_option_key, $this->commulative_survey_responses );
		wp_send_json( [
			'Status' => 'Success',
			'Msg'    => "Responses synced with Survey Monkey",
			'Count'  => count($this->commulative_survey_responses)
		] );
	}
	
	
	public function get_responses( $page = 1 ) {
		$url  = "{$this->base_uri}/surveys/{$this->survey_id}/responses/bulk";
		$data = [
			'per_page'     => 100,
			'status'       => 'completed',
			'question_ids' => $this->question_ids,
			'page'         => $page,
		];
		$url  = add_query_arg( $data, $url );
		$args = array(
			'timeout'     => 100,
			'redirection' => 5,
			'httpversion' => '1.1',
			'blocking'    => true,
			'headers'     => [
				'Content-Type'  => 'application/json',
				'Cache-Control' => 'no-cache',
				'Authorization' => "bearer {$this->access_token}",
			],
		);
		
		$response = wp_remote_get( $url, $args );
		
		$response_code = wp_remote_retrieve_response_code( $response );
		
		if ( 200 != $response_code ) {
			return false;
		}
		$body = wp_remote_retrieve_body( $response );
		
		if ( $body ) {
			$body = json_decode( $body );
		}
		if ( empty( $body->data ) && ! is_array( $body->data ) ) {
			return false;
		}
		$this->parse_and_load_survey_response( $body->data );
		if ( ! empty( $body->links->next ) ) {
			return $this->get_responses( $page + 1 );
		}
		
		return true;
	}
	
	
	public function get_response_details_by_id( $id ) {
		$url  = "{$this->base_uri}/surveys/{$this->survey_id}/responses/{$id}/details";
		$data = [
			'question_ids' => $this->question_ids,
		];
		$url  = add_query_arg( $data, $url );
		$args = array(
			'timeout'     => 100,
			'redirection' => 5,
			'httpversion' => '1.1',
			'blocking'    => true,
			'headers'     => [
				'Content-Type'  => 'application/json',
				'Cache-Control' => 'no-cache',
				'Authorization' => "bearer {$this->access_token}",
			],
		);
		
		$response = wp_remote_get( $url, $args );
		
		$response_code = wp_remote_retrieve_response_code( $response );
		
		if ( 200 != $response_code ) {
			return false;
		}
		$body = wp_remote_retrieve_body( $response );
		
		if ( $body ) {
			$body = json_decode( $body );
		}
		if ( !$body ) {
			return false;
		}
		$name = $this->parse_single_response( $body );
		
		
		return $name;
	}
	
	public function parse_and_load_survey_response( $responses ) {
		foreach ( $responses as $response ) {
			if ( empty( $response->pages[0] ) ) {
				continue;
			}
			if ( empty( $response->pages[0]->questions[0] ) ) {
				continue;
			}
			if ( empty( $response->pages[0]->questions[0]->answers[0] ) ) {
				continue;
			}
			$answers = $response->pages[0]->questions[0]->answers;
			foreach ( $answers as $answer ) {
				if ( 4428789973 == $answer->row_id ) {
					$this->commulative_survey_responses[] = $answer->text;
				}
			}
			
		}
	}
	
	public function parse_single_response( $response ) {
		if ( empty( $response->pages[0] ) ) {
			return '';
		}
		if ( empty( $response->pages[0]->questions[0] ) ) {
			return '';
		}
		if ( empty( $response->pages[0]->questions[0]->answers[0] ) ) {
			return '';
		}
		$answers = $response->pages[0]->questions[0]->answers;
		foreach ( $answers as $answer ) {
			if ( 4428789973 == $answer->row_id ) {
				return $answer->text;
			}
		}
		return '';
	}
	
	public function response_data( $attr, $tag ) {
		$names = get_option( $this->db_option_key );
		if ( ! $names ) {
			return '';
		}
		$html = '';
		foreach ( $names as $name ) {
			$html .= '<li><span>' . $name . '</span></li>';
		}
		
		return '<div class="easl-sm-names"><ul>' . $html . '</ul></div>';
	}
}

EASL_Survey_Monkey::get_instance();