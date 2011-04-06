<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
    <head>
        <title><?php echo htmlspecialchars($xml['name']); ?> API Documentation</title>
        <link rel="stylesheet" type="text/css" href="http://fonts.googleapis.com/css?family=Ubuntu">
        <link rel="stylesheet" type="text/css" href="http://fonts.googleapis.com/css?family=Inconsolata">
        <style type="text/css">
            body {
                font-family: 'Ubuntu', sans-serif;
                margin:0;
                background-color:#EBEDF1;
                color:#3E3E3E;
            }
            a{
                color:#3298BE;
            }
            h1{
                margin:0;
                padding:1em;
                background-color: #3298BE;
                color:white;
                border-bottom: 5px solid #2C8AAC;
            }
            h2{
                margin:0;
                padding-left:1em;
            }
            pre{
                font-family: 'Inconsolata', monospace;
                padding: 1em;
                background-color: #E2F1F8;
                color:black;
                -moz-border-radius: 10px;
                border-radius: 10px;
            }
            p#footer{
                margin:0;
                background-color: #EBEDF1;
                color:#3E3E3E;
                text-align: center;
                font-size:0.8em;
                border-top: 1px solid #cecece;
                padding-top: 1em;
                padding-bottom: 1em;
            }
            p#footer a{
                color:#3E3E3E;
                text-decoration: none;

            }
            div#servicedescription{
                padding-top:1em;
                padding-bottom:1em;
                padding-right:2em;
                padding-left:2em;
                margin:0;
                background-color: white;
            }
            li em{
                font-family: 'Inconsolata', monospace;
                font-weight: normal;
                font-style: normal;
            }
            li{
                padding-bottom: 1em;
                padding-left: 1em;
                padding-right: 1em;
                list-style-type: none;
                border:1px solid #cecece;
                -moz-border-radius: 10px;
                border-radius: 10px;
                margin-bottom: 1em;
            }
            div.documentation pre{
                font-size: 0.8em;
            }
            span.separator{
                font-weight: normal;
                color:#cecece;
            }
        </style>
    </head>
<?php

function getMessage($xml, $message_name)
{
    $parts = array();
    foreach($xml->message as $message)
    {
        if($message['name'] == $message_name)
        {
            if(count($message->part) > 1)
            {
                foreach($message->part as $part)
                {
                    if(!isset ($part['name']) || !isset($part['type']))
                    {
                        continue;
                    }
                    $parts[] = $part;
                }
            }
            else
            {
                if(!isset ($message->part['name']) || !isset($message->part['type']))
                {
                    continue;
                }
                $parts[] = $message->part;
            }
        }
    }
    return $parts;
}

function getMessageIn($xml, $operation_name)
{
    $message_name = $operation_name . 'In';
    return getMessage($xml, $message_name);
}

function getMessageOut($xml, $operation_name)
{
    $message_name = $operation_name . 'Out';
    return getMessage($xml, $message_name);
}

?>
<body>
    <h1><?php echo htmlspecialchars($xml['name']); ?> API Documentation</h1>
    <div id="servicedescription">
        <h2>Methods:
        <?php foreach($xml->portType->operation as $operation){ ?>
            <a href="#anch<?php echo  htmlspecialchars($operation['name']);  ?>">
            <?php echo htmlspecialchars($operation['name']); ?>
            </a>
            <span class="separator">&nbsp;|&nbsp;</span>
        <?php }?>
        </h2>
        <ul>
        <?php foreach($xml->portType->operation as $operation){ ?>
            <li>
                <a name="anch<?php echo  htmlspecialchars($operation['name']);  ?>"></a>
                <h3><?php $returnargs = getMessageOut($xml, $operation['name']); if(count($returnargs)){
                    echo '<em>' . htmlspecialchars(str_replace(array('tns:', 'soap-enc:', 'xsd:'), array('', ''), $returnargs[0]['type'])) . '</em> ';
                } ?>
                    <?php echo htmlspecialchars($operation['name']); ?>(<?php
                    $argsin = getMessageIn($xml, $operation['name']);
                foreach ($argsin as $k => $argument)
                {
                    echo '<em>' . htmlspecialchars(str_replace(array('tns:', 'soap-enc:', 'xsd:'), array('', ''), $argument['type'])) . '</em> ' . htmlspecialchars($argument['name']);
                    if($argument != end($argsin))
                    {
                        echo ', ';
                    }
                }
                ?>)</h3>
                <div class="documentation"><?php echo str_replace(array('&lt;code&gt;', '&lt;/code&gt;'), array('<pre>', '</pre>'), htmlspecialchars($operation->documentation)); ?></div>
            </li>
        <?php } ?>
        </ul>
    </div>
    <p id="footer">Copyright © 2011 <a href="http://www.desfrenes.com/" target="_blank">M. Desfrênes</a></p>
</body>
</html>