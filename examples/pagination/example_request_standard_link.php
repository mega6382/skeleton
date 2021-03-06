<html>
<head>
<title>Skeleton - Pagination example - Standard Request Link</title>
</head>
<body>
<?php
include 'config.php';
include dirname(__FILE__) . '/../../A/autoload.php';

// initialize an array for testing
for ($i=0; $i<=750; ++$i) {
	$myarray[$i]['title'] = 'This is row ' . $i;
	$myarray[$i]['month'] = date ('F', time() + ($i * 60 * 60 * 24 * 30));
}

// create a data object that has the interface needed by the Pager object
$datasource = new Datasource($myarray);

// create a request processor to set pager from GET parameters
$pager = new A_Pagination_Request($datasource);

// set range (number of links on either side of current page) and process core based on request
$pager->setRangeSize(3)->process();

// create a new standard view
$view = new A_Pagination_View_Standard($pager);

// retrieve items on current page
$rows = $pager->getItems();

// Set up links in $link array
$links = '';
$links .= $view->link()->first('First');
$links .= $view->link()->previous('Previous');
$links .= $view->link()->range();
$links .= $view->link()->next('Next');
$links .= $view->link()->last('Last');

// display the paging links
echo "<div>$links</div>";

// display the data
echo '<table border="1">';
echo '<tr><th>' . $view->link()->order('', 'Row') . '</th><th>' . $view->link()->order('title', 'Title') . '</th><th>' . $view->link()->order('month', 'Month') . '</th></tr>';
$n = 1;
foreach ($rows as $value) {
	echo '<tr>';
	echo '<td>' . $n++ . '.</td><td>' . $value['title'] . '</td><td>' . $value['month'] . '</td>';
	echo '</tr>';
}
echo '</table>';

// display the paging links
echo "<div>$links</div>";

?>
<p/>
<a href="../">Return to Examples</a>
</p>

</body>
</html>