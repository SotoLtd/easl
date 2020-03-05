<?php
// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

function easl_mz_get_list_countries() {
	$country_codes = array(
		"AFG" => "Afghanistan",
		"ALA" => "Aland Islands",
		"ALB" => "Albania",
		"DZA" => "Algeria",
		"ASM" => "American Samoa",
		"AND" => "Andorra",
		"AGO" => "Angola",
		"AIA" => "Anguilla",
		"ATA" => "Antarctica",
		"ATG" => "Antigua and Barbuda",
		"ARG" => "Argentina",
		"ARM" => "Armenia",
		"ABW" => "Aruba",
		"AUS" => "Australia",
		"AUT" => "Austria",
		"AZE" => "Azerbaijan",
		"BHS" => "Bahamas",
		"BHR" => "Bahrain",
		"BGD" => "Bangladesh",
		"BRB" => "Barbados",
		"BLR" => "Belarus",
		"BEL" => "Belgium",
		"BLZ" => "Belize",
		"BEN" => "Benin",
		"BMU" => "Bermuda",
		"BTN" => "Bhutan",
		"BOL" => "Bolivia (Plurinational State of)",
		"BES" => "Bonaire",
		"BIH" => "Bosnia and Herzegovina",
		"BWA" => "Botswana",
		"BVT" => "Bouvet Island",
		"BRA" => "Brazil",
		"IOT" => "British Indian Ocean Territory",
		"BRN" => "Brunei Darussalam",
		"BGR" => "Bulgaria",
		"BFA" => "Burkina Faso",
		"BDI" => "Burundi",
		"CPV" => "Cabo Verde",
		"KHM" => "Cambodia",
		"CMR" => "Cameroon",
		"CAN" => "Canada",
		"CYM" => "Cayman Islands",
		"CAF" => "Central African Republic",
		"TCD" => "Chad",
		"CHL" => "Chile",
		"CHN" => "China",
		"CXR" => "Christmas Island",
		"CCK" => "Cocos (Keeling) Islands",
		"COL" => "Colombia",
		"COM" => "Comoros",
		"COG" => "Congo",
		"COD" => "Congo (Democratic Republic of the)",
		"COK" => "Cook Islands",
		"CRI" => "Costa Rica",
		"CIV" => "Côte d'Ivoire",
		"HRV" => "Croatia",
		"CUB" => "Cuba",
		"CUW" => "Curaçao",
		"CYP" => "Cyprus",
		"CZE" => "Czechia",
		"DNK" => "Denmark",
		"DJI" => "Djibouti",
		"DMA" => "Dominica",
		"DOM" => "Dominican Republic",
		"ECU" => "Ecuador",
		"EGY" => "Egypt",
		"SLV" => "El Salvador",
		"GNQ" => "Equatorial Guinea",
		"ERI" => "Eritrea",
		"EST" => "Estonia",
		"SWZ" => "Eswatini",
		"ETH" => "Ethiopia",
		"FLK" => "Falkland Islands (Malvinas)",
		"FRO" => "Faroe Islands",
		"FJI" => "Fiji",
		"FIN" => "Finland",
		"FRA" => "France",
		"GUF" => "French Guiana",
		"PYF" => "French Polynesia",
		"ATF" => "French Southern Territories",
		"GAB" => "Gabon",
		"GMB" => "Gambia",
		"GEO" => "Georgia",
		"DEU" => "Germany",
		"GHA" => "Ghana",
		"GIB" => "Gibraltar",
		"GRC" => "Greece",
		"GRL" => "Greenland",
		"GRD" => "Grenada",
		"GLP" => "Guadeloupe",
		"GUM" => "Guam",
		"GTM" => "Guatemala",
		"GGY" => "Guernsey",
		"GIN" => "Guinea",
		"GNB" => "Guinea-Bissau",
		"GUY" => "Guyana",
		"HTI" => "Haiti",
		"HMD" => "Heard Island and McDonald Islands",
		"VAT" => "Holy See",
		"HND" => "Honduras",
		"HKG" => "Hong Kong",
		"HUN" => "Hungary",
		"ISL" => "Iceland",
		"IND" => "India",
		"IDN" => "Indonesia",
		"IRN" => "Iran (Islamic Republic of)",
		"IRQ" => "Iraq",
		"IRL" => "Ireland",
		"IMN" => "Isle of Man",
		"ISR" => "Israel",
		"ITA" => "Italy",
		"JAM" => "Jamaica",
		"JPN" => "Japan",
		"JEY" => "Jersey",
		"JOR" => "Jordan",
		"KAZ" => "Kazakhstan",
		"KEN" => "Kenya",
		"KIR" => "Kiribati",
		"PRK" => "Korea (Democratic People's Republic of)",
		"KOR" => "Korea (Republic of)",
		"KWT" => "Kuwait",
		"KGZ" => "Kyrgyzstan",
		"LAO" => "Lao People's Democratic Republic",
		"LVA" => "Latvia",
		"LBN" => "Lebanon",
		"LSO" => "Lesotho",
		"LBR" => "Liberia",
		"LBY" => "Libya",
		"LIE" => "Liechtenstein",
		"LTU" => "Lithuania",
		"LUX" => "Luxembourg",
		"MAC" => "Macao",
		"MKD" => "Macedonia (the former Yugoslav Republic of)",
		"MDG" => "Madagascar",
		"MWI" => "Malawi",
		"MYS" => "Malaysia",
		"MDV" => "Maldives",
		"MLI" => "Mali",
		"MLT" => "Malta",
		"MHL" => "Marshall Islands",
		"MTQ" => "Martinique",
		"MRT" => "Mauritania",
		"MUS" => "Mauritius",
		"MYT" => "Mayotte",
		"MEX" => "Mexico",
		"FSM" => "Micronesia (Federated States of)",
		"MDA" => "Moldova (Republic of)",
		"MCO" => "Monaco",
		"MNG" => "Mongolia",
		"MNE" => "Montenegro",
		"MSR" => "Montserrat",
		"MAR" => "Morocco",
		"MOZ" => "Mozambique",
		"MMR" => "Myanmar",
		"NAM" => "Namibia",
		"NRU" => "Nauru",
		"NPL" => "Nepal",
		"NLD" => "Netherlands",
		"NCL" => "New Caledonia",
		"NZL" => "New Zealand",
		"NIC" => "Nicaragua",
		"NER" => "Niger",
		"NGA" => "Nigeria",
		"NIU" => "Niue",
		"NFK" => "Norfolk Island",
		"MNP" => "Northern Mariana Islands",
		"NOR" => "Norway",
		"OMN" => "Oman",
		"PAK" => "Pakistan",
		"PLW" => "Palau",
		"PSE" => "Palestine",
		"PAN" => "Panama",
		"PNG" => "Papua New Guinea",
		"PRY" => "Paraguay",
		"PER" => "Peru",
		"PHL" => "Philippines",
		"PCN" => "Pitcairn",
		"POL" => "Poland",
		"PRT" => "Portugal",
		"PRI" => "Puerto Rico",
		"QAT" => "Qatar",
		"REU" => "Réunion",
		"ROU" => "Romania",
		"RUS" => "Russian Federation",
		"RWA" => "Rwanda",
		"BLM" => "Saint Barthélemy",
		"SHN" => "Saint Helena",
		"KNA" => "Saint Kitts and Nevis",
		"LCA" => "Saint Lucia",
		"MAF" => "Saint Martin (French part)",
		"SPM" => "Saint Pierre and Miquelon",
		"VCT" => "Saint Vincent and the Grenadines",
		"WSM" => "Samoa",
		"SMR" => "San Marino",
		"STP" => "Sao Tome and Principe",
		"SAU" => "Saudi Arabia",
		"SEN" => "Senegal",
		"SRB" => "Serbia",
		"SYC" => "Seychelles",
		"SLE" => "Sierra Leone",
		"SGP" => "Singapore",
		"SXM" => "Sint Maarten (Dutch part)",
		"SVK" => "Slovakia",
		"SVN" => "Slovenia",
		"SLB" => "Solomon Islands",
		"SOM" => "Somalia",
		"ZAF" => "South Africa",
		"SGS" => "South Georgia and the South Sandwich Islands",
		"SSD" => "South Sudan",
		"ESP" => "Spain",
		"LKA" => "Sri Lanka",
		"SDN" => "Sudan",
		"SUR" => "Suriname",
		"SJM" => "Svalbard and Jan Mayen",
		"SWE" => "Sweden",
		"CHE" => "Switzerland",
		"SYR" => "Syrian Arab Republic",
		"TWN" => "Taiwan",
		"TJK" => "Tajikistan",
		"TZA" => "Tanzania",
		"THA" => "Thailand",
		"TLS" => "Timor-Leste",
		"TGO" => "Togo",
		"TKL" => "Tokelau",
		"TON" => "Tonga",
		"TTO" => "Trinidad and Tobago",
		"TUN" => "Tunisia",
		"TUR" => "Turkey",
		"TKM" => "Turkmenistan",
		"TCA" => "Turks and Caicos Islands",
		"TUV" => "Tuvalu",
		"UGA" => "Uganda",
		"UKR" => "Ukraine",
		"ARE" => "United Arab Emirates",
		"GBR" => "United Kingdom of Great Britain and Northern Ireland",
		"USA" => "United States of America",
		"UMI" => "United States Minor Outlying Islands",
		"URY" => "Uruguay",
		"UZB" => "Uzbekistan",
		"VUT" => "Vanuatu",
		"VEN" => "Venezuela (Bolivarian Republic of)",
		"VNM" => "Viet Nam",
		"VGB" => "Virgin Islands (British)",
		"VIR" => "Virgin Islands (U.S.)",
		"WLF" => "Wallis and Futuna",
		"ESH" => "Western Sahara",
		"YEM" => "Yemen",
		"ZMB" => "Zambia",
		"ZWE" => "Zimbabwe",
	);

	return $country_codes;
}

