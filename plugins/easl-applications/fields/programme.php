<?php

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
            'return_format' => 'd/m/Y'
        ],
        [
            'key' => 'end_date',
            'label' => 'End date',
            'name' => 'end_date',
            'type' => 'date_picker',
            'display_format' => 'd/m/Y',
            'return_format' => 'd/m/Y'
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
    'key' => 'programme-email-fields',
    'title' => 'Programme email content',
    'fields' => [
        [
            'key' => 'thanks_email_subject',
            'label' => 'Confirmation email subject',
            'name' => 'thanks_email_subject',
            'type' => 'text'
        ],
        [
            'key' => 'thanks_email',
            'label' => 'Confirmation email content',
            'name' => 'thanks_email',
            'type' => 'wysiwyg',
            'toolbar' => 'basic',
            'media_upload' => 0
        ],
        [
            'key' => 'reviewer_email_subject',
            'label' => 'Reviewer invitation email subject',
            'name' => 'reviewer_email_subject',
            'type' => 'text'
        ],
        [
            'key' => 'reviewer_email',
            'label' => 'Reviewer invitation email content',
            'name' => 'reviewer_email',
            'type' => 'wysiwyg',
            'toolbar' => 'basic',
            'media_upload' => 0
        ],
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
                    'title' => 'Name',
                    'type' => 'text'
                ],
                [
                    'key' => 'scoring_criteria_max',
                    'name' => 'criteria_max',
                    'title' => 'Maximum score',
                    'type' => 'number',
                    'min' => 1,
                    'step' => 1,
                    'instructions' => 'Enter the maximum score that can be achieved for this measure.'
                ],
                [
                    'key' => 'scoring_criteria_instructions',
                    'name' => 'criteria_instructions',
                    'title' => 'Instructions for reviewers',
                    'instructions' => 'This text will be displayed for reviewers when they are scoring applications.',
                    'type' => 'textarea'
                ]
            ]
        ]
    ],
    'location' => EASLApplicationsPlugin::acfPostTypeLocation('programme')
]);