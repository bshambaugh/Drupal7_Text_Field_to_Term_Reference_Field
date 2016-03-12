<?php
// Find the values of the entity_type field and the nodes that they correspond to
// This is like b and nbid..
$field_name = 'entity_type';
$field_machine_name = 'field_'.$field_name;
$table_text_field_name = 'field_data_field_'.$field_name;
$table_text_field_name_value = 'field_'.$field_name.'_value';

//$table_text_field_name = 'field_data_field_entity_type';
//$query = db_query('SELECT * from field_data_field_entity_type')->fetchObject();
//$query = db_query('SELECT * FROM {field_data_field_entity_type}',array('field_data_field_entity_type' => $table_text_field_name))->fetchObject();

//$query2 = db_query('SELECT * FROM {'.$table_text_field_name.'}')->fetchObject();
//$query = db_query('SELECT * from text_field_name',array('text_field_name' => $table_text_field_name))->fetchObject();

//$query = db_query('SELECT * from $table_text_field_name')->fetchObject();
//$query = db_query('SELECT * FROM :text_field_name',array(':text_field_name' => $table_text_field_name))->fetchObject();
//print_r($query2);
$dbg = 1;
$specified_vid = 1;

$result = db_query('SELECT {node}.nid, {'.$table_text_field_name.'}.'.$table_text_field_name_value.'
FROM {node}
JOIN {'.$table_text_field_name.'} ON
{node}.nid = {'.$table_text_field_name.'}.entity_id');
/*
$result = db_query('SELECT {node}.nid, {'.$table_text_field_name.'}.field_entity_type_value
FROM {node}
JOIN {'.$table_text_field_name.'} ON
{node}.nid = {'.$table_text_field_name.'}.entity_id');
*/
/*
$result = db_query('SELECT {node}.nid, {field_data_field_entity_type}.field_entity_type_value
FROM {node}
JOIN {field_data_field_entity_type} ON
{node}.nid = {field_data_field_entity_type}.entity_id');
*/
// like bnid
$nid_array = array();
// like b
$field_text_field_name_value_array = array();
foreach($result as $record) {
   array_push($nid_array, $record->nid);
   array_push($field_text_field_name_value_array, $record->$table_text_field_name_value);
 }
if($dbg = 1) {
print_r($nid_array);
print_r($field_text_field_name_value_array);
}

/*
$b = array();
$nid_array = array();

foreach($nid_array as $i => $value) {
  array_push($b,$field_text_field_name_value_array[$i]);
  array_push($nid_array,$nid_array[$i]);
//  array_push($,$b[$i]);
}
*/

// Acquire all existing taxonomy name, term ids, and vocabulary ids
/*
$nid_array = array();
$field_text_field_name_value_array = array();
foreach($result as $record) {
   array_push($nid_array, $record->nid);
   array_push($field_text_field_name_value_array, $record->field_entity_type_value);}
if($dbg = 1) {
print_r($nid_array);
print_r($field_text_field_name_value_array);
}
*/
// Acquire all existing taxonomy name, term ids, and vocabulary ids
/*
$taxonomy_name= array("Mars Probe","Satellite","Launch Vehicle");
$tid = array("1","2","3");
*/
// Here is code I added to do it with SQL
$taxonomy_name = array();
$tid = array();
$vid = array();
$result = db_query('SELECT {taxonomy_term_data}.name, {taxonomy_term_data}.tid, {taxonomy_term_data}.vid FROM {taxonomy_term_data}');
// Result is returned as a iterable object that returns a stdClass object on each iteration
foreach ($result as $record) {
  // Perform operations on $record->title, etc. here.
  echo($record->name);
  echo("\r\n");
  array_push($taxonomy_name, $record->name);
  array_push($tid, $record->tid);
  array_push($vid, $record->vid);
  // in this example the available data would be mapped to object properties:
  // $record->nid, $record->title, $record->created
}
//
echo("\r\n");
echo("\r\n");
echo("The existing taxonomy terms are");
echo("\r\n");
foreach($taxonomy_name as $i => $value) {
  echo($tid[$i]." ".$taxonomy_name[$i]." ".$vid[$i]);
  echo("\r\n");
}
echo("\r\n");
/*
$taxonomy_name= array("apple","banana","grapefruit");
$tid = array("1","2","3");
*/
//$b = array("pear", "watermelon","banana");
//$nid_array = array("45","54","33");
$field_text_field_name_value_array_map = array();
$nid_array_match = array();
$nid_array_map = array();
$match_taxonomy_name = array();
$match_tid = array();
$destmap_taxonomy_name = array();
$destnidmap = array();
$destmaptid = array();

foreach($field_text_field_name_value_array as $i => $value) {
  array_push($field_text_field_name_value_array_map,$field_text_field_name_value_array[$i]);
  array_push($nid_array_map,$nid_array[$i]);
//  array_push($,$b[$i]);
}

foreach( $field_text_field_name_value_array as $i => $value_one) {
  foreach($taxonomy_name as $j => $value_two) {
   if($specified_vid == $vid[$j]) {
    if( $field_text_field_name_value_array[$i] == $taxonomy_name[$j]) {
      // add another if statement checking for the appropriate vocabulary id, (so I need to read this into an array as well,
      // it will be for the vocabulary associated with the "i" index)

      echo("We are equal for ".$field_text_field_name_value_array[$i]." and ".$taxonomy_name[$j]);
      array_push($match_taxonomy_name,$taxonomy_name[$j]);
      array_push($match_tid,$tid[$j]);
      array_push($nid_array_match,$nid_array[$i]);
      unset($field_text_field_name_value_array_map[$i]);
      unset($nid_array_map[$i]);
      echo("\r\n");

  /*
       if ($dbg == 1) {
       echo "map objarray is: ".$mapobjarray[$i]." sub_array_two is: ".$subarray_two[$j]."We are equal: ".'<br/>';
       }
       unset($mapobjarray[$i]);
       unset($mappredarray[$i]);
       unset($mapsubjarray[$i]);
*/
    } elseif ($field_text_field_name_value_array[$i] !== $taxonomy_name[$j]) {
      echo("We are not equal for ".$field_text_field_name_value_array[$i]." and ".$taxonomy_name[$j]);
      echo("\r\n");
      /*
       if ($dbg == 1) {
       echo "map objarray is: ".$mapobjarray[$i]." sub_array_two is: ".$subarray_two[$j]."We are not equal: ".'<br/>';
       }
       */
    }
   } // end of vid_array if statement
  }
}

echo("The matches are");
echo("\r\n");
foreach($match_taxonomy_name as $i => $value) {
  echo($match_taxonomy_name[$i]." ".$match_tid[$i]." ".$nid_array_match[$i]);
  echo("\r\n");
}

foreach($match_taxonomy_name as $i => $value) {
  array_push($destmap_taxonomy_name,$match_taxonomy_name[$i]);
}

foreach($match_tid as $i => $value) {
  array_push($destmaptid,$match_tid[$i]);
}

foreach($nid_array_match as $i => $value) {
  array_push($destnidmap,$nid_array_match[$i]);
}


echo("The non-matches are");
echo("\r\n");
foreach($field_text_field_name_value_array_map as $i => $value) {
  echo($field_text_field_name_value_array_map[$i]." ".$nid_array_map[$i]);
  echo("\r\n");
}

echo("The final mapping before adding the non-matches is");
echo("\r\n");
echo("taxonomy_term_id"." "."taxonomy_term_name"." "."referencing_node_id");
echo("\r\n");
foreach($destmap_taxonomy_name as $i => $value) {
  echo($destmaptid[$i]." ".$destmap_taxonomy_name[$i]." ".$destnidmap[$i]);
  echo("\r\n");
}
// feed the non-matches into the custom create taxonomy term function


function custom_create_taxonomy_term($name, $vid, $parent_id = 0) {
  $term = new stdClass();
  $term->name = $name;
  $term->vid = $vid;
  $term->parent = array($parent_id);
  taxonomy_term_save($term);
  return $term->tid;
}

// cross out for debug

foreach($field_text_field_name_value_array_map as $i => $value) {
  array_push($destmaptid,custom_create_taxonomy_term($field_text_field_name_value_array_map[$i], $specified_vid));
  array_push($destnidmap,$nid_array_map[$i]);
  array_push($destmap_taxonomy_name,$field_text_field_name_value_array_map[$i]);
//  echo("The id is".$destmaptid[$i]);
//  echo("\r\n");
}

// Assign ids to pear and watermelon from $b
// I believe some of this work is done by the taxonomy save module...I will need
// to test it in full or partial isolation
echo("The count of the original array is ".count($taxonomy_name));
echo("\r\n");
/*
for($i = count($a) + 1; $i <= count($a) + count($field_text_field_name_value_array_map); $i++) {
  array_push($destmap_taxonomy_name,$field_text_field_name_value_array_map[$i - count($a) - 1]);
  array_push($destnidmap,$nid_array_map[$i - count($a) - 1]);
  array_push($destmaptid,$i);
  echo $i;
  echo("\r\n");
}
*/
// This is not needed since it was added with the custom_create_taxonomy_term loop
/*
for($i = 1; $i <= count($field_text_field_name_value_array_map); $i++) {
  array_push($destmap_taxonomy_name,$field_text_field_name_value_array_map[$i]);
  array_push($destnidmap,$nid_array_map[$i]);
  array_push($destmaptid,$i + count($taxonomy_name) + 1);
  echo $i;
  echo("\r\n");
}
*/
echo("The combined matches are");
echo("\r\n");
echo("taxonomy_term_id"." "."taxonomy_term_name"." "."referencing_node_id");
echo("\r\n");
foreach($destmap_taxonomy_name as $i => $value) {
  echo($destmaptid[$i]." ".$destmap_taxonomy_name[$i]." ".$destnidmap[$i]);
  echo("\r\n");
//  echo("The id is".$destmaptid[$i]);
//  echo("\r\n");
}
