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
     * @var string
     */
    public $key;

    /**
     * @var array of additional ACF settings
     */
    public $settings = [];

    /**
     * @var bool
     */
    public $hideFromOutput;

    /**
     * EASLApplicationField constructor.
     * @param $key
     * @param $name
     * @param $type
     * @param $settings
     * @param bool $hideFromOutput
     */
    public function __construct($key, $name, $type, $settings = [], $hideFromOutput = false) {
        $this->key = $key;
        $this->name = $name;
        $this->type = $type;
        $this->settings = $settings;
        $this->hideFromOutput = $hideFromOutput;

        if ($this->type === 'select') {
            $this->settings['choices'] = [null => 'Please select'] + $this->settings['choices'];
        }
    }
}