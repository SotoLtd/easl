<?php
// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

class EASL_MZ_Ajax_Handler {

	private static $_instance;

	/**
	 * @var EASL_MZ_API
	 */
	private $api;
	/**
	 * @var EASL_MZ_Session_Handler
	 */
	private $session;
	private $view_path;

	private function __construct() {
		add_action( "wp_ajax_nopriv_easl_mz_load_crm_view", array( $this, 'handle' ) );
		add_action( "wp_ajax_easl_mz_load_crm_view", array( $this, 'handle' ) );
	}

	public static function get_instance() {
		if ( ! ( self::$_instance instanceof self ) ) {
			self::$_instance = new self();
		}

		return self::$_instance;
	}

	public function get_action_name() {
		return 'easl_mz_load_crm_view';
	}

	public function respond( $html = '', $status = 200, $extra_data = array() ) {
		if ( ! is_array( $extra_data ) ) {
            $extra_data = (array) $extra_data;
		}
		wp_send_json( array(
			'Status' => $status,
			'Html'   => $html,
			'Data'   => $extra_data,
		) );
	}

	public function respond_field_errors( $errors = array() ) {
		wp_send_json( array(
			'Status' => 400,
			'Errors' => $errors
		) );
	}

	public function get_file_html( $file, $data = array() ) {
		if ( ! is_array( $data ) ) {
			$data = (array) $data;
		}
		ob_start();
		extract( $data );
		include $this->view_path . '/' . rtrim( $file, '/' );

		return trim( ob_get_clean() );
	}

	public function respond_file( $file, $data = array(), $status = 200, $extra_data = array() ) {
		if ( ! is_array( $data ) ) {
			$data = (array) $data;
		}
		ob_start();
		extract( $data );
		include $this->view_path . '/' . rtrim( $file, '/' );
		$html = ob_get_clean();
		wp_send_json( array(
			'Status' => $status,
			'Html'   => $html,
			'Data'   => $extra_data,
		) );
	}

	public function handle() {
		if ( empty( $_POST['method'] ) ) {
			$this->respond( 'No method specified!', 405 );
		}
		if ( ! method_exists( $this, $_POST['method'] ) ) {
			$this->respond( 'Specified method does not exists!', 405 );
		}
		$this->session   = easl_mz_get_manager()->getSession();
		$this->api       = easl_mz_get_manager()->getApi();
		$this->view_path = easl_mz_get_manager()->path( 'CRM_VIEWS' );

		call_user_func( array( $this, $_POST['method'] ) );
	}

	public function reset_member_password() {
		if ( empty( $_POST['request_data']['email'] ) ) {
			$this->respond( 'No fields specified!', 405 );
		}

		// TODO - make some think similar to wp_admin_referer
		$reset = $this->api->reset_password( $_POST['request_data']['email'] );
		if ( ! $reset ) {
			$this->respond( 'Error!', 400 );
		}
		$this->respond( 'Success!', 200 );
	}

	public function get_member_card() {
        if ( ! easl_mz_is_member_logged_in() ) {
            $this->respond( 'Member not logged in!', 401 );
        }
		$current_member_id = $this->session->get_current_member_id();
		if ( ! $current_member_id ) {
			$current_member_id = $this->api->get_member_id();

			if ( $current_member_id ) {
				$this->session->add_data( 'member_id', $current_member_id );
				$this->session->save_session_data();
			} else {
				$this->session->unset_auth_cookie( true );
				$this->respond( 'Member not found!', 404 );
			}
		}
		$this->api->get_user_auth_token();
		$member_details = $this->api->get_member_details( $current_member_id, false );

		if ( ! $member_details ) {
			$this->session->unset_auth_cookie( true );
			$this->respond( 'Members details not found!', 401 );
		}

		$member_details['profile_picture'] = easl_mz_get_member_image_src( $member_details['id'], $member_details['picture'] );

		$card  = $this->get_file_html( '/member-card/member-card.php', array( 'member' => $member_details ) );
		$panel = $this->get_file_html( '/member-card/panel.php', array( 'member' => $member_details ) );
		wp_send_json( array(
			'Status' => 200,
			'Html'   => $card,
			'Panel'  => $panel,
		) );

	}

