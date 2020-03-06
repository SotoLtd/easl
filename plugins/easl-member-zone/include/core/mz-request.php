<?php
// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

class EASL_MZ_Request {
	protected $base_uri;
	protected $response_code;
	protected $response_body;

	protected $request_headers;
	protected $response_headers;

	protected $log_handler;

	public function __construct( $base_uri ) {
		$this->base_uri = $base_uri;
	}

	public function init_logger() {
		if ( is_resource( $this->log_handler ) ) {
			return true;
		}
		$file = $this->get_log_file_path();

		if ( $file ) {
			if ( ! file_exists( $file ) ) {
				$temphandle = @fopen( $file, 'w+' ); // @codingStandardsIgnoreLine.
				@fclose( $temphandle ); // @codingStandardsIgnoreLine.

				if ( defined( 'FS_CHMOD_FILE' ) ) {
					@chmod( $file, FS_CHMOD_FILE ); // @codingStandardsIgnoreLine.
				}
			}

			$resource = @fopen( $file, 'a' ); // @codingStandardsIgnoreLine.

			if ( $resource ) {
				$this->log_handler = $resource;

				return true;
			}
		}

		return false;
	}

	public function close_logger() {
		if ( is_resource( $this->log_handler ) ) {
			fclose( $this->log_handler );// @codingStandardsIgnoreLine.
			$this->log_handler = null;
		}
	}

	public function get_log_file_path() {
		$upload_dir = wp_upload_dir( null, false );

		return $upload_dir['basedir'] . '/mz-logs/' . $this->get_log_file_name();
	}

	public function get_log_file_name() {
		$date_suffix = date( 'Y-m-d', current_time( 'timestamp', true ) );
		$hash_suffix = wp_hash( 'request' );

		return sanitize_file_name( implode( '-', array( 'request', $date_suffix, $hash_suffix ) ) . '.log' );
	}

	public function add_log( $entry ) {
		$result = fwrite( $this->log_handler, $entry . PHP_EOL ); // @codingStandardsIgnoreLine.
	}

	public function log_date_format( $timestamp ) {
		return date( 'c', $timestamp );
	}

	public function log_request( $endpoint, $data = array(), $headers = array() ) {
		if ( ! is_resource( $this->log_handler ) ) {
			return false;
		}
		$this->add_log( $this->log_date_format( time() ) . ' :: Request to ' . $endpoint );
		if ( is_array( $headers ) && count( $headers ) > 0 ) {
			$this->add_log( 'Request headers:' );
			$this->add_log( print_r( $headers, true ) );
		}
		if ( is_array( $data ) && count( $data ) > 1 ) {
			$this->add_log( 'Data:' );
			$this->add_log( print_r( $data, true ) );
		}
		$this->add_log( '____________________' );
	}

	public function log_response( $endpoint, $res_code = '', $headers = array(), $data = false ) {
		if ( ! is_resource( $this->log_handler ) ) {
			return false;
		}
		$this->add_log( $this->log_date_format( time() ) . ' :: Response from ' . $endpoint );
		$this->add_log( 'Response code: ' . $res_code );
		if ( is_array( $headers ) && count( $headers ) > 0 ) {
			$this->add_log( 'Response headers:' );
			$this->add_log( print_r( $headers, true ) );
		}
		if ( is_array( $data ) && count( $data ) > 1 ) {
			$this->add_log( 'Data:' );
			$this->add_log( print_r( $data, true ) );
		}
		$this->add_log( '____________________' );
	}

	public function reset_headers() {
		$this->request_headers  = array();
		$this->response_headers = array();
	}

	public function set_request_header( $key, $value ) {
		$this->request_headers[ $key ] = $value;
	}

	public function response_headers( $key, $value ) {
		$this->request_headers[ $key ] = $value;
	}

	public function reset_response() {
		$this->response_code    = false;
		$this->response_headers = array();
		$this->response_body    = array();
	}

	public function get_response_code() {
		return $this->response_code;
	}

	public function get_response_headers() {
		return $this->response_headers;
	}

	public function get_response_header( $key ) {
		return isset( $this->response_headers[ $key ] ) ? $this->response_headers[ $key ] : '';
	}

	public function get_request_header( $key ) {
		isset( $this->response_headers[ $key ] ) ? $this->response_headers[ $key ] : '';
	}

	public function get_response_body() {
		return $this->response_body;
	}

