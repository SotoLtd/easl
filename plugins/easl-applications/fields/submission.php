<?php
// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
    die( '-1' );
}

add_action('acf/init', 'eapp_acf_add_submission_local_field_groups');

function eapp_acf_add_submission_local_field_groups() {
    if(!is_admin() || empty($_GET['post']) || 'submission' != get_post_type($_GET['post'])) {
        return;
    }
    $programmeId = get_post_meta($_GET['post'], 'programme_id', true);
    if(!$programmeId) {
        return;
    }
    $fieldSets = EASLApplicationsPlugin::getInstance()->getProgrammeFieldSetContainer($programmeId);
    foreach($fieldSets->getFieldSets() as $fieldSet) {
        $group_defs = $fieldSet->getACFDefinition();
        $group_defs['key'] = '_admin_' . $group_defs['key'];
        $group_defs['location'] = EASLApplicationsPlugin::acfPostTypeLocation('submission');
        acf_add_local_field_group($group_defs);
    }
}

