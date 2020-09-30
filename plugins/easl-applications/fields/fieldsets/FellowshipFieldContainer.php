<?php
// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
    die( '-1' );
}


class FellowshipFieldContainer extends AbstractFieldContainer {

    protected function registerFieldSets() {

        $hostInstituteFieldSet = $this->makeFieldSet(
            'host_institute', 'Host institute',
            [
                new EASLApplicationField('host_institute', 'Host Institute', 'text'),
                new EASLApplicationField('street', 'Street', 'text'),
                new EASLApplicationField('city', 'City', 'text'),
                new EASLApplicationField('state', 'State', 'text'),
                new EASLApplicationField('postcode', 'Postcode', 'text'),
                new EASLApplicationField('country', 'Country', 'text'), //@todo sort this out
                new EASLApplicationField('institution_phone_number', 'Institution phone number', 'text'),
                new EASLApplicationField('supervisor', 'Name of supervisor', 'text'),
                new EASLApplicationField('supervisor_member_id', 'Membership ID of supervisor', 'text')
            ]
        );

        $projectInformationFieldSet = $this->makeFieldSet(
            'project_information', 'Project information',
            [
                new EASLApplicationField('project_title', 'Project title', 'text'),
                new EASLApplicationField('project_duration', 'Duration of your project', 'text'),
            ]
        );

        $applicationDocumentFieldSet = $this->makeFieldSet(
            'application_documents', 'Application documents',
            [
                new EASLApplicationField('project_summary', 'Project summary', 'file'), //@todo whitelist mime types
                new EASLApplicationField('cv', 'CV', 'file', ['instructions' => 'Max 4 pages']),
                new EASLApplicationField('letter_of_support', 'Letter of support from host institute', 'file'),
                new EASLApplicationField('reference_letter_1', 'Reference letter 1', 'file'),
                new EASLApplicationField('reference_letter_2', 'Reference letter 2', 'file'),
            ]
        );

        $additionalInformationFieldSet = $this->makeAdditionalInformationFields('Have you applied to an EASL Fellowship Programme in the past?', 'How did you find out about EASL Fellowships?');

        $confirmationFieldSet = $this->makeConfirmationStatementFields('I confirm that I have read and understood the guidelines of the EASL Fellowship Programme and the above information is true and accurate.');

        $this->fieldSets = [
            $hostInstituteFieldSet,
            $projectInformationFieldSet,
            $applicationDocumentFieldSet,
            $additionalInformationFieldSet,
            $confirmationFieldSet
        ];
    }
}