<?php
namespace GiantRobot\Meta\Fields;

/**
 * Checkbox
 */
class Checkbox extends Field
{
    /**
     * The template to use when rendering.
     *
     * @var string
     */
    protected $template = 'checkbox';

    /**
     * Default sanitization method, used when the sanitize option is not set.
     *
     * @param array $input
     *
     * @return string|string[]|null
     */
    protected function sanitizeDefault($input)
    {
        // When not empty, the input should always be an array.
        // If not, somebody's been monkeying around with it.
        if (! is_array($input))
        {
            return null;
        }

        $choices = $this->options('choices', []);
        $validInput = array_intersect($input, array_keys($choices));

        if (count($choices) > 1)
        {
            $sanitized = array_map('sanitize_text_field', $validInput);
        }
        else
        {
            $sanitized = sanitize_text_field(array_shift($validInput));
        }

        return $sanitized;
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
        if (! $value)
        {
            $values = array();
        }
        elseif (! is_array($value))
        {
            $values = array($value);
        }
        else
        {
            $values = $value;
        }

        return [
            'choices' => $this->options('choices', []),
            'values' => $values,
            'layout' => $this->options('layout', 'vertical'),
        ];
    }
}
