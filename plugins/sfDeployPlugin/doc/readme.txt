This plugin has tasks to create & deploy patches and scripts.
It is efficient for deployment on different servers/environments

You can also use it to switch environments




1) How to use the environments?

Add .environmentName to your files for example
- databases.yml.production
- databases.yml.staging

That means, if you are on production you have to type the following:
symfony deploy:switch-env production

and it will replace all files ending by .production for production

2) create patches and scripts
symfony deploy:new-patch
symfony deploy:new-script

check task description for details

A patch is a set of scripts located in the folder batch/[patchNumber]/

A patch has three basic environments (common, dev, prod), you have to include the scripts you want to load in the deploy_me.php

Common = common scripts for dev & prod

File deploy_me.php
<?php
$patch_files = array (
  'users.dev.php' => 'comments on file',
  'users.common.php' => 'comments on file',
);
?>

A script could be a php or mysql script. Both are loaded & executed according to your configuration (databases.yml)

3) deploying patches
symfony deploy:patch [patchNumber]

When you deploy a patch, it is basically reading the deploy_me.php and deploying every script in order



4) backup the db
sf deploy:dump

sf deploy:load