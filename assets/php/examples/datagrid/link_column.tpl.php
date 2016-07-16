<?php require('../includes/header.inc.php'); ?>
<?php $this->RenderBegin(); ?>
<style type="text/css">
	table.simple_table td, table.simple_table th {
		padding: 5px;
	}

	table.simple_table {
		border-collapse: collapse;
		border-spacing: 0;
	}

	table.simple_table tbody tr:nth-child(even) {
		background-color: #ffcccc;
	}
	table.simple_table tbody tr:nth-child(odd) {
		background-color: #f6f6f6;
	}

	table.simple_table thead tr {
		background-color: #333;
		color: #ffffff;
	}

	.amount {
		text-align: right;
	}

</style>

<div id="instructions">
	<h1>The QHtmlTableLinkColumn Class</h1>
	<p>The QHtmlTableLinkColumn is a versatile column class that offers a variety of ways to create a column that has a link or button in it.
	  Such links are typically used for navigation, but can be used to popup dialogs or take other actions.</p>

	<p>To create a link column, call CreateLinkColumn like so:</p>
	<code>
		$objTable->CreateLinkColumn($strColumnName, $mixText, $mixDestination, $getVars, $tagAttributes, $blnAsButton);
	</code>
	<p>A description of each parameter follows:</p>
	<ul>
		<li><strong>$strColumnName</strong>: The name you would like to give the column and that appears in the column header.</li>
		<li><strong>$mixText</strong>: The text that will be visible to the user, typically underlined for standard links, or inside
			a button. This can be a static string, or the value can be extracted from the DataSource item as by specifying one of the following:
			<ul>
				<li>A callable callback function e.g. <code>[$this, 'labelRender']</code></li>
				<li>If the data source contains objects, a property in the object e.g. <code>'->Project'</code></li>
				<li>If the data source contains arrays, a string with a key value of the array, surrounded by brackets e.g. <code>'[id]'</code></li>
				<li>If the data source contains arrays, an array of keys to look for e.g. <code>['person']['name']</code></li>
			</ul>
		</li>

		<li><strong>$mixDestination</strong>: If given, the destination or action to take when the link is clicked. As above,
			you can pass a static string, or extract this value using a callback, a property, or a key in an array. The returned value should be the name
			of a file to go to or a URL. You can also pass a QControlProxy and it will be used as the destination.</li>
		<li><strong>$getVars</strong>: If given, one of the following:
			<ul>
				<li>If $mixDestination was a QControlProxy, this will become the action parameter of the proxy. You can use any
					of the above methods to extract this value from the data source. </li>
				<li>If $mixDestination is a string that represents a file or URL, you should pass in a key/value array.
					Each key will become a get variable attached to the $mixDestination URL, and each value will be used
					as above to extract a value from the data source.</li>
			</ul>
		</li>
		<li><strong>$tagAttributes</strong>: If given, a key/value array that becomes attributes for the anchor or button tag.
			This could be used to specify an class, id, or data- attribute. As above, it can be extracted from the data itself.</li>
		<li><strong>$blnAsButton</strong>: If drawing a QControlProxy, you can set this to true to draw it as a button.</li>
	</ul>

	<p>As you can see, quite elaborate links can be created using this column.</p>
	<p>In the example, the first column has a link column that you click on, and the second column a link that you hover over.
		The first column goes to the same page you are viewing, but adds a GET variable to the URL, detects it, and puts
		a message at the bottom of the table depending on what line you clicked on. The second column use a QControLProxy
		to detect when your mouse hovers over a link, and puts up a similar message.</p>
</div>

<div id="demoZone">
	<div style="margin-left: 100px">
		<?php $this->tblProjects->Render(); ?>
		<?php $this->pnlClick->Render(); ?>
	</div>
<div>

<?php $this->RenderEnd(); ?>
<?php require('../includes/footer.inc.php'); ?>