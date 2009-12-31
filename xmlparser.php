<?php

function get_zones($zarray, $ztree, $depth = 0) {
  foreach ($zarray as $value) {
    if ($value['name'] == 'ZONE') {
      $ztree[$value['attrs']['ID']]->region = $value['attrs']['TITLE'];
      $ztree[$value['attrs']['ID']]->box = $value['attrs']['BOX'];
      $ztree[$value['attrs']['ID']]->ident = $depth;
    }
    if (array_key_exists('child',$value))
    if (is_array($value['child'])) {
      $ztree = get_zones($value['child'],$ztree,$depth + 1); 
    }
  }

  return $ztree;
}

// $p =& new xmlParser();
// $p->parse('zones.xml');
// print_r($p->output);

// $zones = $p->output;

// $ztree = array();
// $ztree = get_zones($zones,$ztree);

// print "Tree: ";
// print_r ($ztree);
// print "\nZones: ";
// print_r($zones);


class xmlParser{

   var $xml_obj = null;
   var $output = array();
 
   function xmlParser(){
    
       $this->xml_obj = xml_parser_create("utf-8");
       xml_set_object($this->xml_obj,$this);
       xml_set_character_data_handler($this->xml_obj, 'dataHandler'); 
       xml_set_element_handler($this->xml_obj, "startHandler", "endHandler");
 
   }
 
   function parse($path){
    
       if (!($fp = fopen($path, "r"))) {
           die("Cannot open XML data file: $path");
           return false;
       }
    
       while ($data = fread($fp, 4096)) {
           if (!xml_parse($this->xml_obj, $data, feof($fp))) {
               die(sprintf("XML error: %s at line %d",
               xml_error_string(xml_get_error_code($this->xml_obj)),
               xml_get_current_line_number($this->xml_obj)));
               xml_parser_free($this->xml_obj);
           }
       }
    
       return true;
   }

   function startHandler($parser, $name, $attribs){
       $_content = array('name' => $name);
       if(!empty($attribs))
         $_content['attrs'] = $attribs;
       array_push($this->output, $_content);
   }

   function dataHandler($parser, $data){
       if(!empty($data)) {
           $_output_idx = count($this->output) - 1;
           $this->output[$_output_idx]['content'] = $data;
       }
   }

   function endHandler($parser, $name){
       if(count($this->output) > 1) {
           $_data = array_pop($this->output);
           $_output_idx = count($this->output) - 1;
           $this->output[$_output_idx]['child'][] = $_data;
       }     
   }

}

?>
