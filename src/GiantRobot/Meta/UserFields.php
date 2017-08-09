<?php
namespace GiantRobot\Meta;

use GiantRobot\Meta\Fields\Field;
use WP_User;

/**
 * UserFields
 */
class UserFields extends FieldGroup
{
    /**
     * The capability required in order to save the metadata.
     *
     * @var string
     */
    const SAVE_CAPABILITY = 'edit_user';

    /**
     * The template to use when rendering.
     *
     * @var string
     */
    protected $template = 'user';

    /**
     * Attach the Group to the required WordPress hooks.
     */
    public function register()
    {
        $addMetaBoxCallback = function ($user) {
            $this->addMetaBox($user);
        };

        add_action('show_user_profile', $addMetaBoxCallback);
        add_action('edit_user_profile', $addMetaBoxCallback);

        add_action('personal_options_update', [$this, 'save']);
        add_action('edit_user_profile_update', [$this, 'save']);
    }

    /**
     * Add the meta box to a specified role.
     *
     * @param \WP_User $user
     */
    public function addMetaBox(WP_User $user)
    {
        if (! $this->isVisible())
        {
            return;
        }

        if ($this->locations && ! array_intersect($user->roles, $this->locations))
        {
            return;
        }

        add_filter('giant_meta_group_title', function () {
            return $this->title;
        });

        $this->render($user);
    }

    /**
     * Get a field's value.
     *
     * Each group type will have to use its own set of WP functions, so the
     * implementation of this method is left to the concrete classes.
     *
     * @param \GiantRobot\Meta\Fields\Field $field
     * @param int                           $userId
     *
     * @return mixed Returns an array of values if field is set as queryable.
     */
    protected function getFieldValue(Field $field, $userId)
    {
        return get_user_meta($userId, $field->id, true);
    }

    /**
     * Save a field's value.
     *
     * Each group type will have to use its own set of WP functions, so the
     * implementation of this method is left to the concrete classes.
     *
     * @param \GiantRobot\Meta\Fields\Field $field
     * @param mixed                         $value
     * @param int                           $userId
     */
    protected function saveFieldValue(Field $field, $value, $userId)
    {
        update_user_meta($userId, $field->id, $value);
    }
}
