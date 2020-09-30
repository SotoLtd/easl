<?php
// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
    die( '-1' );
}


class RegistryGrantFieldContainer extends AbstractFieldContainer {

    protected function registerFieldSets() {

        $projectInformationFieldSet = $this->makeFieldSet(
            'project_information',
            'Project information',
            [
                new EASLApplicationField('registry_title', 'Title of Registry', 'text'),
                new EASLApplicationField('funding_amount', 'Amount of funding', 'text', ['instructions' => 'Indicate the amount of funding you are requesting (max. 100,000 EUR)']),
                new EASLApplicationField('less_than_100000_feasible', 'If you are awarded less than 100,000 EUR would the Registry Project still be feasible to implement?', 'select', [
                    'choices' => [
                        'yes' => 'Yes',
                        'no' => 'No'
                    ]
                ]),
                new EASLApplicationField('min_max_funding', 'Min/max funding', 'text', ['instructions' => 'What is the min/max funding requested for the implementation of the Registry Project?'])
            ]
        );

        $applicationDocumentFieldSet = $this->makeFieldSet(
            'application_documents',
            'Application documents',
            [
                new EASLApplicationField('registry_project_summary', 'Registry Project Summary', 'file'),
                new EASLApplicationField('partner_institutions', 'List of Partner Institutions', 'file'),
                new EASLApplicationField('cost_justification', 'Cost Justification', 'file'),
                new EASLApplicationField('additional_support_documents', 'Additional Support documents', 'file'),
            ]
        );


        $additionalInformationFieldSet = $this->makeAdditionalInformationFields('Have you applied for an EASL Registry Grant in the past?', 'How did you find out about EASL Registry Grants?');


        $confirmationFieldSet = $this->makeConfirmationStatementFields('I confirm that I have read and understood the guidelines of the EASL Registry Grant Programme and the above information is true and accurate.', [
            $this->makeConfirmCheckbox('permanent_position', 'Permanent position', 'I confirm that I hold a permanent position')
        ]);

        $this->fieldSets = [
            $projectInformationFieldSet,
            $applicationDocumentFieldSet,
            $additionalInformationFieldSet,
            $confirmationFieldSet
        ];
    }
}