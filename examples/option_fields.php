<?php
use GiantRobot\Meta\AdminPage;
use GiantRobot\Meta\AdminSubpage;
use GiantRobot\Meta\OptionFields;

$page = new AdminPage('Site Options');
$subPage = new AdminSubpage($page, 'Site Options Subset');

$title = 'Example';
$locations = array($page, $subPage);
$fields = include "all_fields.php";
$options = array();

$postFields = new OptionFields($title, $locations, $fields, $options);
$postFields->register();
