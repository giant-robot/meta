<?php
namespace GiantRobot\Meta;

/**
 * Administration Sub-Page
 *
 * @property-read \GiantRobot\Meta\AdminPage $parent
 */
class AdminSubpage extends AdminPage
{
    protected $parent;

    /**
     * AdminSubpage constructor.
     *
     * @param \GiantRobot\Meta\AdminPage $parent
     * @param string                     $title
     * @param array                      $options
     */
    public function __construct(AdminPage $parent, $title, array $options = [])
    {
        $this->parent = $parent;

        parent::__construct($title, $options);
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

        $this->parent->register();

        add_action('admin_menu', function () {
            $this->screenId = add_submenu_page(
                $this->parent->slug,
                $this->title,
                $this->menuTitle,
                $this->capability,
                $this->slug,
                array($this, 'render')
            );
        });

        $this->isRegistered = true;
    }
}
