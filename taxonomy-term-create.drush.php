<?php
  require_once('taxonomy-combine-maps.drush.php');
  require_once('taxonomy-write-maps.drush.php');
  require_once('taxonomy-add-to-index.drush.php');
  f// clear that field caches
  field_cache_clear(TRUE);
