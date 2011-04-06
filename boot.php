<?php
require(dirname(__FILE__) . '/conf.php');

if(!isset($available_services))
{
    $available_services = array();
}

if(!defined('WSAPI_DEBUG'))
{
    define('WSAPI_DEBUG', false);
}

set_include_path(dirname(__FILE__).'/lib/' . PATH_SEPARATOR . dirname(__FILE__).'/services/');

function __autoload($class_name)
{
    $class_file = str_replace('_', '/', $class_name). '.php';
    return (bool)@include($class_file);
}

if(!defined('WSAPI_SERVER_URL'))
{
    define('WSAPI_SERVER_URL', ($_SERVER['HTTP_HOST'] == '443' ? 'https://' : 'http://') . $_SERVER['HTTP_HOST'] . dirname($_SERVER['REQUEST_URI']) . '/');
}

if(!defined('WSAPI_PROTOCOL'))
{
    if(isset($_GET['protocol']))
    {
        define('WSAPI_PROTOCOL', $_GET['protocol']);
    }
    else
    {
        define('WSAPI_PROTOCOL', 'DOCS');
    }
}

if(!defined('WSAPI_SERVICE'))
{
    if(isset($_GET['service']))
    {
        define('WSAPI_SERVICE', $_GET['service']);
    }
    else
    {
        define('WSAPI_SERVICE', 'Services');
    }
}

try
{
    if(!is_file(dirname(__FILE__).'/services/'.WSAPI_SERVICE.'.php') ||
        !in_array(WSAPI_SERVICE, $available_services))
    {
        throw new Exception('No such service');
    }
    if(WSAPI_PROTOCOL == 'JSON-RPC')
    {
        $server = new Zend_Json_Server();
        $server->setClass(WSAPI_SERVICE);
        $server->handle();die;
    }

    if(WSAPI_PROTOCOL == 'XML-RPC')
    {
        $server = new Zend_XmlRpc_Server();
        $server->setClass(WSAPI_SERVICE, 'test');
        header('content-type: text/xml');
        echo $server->handle();die;
    }

    if(WSAPI_PROTOCOL == 'REST')
    {
        $server = new Zend_Rest_Server();
        $server->setClass(WSAPI_SERVICE);
        $server->handle();die;
    }

    if(WSAPI_PROTOCOL == 'SOAP')
    {
        $server = new Zend_Soap_Server();
        $server->setClass(WSAPI_SERVICE);
        $server->setUri('urn:WSAPI');
        $server->handle();die;
    }

    if(WSAPI_PROTOCOL == 'DOCS')
    {
        $autodiscover = new Zend_Soap_AutoDiscover();
        $autodiscover->setClass(WSAPI_SERVICE);
        $xml = new SimpleXMLElement($autodiscover->toXml());
        header('content-type: text/html; charset=utf-8;');
        require(dirname(__FILE__) . '/ui/gendoc.php');die;
    }
}
catch(Exception $e)
{
    header('HTTP/1.1 500 Internal Server Error');
    header('Content-Type: text/plain');
    if(WSAPI_DEBUG)
    {
        echo get_class($e) . ': ' . $e->getMessage() . "\n\n";
        echo $e->getTraceAsString();
    }
    else
    {
        die('Internal Server Error');
    }
}