	public function get_members_list() {
		if ( ! easl_mz_is_member_logged_in() ) {
			$this->respond( 'Member not logged in!', 401 );
		}
		$search      = '';
		$country     = '';
		$speciality  = '';
		$letter      = '';
		$page_offset = '';

		if ( isset( $_POST['request_data']['search'] ) ) {
			$search = trim( $_POST['request_data']['search'] );
		}
		if ( isset( $_POST['request_data']['country'] ) ) {
			$country = trim( $_POST['request_data']['country'] );
		}
		if ( isset( $_POST['request_data']['speciality'] ) ) {
			$speciality = trim( $_POST['request_data']['speciality'] );
		}
		if ( isset( $_POST['request_data']['letter'] ) ) {
			$letter = trim( $_POST['request_data']['letter'] );
		}
		if ( isset( $_POST['request_data']['page_offset'] ) ) {
			$page_offset = trim( $_POST['request_data']['page_offset'] );
		}
		$page_offset = absint( $page_offset );
		$page_offset = $page_offset ? $page_offset : 1;
		$num         = 12;
		$filter_args = array(
			'max_num'  => $num,
			'offset'   => ( $page_offset - 1 ) * $num,
			'order_by' => 'date_modified:ASC',
			'fields'   => 'id,name,salutation,first_name,last_name,picture,dotb_public_profile,dotb_public_profile_fields,primary_address_country,description,medical_speciality_c,title,dotb_job_function,dotb_job_function_other,department'
		);
		$filter      = array();
		$filter[]    = array(
			'$or' => array(
				array(
					'dotb_public_profile' => array(
						'$equals' => 'Yes'
					)
				),
				array(
					'dotb_public_profile' => array(
						'$equals' => 'Yes_Partial'
					)
				),
			)
		);

		if ( $search ) {
			$search_pieces   = explode( ' ', $search );
			$filter_search   = array();
			$filter_search[] = array(
				'first_name' => array(
					'$contains' => $search
				)
			);
			$filter_search[] = array(
				'last_name' => array(
					'$contains' => $search
				)
			);
			if ( count( $search_pieces ) > 1 ) {
				foreach ( $search_pieces as $search_term ) {
					$filter_search[] = array(
						'first_name' => array(
							'$contains' => $search_term
						)
					);
					$filter_search[] = array(
						'last_name' => array(
							'$contains' => $search_term
						)
					);
				}
			}
			$filter[] = array(
				'$or' => $filter_search
			);
		}
		if ( $letter ) {
			$filter[] = array(
				'last_name' => array(
					'$starts' => $letter
				)
			);
		}
		if ( $country ) {
			$filter[] = array(
				'primary_address_country' => $country
			);
		}
		if ( $speciality ) {
			$filter[] = array(
				'medical_speciality_c' => array(
					'$contains' => $speciality
				)
			);
		}
		if ( count( $filter ) > 0 ) {
			$filter_args['filter'] = $filter;
		}

		$this->api->get_user_auth_token();
		$members = $this->api->get_members( $filter_args );
		if ( ! $members ) {
			$this->respond( 'Member not found!', 404 );
		}
		$total_found_member = $this->api->count_members( array( 'filter' => $filter ) );

		$data = array(
			'members'            => $members,
			'current_page'       => $page_offset,
			'member_per_page'    => $num,
			'total_found_member' => $total_found_member
		);
		$this->respond_file( '/member-directory/member-directory.php', $data, 200 );
	}

	public function get_member_details() {
		if ( ! easl_mz_is_member_logged_in() ) {
			$this->respond( 'Member not logged in!', 401 );
		}
		$member_id = '';

		if ( isset( $_POST['request_data']['member_id'] ) ) {
			$member_id = trim( $_POST['request_data']['member_id'] );
		}
		if ( ! $member_id ) {
			$this->respond( 'Member not found!', 404 );
		}
		$member_details = $this->api->get_member_details( $member_id, false );
		if ( ! $member_details ) {
			$this->respond( 'Member not found!', 404 );
		}
		$this->respond_file( '/member-profile-details.php', array( 'member' => $member_details ), 200 );
	}

