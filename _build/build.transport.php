<?php

function getSnippetContent($filename) {
    $o = file_get_contents($filename);
    $o = str_replace('<?php','',$o);
    $o = str_replace('?>','',$o);
    $o = trim($o);
    return $o;
}

$mtime = microtime();
$mtime = explode(" ", $mtime);
$mtime = $mtime[1] + $mtime[0];
$tstart = $mtime;
set_time_limit(0);

/* define package */
define('PKG_NAME','Domaineer');
define('PKG_NAME_LOWER',strtolower(PKG_NAME));
define('PKG_VERSION','1.0.1');
define('PKG_RELEASE','pl');

$root = dirname(dirname(__FILE__)).'/';
$sources= array (
    'root' => $root,
    'build' => $root .'_build/'
);
unset($root);

require_once $sources['root'] . 'config.core.php';
require_once MODX_CORE_PATH . 'model/modx/modx.class.php';

$modx= new modX();
$modx->initialize('mgr');
$modx->setLogLevel(modX::LOG_LEVEL_INFO);
$modx->setLogTarget('ECHO'); echo '<pre>'; flush();

$modx->loadClass('transport.modPackageBuilder','',false, true);
$builder = new modPackageBuilder($modx);
$builder->directory = dirname(dirname(__FILE__)).'/_packages/';
$builder->createPackage(PKG_NAME_LOWER,PKG_VERSION,PKG_RELEASE);
$builder->registerNamespace(PKG_NAME_LOWER,false,true,'{core_path}components/'.PKG_NAME_LOWER.'/');
$modx->getService('lexicon','modLexicon');

/* add plugins */
$plugins = $modx->newObject('modPlugin');
$plugins->set('id',1);
$plugins->set('name','Domaineer');
$plugins->set('description','Domaineer allows you to set domain-specific settings.');
$plugins->set('plugincode', getSnippetContent(dirname(dirname(__FILE__)) . '/domaineer.plugin.php'));
$plugins->set('category', 0);

$events = array();
$events['OnWebPageInit']= $modx->newObject('modPluginEvent');
$events['OnWebPageInit']->fromArray(array(
    'event' => 'OnWebPageInit',
    'priority' => 0,
    'propertyset' => 0,
),'',true,true);

if (is_array($events) && !empty($events)) {
    $plugins->addMany($events);
    $modx->log(xPDO::LOG_LEVEL_INFO,'Packaged in '.count($events).' Plugin Events for Domaineer'); flush();
} else {
    $modx->log(xPDO::LOG_LEVEL_ERROR,'Could not find plugin events for Domaineer!');
}
unset($events);

$attributes= array(
    xPDOTransport::UNIQUE_KEY => 'name',
    xPDOTransport::PRESERVE_KEYS => false,
    xPDOTransport::UPDATE_OBJECT => true,
    xPDOTransport::RELATED_OBJECTS => true,
    xPDOTransport::RELATED_OBJECT_ATTRIBUTES => array (
        'PluginEvents' => array(
            xPDOTransport::PRESERVE_KEYS => true,
            xPDOTransport::UPDATE_OBJECT => false,
            xPDOTransport::UNIQUE_KEY => array('pluginid','event'),
        ),
    ),
);
$vehicle = $builder->createVehicle($plugins, $attributes);

$modx->log(modX::LOG_LEVEL_INFO,'Packaged in '.count($plugins).' plugins.'); flush();
unset($plugins,$plugin,$attributes);

$builder->putVehicle($vehicle);

/* now pack in the license file, readme and setup options */
$builder->setPackageAttributes(array(
    'license' => file_get_contents(dirname(dirname(__FILE__)) . '/docs/license.txt'),
    'readme' => file_get_contents(dirname(dirname(__FILE__)) . '/docs/readme.txt'),
    'changelog' => file_get_contents(dirname(dirname(__FILE__)) . '/docs/changelog.txt'),
));
$modx->log(modX::LOG_LEVEL_INFO,'Packaged in package attributes.'); flush();

$modx->log(modX::LOG_LEVEL_INFO,'Packing...'); flush();
$builder->pack();

$mtime= microtime();
$mtime= explode(" ", $mtime);
$mtime= $mtime[1] + $mtime[0];
$tend= $mtime;
$totalTime= ($tend - $tstart);
$totalTime= sprintf("%2.4f s", $totalTime);

$modx->log(modX::LOG_LEVEL_INFO,"\n<br />Package Built.<br />\nExecution time: {$totalTime}\n");

?>
