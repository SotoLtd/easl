<?php
// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
    die( '-1' );
}


class MasterclassFieldContainer extends AbstractFieldContainer {

    protected function registerFieldSets() {


        $applicantInformationFieldSet = $this->makeFieldSet(
            'project_information',
            'Project information',
            [
                new EASLApplicationField('applicant_profile', 'Applicant profile: "I am a"', 'select', [
                    'choices' => [
                        'clinical_practitioner' => 'Clinical Practitioner',
                        'academic_researcher' => 'Academic Researcher',
                        'other' => 'Other'
                    ]
                ]),
                new EASLApplicationField('applicant_profile_other', 'Other - please specify', 'text', [], false, ['applicant_profile', 'other']),
                new EASLApplicationField('area_of_interest', 'Area of interest', 'select', [
                    'choices' => [
                        'general_hepatology'                      => 'General Hepatology',
                        'immune_mediated_and_cholestatic_disease' => 'Immune-mediated and cholestatic disease',
                        'cirrhosis_and_complications'             => 'Cirrhosis and complications',
                        'liver_tumors'                            => 'Liver tumours',
                        'metablic_disease'                        => 'Metabolic disease',
                        'alcohol_and_toxitity'                    => 'Alcohol and toxicity',
                        'viral_hepatitis'                         => 'Viral hepatitis'
                    ]
                ]),
                new EASLApplicationField('current_position', 'Current position', 'text'),
                new EASLApplicationField('current_position_from_date', 'In current position from', 'date_picker', [
                    'display_format' => 'd/m/Y',
                    'return_format' => 'd/m/Y'
                ]),
                new EASLApplicationField('career_benefits', 'Describe the benefits of this opportunity to your academic/research career and your motivation for attending', 'textarea'),
                new EASLApplicationField('career_accomplishments_and_goals', 'Please highlight your previous accomplishments and future career goals', 'textarea'),
                new EASLApplicationField('english_language_skills', 'How would you qualify your English language skills', 'select', [
                    'choices' => [
                        'native' => 'Native speaker',
                        'proficient' => 'Proficient',
                        'good' => 'Good working knowledge',
                        'basic' => 'Basic communication skills'
                    ]
                ]),
                new EASLApplicationField('referee_name', 'Name of referee 1', 'text'),
                new EASLApplicationField('referee_email', 'Email address of referee 1', 'text'),
                new EASLApplicationField('referee_recommendation_letter', 'Upload letter of recommendation from referee 1', 'file'),
    
                new EASLApplicationField('referee2_name', 'Name of referee 2', 'text'),
                new EASLApplicationField('referee2_email', 'Email address of referee 2', 'text'),
                new EASLApplicationField('referee2_recommendation_letter', 'Upload letter of recommendation from referee 2', 'file'),
            ]
        );

        $abstractsFieldSet = $this->makeFieldSet('abstracts', 'Abstracts', [
            new EASLApplicationField('background_methods_results', 'Abstract research presentation', 'textarea', ['instructions' => 'In the box below, briefly describe the background, methods, results, and conclusions for your study or research (2500 Characters)','maxlength' => 2500])
        ]);

        $grantApplications = $this->makeFieldSet('grant_applications', 'Grant Applications', [
            self::makeYesNoSelect('have_you_ever_obtained_independent_grants', 'Have you ever obtained independent and competitive grants?'),
            new EASLApplicationField('grants_applied_for', 'If yes please list them:', 'text', [], false, ['have_you_ever_obtained_independent_grants', 'yes'])
        ]);

        $applicationDocumentFieldSet = $this->makeFieldSet(
            'application_documents',
            'Application documents',
            [
                new EASLApplicationField('cv', 'CV', 'file', ['instructions' => 'Max 5 pages']),
                new EASLApplicationField('list_of_publications', 'List of publications', 'file'),
                new EASLApplicationField('confirmation_of_age_or_training_status', 'Confirmation of age or training status', 'file', ['instructions' => 'Please provide a copy of ID or certificate of enrollment']),
            ]
        );

        $additionalInformationFieldSet = $this->makeFieldSet(
            'additional_information',
            'Additional information', [
            self::makeYesNoSelect('previous_masterclasses', 'Have you participated in previous EASL Masterclasses?'),
            new EASLApplicationField('list_previous_masterclasses', 'Please list the EASL Masterclass that you have attended', 'text', [], false, ['previous_masterclasses', 'yes']),
            self::makeHowDidYouFindOutField('How did you find out about EASL Masterclasses?'),
            self::makeHowDidYouFindOutOtherField()
        ]);

        $confirmationFieldSet = $this->makeFieldSet(
            'confirmation',
            'Confirmation', [
            self::makeYesNoSelect('share_contact_details', 'Share my contact details with other participants', ['instructions' => 'I agree to share my contact details with other participants of the EASL Masterclasses']),
            self::makeYesNoSelect('contact_future_events', 'I accept to be contacted for future EASL events'),
            self::makeConfirmCheckbox('personal_information', 'Personal information', 'I confirm that my personal information in the EASL Memberzone is up-to-date'),
            self::makeConfirmCheckbox('guidelines', 'Guidelines', 'I confirm that I have read and understood the guidelines of the EASL Masterclasses and the above information is true and accurate.')
        ]);

        $this->fieldSets = [
            $applicantInformationFieldSet,
            $abstractsFieldSet,
            $grantApplications,
            $applicationDocumentFieldSet,
            $additionalInformationFieldSet,
            $confirmationFieldSet
        ];
    }
}