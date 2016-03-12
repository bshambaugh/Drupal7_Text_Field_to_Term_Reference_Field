<?php
// Drupal 7
// Notice the place holders are now done using the same syntax as PDOs (:uid)
// Placeholders also don't need to be quoted anymore.

// variables called in
/*
$destnidmap = array(30,30,60, 59, 60, 60, 60, 61, 61, 61, 62);
$destmaptid = array(16,13,13, 38, 39, 40, 41, 42, 43, 44, 45);
*/

// Start of loops to acquire the maxdelta
//variables used for this function
$delta_array = array();
$taxonomy_tag_field_name = 'vie_reference_tags';
$table_data_taxonomy_tag_field_name = 'field_data_field_'.$taxonomy_tag_field_name;
$table_revision_taxonomy_tag_field_name = 'field_revision_field_'.$taxonomy_tag_field_name;
$field_taxonomy_term_name_tid = 'field_'.$taxonomy_tag_field_name.'_tid';

foreach($destnidmap as $i => $value) {
//$destiny = 30;


   $result = db_query('SELECT {'.$table_data_taxonomy_tag_field_name.'}.entity_id, MAX({'.$table_data_taxonomy_tag_field_name.'}.delta) AS maxdelta
   FROM {'.$table_data_taxonomy_tag_field_name.'}
   WHERE {'.$table_data_taxonomy_tag_field_name.'}.entity_id = '.$destnidmap[$i].'');

/*
   $result = db_query('SELECT {field_data_field_vie_reference_tags}.entity_id, MAX({field_data_field_vie_reference_tags}.delta) AS maxdelta
   FROM {field_data_field_vie_reference_tags}
   WHERE {field_data_field_vie_reference_tags}.entity_id = '.$destnidmap[$i].'');
   */

//WHERE {field_data_field_vie_reference_tags}.entity_id = '.$destiny.'');
// WHERE {field_data_field_vie_reference_tags}.entity_id = 33');
/* WHERE {field_data_field_vie_reference_tags}.entity_id = :destiny', array(':destiny' => $destiny)); */
// Result is returned as a iterable object that returns a stdClass object on each iteration
// print out the results of the query for debugging
/*
echo("The start of the result");
echo("\r\n");
print_r($result);
echo("\r\n");
echo("The end of the result");
var_dump($result);
echo("\r\n");
*/
  echo("entity_id"." "."maxdelta_field_vie_reference_tags_tid");
  echo("\r\n");
  foreach ($result as $record) {
    // Perform operations on $record->title, etc. here.
    // echo("This is a loop test");
    if($record->maxdelta == NULL) {
     echo($destnidmap[$i]);
     echo(" ");
     echo("Maxdelta is NULL");
     array_push($delta_array,0);
   } else {
     array_push($delta_array,$record->maxdelta+1);
     echo($record->entity_id);
     echo(" ");
     echo($record->maxdelta);
   }
    echo("\r\n");
    // in this example the available data would be mapped to object properties:
    // $record->nid, $record->title, $record->created
  }
}
echo("Before the max delta has been corrected");
echo("\r\n");
foreach ($delta_array as $i => $value) {
  echo($destnidmap[$i].' '.$delta_array[$i]);
  echo("\r\n");
}
echo("\r\n");
echo("----");
echo("\r\n");
/// Give unique deltas for each time a taxonomy term is used with a node
$uniqe = array_unique($destnidmap);

//$uniqe_delta = array_unique($delta_array);

$uniqe_map = array();

//$uniqe_delta_map = array();

foreach($uniqe as $i => $value) {
  array_push($uniqe_map,$uniqe[$i]);
}

/*
foreach($uniqe_delta as $i => $value) {
  array_push($uniqe_delta_map,$uniqe_delta[$i]);
}

echo("The unique delta map");
echo("\r\n");
foreach($uniqe_delta_map as $i => $value) {
  echo($uniqe_delta_map[$i]);
  echo("\r\n");
}
echo("The end of the unique delta map");
echo("\r\n");
echo("The uniqe map");
foreach($uniqe_map as $i => $value) {
  echo($uniqe_map[$i]);
  echo("\r\n");
}
echo("The end of the uniqe map");
echo("\r\n");
*/

$count = array_fill(0,count($uniqe_map),0);
// change this to bring in the original delta array values

echo("The count is");
echo("\r\n");
foreach($count as $i => $value) {
  echo($count[$i]);
  echo("\r\n");
}
echo("The end of the count");
echo("\r\n");

foreach ($destnidmap as $i => $value_one) {
  foreach($uniqe_map as $j => $value_two) {
    if($destnidmap[$i] == $uniqe_map[$j]) {
      $count[$j] = $delta_array[$i];
    }
  }
}

echo("The count after delta array mapping is");
echo("\r\n");
foreach($count as $i => $value) {
  echo($count[$i]);
  echo("\r\n");
}
echo("The end of the mapping to delta array count");
echo("\r\n");


foreach ($destnidmap as $i => $value_one) {
  foreach($uniqe_map as $j => $value_two) {
    if($destnidmap[$i] == $uniqe_map[$j]) {
      $delta_array[$i] = $count[$j];
      $count[$j] = $count[$j] + 1;
    }
  }
}
foreach ($delta_array as $i => $value) {
  echo($destnidmap[$i].' '.$delta_array[$i]);
  echo("\r\n");
}

// end of loop to acquire the max delta
// Acquire bundle type for each node
$bundle_array = array();

foreach($destnidmap as $i => $value) {
$result = db_query('SELECT {node}.type FROM {node} WHERE {node}.nid = '.$destnidmap[$i].'')->fetchObject();
//$result = db_query('SELECT {node}.type FROM {node} WHERE {node}.nid = '.$destnidmap[0].'')
 //print_r($result);

//echo($result->type);
array_push($bundle_array,$result->type);
}

// start of loop to insert the taxonomy term
  echo('entity_type'.' '.'bundle'.' '.'deleted'.' '.'entity_id'.' '.'revision_id'.' '.'language'.' '.'delta'.' '.$field_taxonomy_term_name_tid);
  echo("\r\n");
foreach($destnidmap as $i => $value) {
   echo('node'.' '.$bundle_array[$i].' '.'0'.' '.$destnidmap[$i].' '.$destnidmap[$i].' '.'und'.' '.$delta_array[$i].' '.$destmaptid[$i]);
   echo("\r\n");
//   insert_taxonomy_term('node','semantic_portal_page',0,$destnidmap[$i],$destnidmap[$i],'und',5,$destmaptid[$i],$table_data_taxonomy_tag_field_name,$field_taxonomy_term_name_tid);
//  insert_taxonomy_term('node','semantic_portal_page',0,$destnidmap[$i],$destnidmap[$i],'und',$delta_array[$i],$destmaptid[$i],$table_data_taxonomy_tag_field_name,$field_taxonomy_term_name_tid);
//  insert_taxonomy_term('node','semantic_portal_page',0,$destnidmap[$i],$destnidmap[$i],'und',$delta_array[$i],$destmaptid[$i],$table_revision_taxonomy_tag_field_name,$field_taxonomy_term_name_tid);
}

// These are two taxonomy terms that work that I commented out
/*
insert_taxonomy_term('node','semantic_portal_page',0,60,60,'und',5,13,$table_data_taxonomy_tag_field_name,$field_taxonomy_term_name_tid);
insert_taxonomy_term('node','semantic_portal_page',0,60,60,'und',5,13,$table_revision_taxonomy_tag_field_name,$field_taxonomy_term_name_tid);
*/
// insert_taxonomy_term('node','semantic_portal_page',0,60,60,'und',5,13,'field_data_field_vie_reference_tags','field_vie_reference_tags_tid');


//$newkey = sprintf('%s',$field_vie_reference_tags);

function insert_taxonomy_term($entity_type, $bundle, $deleted, $entity_id, $revision_id, $language, $delta, $tid, $tax_tag_field_table, $field_field_name_tid) {
//  $nid = db_insert(''.$tax_tag_field_table.'')
    $nid = db_insert($tax_tag_field_table)
//  $nid = db_insert('field_data_field_vie_reference_tags')
       ->fields(array(
       'entity_type' => $entity_type,
       'bundle' => $bundle,
       'deleted' => $deleted,
       'entity_id' => $entity_id,
       'revision_id' => $revision_id,
       'language' => $language,
       'delta' => $delta,
    //  'field_vie_reference_tags_tid' => 16
        $field_field_name_tid => $tid
  ))
  ->execute();
}

?>