	public function get_featured_member() {
		$this->api->maybe_get_user_auth_token();
		$featured_members = $this->api->get_featured_members();
		if ( ! $featured_members ) {
			$this->respond( 'Member not found!', 404 );
		}
		$this->respond_file( '/featured-member/featured-member.php', array( 'members' => $featured_members ), 200 );
	}

	public function get_members_memberships() {
        if ( ! easl_mz_is_member_logged_in() ) {
            $this->respond( 'Member not logged in!', 401 );
        }
		$current_member_id = $this->session->get_current_member_id();
		if ( ! $current_member_id ) {
			$current_member_id = $this->api->get_member_id();

			if ( $current_member_id ) {
				$this->session->add_data( 'member_id', $current_member_id );
				$this->session->save_session_data();
			} else {
				$this->session->unset_auth_cookie( true );
				$this->respond( 'Member not found!', 404 );
			}
		}
		$this->api->get_user_auth_token();
		$memberships = $this->api->get_members_membership( $current_member_id );
		if ( ! $memberships ) {
			$this->respond( 'No memberships found.', 404 );
		}

		$this->respond( count( $memberships ) . ' memberships found!', 200, $memberships );
	}

	public function get_memberships_notes() {
        if ( ! easl_mz_is_member_logged_in() ) {
            $this->respond( 'Member not logged in!', 401 );
        }
		if ( ! easl_mz_is_member_logged_in() ) {
			$this->respond( 'Not logged in.', 401 );
		}
		$memberships = array();
		if ( isset( $_POST['request_data']['memberships'] ) ) {
			$memberships = $_POST['request_data']['memberships'];
		}
		if ( ! is_array( $memberships ) && count( $memberships ) < 1 ) {
			$this->respond( 'Not found.', 404 );
		}
		$rows = array();
		foreach ( $memberships as $membership ) {
			if ( empty( $membership['id'] ) ) {
				continue;
			}
			$membership_notes = $this->api->get_membership_notes( $membership['id'] );
			if ( empty( $membership_notes ) ) {
				continue;
			}
			$rows[] = array(
				'membership'       => $membership,
				'membership_notes' => $membership_notes
			);
		}


		if ( count( $rows ) < 1 ) {
			$this->respond( 'No membership note found.', 404 );
		}
		$template_data = array( 'rows' => $rows );
		$this->respond_file( '/members-documents/members-documents-row.php', $template_data, 200 );
	}
    public function get_members_wp_docs() {
        if ( ! easl_mz_is_member_logged_in() ) {
            $this->respond( 'Member not logged in!', 401 );
        }
        $member_email = $this->session->get_current_members_login();
        if(!$member_email) {
            $this->respond( 'Member not found!', 404 );
        }
        $members_doc_handler = easl_mz_get_manager()->getDocHandler();
        $members_docs = $members_doc_handler->get_docs_by_email($member_email);
        if ( empty( $members_docs ) ) {
            $this->respond( 'No documents found.', 404 );
        }
        $template_data = array( 'members_docs' => $members_docs );
        $this->respond_file( '/members-documents/wp-docs.php', $template_data, 200 );
    }

	public function get_members_crm_notes() {
        if ( ! easl_mz_is_member_logged_in() ) {
            $this->respond( 'Member not logged in!', 401 );
        }
        $current_member_id = $this->session->get_current_member_id();
        if ( ! $current_member_id ) {
            $current_member_id = $this->api->get_member_id();
            
            if ( $current_member_id ) {
                $this->session->add_data( 'member_id', $current_member_id );
                $this->session->save_session_data();
            } else {
                $this->session->unset_auth_cookie( true );
                $this->respond( 'Member not found!', 404 );
            }
        }
        $this->api->get_user_auth_token();
        $member_notes = $this->api->get_member_notes( $current_member_id );
        if ( empty( $member_notes ) ) {
            $this->respond( 'No documents found.', 404 );
        }
		$template_data = array( 'member_notes' => $member_notes );
		$this->respond_file( '/members-documents/contact-documents-row.php', $template_data, 200 );
	}

