<?php
namespace GiantRobot\Meta\Fields;

/**
 * Text Field
 */
class Text extends Field
{
    /**
     * The template to use when rendering.
     *
     * @var string
     */
    protected $template = 'text';

    /**
     * Default sanitization method.
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
