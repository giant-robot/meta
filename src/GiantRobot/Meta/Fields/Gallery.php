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
     * @param array $values
     *
     * @return int[]|null
     */
    protected function sanitizeDefault($values)
    {
        // Input should always be an array.
        // If not, somebody's been monkeying around with it.
        if (! is_array($values))
        {
            return null;
        }

        $sanitizeOne = function ($value) {

            if (is_numeric($value) && $value > 0 && intval($value) == $value)
            {
                return intval($value);
            }

            return null;
        };

        return array_filter(array_map($sanitizeOne, $values));
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
