<?php
namespace GiantRobot\Meta\Fields;

/**
 * Textarea Field
 */
class Textarea extends Field
{
    /**
     * The template to use when rendering.
     *
     * @var string
     */
    protected $template = 'textarea';

    /**
     * Default sanitization method.
     *
     * @param mixed $value
     *
     * @return mixed
     */
    protected function sanitizeDefault($value)
    {
        if (! $value)
        {
            return '';
        }

        $lines = explode("\n", $value);
        $lines = array_map('sanitize_text_field', $lines);

        return implode("\n", $lines);
    }
}
