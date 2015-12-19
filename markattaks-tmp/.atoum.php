<?php

date_default_timezone_set('Europe/Paris'); //avoid weird warning

$report = $script->addDefaultReport();
$runner->setBootstrapFile(__DIR__ . DIRECTORY_SEPARATOR .  '.bootstrap.atoum.php');
$runner->addTestsFromDirectory(__DIR__ . DIRECTORY_SEPARATOR . 'website'. DIRECTORY_SEPARATOR);
$script->addDefaultReport();


$xunitWriter = new atoum\writers\file('build/atoum.xunit.xml');

$xunitReport = new atoum\reports\asynchronous\xunit();
$xunitReport->addWriter($xunitWriter);

$runner->addReport($xunitReport);
