<?php if ($type == 'filter'): ?>
  <?php echo select_tag('filters[type]', options_for_select(array(null => 'all', '404' => '404', '500' => '500'), isset($filters['type']) ? $filters['type'] : null)) ?>
<?php endif; ?>
