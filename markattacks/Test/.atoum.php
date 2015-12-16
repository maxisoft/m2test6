<?php

$xunit = new atoum\reports\asynchronous\xunit();
$clover = new atoum\reports\asynchronous\clover();
$runner->addReport($xunit);
$runner->addReport($clover);

$report = $script->addDefaultReport();
$runner->setBootstrapFile(__DIR__ . DIRECTORY_SEPARATOR .  '.bootstrap.atoum.php');
$runner->addTestsFromDirectory(__DIR__ . DIRECTORY_SEPARATOR);

$writer = new atoum\writers\file('build/logs/xunit.xml');
$writer1 = new atoum\writers\file('../atoum.xunit.xml');
$writer2 = new atoum\writers\file('../atoum.coverage.xml');
$writer3 = new atoum\writers\file('build/logs/clover.xml');
$xunit->addWriter($writer);
$xunit->addWriter($writer1);
$clover->addWriter($writer2);
$clover->addWriter($writer3);
?>
