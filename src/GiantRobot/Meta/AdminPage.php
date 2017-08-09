<?php
namespace GiantRobot\Meta;

/**
 * Administration Page
 *
 * @property-read string $id;
 * @property-read string $title;
 * @property-read string $description;
 * @property-read string $menuTitle;
 * @property-read string $slug;
 * @property-read string $capability;
 * @property-read string $icon;
 * @property-read string $screenId;
 * @property-read array $options;
 * @property-read bool $isRegistered;
 */
class AdminPage
{
    protected $id;
    protected $title;
    protected $description;
    protected $menuTitle;
    protected $slug;
    protected $capability;
    protected $icon;
    protected $screenId;
    protected $options;
    protected $isRegistered;

    /**
     * AdminPage constructor.
     *
     * @param string $title
     * @param array  $options
     */
    public function __construct($title, array $options = [])
    {
        $this->title = $title;
        $this->options = $options;

        $this->id = spl_object_hash($this);
        $this->description = $this->options('description', '');
        $this->menuTitle = $this->options('menu_title', $this->title);
        $this->slug = $this->options('menu_slug', sanitize_title_with_dashes($this->title));
        $this->capability = $this->options('capability', 'manage_options');
        $this->icon = $this->options('icon', 'dashicons-admin-generic');
        $this->isRegistered = false;
    }

    /**
     * Provide read access to object properties.
     *
     * @param string $name
     *
     * @return mixed
     */
    public function __get($name)
    {
        return $this->$name;
    }

    /**
     * Add the page to WP Admin.
     */
    public function register()
    {
        if ($this->isRegistered)
        {
            return;
        }

        add_action('admin_menu', function () {
            $this->screenId = add_menu_page(
                $this->title,
                $this->menuTitle,
                $this->capability,
                $this->slug,
                array($this, 'render'),
                $this->icon
            );
        });

        $this->isRegistered = true;
    }

    /**
     * Render that page's contents.
     */
    public function render()
    {
        $root = dirname(dirname(dirname(dirname(__FILE__))));
        $template = $this->options('template', "$root/resources/views/page.php");

        $data = [
            'id' => $this->id,
            'token' => wp_create_nonce("save_giant_meta_$this->id"),
            'title' => $this->title,
            'description' => $this->description
        ];

        extract($data);

        ob_start();
        include $template;
        echo ltrim(ob_get_clean());
    }

    /**
     * Page options, optionally filtered by a given key.
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
