<?php
/**
 * All available fields with all of their options set.
 *
 * This list is meant to be exhaustive.
 */
use GiantRobot\Meta\Fields\Attachment;
use GiantRobot\Meta\Fields\DateTime;
use GiantRobot\Meta\Fields\Gallery;
use GiantRobot\Meta\Fields\Location;
use GiantRobot\Meta\Fields\Message;
use GiantRobot\Meta\Fields\Radio;
use GiantRobot\Meta\Fields\Relation;
use GiantRobot\Meta\Fields\Repeater;
use GiantRobot\Meta\Fields\Text;
use GiantRobot\Meta\Fields\Textarea;

return array(
    new Attachment('file_meta', [
        'label' => 'Attachment',
        'description' => 'Will launch a media gallery modal.',
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
    new Relation('relation_meta', [
        'label' => 'Relation',
        'description' => 'It\'s better together.',
        'post_type' => 'post',
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
            ]),
        ],
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
