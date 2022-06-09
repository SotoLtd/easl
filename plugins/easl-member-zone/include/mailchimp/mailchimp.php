<?php

class EASL_MZ_Mailchimp {
    
    public static function get_date_formatted( $date ) {
        $date = explode( '-', $date );
        if ( count( $date ) === 3 ) {
            return $date[1] . '/' . $date[2] . '/' . $date[0];
        }
        
        return '';
    }
    
    public static function get_alpha2_from_alpha3( $cc ) {
        $map = array(
            "AFG" => "AF",
            "ALA" => "AX",
            "ALB" => "AL",
            "DZA" => "DZ",
            "ASM" => "AS",
            "AND" => "AD",
            "AGO" => "AO",
            "AIA" => "AI",
            "ATA" => "AQ",
            "ATG" => "AG",
            "ARG" => "AR",
            "ARM" => "AM",
            "ABW" => "AW",
            "AUS" => "AU",
            "AUT" => "AT",
            "AZE" => "AZ",
            "BHS" => "BS",
            "BHR" => "BH",
            "BGD" => "BD",
            "BRB" => "BB",
            "BLR" => "BY",
            "BEL" => "BE",
            "BLZ" => "BZ",
            "BEN" => "BJ",
            "BMU" => "BM",
            "BTN" => "BT",
            "BOL" => "BO",
            "BIH" => "BA",
            "BWA" => "BW",
            "BVT" => "BV",
            "BRA" => "BR",
            "VGB" => "VG",
            "IOT" => "IO",
            "BRN" => "BN",
            "BGR" => "BG",
            "BFA" => "BF",
            "BDI" => "BI",
            "KHM" => "KH",
            "CMR" => "CM",
            "CAN" => "CA",
            "CPV" => "CV",
            "CYM" => "KY",
            "CAF" => "CF",
            "TCD" => "TD",
            "CHL" => "CL",
            "CHN" => "CN",
            "HKG" => "HK",
            "MAC" => "MO",
            "CXR" => "CX",
            "CCK" => "CC",
            "COL" => "CO",
            "COM" => "KM",
            "COG" => "CG",
            "COD" => "CD",
            "COK" => "CK",
            "CRI" => "CR",
            "CIV" => "CI",
            "HRV" => "HR",
            "CUB" => "CU",
            "CYP" => "CY",
            "CZE" => "CZ",
            "DNK" => "DK",
            "DJI" => "DJ",
            "DMA" => "DM",
            "DOM" => "DO",
            "ECU" => "EC",
            "EGY" => "EG",
            "SLV" => "SV",
            "GNQ" => "GQ",
            "ERI" => "ER",
            "EST" => "EE",
            "ETH" => "ET",
            "FLK" => "FK",
            "FRO" => "FO",
            "FJI" => "FJ",
            "FIN" => "FI",
            "FRA" => "FR",
            "GUF" => "GF",
            "PYF" => "PF",
            "ATF" => "TF",
            "GAB" => "GA",
            "GMB" => "GM",
            "GEO" => "GE",
            "DEU" => "DE",
            "GHA" => "GH",
            "GIB" => "GI",
            "GRC" => "GR",
            "GRL" => "GL",
            "GRD" => "GD",
            "GLP" => "GP",
            "GUM" => "GU",
            "GTM" => "GT",
            "GGY" => "GG",
            "GIN" => "GN",
            "GNB" => "GW",
            "GUY" => "GY",
            "HTI" => "HT",
            "HMD" => "HM",
            "VAT" => "VA",
            "HND" => "HN",
            "HUN" => "HU",
            "ISL" => "IS",
            "IND" => "IN",
            "IDN" => "ID",
            "IRN" => "IR",
            "IRQ" => "IQ",
            "IRL" => "IE",
            "IMN" => "IM",
            "ISR" => "IL",
            "ITA" => "IT",
            "JAM" => "JM",
            "JPN" => "JP",
            "JEY" => "JE",
            "JOR" => "JO",
            "KAZ" => "KZ",
            "KEN" => "KE",
            "KIR" => "KI",
            "PRK" => "KP",
            "KOR" => "KR",
            "KWT" => "KW",
            "KGZ" => "KG",
            "LAO" => "LA",
            "LVA" => "LV",
            "LBN" => "LB",
            "LSO" => "LS",
            "LBR" => "LR",
            "LBY" => "LY",
            "LIE" => "LI",
            "LTU" => "LT",
            "LUX" => "LU",
            "MKD" => "MK",
            "MDG" => "MG",
            "MWI" => "MW",
            "MYS" => "MY",
            "MDV" => "MV",
            "MLI" => "ML",
            "MLT" => "MT",
            "MHL" => "MH",
            "MTQ" => "MQ",
            "MRT" => "MR",
            "MUS" => "MU",
            "MYT" => "YT",
            "MEX" => "MX",
            "FSM" => "FM",
            "MDA" => "MD",
            "MCO" => "MC",
            "MNG" => "MN",
            "MNE" => "ME",
            "MSR" => "MS",
            "MAR" => "MA",
            "MOZ" => "MZ",
            "MMR" => "MM",
            "NAM" => "NA",
            "NRU" => "NR",
            "NPL" => "NP",
            "NLD" => "NL",
            "ANT" => "AN",
            "NCL" => "NC",
            "NZL" => "NZ",
            "NIC" => "NI",
            "NER" => "NE",
            "NGA" => "NG",
            "NIU" => "NU",
            "NFK" => "NF",
            "MNP" => "MP",
            "NOR" => "NO",
            "OMN" => "OM",
            "PAK" => "PK",
            "PLW" => "PW",
            "PSE" => "PS",
            "PAN" => "PA",
            "PNG" => "PG",
            "PRY" => "PY",
            "PER" => "PE",
            "PHL" => "PH",
            "PCN" => "PN",
            "POL" => "PL",
            "PRT" => "PT",
            "PRI" => "PR",
            "QAT" => "QA",
            "REU" => "RE",
            "ROU" => "RO",
            "RUS" => "RU",
            "RWA" => "RW",
            "BLM" => "BL",
            "SHN" => "SH",
            "KNA" => "KN",
            "LCA" => "LC",
            "MAF" => "MF",
            "SPM" => "PM",
            "VCT" => "VC",
            "WSM" => "WS",
            "SMR" => "SM",
            "STP" => "ST",
            "SAU" => "SA",
            "SEN" => "SN",
            "SRB" => "RS",
            "SYC" => "SC",
            "SLE" => "SL",
            "SGP" => "SG",
            "SVK" => "SK",
            "SVN" => "SI",
            "SLB" => "SB",
            "SOM" => "SO",
            "ZAF" => "ZA",
            "SGS" => "GS",
            "SSD" => "SS",
            "ESP" => "ES",
            "LKA" => "LK",
            "SDN" => "SD",
            "SUR" => "SR",
            "SJM" => "SJ",
            "SWZ" => "SZ",
            "SWE" => "SE",
            "CHE" => "CH",
            "SYR" => "SY",
            "TWN" => "TW",
            "TJK" => "TJ",
            "TZA" => "TZ",
            "THA" => "TH",
            "TLS" => "TL",
            "TGO" => "TG",
            "TKL" => "TK",
            "TON" => "TO",
            "TTO" => "TT",
            "TUN" => "TN",
            "TUR" => "TR",
            "TKM" => "TM",
            "TCA" => "TC",
            "TUV" => "TV",
            "UGA" => "UG",
            "UKR" => "UA",
            "ARE" => "AE",
            "GBR" => "GB",
            "USA" => "US",
            "UMI" => "UM",
            "URY" => "UY",
            "UZB" => "UZ",
            "VUT" => "VU",
            "VEN" => "VE",
            "VNM" => "VN",
            "VIR" => "VI",
            "WLF" => "WF",
            "ESH" => "EH",
            "YEM" => "YE",
            "ZMB" => "ZM",
            "ZWE" => "ZW",
            "XKX" => "XK",
        );
        if ( array_key_exists( $cc, $map ) ) {
            return $map[ $cc ];
        }
        
        return false;
    }
    
