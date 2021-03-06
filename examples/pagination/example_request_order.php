<html>
<head>
<title>Skeleton - Pagination example - basic</title>
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
#$myarray = null;
// create a data object that has the interface needed by the Pager object
$datasource = new Datasource($myarray);

// create a request processor to set pager from GET parameters
$pager = new A_Pagination_Request($datasource);

// set range (number of links on either side of current page) and process core based on request
$pager->setRangeSize(3)->process();

// create a new link helper
$link = new A_Pagination_Helper_Link($pager);

// create a new order link helper
$order = new A_Pagination_Helper_Order($pager, $url, array(''=>'Row', 'title'=>'Title', 'month'=>'Month'));

// retrieve items on current page
$rows = $pager->getItems();

// display the paging links ... should this go in a template?
$links = '';
$links .= $link->first('First');
$links .= $link->previous('Previous');
$links .= $link->range();
$links .= $link->next('Next');
$links .= $link->last('Last');

// display the data
echo "<div>$links</div>";
echo '<table border="1">';
echo '<tr><th>' . $order->render() . '</th></tr>';
$n = 1;
foreach ($rows as $value) {
	echo '<tr>';
	echo '<td>' . $n++ . '.</td><td>' . $value['title'] . '</td><td>' . $value['month'] . '</td>';
	echo '</tr>';
}
echo '</table>';
echo "<div>$links</div>";

?>
<p/>
<a href="../">Return to Examples</a>
</p>

</body>
</html>