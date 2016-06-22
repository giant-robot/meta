<?php
namespace GiantRobot\Meta;

use GiantRobot\Meta\Fields\Field;

/**
 * Fields Group
 */
abstract class FieldGroup
{
    /**
     * The capability required in order to save the metadata.
     *
     * @var string
     */
    const SAVE_CAPABILITY = 'manage_options';

    /**
     * The Group Id.
     *
     * @var string
     */
    protected $id;

    /**
     * A title.
     *
     * @var string
     */
    protected $title;

    /**
     * A tuple array of locations.
     *
     * @var array
     */
    protected $locations;

    /**
     * A tuple array of Field implementations.
     *
     * @var array
     */
    protected $fields;

    /**
     * An associative array of options.
     *
     * @var array
     */
    protected $options;

    /**
     * The template to use when rendering.
     *
     * @var string
     */
    protected $template;

    /**
     * FieldGroup constructor.
     *
     * @param string $id
     * @param string $title
     * @param array  $locations
     * @param array  $fields
     * @param array  $options
     */
    public function __construct($id, $title, array $locations, array $fields, array $options = [])
    {
        $this->id = $id;
        $this->title = $title;
        $this->locations = $locations;
        $this->options = $options;

        // Add fields to the Group.
        foreach ($fields as $field)
        {
            $this->addField($field);
        }

        // Check for valid visibility constraint.
        $showWhen = $this->options('show_when');

        if (isset($showWhen) && ! is_callable($showWhen))
        {
            $type = is_object($showWhen) ? get_class($showWhen) : gettype($showWhen);

            throw new \InvalidArgumentException(
                sprintf(
                    'Option [show_when] is expected to be callable, %s given.',
                    $type
                )
            );
        }

        // Enqueue scripts and styles.
        add_action('admin_enqueue_scripts', function () {

            if (wp_script_is('giant-fields', 'enqueued'))
            {
                return;
            }

            $dir = dirname(dirname(dirname(__FILE__)));

            // Enqueue CSS.
            wp_register_style(
                'giant-fields',
                plugin_dir_url($dir) . 'public/css/fields.css',
                array(),
                null
            );
            wp_enqueue_style('giant-fields');

            // Enqueue JS.
            wp_register_script(
                'giant-fields',
                plugin_dir_url($dir) . 'public/js/fields.js',
                array('jquery'),
                null,
                null
            );
            wp_enqueue_script('giant-fields');

            // Enqueue all scripts, styles, settings, and templates necessary to use
            // WP's media JavaScript APIs.
            wp_enqueue_media();
        });
    }

    /**
     * Attach the Group to the required WordPress hooks.
     *
     * Each group type will have to use its own set of hooks, so the
     * implementation of this method is left to the concrete classes.
     */
    abstract public function register();

    /**
     * Render group fields.
     *
     * @param object $object The object that the metadata relate to.
     *
     * @return string
     */
    public function render($object = null)
    {
        // Guess object id.
        if (isset($object->ID))
        {
            // Posts and Users.
            $objectId = $object->ID;
        }
        elseif (isset($object->term_id))
        {
            // Terms.
            $objectId = $object->term_id;
        }
        else
        {
            // Options Groups save their meta in the options table using the
            // group's id as key.
            $objectId = $this->id;
        }

        // Get rendered fields markup.
        $fieldsHtml = "";

        foreach ($this->fields as $field)
        {
            /* @var $field \GiantRobot\Meta\Fields\Field */
            $value = $this->getFieldValue($field, $objectId);
            $fieldsHtml .= $field->render($value);
        }

        $root = dirname(dirname(dirname(dirname(__FILE__))));
        $template = $this->options('template', "$root/resources/views/groups/$this->template.php");

        $data = [
            'id'          => $this->id,
            'token'       => wp_create_nonce("save_giant_meta_$this->id"),
            'label'       => $this->options('label'),
            'description' => $this->options('description'),
            'fields'      => $fieldsHtml,
        ];

        extract($data);

        ob_start();
        include $template;
        echo ltrim(ob_get_clean());
    }

    /**
     * Save group fields.
     *
     * @param int $objectId The object Id that the metadata relate to.
     *
     * @throws \LogicException
     */
    public function save($objectId)
    {
        if (! isset($_REQUEST['_giant_meta_tokens']))
        {
            return;
        }

        $tokens = $_REQUEST['_giant_meta_tokens'];

        // Look for a valid token and a user-initiated save action.
        if (! isset($tokens[$this->id])
            || ! wp_verify_nonce($tokens[$this->id], "save_giant_meta_$this->id")
            || defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)
        {
            return;
        }

        // Get required capability and check user permissions.
        $capability = $this->getRequiredCap();

        if (! current_user_can($capability, $objectId))
        {
            return;
        }

        // Save.
        $input = isset($_REQUEST['giant_meta']) ? $_REQUEST['giant_meta'] : array();

        foreach ($this->fields as $field)
        {
            /* @var $field \GiantRobot\Meta\Fields\Field */
            if (! isset($input[$field->id]))
            {
                continue;
            }

            $clean = $field->sanitize($input[$field->id]);

            /**
             * Save under different meta keys when explicitly instructed to.
             */
            $this->saveFieldValue($field, $clean, $objectId);
        }
    }

    /**
     * Add a field to the Group.
     *
     * @param \GiantRobot\Meta\Fields\Field $field
     */
    protected function addField(Field $field)
    {
        $this->fields[] = $field;
    }

    /**
     * Get the capability required to save the group's fields.
     * Capability is looked up in the following order:
     *   1. Option set during instantiation
     *   2. Group class $saveCapability property
     *   3. Admin default.
     *
     * @return string
     */
    protected function getRequiredCap()
    {
        return $this->options('save_cap', static::SAVE_CAPABILITY);
    }

    /**
     * Check if the group should be displayed.
     *
     * @return bool
     */
    protected function isVisible()
    {
        if (! isset($this->options['show_when']))
        {
            return true;
        }

        return false !== call_user_func($this->options['show_when']);
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
    abstract protected function getFieldValue(Field $field, $objectId);

    /**
     * Save a field's value.
     *
     * Each group type will have to use its own set of WP functions, so the
     * implementation of this method is left to the concrete classes.
     *
     * @param \GiantRobot\Meta\Fields\Field $field
     * @param mixed                         $value
     * @param int                           $objectId
     */
    abstract protected function saveFieldValue(Field $field, $value, $objectId);

    /**
     * Group options, optionally filtered by a given key.
     *
     * @param string $key
     * @param mixed  $default
     *
     * @return mixed
     */
    protected function options($key = null, $default = null)
    {
        if (isset($key))
        {
            return isset($this->options[$key]) ? $this->options[$key] : $default;
        }

        return $this->options;
    }
}