    public static function get_merge_fields( $request_data ) {
        $merge_fields = array();
        if ( ! empty( $request_data['first_name'] ) ) {
            $merge_fields['FNAME'] = $request_data['first_name'];
        }
        if ( ! empty( $request_data['last_name'] ) ) {
            $merge_fields['LNAME'] = $request_data['last_name'];
        }
        
        if ( ! empty( $request_data['primary_address_country'] ) ) {
            $merge_fields['COUNTRY'] = easl_mz_get_list_item_name('countries', $request_data['primary_address_country']);
        }
        if ( ! empty( $request_data['salutation'] ) ) {
            $merge_fields['TITLE'] = $request_data['salutation'];
        }
        if ( ! empty( $request_data['birthdate'] ) ) {
            $merge_fields['DOB'] = self::get_date_formatted( $request_data['birthdate'] );
        }
        if ( ! empty( $request_data['dotb_gender'] ) ) {
            $merge_fields['GENDER'] = easl_mz_get_list_item_name( 'genders', $request_data['dotb_gender'] );
        }
        if ( ! empty( $request_data['dotb_job_function'] ) ) {
            if ( 'other' == $request_data['dotb_job_function'] ) {
                $merge_fields['JOBFUNC']   = 'Other';
                $merge_fields['JOBFUNC_O'] = $request_data['dotb_job_function_other'];
            } else {
                $merge_fields['JOBFUNC'] = easl_mz_get_list_item_name( 'job_functions', $request_data['dotb_job_function'] );
            }
        }
        if ( ! empty( $request_data['dotb_user_category'] ) ) {
            if ( 'other' == $request_data['dotb_user_category'] ) {
                $merge_fields['USERCAT']   = 'Other';
                $merge_fields['USERCAT_O'] = $request_data['mzf_dotb_user_category_other'];
            } else {
                $merge_fields['USERCAT'] = easl_mz_get_list_item_name( 'user_categories', $request_data['dotb_user_category'] );
            }
        }
        if ( ! empty( $request_data['medical_speciality_c'] ) && in_array( 'other', $request_data['medical_speciality_c'] ) && !empty( $request_data['medical_speciality_c_other'] ) ) {
            $merge_fields['SPECALIT_O'] = $request_data['medical_speciality_c_other'];
        }
        if ( ! empty( $request_data['dotb_place_of_work'] ) ) {
            $merge_fields['PLACEWORK'] = easl_mz_get_list_item_name( 'places_of_work', $request_data['dotb_place_of_work'] );
        }
        
        return $merge_fields;
    }
    
