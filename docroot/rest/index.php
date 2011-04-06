<?php
define('WSAPI_PROTOCOL', 'REST');
if(isset($_GET['service']))
{
    define('WSAPI_SERVICE', $_GET['service']);
}
include(dirname(__FILE__).'/../../boot.php');