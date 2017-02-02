<?php
namespace GiantRobot\Meta\Fields;

/**
 * Message Field
 */
class Message extends Field
{
    /**
     * The template to use when rendering.
     *
     * @var string
     */
    protected $template = 'message';

    /**
     * Gather field-specific view data.
     *
     * @param mixed $value
     *
     * @return array
     */
    protected function viewData($value = null)
    {
        $content = $this->options('content', '');

        return [
            'content' => $this->options('escape', true) ? esc_html($content) : $content,
        ];
    }

    /**
     * Default sanitization method.
     *
     * @param mixed $value
     *
     * @return mixed
     */
    protected function sanitizeDefault($value)
    {
        return null;
    }
}
