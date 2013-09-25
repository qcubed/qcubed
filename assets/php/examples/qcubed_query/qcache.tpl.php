<?php require('../includes/header.inc.php'); ?>


<div id="instructions">
	<h1>Caching Query Results with QueryArrayCached</h1>

	<p>If your application has a component that needs to issue the same query
	multiple times, you should know about <strong>QueryArrayCached</strong>. This method is
	identical to <strong>QueryArray</strong>, except that the results of your query will be
	stored in the QCache object, and if there's a match, the query won't be
	sent to the database server - cached results will be used instead.</p>

	<p>Why is this cool? Because this can give you amazing performance improvements.
	What if your page has a list of product categories that rarely changes?
	And that list appears on a highly-trafficked page on your site? Just change
	your query calls to use <strong>QueryArrayCached</strong> method, and the rest will
	be taken care of for you.</p>

	<p>In the example below, pay attention to the Number of Queries performed at the very
	bottom - the first time you load this page, two queries; subsequent page reloads
	will not cause any queries at all. That is, of course, until you clear the cache - 
	you can do so by passing in a $blnForceUpdate parameter set to true to
	QueryArrayCached, or by using the <strong>QCache::ClearNamespace()</strong>
	method - see more in the sample code of this example.</p>

	<p>You should also be aware of persistent controls (<a href="../other_controls/persist.php">example</a>)
	- a higher-level abstraction that allows you to cache entire QControls.</p>
</div>

<div id="demoZone">
	<?php $this->RenderBegin(); ?>
	<p><?php $this->btnReloadPage->Render() ?>&nbsp;<?php $this->btnClearCache->Render(); ?></p>
	<p><?php $this->lblMessage->Render(); ?></p>
<?php 
	QApplication::$Database[1]->EnableProfiling();
	$arrObjects = Person::QueryArrayCached(QQ::All());
?>
	<p><strong>Items in the Persons table:</strong></p>
	<ul>
<?php
	foreach($arrObjects as $person) {
		echo '<li>'.$person->FirstName . " " . $person->LastName . '</li>';        
	}
?>
	</ul>
	<?php $this->RenderEnd(); ?>
	<p><?php QApplication::$Database[1]->OutputProfiling();?></p>
</div>

<?php require('../includes/footer.inc.php'); ?>