    public static function get_interests_fields( $request_data ) {
        $speciality_interests = array(
            'Anaethesiology'                                   => '13de53af6a',
            'Bariatric surgery'                                => '488d1a43e0',
            'Cardiology'                                       => 'ab6e0de9a1',
            'Diabetology'                                      => 'd3f3a93e44',
            'Endocrinology'                                    => 'b214944c68',
            'General practitioner'                             => '3122b8b909',
            'Hepatology'                                       => '8e380a248c',
            'Hepatology / gastrointestinal / gastroenterology' => 'c33704200c',
            'Immunology'                                       => 'a5a140c3a0',
            'Infectiology'                                     => 'cceb90b139',
            'Oncology'                                         => '2b3e52a30e',
            'Paediatrics'                                      => 'ac95a7a360',
            'Pathology'                                        => 'd394c987e2',
            'Pharmacology'                                     => '1ba0438a20',
            'Pulmonology'                                      => 'd617f89e81',
            'Radiology'                                        => '125be6a774',
            'Surgery'                                          => 'd17d676ad2',
            'Virology'                                         => 'd383522df2',
            'Epidemiology-statistics'                          => '7fbfe25f22',
            'Other'                                            => 'cacc7be92b',
        );
        $aoi_interests        = array(
            'Basic Scientist'                            => 'a94479f528',
            'Clinical Scientist'                         => '33543801f9',
            'Acute Liver Failure'                        => '400aaa56b0',
            'Alcohol Related Liver Disease'              => '76c653ad39',
            'Autoimmune Liver Disease'                   => '6e6eac9c6b',
            'Basic Translational Science'                => '898c216aba',
            'Complications Of Cirrhosis'                 => '0a097f8d7b',
            'Fibrosis Pathogenesis'                      => '725bcbd154',
            'General Hepatology'                         => '3d9a578a18',
            'Imaging Interventional'                     => 'a65839f85a',
            'Liver Transplantation'                      => '8bd7e479ec',
            'Liver Tumors'                               => '427b7a4efc',
            'Molecular Cellular Biology'                 => '5538c1f5bd',
            'Non Alcoholic Fatty Liver Disease and NASH' => 'e61a527b10',
            'Pediatric Hepatology'                       => '0e8caf868d',
            'Public Health'                              => '78943d12fb',
            'Rare Liver Diseases'                        => '005a61c22f',
            'Viral Hepatitis'                            => '9b95ed5fe4',
        );
        $interests            = array();
        if ( ! empty( $request_data['medical_speciality_c'] ) ) {
            foreach ( $request_data['medical_speciality_c'] as $sp ) {
                $sp = easl_mz_get_list_item_name( 'specialities', $sp );
                if ( $sp && array_key_exists( $sp, $speciality_interests ) ) {
                    $interests[ $speciality_interests[ $sp ] ] = true;
                }
            }
        }
        if ( ! empty( $request_data['area_of_interest_c'] ) ) {
            foreach ( $request_data['area_of_interest_c'] as $aoi ) {
                $aoi = easl_mz_get_list_item_name( 'area_of_interests', $aoi );
                if ( $aoi && array_key_exists( $aoi, $aoi_interests ) ) {
                    $interests[ $aoi_interests[ $aoi ] ] = true;
                }
            }
        }
        
        return $interests;
    }
    
