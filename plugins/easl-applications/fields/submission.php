<?php
// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
    die( '-1' );
}

$field = new EASLApplicationField('programme', 'post');
acf_add_local_field_group([
    'key' => '',
    'title' => '',
    'fields' => [
        [
            'key' => 'programme',
            'title' => 'Programme',
            'type' => 'post_object',
            'post_type' => 'programme'
        ]
    ],
    'location' => EASLApplicationsPlugin::acfPostTypeLocation('submission')
]);