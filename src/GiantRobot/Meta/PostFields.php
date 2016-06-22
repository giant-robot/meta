<?php
namespace GiantRobot\Meta;

use GiantRobot\Meta\Fields\Field;

/**
 * PostFields
 */
class PostFields extends FieldGroup
{
    /**
     * The capability required in order to save the metadata.
     *
     * @var string
     */
    const SAVE_CAPABILITY = 'edit_page';

    /**
     * The template to use when rendering.
     *
     * @var string
     */
    protected $template = 'post';

    /**
     * Register the Group at the required WordPress hooks.
     */
    public function register()
    {
        foreach ($this->locations as $posttype)
        {
            add_action(
                "add_meta_boxes_$posttype",
                function () use ($posttype) {
                    $this->addMetaBox($posttype);
                }
            );
        }

        add_action('save_post', [$this, 'save']);
    }

    /**
     * Add the meta box to a specified post type.
     *
     * @param string $posttype
     */
    public function addMetaBox($posttype)
    {
        if (! $this->isVisible())
        {
            return;
        }

        add_meta_box(
            $this->id,
            $this->title,
            [$this, 'render'],
            $posttype,
            $this->options('context', 'advanced'),
            $this->options('priority', 'default')
        );
    }

    /**
     * Get a field's value.
     *
     * @param \GiantRobot\Meta\Fields\Field $field
     * @param int                           $postId
     *
     * @return mixed Returns an array of values if field is set as queryable.
     */
    protected function getFieldValue(Field $field, $postId)
    {
        return $value = get_post_meta($postId, $field->id, true);
    }

    /**
     * Save a field's value.
     *
     * @param \GiantRobot\Meta\Fields\Field $field
     * @param mixed                         $value
     * @param int                           $postId
     */
    protected function saveFieldValue(Field $field, $value, $postId)
    {
        update_post_meta($postId, $field->id, $value);
    }
}
