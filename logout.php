<?php
require_once __DIR__ . '/user_manager.php';

logoutUser();
header('Location: index.php');
exit;