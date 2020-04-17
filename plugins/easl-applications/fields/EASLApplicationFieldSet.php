<?php

class EASLApplicationFieldSet
{

    /**
     * @var string
     */
    public $name;

    /**
     * @var array of additional ACF settings
     */
    public $settings = [];

    /**
     * @var array of fields
     */
    public $fields;

    public function __construct($name, $fields, $settings = [])
    {
        $this->name = $name;
        $this->fields = $fields;
        $this->settings = $settings;
    }

    private function getKey($name) {
        return self::class . '_' . EASLApplicationsPlugin::getSlug($name);
    }

    public function getFieldSetKey() {
        return $this->getKey($this->name);
    }

    private function getACFFieldDefinitions() {
        return array_map(function($field) {

            $key = $this->getKey($field->name);
            $output = [
                'label' => $field->name,
                'name' => $key,
                'key' => $key,
                'type' => $field->type,
            ];
            $output += $field->settings;
            return $output;

        }, $this->fields);
    }

    public function getACFDefinition() {

        return [
            'key' => $this->getFieldSetKey(),
            'title' => $this->name,
            'fields' => $this->getACFFieldDefinitions(),
            'location' => []
        ] + $this->settings;
    }
}