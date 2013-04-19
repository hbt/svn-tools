<?php if ($pager->haveToPaginate()): ?>
  <?php echo link_to(image_tag(sfConfig::get('sf_admin_web_dir').'/images/first.png', array('align' => 'absmiddle', 'alt' => __('First'), 'title' => __('First'))), $link . '?page=1&' . $params) ?>
  <?php echo link_to(image_tag(sfConfig::get('sf_admin_web_dir').'/images/previous.png', array('align' => 'absmiddle', 'alt' => __('Previous'), 'title' => __('Previous'))), $link . '?page='.$pager->getPreviousPage() . '&' . $params) ?>

  <?php foreach ($pager->getLinks() as $page): ?>
    <?php echo link_to_unless($page == $pager->getPage(), $page, $link . '?page='.$page . '&' . $params) ?>
  <?php endforeach; ?>

  <?php echo link_to(image_tag(sfConfig::get('sf_admin_web_dir').'/images/next.png', array('align' => 'absmiddle', 'alt' => __('Next'), 'title' => __('Next'))), $link . '?page='.$pager->getNextPage() . '&' . $params) ?>
  <?php echo link_to(image_tag(sfConfig::get('sf_admin_web_dir').'/images/last.png', array('align' => 'absmiddle', 'alt' => __('Last'), 'title' => __('Last'))), $link .'?page='.$pager->getLastPage(). '&' . $params) ?>
<?php endif; ?>