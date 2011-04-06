<?php
set_include_path(dirname(__FILE__).'/../lib/');

function __autoload($class_name)
{
    $class_file = str_replace('_', '/', $class_name). '.php';
    include $class_file;
}

try
{
    header('content-type: text/plain; charset=utf-8;');
    
    echo 'Tests clients:';

    echo "\n- Test SOAP... ";
    $client = new SoapClient(null, array(
        'location' => 'http://www.desfrenes.com/services/soap.php',
        'uri'      => 'urn:WSAPI',
        'trace'    => 1,
        'soap_version'   => SOAP_1_2
    ));
    echo $client->sayHello('Olivier');

    echo "\n- Test XML-RPC... ";
    $client = new Zend_XmlRpc_Client('http://www.desfrenes.com/services/xml-rpc.php');
    echo $client->getProxy('test')->sayHello('Olivier');

    echo "\n- Test JSON-RPC... ";
    $client = new jsonRPCClient('http://www.desfrenes.com/services/json-rpc.php');
    echo $client->sayHello('Olivier');

    echo "\n- Test REST... ";
    $client = new Zend_Rest_Client('http://www.desfrenes.com/services/');
    echo $client->sayHello('Olivier')->get();
}
catch(Exception $e)
{
    echo get_class($e) . ': ' . $e->getMessage() . "\n\n";
}