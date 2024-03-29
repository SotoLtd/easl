<?php
// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
    die( '-1' );
}


class EASLSchoolsFieldContainer extends AbstractFieldContainer {

    protected function registerFieldSets() {

        $applicantInformationFieldSet = $this->makeFieldSet(
            'project_information',
            'Project information',
            [
                new EASLApplicationField('date_of_birth', 'Date of birth', 'date_picker', [
                    'display_format' => 'd/m/Y',
                    'return_format' => 'd/m/Y'
                ]),
                new EASLApplicationField('applicant_profile', 'Applicant profile', 'select', [
                    'choices' => [
                        'clinical_practitioner' => 'Clinical Practitioner',
                        'academic_researcher' => 'Academic Researcher',
                        'other' => 'Other'
                    ]
                ]),
                new EASLApplicationField('applicant_profile_other', 'Other - please specify', 'text', [], false, [
                    'applicant_profile', 'other'
                ]),
                new EASLApplicationField('current_position', 'Current position', 'text'),
                new EASLApplicationField('current_position_from_date', 'In current position from', 'date_picker', [
                    'display_format' => 'd/m/Y',
                    'return_format' => 'd/m/Y'
                ]),
                new EASLApplicationField('training', 'Training', 'text'),
                new EASLApplicationField('anticipated_year_of_completion', 'Anticipated year of completion', 'text'),
                new EASLApplicationField('referee_name', 'Name of referee', 'text'),
                new EASLApplicationField('referee_email', 'Email address of referee', 'text'),
                new EASLApplicationField('english_language_skills', 'How would you qualify your English language skills', 'select', [
                    'choices' => [
                        'native' => 'Native speaker',
                        'proficient' => 'Proficient',
                        'good' => 'Good working knowledge',
                        'basic' => 'Basic communication skills'
                    ]
                ]),
                new EASLApplicationField('abstracts_at_easl_events_year', 'Abstracts presented at EASL events: Presentation years', 'text'),
                new EASLApplicationField('abstracts_at_easl_events_title', 'Abstracts presented at EASL events: Titles of publications / abstracts', 'text'),
                new EASLApplicationField('abstracts_at_non_easl_events_year', 'Abstracts presented at non-EASL events: Presentation years', 'text'),
                new EASLApplicationField('abstracts_at_non_easl_events_title', 'Abstracts presented at non-EASL events: Titles of publications / abstracts', 'text'),
            ]
        );

        $applicationDocumentFieldSet = $this->makeFieldSet(
            'application_documents',
            'Application documents',
            [
                new EASLApplicationField('cv', 'CV', 'file', ['instructions' => 'Max 4 pages']),
                new EASLApplicationField('confirmation_age_training_status', 'Confirmation of age or training status', 'file', ['instructions' => 'Please provide a copy of ID or certificate of enrollment']),
                new EASLApplicationField('reference_letter', 'Reference Letter', 'file'),
            ]
        );

        $additionalInformationFieldSet = $this->makeFieldSet(
            'additional_information',
            'Additional Information',
            [
                new EASLApplicationField('previously_applied', 'Have you participated in previous EASL schools?', 'radio', [
                    'choices' => [
                        'yes' => 'Yes',
                        'no' => 'No'
                    ]
                ]),
                new EASLApplicationField('easl_schools_previous_years', 'Please list the EASL school(s) that you have attended', 'text', [], false, ['previously_applied', 'yes']),
                self::makeHowDidYouFindOutField('How did you find out about EASL schools?'),
                self::makeHowDidYouFindOutOtherField()
            ]
        );

        $confirmationFieldSet = $this->makeFieldSet('confirmation', 'Confirmation', [
            self::makeYesNoSelect('share_details', 'I agree to share my contact details with the other participants of the EASL school'),
            self::makeYesNoSelect('contact_future_events', 'I accept to be contacted for future EASL events'),
            self::makeConfirmCheckbox('personal_information','Personal information', 'I confirm that my personal information in the EASL Memberzone is up-to-date'),
            self::makeConfirmCheckbox('guidelines', 'Guidelines', 'I confirm that I have read and understood the guidelines of the EASL schools and the above information is true and accurate.')
        ]);

        $this->fieldSets = [
            $applicantInformationFieldSet,
            $applicationDocumentFieldSet,
            $additionalInformationFieldSet,
            $confirmationFieldSet
        ];
    }
}