	public function get_member_statistics() {
        if ( ! easl_mz_is_member_logged_in() ) {
            $this->respond( 'Member not logged in!', 401 );
        }
		if ( ! easl_mz_is_member_logged_in() ) {
			$this->respond( 'Member not logged in!', 401 );
		}
		$top_member_country_data = array();

		$country_data               = easl_mz_get_country_member_count_data();
		$membership_category_data   = $this->api->get_ms_membership_category_member_count();
		$membership_speciality_data = $this->api->get_ms_speciality_member_count();

		if ( $country_data ) {
			$top_member_country_data = easl_mz_get_top_country_member_count_data( $country_data );
		}
		if ( ! $membership_category_data ) {
			$membership_category_data = array();
		}
		if ( ! $membership_speciality_data ) {
			$membership_speciality_data = array();
		}
		$template_data = array(
			'countries'             => $country_data,
			'top_countries'         => $top_member_country_data,
			'membership_categories' => $membership_category_data,
			'specialities'          => $membership_speciality_data
		);
		$extra_data    = array(
			'map_data' => $top_member_country_data
		);
		$this->respond_file( '/member-statistics/member-statistics.php', $template_data, 200, $extra_data );
	}

	public function get_membership_form() {
        if ( ! easl_mz_is_member_logged_in() ) {
            $this->respond( 'Member not logged in!', 401 );
        }
		$current_member_id = $this->session->get_current_member_id();
		if ( ! $current_member_id ) {
			$current_member_id = $this->api->get_member_id();

			if ( $current_member_id ) {
				$this->session->add_data( 'member_id', $current_member_id );
				$this->session->save_session_data();
			}
		}
		if ( ! $current_member_id ) {
			$this->respond( 'Member not found!', 404 );
		}
		$member_details = $this->api->get_member_details( $current_member_id, false );
		if ( ! $member_details ) {
			$this->respond( 'Member ' . $current_member_id . ' not found!', 404 );
		}

		$member_details['profile_picture']   = easl_mz_get_member_image_src( $member_details['id'], $member_details['picture'] );
		$member_details['latest_membership'] = $this->api->get_members_latest_membership( $current_member_id );

		$extra_data = array();
        $extra_data['details'] = $member_details;
		$membership_expiring = easl_mz_get_membership_expiring( array(
			'dotb_mb_current_end_date' => $member_details['dotb_mb_current_end_date'],
			'dotb_mb_id'               => $member_details['dotb_mb_id'],
			'latest_membership'        => $member_details['latest_membership'],
			'first_name'               => $member_details['first_name'],
			'last_name'                => $member_details['last_name'],
			'dotb_mb_current_status'   => $member_details['dotb_mb_current_status'],
		) );
		if ( $membership_expiring ) {
			$extra_data['banner'] = $membership_expiring;
		}


		$this->respond_file( '/memeber-details/memeber-details.php', array( 'member' => $member_details, 'highlight_errored_fields' ), 200, $extra_data );
	}

	public function get_membership_banner() {
        if ( ! easl_mz_is_member_logged_in() ) {
            $this->respond( 'Member not logged in!', 401 );
        }
        $current_member_id = $this->session->get_current_member_id();
        if ( ! $current_member_id ) {
            $current_member_id = $this->api->get_member_id();
            
            if ( $current_member_id ) {
                $this->session->add_data( 'member_id', $current_member_id );
                $this->session->save_session_data();
            }
        }
        if ( ! $current_member_id ) {
            $this->respond( 'Member not found!', 404 );
        }
        $this->api->get_user_auth_token();
        $member_details = $this->api->get_member_details( $current_member_id, false );
        if ( ! $member_details ) {
            $this->respond( 'Member ' . $current_member_id . ' not found!', 404 );
        }
        
        $member_details['latest_membership'] = $this->api->get_members_latest_membership( $current_member_id );
        
        $membership_expiring = easl_mz_get_membership_expiring( array(
            'dotb_mb_current_end_date' => $member_details['dotb_mb_current_end_date'],
            'dotb_mb_id'               => $member_details['dotb_mb_id'],
            'latest_membership'        => $member_details['latest_membership'],
            'first_name'               => $member_details['first_name'],
            'last_name'                => $member_details['last_name'],
            'dotb_mb_current_status'   => $member_details['dotb_mb_current_status'],
        ) );
		if ( ! $membership_expiring ) {
			$this->respond( '', 400 );
		}
		$this->respond( $membership_expiring, 200 );
	}

