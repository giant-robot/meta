<?php
namespace GiantRobot\Meta\Fields;

/**
 * Relation
 */
class Relation extends Field
{
    /**
     * The template to use when rendering.
     *
     * @var string
     */
    protected $template = 'relation';

    /**
     * Default sanitization method, used when the sanitize option is not set.
     *
     * @param mixed $value
     *
     * @return mixed
     */
    protected function sanitizeDefault($value)
    {
        return $value;
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

        $filter = $this->options('post_type', 'any');

        return [
            'values' => $values,
            'filter' => is_array($filter) ? implode(',', $filter) : $filter,
            'token' => wp_create_nonce('giant|find|posts')
        ];
    }
}
