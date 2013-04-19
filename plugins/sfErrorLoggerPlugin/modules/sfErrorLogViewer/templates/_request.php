<?php $request = $sf_error_log->getRequest() ?>

<style>
  #sf_error_log_request tr, #sf_error_log_request td
  {
    padding: 4px;
    spacing: 0;
    text-align: left;
  }
</style>

<div id="sf_error_log_request"
<table cellspacing="0">
<tr>
  <th>URI</th>
  <td><?php echo $request->getUri() ?></td>
</tr>
<tr>
  <th>Referer</th>
  <td><?php echo $request->getReferer() ?></td>
</tr>
<tr>
  <th>Method</th>
  <td><?php echo $request->getMethodName() ?></td>
</tr>
<tr>
  <th>Parameters</th>
  <td>
    <table cellspacing="0">
    <?php foreach ($request->getParameterHolder()->getAll() as $key => $value): ?>
      <tr><th><?php echo $key ?></th><td><?php echo $value ?></td></tr>
    <?php endforeach; ?>
    </table>
  </td>
</tr>
<tr>
  <th>Cookies</th>
  <td><?php echo $request->getHttpHeader('cookie') ?></td>
</tr>
<tr>
  <th>User Agent</th>
  <td><?php echo $request->getHttpHeader('user-agent') ?></td>
</tr>
<tr>
  <th>Accept</th>
  <td><?php echo $request->getHttpHeader('accept') ?></td>
</tr>
<tr>
  <th>Accept encoding</th>
  <td><?php echo $request->getHttpHeader('accept-encoding') ?></td>
</tr>
<tr>
  <th>Accept language</th>
  <td><?php echo $request->getHttpHeader('accept-language') ?></td>
</tr>
<tr>
  <th>Accept charset</th>
  <td><?php echo $request->getHttpHeader('accept-charset') ?></td>
</tr>
</table>
