<?php if ('500' != $sf_error_log->getType()) return ?>

<?php if (!$sf_error_log->getExceptionObject() instanceof sfException): ?>
  &nbsp;
<?php return; endif; ?>

<?php $traces = $sf_error_log->getExceptionObject()->getTraces($sf_error_log->getExceptionObject(), 'html') ?>

<style>
  #main { font: 11px Verdana, Arial, sans-serif; color: #333 }
  ul { list-style: decimal }
  ul li { padding-bottom: 5px; margin: 0 }
  ol { font-family: monospace; white-space: pre; list-style-position: inside; margin: 0; padding: 10px 0 }
  ol li { margin: -5px; padding: 0 }
  ol .selected { font-weight: bold; background-color: #ddd; padding: 2px 0 }
</style>

<script type="text/javascript">
function toggle(id)
{
  el = document.getElementById(id); el.style.display = el.style.display == 'none' ? 'block' : 'none';
}
</script>

<ul id="main">
  <li><?php echo implode("</li>\n<li>", $traces) ?></li>
</ul>
