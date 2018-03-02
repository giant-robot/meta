<?php
namespace GiantRobot\Meta\Fields;

/**
 * Attachment Field
 */
class Attachment extends Field
{
    /**
     * The template to use when rendering.
     *
     * @var string
     */
    protected $template = 'attachment';

    /**
     * Default sanitization method, used when the sanitize option is not set.
     *
     * @param mixed $value
     *
     * @return string
     */
    protected function sanitizeDefault($value)
    {
        if (is_numeric($value) && $value > 0 && intval($value) == $value)
        {
            return intval($value);
        }

        return null;
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
        $defaults = array(
            'kind'      => '',
            'thumbnail' => '',
            'title'     => '',
            'filename'  => '',
            'filesize'  => ''
        );

        $attachment = get_post($value);

        if (! $attachment)
        {
            return $defaults;
        }

        $file = get_attached_file($value);
        $bytes = filesize($file);
        $size = $bytes > 1048576 ? round($bytes/1048576, 1) . " MB" : round($bytes/1024) . " kB";
        $thumb = wp_get_attachment_image_src($value, 'thumbnail', true);

        return [
            'kind'      => wp_attachment_is_image($value) ? 'image' : 'application',
            'thumbnail' => $thumb[0],
            'title'     => $attachment->post_title,
            'filename'  => basename($file),
            'filesize'  => $size
        ];
    }
}
