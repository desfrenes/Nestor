<?php
define('WSAPI_PROTOCOL', 'XML-RPC');
if(isset($_GET['service']))
{
    define('WSAPI_SERVICE', $_GET['service']);
}
include(dirname(__FILE__).'/../../boot.php');