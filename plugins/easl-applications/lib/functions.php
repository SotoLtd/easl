<?php
// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
    die( '-1' );
}
/**
 * @param $field acf_field
 */
function easl_app_show_personal_info( $field ) {
    if ( is_admin() ) {
        return;
    }
    if ( 'masterclass_confirmation_personal_information' != $field['key'] ) {
        return;
    }
    $sessionData = easl_mz_get_current_session_data();
    $memberData  = easl_mz_get_member_data( $sessionData['member_id'] );
    
    $member_profile_url = get_field( 'member_profile_url', 'option' );
    echo '<table>';
    foreach ( EASLAppSubmission::MEMBER_DATA_FIELDS as $key => $label ) {
        if('dotb_job_function' == $key) {
            $value = easl_mz_get_list_item_name('job_functions', $memberData[ $key ]);
        }elseif('primary_address_country' == $key){
            $value = easl_mz_get_country_name( $memberData[ $key ]);
        }else{
            $value = $memberData[ $key ];
        }
        echo '<tr>';
        echo '<th>' . $label . '</th>';
        echo '<td>' . $value . '</td>';
        echo '</tr>';
    }
    echo '</table>';
    echo '<p>If you want to update your personal details, please go <a href="'. $member_profile_url .'" target="_blank">My Profile</a> page.</p>';
}