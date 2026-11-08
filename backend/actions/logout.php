<?php
require_once dirname(__DIR__) . '/bootstrap.php';
Auth::logout();
redirect('/gruz/frontend/login.php');