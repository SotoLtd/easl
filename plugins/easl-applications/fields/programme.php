<?php
// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
    die( '-1' );
}

acf_add_local_field_group( array(
    'key'                   => 'group_programme_cat_app_scoring',
    'title'                 => 'Programme category & Application scoring',
    'fields'                => array(
        array(
            'key'               => 'field_programme_category',
            'label'             => 'Programme category',
            'name'              => 'programme-category',
            'type'              => 'select',
            'instructions'      => 'This defines which fields will be shown',
            'required'          => 1,
            'conditional_logic' => 0,
            'wrapper'           => array(
                'width' => '',
                'class' => '',
                'id'    => '',
            ),
            'choices' => [null => 'Please select'] + EASLApplicationsPlugin::SUBMISSION_FIELD_SETS,
            'default_value'     => false,
            'allow_null'        => 0,
            'multiple'          => 0,
            'ui'                => 0,
            'return_format'     => 'value',
            'ajax'              => 0,
            'placeholder'       => '',
        ),
        array(
            'key'               => 'field_scoring_criteria',
            'label'             => 'Scoring categories',
            'name'              => 'scoring_criteria',
            'type'              => 'repeater',
            'instructions'      => 'Define the scoring criteria',
            'required'          => 0,
            'conditional_logic' => array(
                array(
                    array(
                        'field'    => 'field_programme_category',
                        'operator' => '!=',
                        'value'    => 'easl-schools-all',
                    ),
                ),
            ),
            'wrapper'           => array(
                'width' => '',
                'class' => '',
                'id'    => '',
            ),
            'collapsed'         => '',
            'min'               => 0,
            'max'               => 0,
            'layout'            => 'table',
            'button_label'      => '',
            'sub_fields'        => array(
                array(
                    'key'               => 'field_criteria_name',
                    'label'             => 'Category',
                    'name'              => 'criteria_name',
                    'type'              => 'text',
                    'instructions'      => '',
                    'required'          => 1,
                    'conditional_logic' => 0,
                    'wrapper'           => array(
                        'width' => '',
                        'class' => '',
                        'id'    => '',
                    ),
                    'default_value'     => '',
                    'placeholder'       => '',
                    'prepend'           => '',
                    'append'            => '',
                    'maxlength'         => '',
                ),
                array(
                    'key'               => 'field_criteria_max',
                    'label'             => 'Maximum score',
                    'name'              => 'criteria_max',
                    'type'              => 'number',
                    'instructions'      => 'Enter the maximum score that can be achieved for this measure.',
                    'required'          => 1,
                    'conditional_logic' => 0,
                    'wrapper'           => array(
                        'width' => '',
                        'class' => '',
                        'id'    => '',
                    ),
                    'default_value'     => '',
                    'placeholder'       => '',
                    'prepend'           => '',
                    'append'            => '',
                    'min'               => 1,
                    'max'               => '',
                    'step'              => 1,
                ),
                array(
                    'key'               => 'field_criteria_instructions',
                    'label'             => 'Instructions for reviewers',
                    'name'              => 'criteria_instructions',
                    'type'              => 'textarea',
                    'instructions'      => 'This text will be displayed for reviewers when they are scoring applications.',
                    'required'          => 1,
                    'conditional_logic' => 0,
                    'wrapper'           => array(
                        'width' => '',
                        'class' => '',
                        'id'    => '',
                    ),
                    'default_value'     => '',
                    'placeholder'       => '',
                    'maxlength'         => '',
                    'rows'              => 2,
                    'new_lines'         => '',
                ),
            ),
        ),
        array(
            'key'               => 'field_scoring_criteria_schools',
            'label'             => 'Scoring categories',
            'name'              => 'scoring_criteria_schools',
            'type'              => 'group',
            'instructions'      => 'Define the scoring criteria',
            'required'          => 1,
            'conditional_logic' => array(
                array(
                    array(
                        'field'    => 'field_programme_category',
                        'operator' => '==',
                        'value'    => 'easl-schools-all',
                    ),
                ),
            ),
            'wrapper'           => array(
                'width' => '',
                'class' => '',
                'id'    => '',
            ),
            'layout'            => 'block',
            'sub_fields'        => array(
                array(
                    'key'               => 'field_detailed_cv_max_score',
                    'label'             => 'Detailed CV Max Score',
                    'name'              => 'detailed_cv_max_score',
                    'type'              => 'number',
                    'instructions'      => 'Enter the maximum score that can be achieved for Detailed CV',
                    'required'          => 1,
                    'conditional_logic' => 0,
                    'wrapper'           => array(
                        'width' => '',
                        'class' => '',
                        'id'    => '',
                    ),
                    'default_value'     => '',
                    'placeholder'       => '',
                    'prepend'           => '',
                    'append'            => '',
                    'min'               => 1,
                    'max'               => '',
                    'step'              => 1,
                ),
                array(
                    'key'               => 'field_publications_max_score',
                    'label'             => 'Publications Max Score',
                    'name'              => 'publications_max_score',
                    'type'              => 'number',
                    'instructions'      => 'Enter the maximum score that can be achieved for Publications',
                    'required'          => 1,
                    'conditional_logic' => 0,
                    'wrapper'           => array(
                        'width' => '',
                        'class' => '',
                        'id'    => '',
                    ),
                    'default_value'     => '',
                    'placeholder'       => '',
                    'prepend'           => '',
                    'append'            => '',
                    'min'               => 1,
                    'max'               => '',
                    'step'              => 1,
                ),
                array(
                    'key'               => 'field_reference_letter_max_score',
                    'label'             => 'Motivation Letter Max Score',
                    'name'              => 'reference_letter_max_score',
                    'type'              => 'number',
                    'instructions'      => 'Enter the maximum score that can be achieved for Motivation Letter. This will duplicated for the selected schools.',
                    'required'          => 1,
                    'conditional_logic' => 0,
                    'wrapper'           => array(
                        'width' => '',
                        'class' => '',
                        'id'    => '',
                    ),
                    'default_value'     => '',
                    'placeholder'       => '',
                    'prepend'           => '',
                    'append'            => '',
                    'min'               => 1,
                    'max'               => '',
                    'step'              => 1,
                ),
                array(
                    'key'               => 'field_recommendation_letter_max_score',
                    'label'             => 'Recommendation Letter Max Score',
                    'name'              => 'recommendation_letter_max_score',
                    'type'              => 'number',
                    'instructions'      => 'Enter the maximum score that can be achieved for Recommendation Letter. This will duplicated for the selected schools.',
                    'required'          => 1,
                    'conditional_logic' => 0,
                    'wrapper'           => array(
                        'width' => '',
                        'class' => '',
                        'id'    => '',
                    ),
                    'default_value'     => '',
                    'placeholder'       => '',
                    'prepend'           => '',
                    'append'            => '',
                    'min'               => 1,
                    'max'               => '',
                    'step'              => 1,
                ),
                array(
                    'key'               => 'field_appreciation_by_reviewer_max_score',
                    'label'             => 'Appreciation by reviewer Max Score',
                    'name'              => 'appreciation_by_reviewer_max_score',
                    'type'              => 'number',
                    'instructions'      => 'Enter the maximum score that can be achieved for Appreciation by reviewer',
                    'required'          => 1,
                    'conditional_logic' => 0,
                    'wrapper'           => array(
                        'width' => '',
                        'class' => '',
                        'id'    => '',
                    ),
                    'default_value'     => '',
                    'placeholder'       => '',
                    'prepend'           => '',
                    'append'            => '',
                    'min'               => 1,
                    'max'               => '',
                    'step'              => 1,
                ),
            ),
        ),
    ),
    'location'              => array(
        array(
            array(
                'param'    => 'post_type',
                'operator' => '==',
                'value'    => 'programme',
            )
        ),
    ),
    'menu_order'            => 0,
    'position'              => 'normal',
    'style'                 => 'default',
    'label_placement'       => 'top',
    'instruction_placement' => 'label',
    'hide_on_screen'        => '',
    'active'                => true,
    'description'           => '',
) );

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
        ],
        [
            'key' => 'hide',
            'label' => 'Hide programme',
            'name' => 'hide',
            'type' => 'true_false',
            'instructions' => 'Hide this programme?'
        ]
    ],
    'location' => EASLApplicationsPlugin::acfPostTypeLocation('programme'),
    'menu_order'            => 1,
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
    'location' => EASLApplicationsPlugin::acfPostTypeLocation('programme'),
    'menu_order'            => 2,
]);