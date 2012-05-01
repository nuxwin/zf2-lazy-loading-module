<?php

chdir(dirname(__DIR__));
require_once (getenv('ZF2_PATH') ?: 'vendor/ZendFramework/library') . '/Zend/Loader/AutoloaderFactory.php';

Zend\Loader\AutoloaderFactory::factory();
Zend\Loader\AutoloaderFactory::factory(array('Zend\Loader\ClassMapAutoloader'=>array(include 'config/autoload_classmap.php')));

$appConfig = include 'config/application.config.php';

$listenerOptions  = new ZFMLL\Module\Listener\ListenerOptions($appConfig['module_listener_options']);
$defaultListeners = new ZFMLL\Module\Listener\EnvironmentListenerAggregate($listenerOptions);

$moduleManager = new ZFMLL\Module\Manager($appConfig['modules']);
$moduleManager->events()->attachAggregate($defaultListeners);
$moduleManager->loadModules();

$bootstrap   = new Zend\Mvc\Bootstrap($defaultListeners->getConfigListener()->getMergedConfig());
$application = new ZFMLL\Mvc\Application();
$bootstrap->bootstrap($application);
$application->run()->send();