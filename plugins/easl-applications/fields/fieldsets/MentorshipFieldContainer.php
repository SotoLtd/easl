<?php
// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
    die( '-1' );
}

class MentorshipFieldContainer extends AbstractFieldContainer {

    protected function registerFieldSets() {

        $applicationDocumentFieldSet = $this->makeFieldSet(
            'application_documents',
            'Application documents',
            [
                new EASLApplicationField('motivation_letter', 'Motivation Letter', 'file', ['instructions' => 'Max 4 pages']),
                new EASLApplicationField('cv', 'CV', 'file', ['instructions' => 'Max 4 pages']),
                new EASLApplicationField('letter_of_recommendation', 'Letter of recommendation', 'file', ['instructions' => 'A letter of recommendation from your current supervisor'])
            ]
        );

        $additionalInformationFieldSet = $this->makeAdditionalInformationFields('Have you applied to an EASL Mentorship Programme in the past?', 'How did you find out about EASL Mentorships?');

        $confirmationFieldSet = $this->makeConfirmationStatementFields('I confirm that I have read and understood the guidelines of the EASL Mentorship Programme and the above information is true and accurate.', [
            $this->makeConfirmCheckbox('permanent_position', 'Permanent position', 'I confirm that I do not hold a permanent position')
        ]);

        $this->fieldSets = [
            $applicationDocumentFieldSet,
            $additionalInformationFieldSet,
            $confirmationFieldSet
        ];
    }

}