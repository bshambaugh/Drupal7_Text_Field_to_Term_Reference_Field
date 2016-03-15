<?php
/*
* This code
*/

// variables called in
// This code does not check to see whether the node ids that are fed to it actually exist
// Dummy data for node ids and corresponding taxonomy term ids. Uncomment the next three variabkes to run this
// script alone.
//$destnidmap = array(60,33,59,60,55);
// $destmaptid = array(13,13,80,81,80);
// $term_reference_field_name = 'field_ispcolumn';

// Set this to the integer 1 for debugging
$dbg = 1;

// Start of loops to acquire the maxdelta
// Array to hold the corresponding delta value for each term id and node
$delta_array = array();
// This is the same as $term_reference_field_name . It needs to be changed.
$table_data_taxonomy_tag_field_name = 'field_data_'.$term_reference_field_name;
$table_revision_taxonomy_tag_field_name = 'field_revision_'.$term_reference_field_name;
$field_taxonomy_term_name_tid = $term_reference_field_name.'_tid';

foreach($destnidmap as $i => $value) {

// SQL Query to find the maximum delta value for a particular entity_id (or assuming node id)
   $result = db_query('SELECT {'.$table_data_taxonomy_tag_field_name.'}.entity_id, MAX({'.$table_data_taxonomy_tag_field_name.'}.delta) AS maxdelta
   FROM {'.$table_data_taxonomy_tag_field_name.'}
   WHERE {'.$table_data_taxonomy_tag_field_name.'}.entity_id = '.$destnidmap[$i].'');
   if($dbg == 1) {
     echo("entity_id"." "."maxdelta_field_taxonomy_tags_tid");
     echo("\r\n");
   }
  foreach ($result as $record) {
    // Assign a value of zero to delta if the tid does not exist
    // Otherwise iterate the value of delta by one for each node (regardless of the number of term ids)
    if($record->maxdelta == NULL) {
       if($dbg = 1){
         echo($destnidmap[$i]);
         echo(" ");
         echo("Maxdelta is NULL");
       }
     array_push($delta_array,0);
   } else {
     array_push($delta_array,$record->maxdelta+1);
     if($dbg == 1){
       echo($record->entity_id);
       echo(" ");
       echo($record->maxdelta);
     }
   }
    if($dbg == 1) {
     echo("\r\n");
    }
  }
}

if($dbg == 1){
  echo("Before the max delta has been corrected");
  echo("\r\n");
  foreach ($delta_array as $i => $value) {
    echo($destnidmap[$i].' '.$delta_array[$i]);
    echo("\r\n");
  }
  echo("\r\n");
  echo("----");
  echo("\r\n");
}

// Give unique node ids
$uniqe = array_unique($destnidmap);

// Map to hold unique node ids
$uniqe_map = array();

//$uniqe_delta_map = array();

// Create a mapping for the unique node ids
foreach($uniqe as $i => $value) {
  array_push($uniqe_map,$uniqe[$i]);
}

// Create an array as a zero matrix with the same number of elements as uniqe_map
$count = array_fill(0,count($uniqe_map),0);
// change this to bring in the original delta array values

if($dbg == 1) {
  echo("The count is");
  echo("\r\n");
  foreach($count as $i => $value) {
    echo($count[$i]);
    echo("\r\n");
  }
  echo("The end of the count");
  echo("\r\n");
}

// Populate the count array with values of the count array for each node
// At this time all values of the delta array are the same for each node id
foreach ($destnidmap as $i => $value_one) {
  foreach($uniqe_map as $j => $value_two) {
    if($destnidmap[$i] == $uniqe_map[$j]) {
      $count[$j] = $delta_array[$i];
    }
  }
}

if($dbg == 1){
  echo("The count after delta array mapping is");
  echo("\r\n");
  foreach($count as $i => $value) {
    echo($count[$i]);
    echo("\r\n");
  }
  echo("The end of the mapping to delta array count");
  echo("\r\n");
}

// Increase the value of delta_array by one for each time a particular node id occurs
foreach ($destnidmap as $i => $value_one) {
  foreach($uniqe_map as $j => $value_two) {
    if($destnidmap[$i] == $uniqe_map[$j]) {
      $delta_array[$i] = $count[$j];
      $count[$j] = $count[$j] + 1;
    }
  }
}

if($dbg == 1){
  foreach ($delta_array as $i => $value) {
    echo($destnidmap[$i].' '.$delta_array[$i]);
    echo("\r\n");
  }
}

// end of updating max delta

// Create and Array to Acquire the bundle type for each node
$bundle_array = array();

// Acquire the bundle type for each node id
foreach($destnidmap as $i => $value) {
$result = db_query('SELECT {node}.type FROM {node} WHERE {node}.nid = '.$destnidmap[$i].'')->fetchObject();

array_push($bundle_array,$result->type);
}

// start of loop to insert the taxonomy term

if($dbg == 1) {
  echo('entity_type'.' '.'bundle'.' '.'deleted'.' '.'entity_id'.' '.'revision_id'.' '.'language'.' '.'delta'.' '.$field_taxonomy_term_name_tid);
  echo("\r\n");
}

foreach($destnidmap as $i => $value) {
  if($dbg == 1) {
   echo('node'.' '.$bundle_array[$i].' '.'0'.' '.$destnidmap[$i].' '.$destnidmap[$i].' '.'und'.' '.$delta_array[$i].' '.$destmaptid[$i]);
   echo("\r\n");
  }
/* Comment the next two lines out for testing purposes. This is the point where the taxonomy term relations for each node id are
  *Added to the database. */
   insert_taxonomy_term('node','semantic_portal_page',0,$destnidmap[$i],$destnidmap[$i],'und',$delta_array[$i],$destmaptid[$i],$table_data_taxonomy_tag_field_name,$field_taxonomy_term_name_tid);
   insert_taxonomy_term('node','semantic_portal_page',0,$destnidmap[$i],$destnidmap[$i],'und',$delta_array[$i],$destmaptid[$i],$table_revision_taxonomy_tag_field_name,$field_taxonomy_term_name_tid);
}

function insert_taxonomy_term($entity_type, $bundle, $deleted, $entity_id, $revision_id, $language, $delta, $tid, $tax_tag_field_table, $field_field_name_tid) {
    $nid = db_insert($tax_tag_field_table)
       ->fields(array(
       'entity_type' => $entity_type,
       'bundle' => $bundle,
       'deleted' => $deleted,
       'entity_id' => $entity_id,
       'revision_id' => $revision_id,
       'language' => $language,
        $field_field_name_tid => $tid
  ))
  ->execute();
}

?>