    public static function sign_up( $request_data ) {
        
        $api = easl_mz_get_manager()->getApi();
        $api->get_request_object()->init_logger();
        
        $api_key = get_field( 'mz_mailchimp_api_key', 'options' );
        $list_id = get_field( 'mz_mailchimp_list_id', 'options' );
        
        if ( ! $api_key || ! $list_id ) {
            return false;
        }
        
        $data                  = array();
        $merge_fields          = self::get_merge_fields( $request_data );
        $interests             = self::get_interests_fields( $request_data );
        $data['email_address'] = $request_data['email1'];
        $data['status']        = 'subscribed';
        $data['merge_fields']  = $merge_fields;
        
        if ( count( $merge_fields ) > 0 ) {
            $data['merge_fields'] = $merge_fields;
        }
        if ( count( $interests ) > 0 ) {
            $data['interests'] = $interests;
        }
        $json = json_encode( $data );
        $api->get_request_object()->add_log( date( 'c', time() ) . ' :: Subscribe member to Mailchimp ' );
        $api->get_request_object()->add_log( 'Request Data:' );
        $api->get_request_object()->add_log( print_r( $data, true ) );
        $api->get_request_object()->add_log( $json );
        
        $url = 'https://us1.api.mailchimp.com/3.0/lists/' . $list_id . '/members';
        
        $ch = curl_init( $url );
        curl_setopt( $ch, CURLOPT_USERPWD, 'user:' . $api_key );
        curl_setopt( $ch, CURLOPT_HTTPHEADER, [ 'Content-Type: application/json' ] );
        curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
        curl_setopt( $ch, CURLOPT_TIMEOUT, 10 );
        curl_setopt( $ch, CURLOPT_CUSTOMREQUEST, 'POST' );
        curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, false );
        curl_setopt( $ch, CURLOPT_POSTFIELDS, $json );
        
        $result   = curl_exec( $ch );
        $httpCode = curl_getinfo( $ch, CURLINFO_HTTP_CODE );
        curl_close( $ch );
    
    
        $api->get_request_object()->add_log( 'Response Code: ' . $httpCode );
        $api->get_request_object()->add_log( 'Response Body: ' );
        $api->get_request_object()->add_log( print_r( json_decode($result), true ) );
        $api->get_request_object()->close_logger();
        
        return $httpCode;
    }
}