	public function is_valid_response_code( $codes = array() ) {
		if ( is_int( $codes ) ) {
			$codes = array( $codes );
		}
		if ( in_array( $this->response_code, $codes ) ) {
			return true;
		}

		return false;
	}

	public function post( $endpoint, $data = array(), $data_format = 'body', $cookies = array(), $parse_json = true, $json_encode_body = true ) {
		$url  = $this->base_uri . $endpoint;
		$body_data = $json_encode_body ? json_encode( $data ) : $data;
		$args = array(
			'method'      => 'POST',
			'timeout'     => 45,
			'redirection' => 5,
			'httpversion' => '1.1',
			'blocking'    => true,
			'body'        => $body_data,
			'data_format' => $data_format,
			'headers'     => $this->request_headers,
			'cookies'     => $cookies
		);
		$this->reset_response();

		$this->log_request( $endpoint, $data, $this->request_headers );

		$response               = wp_remote_post( $url, $args );
		$this->response_code    = wp_remote_retrieve_response_code( $response );
		$this->response_headers = wp_remote_retrieve_headers( $response );

		$body = wp_remote_retrieve_body( $response );
		if ( $body ) {
			$this->response_body = $parse_json ? json_decode( $body ) : $body;
		}

		$this->log_response( $endpoint, $this->response_code, $this->response_headers, $this->response_body );
	}

	public function put( $endpoint, $data = array(), $data_format = 'body', $cookies = array(), $parse_json = true, $body_json_encode = true ) {
		$url  = $this->base_uri . $endpoint;
		$args = array(
			'method'      => 'PUT',
			'timeout'     => 45,
			'redirection' => 5,
			'httpversion' => '1.1',
			'blocking'    => true,
			'body'        => $body_json_encode ? json_encode( $data ) : $data,
			'data_format' => $data_format,
			'headers'     => $this->request_headers,
			'cookies'     => $cookies
		);
		$this->reset_response();

		$this->log_request( $endpoint, $data, $this->request_headers );

		$response               = wp_remote_request( $url, $args );
		$this->response_code    = wp_remote_retrieve_response_code( $response );
		$this->response_headers = wp_remote_retrieve_headers( $response );

		$this->log_response( $endpoint, $this->response_code, $this->response_headers );

		$body = wp_remote_retrieve_body( $response );

		if ( $body ) {
			$this->response_body = $parse_json ? json_decode( $body ) : $body;
		}
	}

	public function delete( $endpoint, $data = array(), $data_format = 'body', $cookies = array(), $parse_json = true ) {
		$url  = $this->base_uri . $endpoint;
		$args = array(
			'method'      => 'DELETE',
			'timeout'     => 45,
			'redirection' => 5,
			'httpversion' => '1.1',
			'blocking'    => true,
			'body'        => json_encode( $data ),
			'data_format' => $data_format,
			'headers'     => $this->request_headers,
			'cookies'     => $cookies
		);

		$this->log_request( $endpoint, $data, $this->request_headers );

		$this->reset_response();
		$response               = wp_remote_request( $url, $args );
		$this->response_code    = wp_remote_retrieve_response_code( $response );
		$this->response_headers = wp_remote_retrieve_headers( $response );

		$this->log_response( $endpoint, $this->response_code, $this->response_headers );

		$body = wp_remote_retrieve_body( $response );

		if ( $body ) {
			$this->response_body = $parse_json ? json_decode( $body ) : $body;
		}
	}

	public function get( $endpoint, $data = array(), $cookies = array(), $parse_json = true ) {
		$url  = $this->base_uri . $endpoint;
		$url  = add_query_arg( $data, $url );
		$args = array(
			'timeout'     => 10,
			'redirection' => 5,
			'httpversion' => '1.1',
			'blocking'    => true,
			'headers'     => $this->request_headers,
			'cookies'     => $cookies,
		);
		$this->reset_response();

		$response = wp_remote_get( $url, $args );

		$this->response_code    = wp_remote_retrieve_response_code( $response );
		$this->response_headers = wp_remote_retrieve_headers( $response );

		$body = wp_remote_retrieve_body( $response );

		if ( $body ) {
			$this->response_body = $parse_json ? json_decode( $body ) : $body;
		}
	}

	public function raw_request( $endpoint, $args ) {
		$url = $this->base_uri . $endpoint;
		$this->log_request($endpoint, $args);
		$response = wp_remote_request( $url, $args );
		$this->log_response( $endpoint, wp_remote_retrieve_response_code( $response ), wp_remote_retrieve_headers( $response ) );
	}
}