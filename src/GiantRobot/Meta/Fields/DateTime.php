<?php
namespace GiantRobot\Meta\Fields;

/**
 * DateTime
 */
class DateTime extends Field
{
    /**
     * The template to use when rendering.
     *
     * @var string
     */
    protected $template = 'datetime';

    /**
     * Gather field-specific view data.
     *
     * @param mixed $value
     *
     * @return array
     */
    protected function viewData($value = null)
    {
        $saveFormat = $this->options('save_format', 'Y-m-d H:i:s');

        return [
            'save_format' => $saveFormat,
            'display_format' => $this->options('display_format', $saveFormat),
            'show_datepicker' => $this->options('show_datepicker', true),
            'show_timepicker' => $this->options('show_timepicker', true),
        ];
    }

    /**
     * Default sanitization method, used when the sanitize option is not set.
     *
     * @param mixed $value
     *
     * @return mixed
     */
    protected function sanitizeDefault($value)
    {
        return sanitize_text_field($value);
    }
}
