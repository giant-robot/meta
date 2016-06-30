<?php
namespace GiantRobot\Meta\Fields;

/**
 * Map
 */
class Location extends Field
{
    /**
     * The template to use when rendering.
     *
     * @var string
     */
    protected $template = 'location';

    /**
     * Gather field-specific view data.
     *
     * @param mixed $value
     *
     * @return array
     */
    protected function viewData($value = null)
    {
        $defaults = array_merge(
            array('lat' => '', 'lng'  => '', 'zoom' => ''),
            $this->options('defaults', [])
        );

        $center = array(
            'lat'  => isset($value['lat']) ? $value['lat'] : $defaults['lat'],
            'lng'  => isset($value['lng']) ? $value['lng'] : $defaults['lng'],
        );

        $zoom = (! $center['lat'] && ! $center['lng']) ? $defaults['zoom'] : $this->options('zoom');

        return [
            'center' => $center,
            'zoom'   => $zoom,
            'key'    => $this->options('key'),
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
        if (! $value)
        {
            return null;
        }

        $sanitized = array_map(function ($component) {
            return sanitize_text_field($component);
        }, $value);

        return $sanitized;
    }
}
