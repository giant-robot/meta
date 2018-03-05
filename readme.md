# Hello Meta

Meta is a library that creates WordPress custom fields.

It is straight forward, flexible, VCS friendly. Hell, it's kosher for code.

Meta **loves** your database. It will not hog on queries if you don't want it to.

# Installation

Install using Composer:

`composer require giant-meta`

or manually grab the [latest release](https://github.com/giant-robot/meta/releases/latest).  
In this case, include `autoload.php` in your script: it will register the library's autoloader.


Now you're ready to define WP custom fields *in your code* - where you should be f***ing doing it in the first place.


# The Complete Example

The following piece of code will add a Text field to WordPress pages, categories, authors and a new admin page.

```php
use GiantRobot\Meta\AdminPage;
use GiantRobot\Meta\AdminSubpage;
use GiantRobot\Meta\OptionFields;
use GiantRobot\Meta\PostFields;
use GiantRobot\Meta\TermFields;
use GiantRobot\Meta\UserFields;
use GiantRobot\Meta\Fields\Text;

// Define an array of fields.
$fields = array(
    new Text('text_meta', [
        'label' => 'Plain old text field',
        'description' => 'For those who enjoy typing.'
    ]),
);

// Add the fields to all posts with the post type "page".
$postFields = new PostFields('Example', ['page'], $fields);
$postFields->register();

// Add the fields to all terms of the taxonomy "category".
$termFields = new TermFields('Example', ['category'], $fields);
$termFields->register();

// Add the fields to all users with the role "author".
$userFields = new UserFields('Example', ['author'], $fields);
$userFields->register();

// Add the fields to a page called "Text Options",
// which is a sub-page of another admin page called "Example Options".
$adminPage = new AdminPage('Example Options');
$adminSubpage = new AdminSubpage($adminPage, 'Text Options');

$optionFields = new OptionFields('Example', [$adminSubpage], $fields);
$optionFields->register();

```

That wasn't too bad, was it?


# Index

* [Groups](#groups)
	* [PostFields](#post-group)
	* [UserFields](#user-group)
	* [TermFields](#term-group)
	* [OptionFields](#options-group)
* [Admin Pages](#admin-pages)
	* [AdminPage](#admin-page)
	* [AdminSubpage](#admin-subpage)
* [Fields](#fields)
	* [Attachment](#attachment-field)
	* [Checkbox](#checkbox-field)
	* [DateTime](#datetime-field)
	* [Gallery](#gallery-field)
	* [Location](#location-field)
	* [Message](#message-field)
	* [Radio](#radio-field)
	* [Relation](#relation-field)
	* [Repeater](#repeater-field)
	* [Select](#select-field)
	* [Text](#text-field)
	* [Textarea](#textarea-field)


<a name="groups"></a>
# Field Groups

In order to add some custom fields to your WordPress administration pages, you need to create a FieldGroup object and
register it with WordPress.

That's actually easier done than said:

```php
$group = new \GiantRobot\Meta\PostFields(string $title, array $locations, array $fields[, array $options = array()]);
$group->register();
```

### Parameters

`$title`  
The group title, as shown to the user when they are viewing an admin page.

`$locations`  
An array of locations in which the fields should be visible. The locations
depend on the type of group you are creating. For example, when building a PostFields group, $locations would be a list of post types.

`$fields`  
An array of [Fields](#fields).

<a name="common-group-options"></a>
`$options`  
An associative array of group options. The options below work for all groups:

**template** *(string)*  
Full path to a template file that will be used to render the group. Use this to override a group's default template, but tread carefully.

**save_cap** *(string)* `Default: 'manage_options'`  
The capability required to save the group's fields.

**show_when** *(callable)* `Default: null`  
A callaback that defines whether the group is visible or not.  
Must return a boolean result - `true` if the group is to be displayed or `false` otherwise. If this is not set (and by default), the group is displayed to everyone.


<a name="post-group"></a>
## PostFields Group

```php
$group = new \GiantRobot\Meta\PostFields(string $title, array $locations, array $fields[, array $options = array()]);
```

In the PostFields context, the `locations` parameter is expected to be an array of post types.

In addition to the [common options](#common-group-options) that are available to all groups,
you can also define a the following options to affect how the PostFields group behaves.

**context** *(string)*  
The part of the page where the generated meta box should be shown.

**priority** *(string)*  
The priority within the context where the generated meta boxes should be shown.

Both options are passed directly to WP's `add_meta_box()`. Consult the [Codex](http://codex.wordpress.org/Function_Reference/add_meta_box) for for supported values and defaults.


<a name="user-group"></a>
## UserFields Group

```php
$group = new \GiantRobot\Meta\UserFields(string $title, array $locations, array $fields[, array $options = array()]);
```

In the UserFields context, the `locations` parameter is expected to be an array of user roles. Passing an empty array will display the fields to all users.

The UserFields group does not support any additional options. See [common options](#common-group-options) available to all fields.


<a name="term-group"></a>
## TermFields Group

```php
$group = new \GiantRobot\Meta\TermFields(string $title, array $locations, array $fields[, array $options = array()]);
```

In the TermFields context, the `locations` parameter is expected to be an array of taxonomies.

The TermFields group does not support any additional options. See [common options](#common-group-options) available to all fields.


<a name="options-group"></a>
## OptionFields Group

```php
$group = new \GiantRobot\Meta\OptionFields(string $title, array $locations, array $fields[, array $options = array()]);
```

In the OptionFields context, the `locations` parameter is expected to be an array of [\GiantRobot\Meta\AdminPage](#admin-pages) instances.

In addition to the [common options](#common-group-options) that are available to all groups,
you can also define a the following options to affect how the OptionFields group behaves.

**group_as** *(string)* `Default: false`  
If you want all options in the group to be saved in a single option, you can specify that option's name here. Leave this undefined to have each field saved as a separate option.


<a name="admin-pages"></a>
# Admin Pages

You can define new WP admin pages and subpages to use as `locations` for [OptionFields](#options-group) groups.


<a name="admin-page"></a>
## AdminPage

```php
$page = new \GiantRobot\Meta\AdminPage(string $title[, array $options = array()]);
```

`$title`  
The page title, as shown to the user when they are viewing the admin page.

<a name="admin-page-options"></a>
`$options`  
An associative array of page options. The following options are supported:

**description** *(string)* `Default: ''`  
A description to show below the page title.

**menu_title** *(string)* `Default: $title`  
The text to use for the page's entry in WP menu.

**menu_slug** *(string)*  
The slug name to be used for this page. It should be unique for this menu. By default, it will be the result of the following expression:

```php
sanitize_title_with_dashes($title)
```

**capability** *(string)*  `Default: 'manage_options'`
The capability required for a user to see the page.

**icon** *(string)* `Default: 'dashicons-admin-generic'`  
The class name of the [dashicon](https://developer.wordpress.org/resource/dashicons) to use for the page's entry in the WP menu.


<a name="admin-subpage"></a>
## AdminSubpage

```php
$page = new \GiantRobot\Meta\AdminSubpage(\GiantRobot\Meta\AdminPage $parent, string $title[, array $options = array()])
```

`$parent`  
The parent AdminPage that the sub-page will be added to.

`$title`  
Refer to the documentation for [AdminPage](#admin-page).

`$options`  
Refer to the documentation for [AdminPage](#admin-page).


<a name="fields"></a>
# Fields

You define a field by creating a new instance of its respective class. For example:

```php
$field = new \GiantRobot\Meta\Fields\Text(string $id [, array $options = array()]);
```

### Parameters

`$id`  
A unique identifier for the field. This is going to be the meta key (or option name) under which the field input will be saved.

<a name="common-field-options"></a>
`$options`   
An associative array of field options. The options below work for all fields:

**label** *(string)*  
The field label. No label is displayed by default.

**description** *(string)* `default: ''`  
A description for the field, displayed right under the field's label. No description is displayed by default.

**template** *(string)*  
Full path to a template file that will be used to render the field. Use this to override a field's default template, but tread carefully.

**sanitize** *(callable)*
A callback that will be used to sanitize the field's value before saving it to the database. The callback will receive the unsanitized value and should return it sanitized. 


<a name="attachment-field"></a>
## Attachment Field

```php
$field = new \GiantRobot\Meta\Fields\Attachment(string $id [, array $options = array()]);
```

The Attachment field does not support any additional options. See [common options](#common-field-options) available to all fields.


<a name="checkbox-field"></a>
## Checkbox Field

```php
$field = new \GiantRobot\Meta\Fields\Checkbox(string $id [, array $options = array()]);
```

In addition to the [common options](#common-field-options) that are available to all fields,
you can also define the following options to affect how the Checkbox field behaves.

**choices** *(string)* `Default: []`  
An associative array representing the list of available options. The array's keys are the values that are actually saved.  

**layout** *(string)* `Default: 'vertical'`  
The layout for the list of options. Supported values are `horizontal` and `vertical`.


<a name="datetime-field"></a>
## DateTime Field

```php
$field = new \GiantRobot\Meta\Fields\DateTime(string $id [, array $options = array()]);
```

In addition to the [common options](#common-field-options) that are available to all fields,
you can also define the following options to affect how the DateTime field behaves.

**save_format** *(string)* `Default: 'Y-m-d H:i:s'`  
The format in which the user's input will be saved. All of PHP's [date formats](http://php.net/manual/en/function.date.php) are supported.

**display_format** *(string)* `Default: save_format`  
The format in which the field's value will be displayed at the backend. All of PHP's [date formats](http://php.net/manual/en/function.date.php) are supported.

**show_datepicker** *(bool)* `Default: true`  
Whether to allow the user to select a date.

**show_timepicker** *(bool)* `Default: true`  
Whether to allow the user to select a time.


<a name="gallery-field"></a>
## Gallery Field

```php
$field = new \GiantRobot\Meta\Fields\Gallery(string $id [, array $options = array()]);
```

The Gallery field does not support any additional options. See [common options](#common-field-options) available to all fields.


<a name="location-field"></a>
## Location Field

```php
$field = new \GiantRobot\Meta\Fields\Location(string $id [, array $options = array()]);
```

In addition to the [common options](#common-field-options) that are available to all fields,
you can also define the following options to affect how the Location field behaves.

**key** *(string)* `Default: ''`  
Your Google Maps JavaScript API key. [Click here](https://developers.google.com/maps/documentation/javascript/get-api-key) to find out how to obtain one.

**zoom** *(int)* `Default: null`  
The amount of zoom to apply to the map, when the field has a value set.   
This is included in the options passed to the new map instance when it is constructed. Check out the [Google Maps JavaScript API V3 Reference](https://developers.google.com/maps/documentation/javascript/reference#MapOptions) for additional details.

**defaults** *(array)* `Default: []`  
The latitude, longitude and zoom level to apply to the map when the field does not have a value set.  
For example, here is how you would set the Location field to default to Stonehenge, UK:


<a name="message-field"></a>
## Message Field

```php
$field = new \GiantRobot\Meta\Fields\Message(string $id [, array $options = array()]);
```

In addition to the [common options](#common-field-options) that are available to all fields,
you can also define the following options to affect how the Message field behaves.

**content** *(string)* `Default: ''`  
The field's content. This is displayed below the label and field description and can be anything you like.

**escape** *(bool)* `Default: true`  
Whether to escape the value of `content` when it is rendered on the page. If your content includes any HTML you *want* to be displayed, you should set this to `false`.


<a name="radio-field"></a>
## Radio Field

```php
$field = new \GiantRobot\Meta\Fields\Radio(string $id [, array $options = array()]);
```

In addition to the [common options](#common-field-options) that are available to all fields,
you can also define the following options to affect how the Radio field behaves.

**choices** *(string)* `Default: []`  
An associative array representing the list of available options. The array's keys are the values that are actually saved.  

**layout** *(string)* `Default: 'horizontal'`  
The layout for the list of options. Supported values are `horizontal` and `vertical`.


<a name="relation-field"></a>
## Relation Field

Create a relation to a post, user or term.

```php
$field = new \GiantRobot\Meta\Fields\Relation(string $id [, array $options = array()]);
```

In addition to the [common options](#common-field-options) that are available to all fields,
you can also define the following options to affect how the Relation field behaves.

**post_type** *(string|array)* `Default: 'any'`  
A post type or an array of post types to filter related posts by. This switches the Relation field to *post mode*. The post id will be saved in the database.

**user_role** *(string|array)* `Default: 'any'`  
A user role or an array of roles to filter related users by. This switches the Relation field to *user mode*. The user id will be saved in the database.

**taxonomy** *(string|array)* `Default: 'any'`  
A taxonomy or an array of taxonomies to filter related terms by. This switches the Relation field to *term mode*. The term taxonomy id will be saved in the database.

Note: If none of the above options is set, the field defaults to *post mode*.

**multi** *(bool)* `Default: false`  
Whether to allow the user to make multiple selections.


<a name="repeater-field"></a>
## Repeater Field

```php
$field = new \GiantRobot\Meta\Fields\Repeater(string $id [, array $options = array()]);
```

In addition to the [common options](#common-field-options) that are available to all fields,
you can also define the following options to affect how the Repeater field behaves.

**fields** *(array)* `Default: []`  
An array of Fields to include with each repeated row.

**layout** *(string)* `Default: 'table'`  
The layout of each repeated row. Supported values are `table` and `lines`.


<a name="select-field"></a>
## Select Field

```php
$field = new \GiantRobot\Meta\Fields\Select(string $id [, array $options = array()]);
```

In addition to the [common options](#common-field-options) that are available to all fields,
you can also define the following options to affect how the Select field behaves.

**choices** *(string)* `Default: []`  
An associative array representing the list of available choices. The array's keys are the values that are actually saved.  

**multi** *(bool)* `Default: false`  
Whether the user will be allowed to make more than one choice.


<a name="text-field"></a>
## Text Field

```php
$field = new \GiantRobot\Meta\Fields\Text(string $id [, array $options = array()]);
```

The Text field does not support any additional options. See [common options](#common-field-options) available to all fields.


<a name="textarea-field"></a>
## Textarea Field

```php
$field = new \GiantRobot\Meta\Fields\Textarea(string $id [, array $options = array()]);
```

The Textarea field does not support any additional options. See [common options](#common-field-options) available to all fields.