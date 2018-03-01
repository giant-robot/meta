<?php
use GiantRobot\Meta\TermFields;

$title = 'Example';
$locations = array('category');
$fields = include "all_fields.php";
$options = array();

$termFields = new TermFields($title, $locations, $fields, $options);
$termFields->register();
