<?php
use GiantRobot\Meta\PostFields;

$title = 'Example';
$locations = array('post');
$fields = include "all_fields.php";
$options = array();

$postFields = new PostFields($title, $locations, $fields, $options);
$postFields->register();
