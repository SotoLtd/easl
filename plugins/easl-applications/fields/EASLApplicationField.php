<?php

class EASLApplicationField
{

    /**
     * @var string
     */
    public $type;

    /**
     * @var string
     */
    public $name;

    /**
     * @var array of additional ACF settings
     */
    public $settings = [];

    /**
     * EASLApplicationField constructor.
     * @param $name
     * @param $type
     * @param $settings
     */
    public function __construct($name, $type, $settings = []) {
        $this->name = $name;
        $this->type = $type;
        $this->settings = $settings;
    }
}