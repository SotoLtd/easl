<?php
class EASL_MZ_Mailchimp {

    public static function sign_up($request_data) {
    
        $api_key = get_field( 'mz_mailchimp_api_key', 'options' );
        $list_id = get_field( 'mz_mailchimp_list_id', 'options' );
    
        if ( ! $api_key || $list_id ) {
            return false;
        }
        
        $email = $request_data['email1'];
        $json = json_encode([
            'email_address' => $email,
            'status' => 'subscribed',
            'merge_fields' => [
                'FNAME' => $request_data['first_name'],
                'LNAME' => $request_data['last_name']
            ]
        ]);

        $url = 'https://us1.api.mailchimp.com/3.0/lists/' . $list_id . '/members';

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_USERPWD, 'user:' . $api_key);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $json);

        $result = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        return $httpCode;
    }
}