<?php
namespace GiantRobot\Meta;

use GiantRobot\Meta\Fields\Field;

/**
 * OptionFields
 */
class OptionFields extends FieldGroup
{
    /**
     * A tuple array of AdminPage instances.
     *
     * @var \GiantRobot\Meta\AdminPage[]
     */
    protected $locations;

    /**
     * The template to use when rendering.
     *
     * @var string
     */
    protected $template = 'option';

    /**
     * Attach the Group to the required WordPress hooks.
     */
    public function register()
    {
        foreach ($this->locations as $page)
        {
            $page->register();

            // Add a meta box to each of the specified locations.
            //
            // The add_meta_boxes hook is only fired on post edit pages, so we
            // hook up to current_screen (which is late enough to register our
            // meta boxes, but not too late).
            add_action('current_screen', function () use ($page) {
                $this->addMetaBox($page);
            });

            // Register the saving procedure.
            add_action('current_screen', function ($screen) use ($page) {

                if ($screen->id === $page->screenId)
                {
                    $this->save($this->id);
                }
            });
        }
    }

    /**
     * Add a meta box to a specified options page.
     *
     * @param \GiantRobot\Meta\AdminPage $page
     */
    protected function addMetaBox(AdminPage $page)
    {
        if (! $this->isVisible())
        {
            return;
        }

        add_meta_box(
            $this->id,
            $this->title,
            [$this, 'render'],
            $page->id,
            'normal',
            $this->options('priority', 'default')
        );
    }

    /**
     * Get a field's value.
     *
     * @param \GiantRobot\Meta\Fields\Field $field
     * @param string                        $groupId
     *
     * @return mixed
     */
    protected function getFieldValue(Field $field, $groupId)
    {
        if ($optionName = $this->options('group_as', false))
        {
            $groupValue = get_option($optionName);
            return isset($groupValue[$field->id]) ? $groupValue[$field->id] : '';
        }

        return get_option($field->id);
    }

    /**
     * Save a field's value.
     *
     * @param \GiantRobot\Meta\Fields\Field $field
     * @param mixed                         $value
     * @param string                        $groupId
     */
    protected function saveFieldValue(Field $field, $value, $groupId)
    {
        if ($optionName = $this->options('group_as', false))
        {
            $groupValue = get_option($optionName);
            $groupValue[$field->id] = $value;
            $optionValue = $groupValue;
        }
        else
        {
            $optionName = $field->id;
            $optionValue = $value;
        }

        update_option($optionName, $optionValue);
    }
}
