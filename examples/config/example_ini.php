<h1>A_Config_Ini</h1>
<p>This example shows using error handler or exceptions. Edit file names to show loading of INI files.</p>
<?php
include 'config.php';
include '../../A/autoload.php';

$config = new A_Config_Ini('example1.ini', '');
$data = $config->loadFile();
if ($data === false) {
	echo "Error found: loading file<br/>";
}
dump($data);

$config = new A_Config_Ini('example1.ini', '', new Exception('Ini file error.'));
try {
	$data = $config->loadFile();
} catch (Exception $e) {
    echo 'Caught exception: ',  $e->getMessage(), '<br/>';
}
dump($data);
