<?php use_helper ('Javascript') ?>


<?php if ($pager->haveToPaginate()): ?>
 
  <?php echo link_to_remote(image_tag(sfConfig::get('sf_admin_web_dir').'/images/first.png', array('border' => "0", 'align' => 'absmiddle', 'alt' => __('First'), 'title' => __('First'))), array (
  	'update' => $update_id,
  	'url' => $link . '?page=1&' . $params
  )) ?>
  <?php echo link_to_remote(image_tag(sfConfig::get('sf_admin_web_dir').'/images/previous.png', array('border' => "0", 'align' => 'absmiddle', 'alt' => __('Previous'), 'title' => __('Previous'))), array (
  	'update' => $update_id,
  	'url' => $link . '?page='.$pager->getPreviousPage() . '&' . $params
  )) ?>

  <?php foreach ($pager->getLinks() as $page): ?>
    <?php if ($page != $pager->getPage()): ?>
    	<?php echo link_to_remote ($page, array (
    		'update' => $update_id,
    		'url' => $link . '?page='.$page . '&' . $params
    	)) ?>
    <?php else: ?>
    	<?php echo $page ?>	
    <?php endif; ?>
    
  <?php endforeach; ?>

  <?php echo link_to_remote(image_tag(sfConfig::get('sf_admin_web_dir').'/images/next.png', array('border' => "0", 'align' => 'absmiddle', 'alt' => __('Next'), 'title' => __('Next'))), array (
  	'update' => $update_id,
  	'url' => $link . '?page='.$pager->getNextPage() . '&' . $params
  )) ?>
  
  <?php echo link_to_remote(image_tag(sfConfig::get('sf_admin_web_dir').'/images/last.png', array('border' => "0", 'align' => 'absmiddle', 'alt' => __('Last'), 'title' => __('Last'))), array (
  'update' => $update_id,
  	'url' => $link .'?page='.$pager->getLastPage(). '&' . $params
  )) ?>
  
<?php endif; ?>