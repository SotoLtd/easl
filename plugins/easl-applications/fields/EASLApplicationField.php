<?php
// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
    die( '-1' );
}

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
     * @var array|null
     */
    public $conditionalLogic;

    /**
     * EASLApplicationField constructor.
     * @param $key
     * @param $name
     * @param $type
     * @param array $settings
     * @param bool $hideFromOutput
     * @param array $conditionalLogic
     */
    public function __construct($key, $name, $type, $settings = [], $hideFromOutput = false, $conditionalLogic = null) {
        $this->key = $key;
        $this->name = $name;
        $this->type = $type;
        $this->settings = $settings;
        $this->hideFromOutput = $hideFromOutput;
        $this->conditionalLogic = $conditionalLogic;

        if ($this->type === 'select') {
            $this->settings['choices'] = [null => 'Please select'] + $this->settings['choices'];
        }
    }
}