<?php

/** Usage : fetchTomcatStats <user> <password> <server> <port> <connector name to check> */

//error_reporting(E_ALL);
//var_dump($argv);
if ($argc<>6) {
	echo "Not enough parameters. Usage : fetchTomcatStats <user> <password> <server> <port> <connector name to check>";
	exit(1);
}
$user=$argv[1];
$passwd=$argv[2];
$server=$argv[3];
$port=$argv[4];
$connector_to_check=$argv[5];

error_reporting(0);

$url="http://$user:$passwd@$server:$port/manager/status?XML=true";
//print $url;
$contents = file_get_contents($url);
//print $contents;

$doc=new DomDocument();
$doc->loadXML($contents);
//echo PHP_EOL;

// First we fetch jvm meory information
$nodes=$doc->getElementsByTagName("memory");
foreach ($nodes as $memory) {
	//echo "Node Value = ".$memory->nodeValue;
	//echo "Has Attributes = ".$memory->hasAttributes();
	if ($memory->hasAttributes()) 
    { 
        foreach ($memory->attributes as $attr) 
        { 
			echo $attr->nodeName.":".$attr->nodeValue." ";
        } 
    } 
}

// Now we check the required connector
//echo PHP_EOL;
$nodes=$doc->getElementsByTagName("connector");
//print "taille tableau = ".$nodes->length;

foreach ($nodes as $connector) {
	//echo "Has Attributes = ".$connector->hasAttributes();
	if ($connector->hasAttributes()) 
    { 
        foreach ($connector->attributes as $attr) 
        {
			//echo "attribute ".$attr->nodeName." = ".$attr->nodeValue;
			//echo "connector to check = ".$connector_to_check;
			if ($attr->nodeName=="name" && $attr->nodeValue==$connector_to_check) {
				//echo "we found the good connector";
				
				// We're going to fetch threadInfo
				$nodes=$doc->getElementsByTagName("threadInfo");
				foreach ($nodes as $threadInfo) {
					//echo "Node Value = ".$threadInfo->nodeValue;
					//echo "Has Attributes = ".$threadInfo->hasAttributes();
					if ($threadInfo->hasAttributes()) 
					{ 
						foreach ($threadInfo->attributes as $attr) 
						{ 
							echo $attr->nodeName.":".$attr->nodeValue." ";
						} 
					} 
				}

				// We finish with requestInfo
				$nodes=$doc->getElementsByTagName("requestInfo");
				foreach ($nodes as $requestInfo) {
					//echo "Node Value = ".$requestInfo->nodeValue;
					//echo "Has Attributes = ".$requestInfo->hasAttributes();
					if ($requestInfo->hasAttributes()) 
					{ 
						foreach ($requestInfo->attributes as $attr) 
						{ 
							echo $attr->nodeName.":".$attr->nodeValue." ";
						} 
					} 
				}
			}
        } 
    } 
}

?>