<?php

// Declare constants for guifi World maps

// Language (ca = catalan, en = english, es = spanish....)
// lang file must be provided at lang directory
define('LANG','ca');

// Available languages
$languages = array(
  'ca'=>'Català',
  'en'=>'English',
  'es'=>'Español');

// URL for node links, %d will be replaced by NODE_ID
define('NodeURL',"http://guifi.net/node/%d"); 

// URL for adding nodes, %f $f will be replaced by Lon & Lat
define('NodeAddURL',"http://guifi.net/node/add/guifi-node?Lon=%f&Lat=%f"); 
// URL for adding nodes, %f $f will be replaced by Lon & Lat on TESTING SITE
define('NodeAddURLPr',"http://proves.guifi.net/node/add/guifi-node?Lon=%f&Lat=%f");

// Scale up tp Add node link appears
define('AddNode',15000);

// zones file (nodexchange format)
define ('zonesXML','../data/zones.xml');
?>
