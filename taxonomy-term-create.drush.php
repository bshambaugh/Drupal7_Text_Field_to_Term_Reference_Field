<?php
  require_once('select_text_nid.drush.php');
  require_once('remove-duplicate-text-nid-pairs.drush.php');
  require_once('create_taxonomy_term_with_text.drush.php');
  require_once('remove-duplicate-nid-tid-pairs.drush.php');
  require_once('taxonomy-write-maps.drush.php');
  require_once('taxonomy-add-to-index.drush.php');
  // clear that field caches
  field_cache_clear(TRUE);