function easl_mz_get_list_geo_regions() {
	$regions = array(
		"north_america"             => "North America",
		"europe"                    => "Europe",
		"south_america"             => "South America",
		"asia"                      => "Asia",
		"ocenia"                    => "Oceania",
		"africa"                    => "Africa",
		"caribbean_central_america" => "Caribbean & Central America",
		"other"                     => "Other",
	);

	return $regions;
}

function easl_mz_get_list_salutations( $current_salutation = '' ) {
	$salutations = array(
		'Mr.'   => 'Mr.',
		'Ms.'   => 'Ms.',
		'Mrs.'  => 'Mrs.',
		'Dr.'   => 'Dr.',
		'Prof.' => 'Prof.',
	);

	return $salutations;
}

function easl_mz_get_list_job_functions( $current = '' ) {
	$job_functions = array(
		'basic_researcher'  => 'Basic researcher',
		'clipra_meddoc'     => 'Clinical practitioner/Medical Doctor',
		'clires'            => 'Clinical researcher',
		'ind_corpro'        => 'Industry / Corporate professional',
		'nurse'             => 'Nurse',
		'nurse_pract'       => 'Nurse practitioner',
		'adm_org_employee'  => 'Administration/Organization employees',
		'fellow'            => 'Fellow',
		'allied_health_pro' => 'Allied Health Professional',
		'press'             => 'Press',
		'patient'           => 'Patient',
		'other'             => 'Other please specify',
	);

	return $job_functions;
}

