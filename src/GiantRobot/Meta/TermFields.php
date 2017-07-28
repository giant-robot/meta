<?php
namespace GiantRobot\Meta;

use GiantRobot\Meta\Fields\Field;

/**
 * TermFields
 */
class TermFields extends FieldGroup
{
    /**
     * The capability required in order to save the metadata.
     *
     * @var string
     */
    const SAVE_CAPABILITY = 'manage_categories';

    /**
     * The template to use when rendering.
     *
     * @var string
     */
    protected $template = 'term';

    /**
     * Attach the Group to the required WordPress hooks.
     */
    public function register()
    {
        foreach ($this->locations as $taxonomy)
        {
            add_action(
                "{$taxonomy}_edit_form_fields",
                function ($term) {
                    $this->addFieldsRow($term);
                }
            );

            add_action("edit_{$taxonomy}", [$this, 'save']);
        }
    }

    /**
     * Add a row of fields to the edit term page.
     *
     * @param \WP_Term $term
     */
    protected function addFieldsRow(\WP_Term $term)
    {
        if (! $this->isVisible())
        {
            return;
        }

        add_filter('giant_meta_group_title', function () {
            return $this->title;
        });

        $this->render($term);
    }

    /**
     * Get a field's value.
     *
     * Each group type will have to use its own set of WP functions, so the
     * implementation of this method is left to the concrete classes.
     *
     * @param \GiantRobot\Meta\Fields\Field $field
     * @param int                           $objectId
     *
     * @return mixed Returns an array of values if field is set as queryable.
     */
    protected function getFieldValue(Field $field, $objectId)
    {
        return $value = get_term_meta($objectId, $field->id, true);
    }

    /**
     * Save a field's value.
     *
     * @param \GiantRobot\Meta\Fields\Field $field
     * @param mixed                         $value
     * @param int                           $objectId
     */
    protected function saveFieldValue(Field $field, $value, $objectId)
    {
        update_term_meta($objectId, $field->id, $value);
    }
}
