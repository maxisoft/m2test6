<?php
use \mageekguy\atoum;

date_default_timezone_set('Europe/Paris'); //avoid weird warning
$path = __DIR__ . DIRECTORY_SEPARATOR . 'build' . DIRECTORY_SEPARATOR . 'atoum';
function my_mkdir($path) {
    if(!is_dir($path)){
        mkdir( $path, 0777, true );
    }
}
my_mkdir($path);
my_mkdir( $path . DIRECTORY_SEPARATOR . 'cover');
my_mkdir( $path . DIRECTORY_SEPARATOR . 'treemap');


$report = $script->addDefaultReport();
$runner->setBootstrapFile(__DIR__ . DIRECTORY_SEPARATOR .  '.bootstrap.atoum.php');
$runner->addTestsFromDirectory(__DIR__ . DIRECTORY_SEPARATOR . 'website'. DIRECTORY_SEPARATOR .
    'tests' . DIRECTORY_SEPARATOR . 'units' . DIRECTORY_SEPARATOR);
//$script->addDefaultReport();


$xunitWriter = new atoum\writers\file('build/atoum.xunit.xml');

$xunitReport = new atoum\reports\asynchronous\xunit();
$xunitReport->addWriter($xunitWriter);

$runner->addReport($xunitReport);

/*
Please replace in next line /path/to/destination/directory by your destination directory path for html files.
*/
$coverageHtmlField = new atoum\report\fields\runner\coverage\html('markattaks', 'build/atoum/cover');

/*
Please replace in next line http://url/of/web/site by the root url of your code coverage web site.
*/
$coverageHtmlField->setRootUrl('http://m2gl.deptinfo-st.univ-fcomte.fr:9080/jenkins/job/MarkAttacks%20(w-o%20Squash%20TA)/PHP_Coverage');

// Treemap (not mandatory)

/*
Please replace in next line /path/to/destination/directory by your destination directory path for html files.
*/
$coverageTreemapField = new atoum\report\fields\runner\coverage\treemap('markattaks', 'build/atoum/treemap');

/*
Please replace in next line http://url/of/treemap by the root url of your treemap web site.
*/
$coverageTreemapField
    ->setTreemapUrl('http://m2gl.deptinfo-st.univ-fcomte.fr:9080/jenkins/job/MarkAttacks%20(w-o%20Squash%20TA)/PHP_Coverage_treemap')
    ->setHtmlReportBaseUrl($coverageHtmlField->getRootUrl())
;

$script
    ->addDefaultReport()
    ->addField($coverageHtmlField)
    ->addField($coverageTreemapField)
;