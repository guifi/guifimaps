<?php

  $rootZone = 3671;

  $minX = 999;
  $minY = 999;
  $maxX = -999;
  $maxY = -999;

  $members = array();

  $hlastnow = @fopen("http://".$_ENV["GUIFI_WEB"]."/guifi/refresh/maps", "r") or die('Error reading changes\n');
  $last_now = fgets($hlastnow);
  fclose($hlastnow);

  clearstatcache();
  if(!file_exists("/tmp/last_update")) {
   $fp = fopen("/tmp/last_update","w");
   fwrite($fp,"0");
   fclose($fp);
  }

  $hlast= @fopen("/tmp/last_update", "r") or die('Error reading /tmp/last_update !');
  if ($last_now == fgets($hlast)) {
    fclose($hlast);
    echo "No changes.\n";
  }

  echo "Dumping links in gml format\n";
  $hlinks = @fopen("http://".$_ENV["GUIFI_WEB"]."/guifi/gml/".$rootZone."/links/csv", "r") or die("Error getting links cv\n");;
  if ($hlinks) {
   while (!feof($hlinks)) {
       print_r($member);
       $member = explode(',',stream_get_line($hlinks, 4096,"\n"));
       $members[] = $member;
       if (count($member) == 12) {
         if ($minX > $member[8]) $minX=$member[8];
         if ($minY > $member[9]) $minY=$member[9];
         if ($maxX < $member[10]) $maxX=$member[10];
         if ($maxY < $member[11]) $maxY=$member[11];
       }
   }
   fclose($hlinks);
  }

  $hlinks = @fopen('data/dlinks.gml', 'w') or die("Error!!");
  fwrite($hlinks, '<?xml version="1.0" encoding="utf-8" ?>
<ogr:FeatureCollection
     xmlns:xsi="http://www.w3c.org/2001/XMLSchema-instance"
     xsi:schemaLocation=". dlinks.xsd"
     xmlns:ogr="http://ogr.maptools.org/"
     xmlns:gml="http://www.opengis.net/gml">
  <gml:boundedBy>
    <gml:Box>
      <gml:coord><gml:X>'.$minX.'</gml:X><gml:Y>'.$minY.'</gml:Y></gml:coord>
      <gml:coord><gml:X>'.$maxX.'</gml:X><gml:Y>'.$maxY.'</gml:Y></gml:coord>
    </gml:Box>
  </gml:boundedBy>'."\n");

  foreach ($members as $member)
  if (count($member) == 12) fwrite($hlinks, '<gml:featureMember>
    <dlinks fid="'.$member[0].'">
      <NODE1_ID>'.$member[1].'</NODE1_ID>
      <NODE1_NAME>'.$member[2].'</NODE1_NAME>
      <NODE2_ID>'.$member[3].'</NODE2_ID>
      <NODE2_NAME>'.$member[4].'</NODE2_NAME>
      <KMS>'.$member[5].'</KMS>
      <LINK_TYPE>'.$member[6].'</LINK_TYPE>
      <STATUS>'.$member[7].'</STATUS>
      <ogr:geometryProperty><gml:LineString><gml:coordinates>'.$member[8].','.$member[9].' '.$member[10].','.$member[11].'</gml:coordinates></gml:LineString></ogr:geometryProperty>
    </dlinks>
  </gml:featureMember>'."\n");
  fwrite($hlinks,'</ogr:FeatureCollection>'."\n");
  fclose($hlinks);

  // nodes
  $minX = 999;
  $minY = 999;
  $maxX = -999;
  $maxY = -999;

  $members = array();

  echo "Dumping nodes in gml format\n";
  $hnodes = @fopen("http://".$_ENV["GUIFI_WEB"]."/guifi/gml/".$rootZone."/nodes/csv", "r");
  if ($hnodes) {
   while (!feof($hnodes)) {
       $member = explode(',',stream_get_line($hnodes, 4096,"\n"));
       $members[] = $member;
       if (count($member) == 6) {
         if ($minX > $member[1]) $minX=$member[1];
         if ($minY > $member[2]) $minY=$member[2];
         if ($maxX < $member[1]) $maxX=$member[1];
         if ($maxY < $member[2]) $maxY=$member[2];
       }
//       echo $buffer;
   }
   fclose($hnodes);
  }

  $hnodes = @fopen('data/dnodes.gml', 'w') or die("Error!!");
  fwrite($hnodes, '<?xml version="1.0" encoding="utf-8" ?>
<ogr:FeatureCollection
     xmlns:xsi="http://www.w3c.org/2001/XMLSchema-instance"
     xsi:schemaLocation=". dlinks.xsd"
     xmlns:ogr="http://ogr.maptools.org/"
     xmlns:gml="http://www.opengis.net/gml">
  <gml:boundedBy>
    <gml:Box>
      <gml:coord><gml:X>'.$minX.'</gml:X><gml:Y>'.$minY.'</gml:Y></gml:coord>
      <gml:coord><gml:X>'.$maxX.'</gml:X><gml:Y>'.$maxY.'</gml:Y></gml:coord>
    </gml:Box>
  </gml:boundedBy>'."\n");

  foreach ($members as $member)
  if (count($member) == 6)
  fwrite($hnodes,
'  <gml:featureMember>
    <dnodes fid="'.$member[0].'">
      <ogr:geometryProperty><gml:Point><gml:coordinates>'.$member[1].','.$member[2].'</gml:coordinates></gml:Point></ogr:geometryProperty>
      <NODE_ID>'.$member[0].'</NODE_ID>
      <NODE_NAME>'.$member[3].'</NODE_NAME>
      <NODE_TYPE>'.$member[4].'</NODE_TYPE>
      <STATUS>'.$member[5].'</STATUS>
    </dnodes>
  </gml:featureMember>'."\n");
  fwrite($hnodes,'</ogr:FeatureCollection>'."\n");
  fclose($hnodes);

  $hlast= @fopen("/tmp/last_update", "w") or die('Error!');
  fwrite($hlast,$last_now);
  fclose($hlast);
?>