	public function get_new_membership_form() {
        if ( ! easl_mz_is_member_logged_in() ) {
            $this->respond( 'Member not logged in!', 401 );
        }
		$current_member_id = $this->session->get_current_member_id();
		if ( ! $current_member_id ) {
			$current_member_id = $this->api->get_member_id();

			if ( $current_member_id ) {
				$this->session->add_data( 'member_id', $current_member_id );
				$this->session->save_session_data();
			}
		}
		if ( ! $current_member_id ) {
			$this->respond( 'Member not found!', 404 );
		}
		$member_details = $this->api->get_member_details( $current_member_id, false );
		if ( ! $member_details ) {
			$this->respond( 'Member ' . $current_member_id . ' not found!', 404 );
		}
        
        $renew    = 'no';
        $messages = false;
        if ( isset( $_POST['request_data']['renew'] ) ) {
            $renew = $_POST['request_data']['renew'];
        }
        if ( isset( $_POST['request_data']['messages'] ) ) {
            $messages = $_POST['request_data']['messages'];
        }
		
		if( 'yes' == $renew && easl_mz_members_has_empty_mandatory_fields($member_details)) {
            $this->respond_file( '/new-membership-form/profile-mandatory-fields-empty.php', array(
                'member'   => $member_details,
            ), 200 );
        }

		$member_details['latest_membership'] = $this->api->get_members_latest_membership( $current_member_id );
		$extra_data                          = array();

		$membership_expiring = easl_mz_get_membership_expiring( array(
			'dotb_mb_current_end_date' => $member_details['dotb_mb_current_end_date'],
			'dotb_mb_id'               => $member_details['dotb_mb_id'],
			'latest_membership'        => $member_details['latest_membership'],
			'first_name'               => $member_details['first_name'],
			'last_name'                => $member_details['last_name'],
			'dotb_mb_current_status'   => $member_details['dotb_mb_current_status'],
		) );
		if ( $membership_expiring ) {
			$extra_data['banner'] = $membership_expiring;
		}
		$this->respond_file( '/new-membership-form/new-membership-form.php', array(
			'member'   => $member_details,
			'renew'    => $renew,
			'messages' => $messages,
            'skip_dashboard' => $_POST['request_data']['skip_dashboard']
		), 200, $extra_data );
	}
    
