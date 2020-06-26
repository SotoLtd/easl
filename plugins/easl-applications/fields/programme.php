<?php
// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
    die( '-1' );
}

acf_add_local_field_group([
    'key' => 'programme-dates',
    'title' => 'Programme application dates',
    'fields' => [
        [
            'key' => 'start_date',
            'label' => 'Start date',
            'name' => 'start_date',
            'type' => 'date_picker',
            'display_format' => 'd/m/Y',
            'return_format' => 'd/m/Y',
            'required' => true,
        ],
        [
            'key' => 'end_date',
            'label' => 'End date',
            'name' => 'end_date',
            'type' => 'date_picker',
            'display_format' => 'd/m/Y',
            'return_format' => 'd/m/Y',
            'required' => true,
        ],
        [
            'key' => 'review_deadline',
            'label' => 'Review deadline',
            'name' => 'review_deadline',
            'type' => 'date_picker',
            'display_format' => 'd/m/Y',
            'return_format' => 'd/m/Y',
            'required' => true,
            'instructions' => 'When should reviewers complete their reviews of applications for this programme?'
        ]
    ],
    'location' => EASLApplicationsPlugin::acfPostTypeLocation('programme')
]);

acf_add_local_field_group([
    'key' => 'programme-fieldset-more-info',
    'title' => 'Related page links',
    'fields' => [
        [
            'key' => 'more_info_link',
            'label' => 'More info link',
            'name' => 'more_info_link',
            'type' => 'page_link',
            'instructions' => 'Choose which page has the details of this programme'
        ],
        [
            'key' => 'guidelines_link',
            'label' => 'Guidelines link',
            'name' => 'guidelines_link',
            'type' => 'link',
            'instructions' => 'The URL of the application guidelines (sent in the email to reviewers)',
            'required' => true
        ]
    ],
    'location' => EASLApplicationsPlugin::acfPostTypeLocation('programme')
]);

acf_add_local_field_group([
    'key' => 'programme-fieldset-category',
    'title' => 'Programme category',
    'fields' => [
        [
            'key' => 'programme-category',
            'name' => 'programme-category',
            'instructions' => 'This defines which fields will be shown',
            'label' => 'Programme category',
            'type' => 'select',
            'choices' => [null => 'Please select'] + EASLApplicationsPlugin::SUBMISSION_FIELD_SETS,
            'required' => true,
            'allow_null' => 0
        ]
    ],
    'location' => EASLApplicationsPlugin::acfPostTypeLocation('programme')
]);

acf_add_local_field_group([
    'key' => 'scoring-fields',
    'title' => 'Application scoring',
    'fields' => [
        [
            'key' => 'scoring_criteria',
            'title' => 'Scoring categories',
            'name' => 'scoring_criteria',
            'type' => 'repeater',
            'instructions' => 'Define the scoring criteria',
            'sub_fields' => [
                [
                    'key' => 'scoring_criteria_name',
                    'name' => 'criteria_name',
                    'type' => 'text',
                    'label' => 'Category',
                    'required' => true
                ],
                [
                    'key' => 'scoring_criteria_max',
                    'name' => 'criteria_max',
                    'label' => 'Maximum score',
                    'type' => 'number',
                    'min' => 1,
                    'step' => 1,
                    'instructions' => 'Enter the maximum score that can be achieved for this measure.',
                    'required' => true
                ],
                [
                    'key' => 'scoring_criteria_instructions',
                    'name' => 'criteria_instructions',
                    'label' => 'Instructions for reviewers',
                    'instructions' => 'This text will be displayed for reviewers when they are scoring applications.',
                    'type' => 'textarea',
                    'required' => true
                ]
            ]
        ]
    ],
    'location' => EASLApplicationsPlugin::acfPostTypeLocation('programme')
]);