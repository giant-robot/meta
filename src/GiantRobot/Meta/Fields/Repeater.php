<?php
namespace GiantRobot\Meta\Fields;

/**
 * Repeater Field
 */
class Repeater extends Field
{
    /**
     * The template to use when rendering.
     *
     * @var string
     */
    protected $template = 'repeater';

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

        /* @var Field[] $fields */
        $fields = $this->options('fields', array());
        $sanitized = array();
        $rowIndex = 0;

        foreach ($values as $row)
        {
            foreach ($fields as $field)
            {
                $sanitized[$rowIndex][$field->id] = $field->sanitize($row[$field->id]);
            }

            $rowIndex++;
        }

        return $sanitized;
    }

    /**
     * Gather field-specific view data.
     *
     * @param mixed $values
     *
     * @return array
     */
    protected function viewData($values = null)
    {
        return [
            'layout' => $this->options('layout', 'table'),
            'fields' => $this->options('fields', []),
            'values' => $values ?: array()
        ];
    }
}
