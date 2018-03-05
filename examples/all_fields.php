<?php
/**
 * All available fields with all of their options set.
 *
 * This list is meant to be exhaustive.
 */
use GiantRobot\Meta\Fields\Attachment;
use GiantRobot\Meta\Fields\Checkbox;
use GiantRobot\Meta\Fields\DateTime;
use GiantRobot\Meta\Fields\Gallery;
use GiantRobot\Meta\Fields\Location;
use GiantRobot\Meta\Fields\Message;
use GiantRobot\Meta\Fields\Radio;
use GiantRobot\Meta\Fields\Relation;
use GiantRobot\Meta\Fields\Repeater;
use GiantRobot\Meta\Fields\Select;
use GiantRobot\Meta\Fields\Text;
use GiantRobot\Meta\Fields\Textarea;

return array(
    new Attachment('file_meta', [
        'label' => 'Attachment',
        'description' => 'Will launch a media gallery modal.',
    ]),
    new Checkbox('single_checkbox_meta', [
        'label' => 'Solo Checkbox',
        'choices' => [
            'one' => 'One',
        ],
    ]),
    new Checkbox('multi_checkbox_meta', [
        'label' => 'Checkbox Farm',
        'choices' => [
            'one' => 'One',
            'two' => 'Two',
        ],
    ]),
    new DateTime('datetime_meta', [
        'label' => 'Date & Time',
        'description' => 'This is when it\'s on',
        'save_format' => 'Y-m-d 00:00:00',
        'display_format' => 'F Y',
        'show_datepicker' => true,
        'show_timepicker' => false,
    ]),
    new Gallery('gallery_meta', [
        'label' => 'Media Gallery',
        'description' => 'Drag to re-order.',
    ]),
    new Location('location_meta', [
        'label' => 'Location',
        'description' => 'This is where it\'s at',
        'defaults' => array(
            'lat' => 51.178878,
            'lng' => -1.826211,
            'zoom' => 15,
        ),
        'zoom' => 12,
        'key' => 'AIzaSyAloFllqeQEDtJXVvXh6KGlv9zvR0vq35c',
    ]),
    new Message('message', [
        'label' => 'This is a message',
        'content' => 'Hello fellow forest friends!',
        'escape' => true,
    ]),
    new Radio('radio_meta', [
        'label' => 'El Radio',
        'choices' => [
            'one' => 'One',
            'two' => 'Two',
        ],
    ]),
    new Relation('relation_to_post_meta', [
        'label' => 'Relation',
        'description' => 'Let\'s relate to posts.',
        'post_type' => ['post', 'page'],
        'multi' => true,
    ]),
    new Relation('relation_to_user_meta', [
        'label' => 'User Relation',
        'description' => 'Let\'s relate to users.',
        'user_role' => ['author'],
    ]),
    new Relation('relation_to_term_meta', [
        'label' => 'Term Relation',
        'description' => 'Let\'s relate to terms.',
        'taxonomy' => 'any',
    ]),
    new Repeater('repeater_meta', [
        'label' => 'Repeater',
        'description' => 'Will repeat stuff.',
        'layout' => 'lines',
        'fields' => [
            new Textarea('text', [
                'label' => 'Plain old text field',
                'description' => 'For those who enjoy typing.',
            ]),
            new Checkbox('single_checkbox_meta', [
                'label' => 'Solo Checkbox',
                'choices' => [
                    'one' => 'One',
                ],
            ]),
            new Checkbox('multi_checkbox_meta', [
                'label' => 'Checkbox Farm',
                'choices' => [
                    'one' => 'One',
                    'two' => 'Two',
                ],
            ]),
            new Radio('radio', [
                'label' => 'El Radio',
                'choices' => [
                    'one' => 'One',
                    'two' => 'Two',
                ]
            ]),
            new Attachment('file', [
                'label' => 'Attachment',
                'description' => 'Will launch a media gallery modal.',
            ]),
            new Relation('relation', [
                'label' => 'Relation',
                'description' => 'Better together',
                'multi' => true,
            ]),
        ],
    ]),
    new Select('single_select_meta', [
        'label' => 'Single Select',
        'description' => 'Pick a card.',
        'choices' => [
            'jack' => 'Jack',
            'ace' => 'Ace',
        ],
    ]),
    new Select('multi_select_meta', [
        'label' => 'Multi Select',
        'description' => 'Make a band.',
        'choices' => [
            'page' => 'Page',
            'plant' => 'Plant',
            'jones' => 'Jones',
            'bonham' => 'Bonham',
        ],
        'multi' => true,
    ]),
    new Text('text_meta', [
        'label' => 'Plain old text field',
        'description' => 'For those who enjoy typing.',
    ]),
    new Textarea('text', [
        'label' => 'Plain old text field',
        'description' => 'For those who enjoy typing.',
    ]),
);
