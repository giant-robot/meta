<?php
use GiantRobot\Meta\UserFields;

$title = 'Example';
$locations = array('administrator');
$fields = include "all_fields.php";
$options = array();

$userFields = new UserFields($title, $locations, $fields, $options);
$userFields->register();