    public function subscribe_member_to_mailing_list() {
        if ( ! easl_mz_is_member_logged_in() ) {
            $this->respond( 'Member not logged in!', 401 );
        }
        if ( empty( $_POST['request_data']['sub_type'] ) ) {
            $this->respond( 'No fields specified!', 404 );
        }
        $sub_type = $_POST['request_data']['sub_type'];
        $current_member_id = $this->session->get_current_member_id();
        if ( ! $current_member_id ) {
            $current_member_id = $this->api->get_member_id();
            
            if ( $current_member_id ) {
                $this->session->add_data( 'member_id', $current_member_id );
                $this->session->save_session_data();
            }
        }
        if ( ! $current_member_id ) {
            $this->respond( 'Member not found!', 404 );
        }
        $member_details = $this->api->get_member_details( $current_member_id, false );
        if ( ! $member_details ) {
            $this->respond( 'Member ' . $current_member_id . ' not found!', 404 );
        }
        $manager = EASL_MZ_Manager::get_instance();
        require_once $manager->path( 'APP_ROOT', 'include/mailchimp/mailchimp.php' );
        
        $status_code = 400;
        if ( 'subscribe' == $sub_type ) {
            $result = EASL_MZ_Mailchimp::sign_up( $member_details );
            if ( $result ) {
                $status_code                   = 200;
                $response_data['type']         = 'unsubscribe';
                $response_data['button_title'] = 'Unsubscribe from mailing list';
                $response_data['msg']          = 'Thank you! You are added to the mailing list.';
    
                $this->api->update_member_personal_info( $current_member_id, ['dotb_easl_newsletter_agree' => true], false );
            } else {
                $status_code          = 400;
                $response_data['msg'] = 'We are sorry! There is error in adding you in the mailing list. Please try again later.';
            }
        } elseif ( 'unsubscribe' == $sub_type ) {
            $result = EASL_MZ_Mailchimp::unsubscribe( $member_details['email1'] );
            if ( $result ) {
                $status_code                   = 200;
                $response_data['type']         = 'subscribe';
                $response_data['button_title'] = 'Resubscribe to mailing list';
                $response_data['msg']          = 'You are unsubscribed! You can always subscribe to the mailing list anytime from your profile page.';
                $this->api->update_member_personal_info( $current_member_id, ['dotb_easl_newsletter_agree' => false], false );
            } else {
                $status_code          = 400;
                $response_data['msg'] = 'We are sorry! There is error while unsubscribing you from the mailing list. Please try again later.';
            }
        }
        $this->respond( '', $status_code, $response_data );
    }
	public function update_member_profile() {
        if ( ! easl_mz_is_member_logged_in() ) {
            $this->respond( 'Member not logged in!', 401 );
        }
		if ( empty( $_POST['request_data'] ) ) {
			$this->respond( 'No fields specified!', 405 );
		}
		$request_data = array();
		parse_str( $_POST['request_data'], $request_data );
		if ( empty( $request_data['id'] ) ) {
			$this->respond( 'No member specified!', 405 );
		}
		$member_id = $request_data['id'];

		$current_member_id = $this->session->get_current_member_id();
		if ( ! $current_member_id ) {
			$current_member_id = $this->api->get_member_id();

			if ( $current_member_id ) {
				$this->session->add_data( 'member_id', $current_member_id );
				$this->session->save_session_data();
			}
		}
		if ( ! $current_member_id || ( $current_member_id != $member_id ) ) {
			$this->respond( 'Member not found!', 404 );
		}

        $errors = easl_mz_validate_new_member_form( $request_data, isset($request_data['title']) );

		if ( count( $errors ) > 0 ) {
			$this->respond_field_errors( $errors );
		}
        
        $request_data['first_name'] = ucfirst( $request_data['first_name'] );
        $request_data['last_name']  = ucfirst( $request_data['last_name'] );
        
        if ( ! empty( $request_data['dotb_job_function_other'] ) ) {
            $request_data['dotb_job_function_other'] = ucfirst( $request_data['dotb_job_function_other'] );
        }
        if ( ! empty( $request_data['medical_speciality_c_other'] ) ) {
            $request_data['medical_speciality_c_other'] = ucfirst( $request_data['medical_speciality_c_other'] );
        }
        if ( ! empty( $request_data['participant_type_c_other'] ) ) {
            $request_data['participant_type_c_other'] = ucfirst( $request_data['participant_type_c_other'] );
        }
        if ( ! empty( $request_data['title'] ) ) {
            $request_data['title'] = ucfirst( $request_data['title'] );
        }
        if ( ! empty( $request_data['department'] ) ) {
            $request_data['department'] = ucfirst( $request_data['department'] );
        }
        if ( ! empty( $request_data['dotb_tmp_account'] ) ) {
            $request_data['dotb_tmp_account'] = ucfirst( $request_data['dotb_tmp_account'] );
        }
        if ( ! empty( $request_data['primary_address_city'] ) ) {
            $request_data['primary_address_city'] = ucfirst( $request_data['primary_address_city'] );
        }
        if ( ! empty( $request_data['primary_address_state'] ) ) {
            $request_data['primary_address_state'] = ucfirst( $request_data['primary_address_state'] );
        }
        if ( ! empty( $request_data['alt_address_state'] ) ) {
            $request_data['alt_address_state'] = ucfirst( $request_data['alt_address_state'] );
        }
        if ( ! empty( $request_data['assistant'] ) ) {
            $request_data['assistant'] = ucfirst( $request_data['assistant'] );
        }
        if ( ! empty( $request_data['description'] ) ) {
            $request_data['description'] = ucfirst( $request_data['description'] );
        }
		
		if ( ! isset( $request_data['dotb_public_profile'] ) ) {
			$request_data['dotb_public_profile'] = 'No';
		}
		if ( $request_data['dotb_public_profile'] == 'No' ) {
			$request_data['dotb_public_profile_fields'] = '';
		}

		$request_data['description'] = wp_unslash( $request_data['description'] );

		unset( $request_data['id'] );
		$updated = $this->api->update_member_personal_info( $member_id, $request_data, false );
		if ( ! $updated ) {
			$this->respond( 'Error!', 405 );
		}
		$this->respond( 'Your profile updated successfully!', 200, $request_data );
	}

