<?php

//$destnidmap = array(60,59);
//$destmaptid = array(13,52);

// I need to pull the created varible from the node table..
$nid_created_array = array();

foreach($destnidmap as $i => $value) {
$result = db_query('SELECT {node}.created FROM {node} WHERE {node}.nid = '.$destnidmap[$i].'')->fetchObject();
//$result = db_query('SELECT {node}.type FROM {node} WHERE {node}.nid = '.$destnidmap[0].'')
 //print_r($result);

//echo($result->type);
array_push($nid_created_array,$result->created);
}

echo('Node id'.' '.'Creation Date');
echo("\r\n");
foreach($nid_created_array as $i => $value) {
  echo($destnidmap[$i].' '.$nid_created_array[$i]);
  echo("\r\n");
}

function add_taxonomy_term_to_index($nodeid,$tid,$sticky,$created) {
  $nid = db_insert('taxonomy_index')
        ->fields(array(
          'nid' => $nodeid,
          'tid' => $tid,
          'sticky' => $sticky,
          'created' => $created,
        ))
        ->execute();
}


foreach($destnidmap as $i => $value) {
  add_taxonomy_term_to_index($destnidmap[$i],$destmaptid[$i],0,$nid_created_array[$i]);
}

 ?>
