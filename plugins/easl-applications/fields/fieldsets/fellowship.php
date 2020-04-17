<?php
$dir = dirname(__FILE__);
require_once($dir . '/../EASLApplicationField.php');
require_once($dir . '/../EASLApplicationFieldSet.php');

$hostInstituteFields = [
    new EASLApplicationField('Host Institute', 'text'),
    new EASLApplicationField('Street', 'text'),
    new EASLApplicationField('City', 'text'),
    new EASLApplicationField('State', 'text'),
    new EASLApplicationField('Postcode', 'text'),
    new EASLApplicationField('Country', 'text'), //@todo sort this out
    new EASLApplicationField('Institution phone number', 'text'),
    new EASLApplicationField('Name of supervisor', 'text'),
    new EASLApplicationField('Membership ID of supervisor', 'text')
];

$hostInstituteFieldSet = new EASLApplicationFieldSet(
    'Host institute',
    $hostInstituteFields,
    []
);

acf_add_local_field_group($hostInstituteFieldSet->getACFDefinition());

$projectInformationFields = [
    new EASLApplicationField('Project title', 'text'),
    new EASLApplicationField('Duration of your project', 'text'),
];

$projectInformationFieldSet = new EASLApplicationFieldSet(
    'Project information',
    $projectInformationFields,
    []
);

acf_add_local_field_group($projectInformationFieldSet->getACFDefinition());

$applicationDocumentFields = [
    new EASLApplicationField('Project summary', 'file'), //@todo whitelist mime types
    new EASLApplicationField('CV', 'file', ['instructions' => 'Max 4 pages']),
    new EASLApplicationField('Letter of support from host institute', 'file'),
    new EASLApplicationField('Reference letter 1', 'file'),
    new EASLApplicationField('Reference letter 2', 'file'),
];

$applicationDocumentFieldSet = new EASLApplicationFieldSet(
    'Application documents',
    $applicationDocumentFields,
    []
);

acf_add_local_field_group($applicationDocumentFieldSet->getACFDefinition());

$additionalInformationFields = [
    new EASLApplicationField('Have you applied to an EASL Fellowship Programme in the past?', 'text'),
    new EASLApplicationField('How did you find out about EASL Fellowships?', 'select', [
        'choices' => EASLApplicationsPlugin::makeOptionsList([
            'EASL Website',
            'EASL Social Media',
            'At the International Liver Congress',
            'Visiting an EASL Booth',
            'Work/Colleagues',
            'Other'
        ])
    ]),
    new EASLApplicationField('Other - please specify', 'text'), //@todo conditional logic
];

$additionalInformationFieldSet = new EASLApplicationFieldSet(
    'Additional information',
    $additionalInformationFields,
    []
);

acf_add_local_field_group($additionalInformationFieldSet->getACFDefinition());

$confirmationFields = [
    new EASLApplicationField('Personal information', 'checkbox', [
        'choices' => 'I confirm that my personal information in the EASL Memberzone is up-to-date'
    ]),
    new EASLApplicationField('Guidelines', 'checkbox', [
        'choices' => 'I confirm that I have read and understood the guidelines of the EASL Fellowship Programme and the above information is true and accurate.'
    ])
];

$confirmationFieldSet = new EASLApplicationFieldSet(
    'Confirmation',
    $confirmationFields,
    []
);

acf_add_local_field_group($confirmationFieldSet->getACFDefinition());

$fieldsets = [
    'host_institute' => $hostInstituteFieldSet->getFieldSetKey(),
    'project_information' => $projectInformationFieldSet->getFieldSetKey(),
    'documents' => $applicationDocumentFieldSet->getFieldSetKey(),
    'additional_information' => $additionalInformationFieldSet->getFieldSetKey(),
    'confirmation' => $confirmationFieldSet->getFieldSetKey()
];