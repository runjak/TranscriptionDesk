<?php
require_once '../config.php';
Config::getUserManager()->logout();
header('LOCATION: ..');
