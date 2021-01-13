<?php
// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
    die( '-1' );
}

abstract class getProgrammeFieldSetContainer {

    /**
     * @var EASLApplicationFieldSet[]
     */
    protected $fieldSets;

    /**
     * @var string
     */
    protected $name;

    public function __construct($name) {
        $this->name = $name;
        $this->registerFieldSets();
        $this->registerACFFields();
    }

    abstract protected function registerFieldSets();

    protected function registerACFFields() {
        foreach($this->fieldSets as $fieldSet) {
            acf_add_local_field_group($fieldSet->getACFDefinition());
        }
    }

    /**
     * @return EASLApplicationFieldSet[]
     */
    public function getFieldSets() {
        return $this->fieldSets;
    }

    public function getFieldSetKeys() {

        return array_map(function($fieldSet) {
            /** @var EASLApplicationFieldSet $fieldSet */
            return $fieldSet->getKey();
        }, $this->fieldSets);
    }
    
    protected function makeFieldSet($key, $name, $fields, $settings = []) {
        return new EASLApplicationFieldSet($key, $name, $fields, $settings, $this->name);
    }

    protected function makeAdditionalInformationFields($haveYouAppliedBeforeText, $howDidYouFindOutText, $extraFields = []) {
        $extraFields[] = new EASLApplicationField('previously_applied', $haveYouAppliedBeforeText, 'radio', [
            'choices' => [
                'yes' => 'Yes',
                'no' => 'No'
            ]
        ]);
        
        $extraFields[] = self::makeHowDidYouFindOutField($howDidYouFindOutText);

        $extraFields[] = self::makeHowDidYouFindOutOtherField();

        return $this->makeFieldSet('additional_information', 'Additional information', $extraFields);
    }

    protected function makeConfirmationStatementFields($guidelinesText, $extraFields = []) {
        $extraFields[] = self::makeConfirmCheckbox('personal_information', 'Personal information', 'I confirm that my personal information in the EASL Memberzone is up-to-date');
        $extraFields[] = self::makeConfirmCheckbox('guidelines', 'Guidelines', $guidelinesText);

        return $this->makeFieldSet('confirmation', 'Confirmation', $extraFields);
    }

    public static function makeConfirmCheckbox($key, $name, $text) {
        return new EASLApplicationField($key, $name, 'checkbox', [
            'choices' => $text
        ], true);
    }

    public static function makeYesNoSelect($key, $name, $settings = []) {
        $settings['choices'] = [
            'yes' => 'Yes',
            'no' => 'No'
        ];
        return new EASLApplicationField($key, $name, 'select', $settings);
    }

    public static function makeHowDidYouFindOutField($text) {
        return new EASLApplicationField('how_did_you_find_out', $text, 'select', [
            'choices' => EASLApplicationsPlugin::makeOptionsList([
                'EASL Website',
                'EASL Social Media',
                'At the International Liver Congress',
                'Visiting an EASL Booth',
                'Work/Colleagues',
                'Other'
            ])
        ]);
    }

    public static function makeHowDidYouFindOutOtherField() {
        return new EASLApplicationField('other_specify', 'Other - please specify', 'text', [], false, [
                'how_did_you_find_out',
                'Other'
            ]
        );
    }
}