function easl_mz_get_list_area_of_interests( $current = '' ) {
	$area_of_interests = array(
		'basic_scientist'               => 'Basic Scientist',
		'clinical_scientist'            => 'Clinical Scientist',
		'acute_liver_failure'           => 'Acute Liver Failure',
		'alcohol_related_liver_disease' => 'Alcohol Related Liver Disease',
		'autoimmune_liver_disease'      => 'Autoimmune Liver Disease',
		'basic_translational_science'   => 'Basic Translational Science',
		'complications_of_cirrhosis'    => 'Complications Of Cirrhosis',
		'fibrosis_pathogenesis'         => 'Fibrosis Pathogenesis',
		'general_hepatology'            => 'General Hepatology',
		'imaging_interventional'        => 'Imaging Interventional',
		'liver_transplantation'         => 'Liver Transplantation',
		'liver_tumors'                  => 'Liver Tumors',
		'molecular_cellular_biology'    => 'Molecular Cellular Biology',
		'nafld_nash'                    => 'Non Alcoholic Fatty Liver Disease and NASH',
		'pediatric_hepatology'          => 'Pediatric Hepatology',
		'public_health'                 => 'Public Health',
		'rare_liver_diseases'           => 'Rare Liver Diseases',
		'viral_hepatitis'               => 'Viral Hepatitis'
	);

	return $area_of_interests;
}

