<?php

require_once 'Bootstrap.php';

Bootstrap::init();

$app = \Zend\Mvc\Application::init(include 'config/application.config.php');
$app->run();
