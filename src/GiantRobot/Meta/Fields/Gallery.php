<?php
namespace GiantRobot\Meta\Fields;

/**
 * Gallery
 */
class Gallery extends Field
{
    /**
     * The template to use when rendering.
     *
     * @var string
     */
    protected $template = 'gallery';

    /**
     * Default sanitization method, used when the sanitize option is not set.
     *
     * @param mixed $values
     *
     * @return array|null
     */
    protected function sanitizeDefault($values)
    {
        if (! $values)
        {
            return null;
        }

        $sanitized = array_map(function($value) {
            return sanitize_text_field($value);
        }, $values);

        return array_values($sanitized);
    }

    /**
     * Gather field-specific view data.
     *
     * @param mixed $value
     *
     * @return array
     */
    protected function viewData($value = null)
    {
        $values = $value;

        if (! $value)
        {
            $values = array();
        }
        elseif (! is_array($values))
        {
            $values = array($value);
        }

        return [
            'values' => $values
        ];
    }
}
