<?php
// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
    die( '-1' );
}

class EASLApplicationFieldSet
{

    /**
     * @var string
     */
    public $name;

    /**
     * @var string
     */
    public $key;

    /**
     * @var string
     */
    protected $namePrefix;

    /**
     * @var bool
     */
    protected $required;

    /**
     * @var array of additional ACF settings
     */
    public $settings = [];

    /**
     * @var array of fields
     */
    public $fields;

    public function __construct($key, $name, $fields, $settings = [], $namePrefix = '', $optional = false) {
        $this->key = $key;
        $this->name = $name;
        $this->fields = $fields;
        $this->settings = $settings;
        $this->namePrefix = $namePrefix;
        $this->required = !$optional;
    }

    public function getKey() {
        return $this->namePrefix . '_' . $this->key;
    }

    public function getFieldKey($field) {
        return $this->getKey() . '_' . $field->key;
    }

    private function getACFFieldDefinitions() {
        return array_map(function($field) {

            $output = [
                'label' => $field->name,
                'name' => $this->getFieldKey($field),
                'key' => $this->getFieldKey($field),
                'required' => $this->required,
                'type' => $field->type,
            ];

            if ($field->conditionalLogic) {
                $dependsOnKey = $field->conditionalLogic[0];
                $dependsOnValue = $field->conditionalLogic[1];

                $dependsOnField = EASLApplicationsPlugin::findInArray($this->fields, function($field) use ($dependsOnKey) {
                    return $field->key === $dependsOnKey;
                });

                $output['conditional_logic'] = [
                    [
                        [
                            'field' => $this->getFieldKey($dependsOnField),
                            'operator' => '==',
                            'value' => EASLApplicationsPlugin::getSlug($dependsOnValue)
                        ]
                    ]
                ];
            }

            $output += $field->settings;
            return $output;

        }, $this->fields);
    }

    public function getACFDefinition() {

        return [
            'key' => $this->getKey(),
            'title' => $this->name,
            'fields' => $this->getACFFieldDefinitions(),
            'location' => []
        ] + $this->settings;
    }
}