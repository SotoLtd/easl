<?php
// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
    die( '-1' );
}


class EmergingLeaderAwardFieldContainer extends AbstractFieldContainer {
    protected function registerFieldSets() {
        
        $hostInstituteFieldSet = $this->makeFieldSet(
            'host_institute', 'Host institute',
            [
                new EASLApplicationField('host_institute', 'Institute', 'text'),
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
                new EASLApplicationField('cv', 'CV', 'file', ['instructions' => 'Max 4 pages']),
                new EASLApplicationField('letter_of_recommendation', 'Recommendation Letter', 'file'),
                new EASLApplicationField('publications_invited_presentation', 'List of publications and invited presentations in international conferences', 'file'),
                new EASLApplicationField('prominent_publications', 'Three of your most prominent publicatoins with journal impact factor not less than six', 'file'),
                new EASLApplicationField('hindex_publications', 'H-index, number of publications in Pubmed and total number of citations', 'file'),
            ]
        );
        
        $additionalInformationFieldSet = $this->makeAdditionalInformationFields('Have you applied to the EASL Emerging Leader Award in the past?', 'How did you find out about the EASL Emerging Leader Award');
        
        $confirmationFieldSet = $this->makeConfirmationStatementFields('I confirm that I have read and understood the guidelines of the EASL Fellowship Programme and the above information is true and accurate. - Update to: “I confirm that I have read and understood the guidelines of the EASL Emering Leader Award and the above information is true and accurate');
        
        $this->fieldSets = [
            $hostInstituteFieldSet,
            $projectInformationFieldSet,
            $applicationDocumentFieldSet,
            $additionalInformationFieldSet,
            $confirmationFieldSet
        ];
    }
}