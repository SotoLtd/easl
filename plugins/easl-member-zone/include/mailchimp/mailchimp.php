<?php
class EASL_MZ_Mailchimp {

    const API_KEY = 'cb740386de6e8a4cc0227bd7dc2723fb-us1';
    const LIST_ID = '0cdeadc620';

    public static function sign_up($request_data) {
        $email = $request_data['email1'];
        $json = json_encode([
            'email_address' => $email,
            'status' => 'subscribed',
            'merge_fields' => [
                'FNAME' => $request_data['first_name'],
                'LNAME' => $request_data['last_name']
            ]
        ]);

        $url = 'https://us1.api.mailchimp.com/3.0/lists/' . self::LIST_ID . '/members';

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_USERPWD, 'user:' . self::API_KEY);
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