	public function change_member_password() {
        if ( ! easl_mz_is_member_logged_in() ) {
            $this->respond( 'Member not logged in!', 401 );
        }
		if ( empty( $_POST['request_data'] ) ) {
			$this->respond( 'No fields specified!', 405 );
		}

		$errors       = array();
		$request_data = $_POST['request_data'];

		if ( empty( $request_data['old_password'] ) ) {
			$errors['old_password'] = 'Mandatory field.';
		}
		if ( empty( $request_data['new_password'] ) ) {
			$errors['new_password'] = 'Mandatory field.';
		}
		if ( empty( $request_data['new_password2'] ) ) {
			$errors['new_password2'] = 'Mandatory field.';
		}

		if ( $request_data['new_password2'] !== $request_data['new_password'] ) {
			$errors['new_password2'] = 'Must be same as password.';
		}
		if ( count( $errors ) > 0 ) {
			$this->respond_field_errors( $errors );
		}

		$api_args = array(
			'old_password' => $request_data['old_password'],
			'new_password' => $request_data['new_password']
		);

		$updated = $this->api->change_password( $api_args );
		if(! $updated && $this->api->is_member_session_expired()) {
            $this->respond( 'Your session expired!', 401 );
        }
		if ( ! $updated ) {
			$this->respond( 'Error!', 405 );
		}
		$this->respond( 'Password changed successfully!', 200 );
	}

