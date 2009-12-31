<?php



/***********************************************************

All this contributed software -guifimaps- should be 
considered under the GNU/GPL Licensing, with the extension
that can only be used for free networks under the Comuns 
Sensefils license (http://guifi.net/ComunsSensefils).

If that's not the case, you are not granted to copy or use 
the contributed software of guifimaps and you should ask
for permission.

************************************************************/

/******************************************
* Check for local maps if they are
* For now, local maps are defined inline
*******************************************/

$gmi_cntry = "";
$gmi_admin = "";

//    while ( list( $key, $val ) = each( $HTTP_FORM_VARS ) )
//    {
//        printf("%s=%s<BR>\n", $key, $val);
//    }

//    printf ("MapMaxX: %d<br>\n",$gpoMap->extent->maxx);
//    printf ("MapMaxY: %d<br>\n",$gpoMap->extent->maxy);
//    printf ("MapMinX: %d<br>\n",$gpoMap->extent->minx);
//    printf ("MapMinY: %d<br>\n",$gpoMap->extent->miny);
//    printf ("Scale: %d<br>\n",$gpoMap->scale);

$oClickGeo = ms_newPointObj();
// printf("Query Point: X=%f Y=%f<br>\n",$gpoMap->extent->maxx - (($gpoMap->extent->maxx - $gpoMap->extent->minx) / 2),
//                  $gpoMap->extent->maxy - (($gpoMap->extent->maxy - $gpoMap->extent->miny) / 2));

$oClickGeo->setXY($gpoMap->extent->maxx - (($gpoMap->extent->maxx - $gpoMap->extent->minx) / 2),
                  $gpoMap->extent->maxy - (($gpoMap->extent->maxy - $gpoMap->extent->miny) / 2));
$rLayer =  $gpoMap->getlayerbyname('WorldAdminQuery');
// Use '@' to avoid warning if query found nothing
@$rLayer->queryByPoint($oClickGeo, MS_SINGLE, -1);

// Query: Admin
$numResults = $rLayer->getNumResults();
// print "Admin numResults: ".$numResults."<br>\n";
if ($numResults) {
  $rLayer->open();
  $oRes = $rLayer->getResult(0);
  $oShape = $rLayer->getShape($oRes->tileindex,$oRes->shapeindex);
  $gmi_cntry = $oShape->values['GMI_CNTRY']; 
  $gmi_admin = $oShape->values['GMI_ADMIN']; 
//   print_r($oShape->values);
//  $query .= "Admin: ".$oShape->values['CNTRY_NAME'].'-'.$oShape->values['ADMIN_NAME']."<br>\n";
//  print $query;
}


function selectLocalMap($gmi_cntry,$gmi_admin) {
  GLOBAL $gpoMap, $gInfo;

  if (($gpoMap->scale < 3000000) and ($gmi_admin == 'ESP-CTL'))
  {
     $gInfo .=  "Selecting Catalunya<br>\n";
     $gpoMap = ms_newMapObj("catalunya.map");
     GMap75CheckClick();
     return;
  }
  if (($gpoMap->scale < 20000000) and ($gmi_cntry == 'ESP'))
  {
//     print "Selecting Spain";
     $gInfo .=  "Selecting Spain<br>\n";
     $gpoMap = ms_newMapObj("spain.map");
     GMap75CheckClick();
     return;
  }
  if (($gpoMap->scale < 50000000) and ($gmi_cntry == 'USA'))
  {
//     print "Selecting USA";
     $gInfo .=  "Selecting USA<br>\n";
     $gpoMap = ms_newMapObj("usa.map");
     GMap75CheckClick();
     return;
  }
  if (($gpoMap->scale < 40000000) and ($gmi_cntry == 'CAN'))
  {
//     print "Selecting CANADA";
     $gInfo .=  "Selecting USA<br>\n";
     $gpoMap = ms_newMapObj("canada.map");
     GMap75CheckClick();
     return;
 }
}

selectLocalMap($gmi_cntry,$gmi_admin);


?>
