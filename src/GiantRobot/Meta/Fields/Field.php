<?php
namespace GiantRobot\Meta\Fields;

use InvalidArgumentException;

/**
 * Field
 *
 * @property-read string $id
 * @property-read string $template
 * @property-read array  $options
 */
abstract class Field
{
    /**
     * Field Id.
     *
     * @var string
     */
    protected $id;

    /**
     * Field template to use when rendering (name or full path).
     *
     * @var string
     */
    protected $template;

    /**
     * An associative array of options.
     *
     * @var array
     */
    protected $options;

    /**
     * Field constructor.
     *
     * @param string $id
     * @param array  $options
     */
    public function __construct($id, array $options = [])
    {
        $this->id = $id;
        $this->options = $options;

        // Check for a valid sanitization option.
        $sanitize = $this->options('sanitize');

        if (isset($sanitize) && ! is_callable($sanitize))
        {
            $type = is_object($sanitize) ? get_class($sanitize) : gettype($sanitize);

            throw new InvalidArgumentException(
                sprintf(
                    'Option [sanitize] is expected to be callable, %s given.',
                    $type
                )
            );
        }
    }

    /**
     * Make properties readable.
     *
     * @param $name
     *
     * @return mixed
     */
    public function __get($name)
    {
        return $this->$name;
    }

    /**
     * Render the Field's markup.
     *
     * @param mixed $value
     * @param array $options
     *
     * @return string
     */
    public function render($value, array $options = array())
    {
        $root = dirname(dirname(dirname(dirname(dirname(__FILE__)))));
        $template = $this->options('template', "$root/resources/views/fields/field.php");

        $default = [
            'id'   => $this->id,
            'name' => "giant_meta[$this->id]",
            'type' => $this->template,
            'value' => $value,
            'label' => $this->options('label'),
            'description' => $this->options('description'),
        ];

        $data = array_merge($default, $options, $this->viewData($value));

        extract($data);

        ob_start();
        include $template;
        return ltrim(ob_get_clean());
    }

    /**
     * Sanitize a value.
     *
     * @param mixed $value
     *
     * @return mixed
     */
    public function sanitize($value)
    {
        $callback = $this->options('sanitize');

        if (! $callback)
        {
            return $this->sanitizeDefault($value);
        }

        return call_user_func($callback, $value);
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
        return array();
    }

    /**
     * Get Field options, optionally filtered by a given key.
     *
     * @param string $key
     * @param mixed  $default
     *
     * @return mixed
     */
    public function options($key = null, $default = null)
    {
        if (isset($key))
        {
            return isset($this->options[$key]) ? $this->options[$key] : $default;
        }

        return $this->options;
    }

    /**
     * Default sanitization method, used when the sanitize option is not set.
     *
     * @param mixed $value
     *
     * @return mixed
     */
    abstract protected function sanitizeDefault($value);
}