function easl_mz_get_list_specialities( $current = '' ) {
	$specialities = array(
		'anaethesiology'                               => 'Anaethesiology',
		'bariatric_surgery'                            => 'Bariatric surgery',
		'cardiology'                                   => 'Cardiology',
		'diabetology'                                  => 'Diabetology',
		'endocrinology'                                => 'Endocrinology',
		'general_practitioner'                         => 'General practitioner',
		'hepatology'                                   => 'Hepatology',
		'hepatology_gastrointestinal_gastroenterology' => 'Hepatology / gastrointestinal / gastroenterology',
		'immunology'                                   => 'Immunology',
		'iInfectiology'                                => 'Infectiology',
		'oncology'                                     => 'Oncology',
		'paediatrics'                                  => 'Paediatrics',
		'pathology'                                    => 'Pathology',
		'pharmacology'                                 => 'Pharmacology',
		'pulmonology'                                  => 'Pulmonology',
		'radiology'                                    => 'Radiology',
		'surgery'                                      => 'Surgery',
		'virology'                                     => 'Virology',
		'epidemiology_statistics'                      => 'Epidemiology-statistics',
		'other'                                        => 'Other – Please specify',
	);

	return $specialities;
}

function easl_mz_get_list_interactions_patient( $current = '' ) {
	$interactions_with_patients = array(
		'sees_treats' => 'Sees and/or treats patients',
		'no_treat'    => 'Does not treat patients',
	);

	return $interactions_with_patients;
}

function easl_mz_get_list_genders() {
	$genders = array(
		'female'      => 'Female',
		'male'        => 'Male',
		'other'       => 'Other',
		'undisclosed' => 'Undisclosed',
	);

	return $genders;
}

function easl_mz_get_list_membership_categories() {
	// Synchronise these keys with the @function easl_mz_get_membership_category_fees()
	return array(
		"regular"            => "Regular member",
		"regular_jhep"       => "Regular member with JHEP subscription",
		"corresponding"      => "Corresponding member",
		"corresponding_jhep" => "Corresponding member with JHEP subscription",
		"trainee"            => "Trainee member",
		"trainee_jhep"       => "Trainee member with JHEP subscription",
		"nurse"              => "Nurse member",
		"nurse_jhep"         => "Nurse member with JHEP subscription",
		"patient"            => "Patient member",
		"patient_jhep"       => "Patient member with JHEP subscription",
		"emeritus"           => "Emeritus member",
		"emeritus_jhep"      => "Emeritus member with JHEP subscription",
		"allied_pro"         => "Allied Health Professional member",
		"allied_pro_jhep"    => "Allied Health Professional member with JHEP subscription",
	);
}

function easl_mz_get_list_membership_statuses() {
	return array(
		'active'      => 'Active',
		'expired'     => 'Expired',
		'in_progress' => 'In progress',
		'incomplete'  => 'Incomplete',
		'cancelled'   => 'Cancelled',
	);
}

function easl_mz_get_list_places_of_work() {
    return array(
        'hospital_clinic' => 'Hospital / Clinic',
        'industry_healthcare' => 'Industry, healthcare',
        'industry_other' => 'Industry, other',
        'ngo_non_profit' => 'NGO / Not-for-Profit Organization',
        'media' => 'Media',
        'publishing_journals' => 'Publishing / Journals',
        'private_hospital' => 'Private Hospital',
        'private_practice' => 'Private Practice',
        'research_institution' => 'Research Institution',
        'university' => 'University',
        'university_hospital' => 'University Hospital'
    );
}

function easl_mz_get_list_easl_interests() {
    return array(
        'campus' => 'EASL Campus',
        'summits' => 'EASL Summits',
        'fellowships_grants_awards' => 'Fellowships, Grants & Awards',
        'ilc' => 'International Liver Congress (ILC)',
        'jhep_cpgs' => 'Journal of Hepatology, JHEP Reports, CPGs',
        'masterclasses_schools' => 'Masterclasses & Schools',
        'membership' => 'Membership',
        'monothematic_conferences' => 'Monothematic conferences',
        'sister_societies_national_associations' => 'Sister Societies & National Associations'
    );
}

function easl_mz_get_list_user_categories() {
    return array(
        'md' => 'Medical Doctor (MD)',
        'phd' => 'PhD',
        'md_phd' => 'MD PhD',
        'nurse_prescriber' => 'Nurse - Prescriber',
        'nurse_non_prescriber' => 'Nurse - Non Prescriber',
        'pharmacist' => 'Pharmacist',
        'patient' => 'Patient',
        'other' => 'Other - Please Specify'
    );
}