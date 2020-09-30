<?php
// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
    die( '-1' );
}

require_once(__DIR__ . '/../../../easl-member-zone/include/helpers/crm-dropdown-lists.php');
class EndorsementFieldContainer extends AbstractFieldContainer {

    protected function registerFieldSets() {

        $eventInformationFieldSet = $this->makeFieldSet(
            'event_information',
            'Event information',
            [
                new EASLApplicationField('event_title', 'Event Title', 'text'),
                new EASLApplicationField('event_organiser', 'Organization / Society organizing the event', 'text'),
                new EASLApplicationField('start_date', 'Starting Date', 'date_picker', [
                    'display_format' => 'd/m/Y',
                    'return_format' => 'd/m/Y'
                ]),
                new EASLApplicationField('end_date', 'Ending Date', 'date_picker', [
                    'display_format' => 'd/m/Y',
                    'return_format' => 'd/m/Y'
                ]),
                new EASLApplicationField('event_city', 'Location of Event  - City', 'text'),
                new EASLApplicationField('event_country', 'Location of Event  - Country', 'select', [
                    'choices' => easl_mz_get_list_countries()
                ]),
            ]
        );


        $applicationDocumentFieldSet = $this->makeFieldSet(
            'application_documents',
            'Application documents',
            [
                new EASLApplicationField('application_type', 'Type of application', 'select', [
                    'choices' => [
                        'event_endorsement' => 'Event Endorsement',
                        'national_society_endorsement' => 'National Society Event Endorsement',
                        'sponsorship' => 'Sponsorship'
                    ]
                ]),
                new EASLApplicationField('official_request_letter', 'Official Request Letter', 'file'),
                new EASLApplicationField('programme_speaker_list', 'Programme and List of Speakers', 'file'),
                new EASLApplicationField('sponsors_list', 'List of Sponsors / Event Supporters', 'file'),
                new EASLApplicationField('budget', 'Itemized Budget listing the main expenses and income sources', 'file'),
                new EASLApplicationField('non_profit_declaration', 'Not-for-profit declaration', 'file'),
                new EASLApplicationField('additional_documentation', 'Any additional supporting documentation', 'file'),
            ]
        );

        $additionalInformationFieldSet = $this->makeFieldSet(
            'additional_information',
            'Additional Information',
            [
                new EASLApplicationField('previously_applied', 'Have you applied to an EASL Endorsement Programme in the past?', 'radio', [
                    'choices' => [
                        'yes' => 'Yes',
                        'no' => 'No'
                    ]
                ]),
                new EASLApplicationField('previous_endorsement_application_year', 'Please provide year(s)', 'text', [], false, ['previously_applied', 'yes']),
                self::makeHowDidYouFindOutField('How did you find out about EASL Endorsements?'),
                self::makeHowDidYouFindOutOtherField()
            ]
        );

        $confirmationFieldSet = $this->makeConfirmationStatementFields('I confirm that I have read and understood the guidelines of the EASL Endorsement Programme and the above information is true and accurate.', [
            $this->makeConfirmCheckbox('permanent_position', 'Permanent position', 'I confirm that I hold a permanent position')
        ]);

        $this->fieldSets = [
            $eventInformationFieldSet,
            $applicationDocumentFieldSet,
            $additionalInformationFieldSet,
            $confirmationFieldSet
        ];
    }
}