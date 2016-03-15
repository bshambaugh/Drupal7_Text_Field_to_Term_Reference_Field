  This is a script to convert a Text Field to a Term Reference Field for Nodes of Specified
  Content Types. It was built for Drupal 7.

  Please Create a Content Type (bundle) containing a fields for the assigned names of
  the text_field_name and term_reference_field_name variables.

  If you create a new Content Type containing fields for the assigned names
  and wish to migrate content to these fields from a content Type
  containing only a text_field_name name please create a Node Convert Template.
  This node convert template should map the field name assigned to text_field_name from
  the source to target content type.

  If you choose to work with a new content type, please go to Home>Administration>Content in your
  Drupal 7 Installation after you have created  your Node Convert Template and select
  your template name in the Update Options along with any checkboxes for the nodes
  you wish to target for your new content type before running this script.

  Initiate this script by first setting the Text Field, Term Reference, and Bundle Type Variables in
  select_text_nid.drush.php. Then type drush scr taxonomy-term-create.drush.php to run the script.


 The scripts contained include:
 ------------------------------

 * taxonomy-term-create.drush.php
 * select_text_nid.drush.php
 * remove-duplicate-text-nid-pairs.drush.php
 * create_taxonomy_term_with_text.drush.php
 * taxonomy-write-maps.drush.php
 * taxonomy-add-to-index.drush.php


  taxonomy-term-create.drush.php
  -----------------------------

  Wrapper function that calls select_text_nid.drush.php , create_taxonomy_term_with_text.drush.php ,
  taxonomy-write-maps.drush.php , and taxonomy-add-to-index.drush.php and clears the Drupal field cache.


  select_text_nid.drush.php
  -------------------------

  Input: Text Field Name, Term Reference Field Name, Bundle Types (Content Types)
  Output: Node IDs with the Text Field and Term Reference Fields with the Specified Bundle Types,
          Text field Contents

  remove-duplicate-text-nid-pairs
  -------------------------------
  Input: Node IDs with the Text Field and Term Reference Fields with the Specified Bundle Types,
         Text field Contents
  Output: Node IDs with the Text Field and Term Reference Fields with the Specified Bundle Types,
          Text field Contents (without duplicates)


   create_taxonomy_term_with_text.drush.php
   ----------------------------------------

   Input: Node IDs with the Text Field and Term Reference Fields with the Specified Bundle Types,
          Text field Contents (without duplicates)
   Output: Taxonomy Term IDs, Taxonomy Term IDs, and Corresponding Node IDs,
           New Taxonomy Terms for each Text Field string created in the taxonomy_term_data SQL table if they do not already exist.


   taxonomy-write-maps.drush.php
   -----------------------------

   Input: Taxonomy Term IDs and Corresponding Node IDs
   Output: Taxonomy terms added to the Term Reference Field SQL Table


  taxonomy-add-to-index.drush.php
  -------------------------------

  Input: Taxonomy Term IDs and Corresponding Node IDs
  Output: Taxonomy terms added to taxonomy_index SQL table
