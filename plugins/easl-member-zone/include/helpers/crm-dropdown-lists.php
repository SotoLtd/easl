<?php
// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

function easl_mz_get_list_countries() {
	$country_codes = array(
        "AFG" => "Afghanistan",
        "ALB" => "Albania",
        "DZA" => "Algeria",
        "AND" => "Andorra",
        "AGO" => "Angola",
        "ATG" => "Antigua And Barbuda",
        "ARG" => "Argentina",
        "ARM" => "Armenia",
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
        "BTN" => "Bolivia",
        "BIH" => "Bosnia and Herzegovina",
        "BWA" => "Botswana",
        "BRA" => "Brazil",
        "BRN" => "Brunei Darussalam",
        "BGR" => "Bulgaria",
        "BFA" => "Burkina Faso",
        "BDI" => "Burundi",
        "KHM" => "Cambodia",
        "CMR" => "Cameroon",
        "CAN" => "Canada",
        "CPV" => "Cape Verde",
        "CAF" => "Central African Republic",
        "TCD" => "Chad",
        "CHL" => "Chile",
        "CHN" => "China",
        "COL" => "Colombia",
        "COM" => "Comoros",
        "COG" => "Congo",
        "COK" => "Cook Islands",
        "CRI" => "Costa Rica",
        "CIV" => "Côte d'Ivoire",
        "HRV" => "Croatia",
        "CUB" => "Cuba",
        "CYP" => "Cyprus",
        "CZE" => "Czechia",
        "PRK" => "Democratic Peoples Republic of Korea",
        "COD" => "Democratic Republic of the Congo",
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
        "ETH" => "Ethiopia",
        "FJI" => "Fiji",
        "FIN" => "Finland",
        "FRA" => "France",
        "GAB" => "Gabon",
        "GMB" => "Gambia",
        "GEO" => "Georgia",
        "DEU" => "Germany",
        "GHA" => "Ghana",
        "GRC" => "Greece",
        "GRD" => "Grenada",
        "GTM" => "Guatemala",
        "GIN" => "Guinea",
        "GNB" => "Guinea-Bissau",
        "GUY" => "Guyana",
        "HTI" => "Haiti",
        "HND" => "Honduras",
        "HKG" => "Hong Kong",
        "HUN" => "Hungary",
        "ISL" => "Iceland",
        "IND" => "India",
        "IDN" => "Indonesia",
        "IRN" => "Iran, Islamic Republic of",
        "IRQ" => "Iraq",
        "IRL" => "Ireland",
        "ISR" => "Israel",
        "ITA" => "Italy",
        "JAM" => "Jamaica",
        "JPN" => "Japan",
        "JOR" => "Jordan",
        "KAZ" => "Kazakhstan",
        "KEN" => "Kenya",
        "KIR" => "Kiribati",
        "KOR" => "Korea, Republic of",
        "KWT" => "Kuwait",
        "KGZ" => "Kyrgyzstan",
        "LAO" => "Lao People's Democratic Republic",
        "LVA" => "Latvia",
        "LBN" => "Lebanon",
        "LSO" => "Lesotho",
        "LBR" => "Liberia",
        "LBY" => "Libya",
        "LTU" => "Lithuania",
        "LUX" => "Luxembourg",
        "NFK" => "North Macedonia",
        "MDG" => "Madagascar",
        "MWI" => "Malawi",
        "MYS" => "Malaysia",
        "MDV" => "Maldives",
        "MLI" => "Mali",
        "MLT" => "Malta",
        "MHL" => "Marshall Islands",
        "MRT" => "Mauritania",
        "MOZ" => "Mozambique",
        "MUS" => "Mauritius",
        "MEX" => "Mexico",
        "FSM" => "Micronesia, Federated States of",
        "MDA" => "Moldova, Republic of",
        "MCO" => "Monaco",
        "MNG" => "Mongolia",
        "MNE" => "Montenegro",
        "MAR" => "Morocco",
        "MMR" => "Myanmar",
        "NAM" => "Namibia",
        "NRU" => "Nauru",
        "NPL" => "Nepal",
        "NLD" => "Netherlands",
        "NZL" => "New Zealand",
        "NIC" => "Nicaragua",
        "NER" => "Niger",
        "NGA" => "Nigeria",
        "NIU" => "Niue",
        "NOR" => "Norway",
        "OMN" => "Oman",
        "PAK" => "Pakistan",
        "PLW" => "Palau",
        "PAN" => "Panama",
        "PNG" => "Papua New Guinea",
        "PRY" => "Paraguay",
        "PER" => "Peru",
        "PHL" => "Philippines",
        "POL" => "Poland",
        "PRT" => "Portugal",
        "QAT" => "Qatar",
        "ROU" => "Romania",
        "RUS" => "Russian Federation",
        "RWA" => "Rwanda",
        "KNA" => "Saint Kitts and Nevis",
        "LCA" => "Saint Lucia",
        "VCT" => "Saint Vincent and the Grenadines",
        "WSM" => "Samoa",
        "SMR" => "San Marino",
        "STP" => "Sao Tome And Principe",
        "SAU" => "Saudi Arabia",
        "SEN" => "Senegal",
        "SRB" => "Serbia",
        "SYC" => "Seychelles",
        "SLE" => "Sierra Leone",
        "SGP" => "Singapore",
        "SVK" => "Slovakia",
        "SVN" => "Slovenia",
        "SOM" => "Somalia",
        "SLB" => "Solomon Islands",
        "ZAF" => "South Africa",
        "ESP" => "Spain",
        "LKA" => "Sri Lanka",
        "SDN" => "Sudan",
        "SSD" => "South Sudan",
        "SUR" => "Suriname",
        "SWZ" => "Eswatini (Swaziland)",
        "SWE" => "Sweden",
        "CHE" => "Switzerland",
        "SYR" => "Syrian Arab Republic",
        "TZA" => "Tanzania, United Republic of",
        "TJK" => "Tajikistan",
        "THA" => "Thailand",
        "TLS" => "Timor-Leste",
        "TGO" => "Togo",
        "TON" => "Tonga",
        "TTO" => "Trinidad and Tobago",
        "TUN" => "Tunisia",
        "TUR" => "Turkey",
        "TKM" => "Turkmenistan",
        "TUV" => "Tuvalu",
        "UGA" => "Uganda",
        "UKR" => "Ukraine",
        "ARE" => "United Arab Emirates",
        "GBR" => "United Kingdom",
        "TWN" => "Taiwan",
        "USA" => "United States of America",
        "URY" => "Uruguay",
        "UZB" => "Uzbekistan",
        "VUT" => "Vanuatu",
        "VEN" => "Venezuela",
        "VNM" => "Viet Nam",
        "YEM" => "Yemen",
        "ZMB" => "Zambia",
        "ZWE" => "Zimbabwe"
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
		'male'        => 'Male',
		'female'      => 'Female',
		'other'       => 'Non-binary',
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
        'ngo' => 'NGO / Not-for-Profit Organization',
        'media' => 'Media',
        'publishing_journals' => 'Publishing / Journals',
        'private_hospital' => 'Private Hospital',
        'private_practice' => 'Private Practice',
        'research_institution' => 'Research Institution',
        'university' => 'University',
        'university_hospital' => 'University Hospital'
    );
}

function easl_mz_get_list_user_categories() {
    return array(
        'md' => 'Medical Doctor (MD)',
        'phd' => 'PhD',
        'md_phd' => 'MD PhD',
        'nurse_prescr' => 'Nurse - Prescriber',
        'nurse_non_prescr' => 'Nurse - Non Prescriber',
        'pharma' => 'Pharmacist',
        'patient' => 'Patient',
        'other' => 'Other - Please Specify'
    );
}