	public function create_member_profile() {
		if ( empty( $_POST['request_data'] ) ) {
			$this->respond( 'No fields specified!', 405 );
		}
		$request_data = array();
		parse_str( $_POST['request_data'], $request_data );
		if ( empty( $request_data ) ) {
			$this->respond( 'No fields specified!', 405 );
		}
		$errors = easl_mz_validate_new_member_form( $request_data, $request_data['title'] );

		if ( ! $errors ) {
			$errors = array();
		}
		if ( empty( $request_data['password'] ) ) {
			$errors['password'] = 'Mandatory field.';
		}
		if ( empty( $request_data['password2'] ) ) {
			$errors['password2'] = 'Mandatory field.';
		}
		$password  = $request_data['password'];
		$password2 = $request_data['password2'];
		unset( $request_data['password'] );
		unset( $request_data['password2'] );

		if ( $password !== $password2 ) {
			$errors['password2'] = 'Must be same as password.';
		}
		if ( empty($errors['email1']) && $this->api->is_member_exists( $request_data['email1'] ) ) {
			$errors['email1'] = 'An account with this email already exists.';
		}

		if ( count( $errors ) > 0 ) {
			$this->respond_field_errors( $errors );
		}
        
        
        
        $uc_fied_data = array();
        
        $request_data['first_name'] = ucfirst( $request_data['first_name'] );
        $request_data['last_name']  = ucfirst( $request_data['last_name'] );
        
        if ( ! empty( $request_data['dotb_job_function_other'] ) ) {
            $request_data['dotb_job_function_other'] = ucfirst( $request_data['dotb_job_function_other'] );
        }
        if ( ! empty( $request_data['medical_speciality_c_other'] ) ) {
            $request_data['medical_speciality_c_other'] = ucfirst( $request_data['medical_speciality_c_other'] );
        }
        if ( ! empty( $request_data['participant_type_c_other'] ) ) {
            $request_data['participant_type_c_other'] = ucfirst( $request_data['participant_type_c_other'] );
        }
        if ( ! empty( $request_data['title'] ) ) {
            $request_data['title'] = ucfirst( $request_data['title'] );
        }
        if ( ! empty( $request_data['department'] ) ) {
            $request_data['department'] = ucfirst( $request_data['department'] );
        }
        if ( ! empty( $request_data['dotb_tmp_account'] ) ) {
            $request_data['dotb_tmp_account'] = ucfirst( $request_data['dotb_tmp_account'] );
        }
        if ( ! empty( $request_data['primary_address_city'] ) ) {
            $request_data['primary_address_city'] = ucfirst( $request_data['primary_address_city'] );
        }
        if ( ! empty( $request_data['primary_address_state'] ) ) {
            $request_data['primary_address_state'] = ucfirst( $request_data['primary_address_state'] );
        }
        if ( ! empty( $request_data['alt_address_state'] ) ) {
            $request_data['alt_address_state'] = ucfirst( $request_data['alt_address_state'] );
        }
        if ( ! empty( $request_data['assistant'] ) ) {
            $request_data['assistant'] = ucfirst( $request_data['assistant'] );
        }
        if ( ! empty( $request_data['description'] ) ) {
            $request_data['description'] = ucfirst( $request_data['description'] );
        }
		
        if(isset($request_data['description'])) {
            $request_data['description'] = wp_unslash( $request_data['description'] );
        }
        
		$request_data['portal_name']      = $request_data['email1'];
		$request_data['portal_password']  = $password;
		$request_data['portal_password1'] = $password;
		$request_data['portal_active']    = true;
		
		if(!empty($request_data['dotb_easl_newsletter_agree'])) {
            $request_data['dotb_easl_newsletter_agree'] = true;
        }else{
            $request_data['dotb_easl_newsletter_agree'] = false;
        }

		$this->api->get_user_auth_token();

		$created_member_id = $this->api->create_member( $request_data );

		if ( ! $created_member_id ) {
			$this->respond( 'Error!', 405 );
		}

        if (!empty($request_data['dotb_easl_newsletter_agree'])) {
            $manager = EASL_MZ_Manager::get_instance();
            require_once $manager->path('APP_ROOT', 'include/mailchimp/mailchimp.php');
            EASL_MZ_Mailchimp::sign_up($request_data);
        }

        //Redirect to the dashboard
        if ( ! empty( $request_data['redirect'] ) ) {
            $redirect = esc_url( $request_data['redirect'] );
        } else {
            $redirect = get_field( 'member_dashboard_url', 'option' );
            if ( ! empty( $request_data['skip_dashboard'] ) ) {
                $redirect = get_field( 'membership_plan_url', 'option' );
            }
        }

        if ( ! $redirect ) {
            $redirect = get_site_url();
        }

        $auth_response_status = $this->api->get_auth_token( $request_data['portal_name'], $password, true );
        if ( ! $auth_response_status ) {
            $this->respond_file( 'member-login/basic-login-form.php', array( 'redirect_url' => $redirect ), 201 );
        }
		// Member authenticated
		$this->session->set_auth_cookie( $request_data['portal_name'], $this->api->get_credential_data( true ) );
		$this->session->add_data( 'member_id', $created_member_id );
		$this->session->save_session_data();

		$this->respond( $redirect, 200 );
	}

	public function delete_current_member() {
        if ( ! easl_mz_is_member_logged_in() ) {
            $this->respond( 'Member not logged in!', 401 );
        }
		if ( empty( $_POST['request_data'] ) ) {
			$this->respond( 'No fields specified!', 405 );
		}
		$request_data = $_POST['request_data'];
		if ( empty( $request_data['id'] ) ) {
			$this->respond( 'No member specified!', 405 );
		}
		$member_id = $request_data['id'];

		$current_member_id = $this->session->get_current_member_id();
		if ( ! $current_member_id ) {
			$current_member_id = $this->api->get_member_id();
		}
		if ( ! $current_member_id || ( $current_member_id != $member_id ) ) {
			$this->respond( 'Member not found!', 404 );
		}
		$this->api->maybe_get_user_auth_token();
		$deleted = $this->api->delete_member_account( $member_id );
		if ( ! $deleted ) {
			$this->respond( 'Error!', 400 );
		}

		$this->session->unset_auth_cookie();
		$this->api->clear_credentials();

		do_action( 'easl_mz_member_logged_out' );

		$this->respond( 'Your account deleted successfully!', 200 );
	}
}