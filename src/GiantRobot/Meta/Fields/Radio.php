<?php
namespace GiantRobot\Meta\Fields;

/**
 * Radio Field
 */
class Radio extends Field
{
    /**
     * The template to use when rendering.
     *
     * @var string
     */
    protected $template = 'radio';

    /**
     * Default sanitization method, used when the sanitize option is not set.
     *
     * @param mixed $value
     *
     * @return string|null
     */
    protected function sanitizeDefault($value)
    {
        $choices = $this->options('choices', []);

        return array_key_exists($value, $choices) ? sanitize_text_field($value) : null;
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
        return [
            'choices' => $this->options('choices', [])
        ];
    }
}
