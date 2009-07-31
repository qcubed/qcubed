<?php require('../includes/header.inc.php'); ?>
	<?php $this->RenderBegin(); ?>

	<div class="instructions">
		<h1 class="instruction_title">Caching Query Results with QueryArrayCached</h1>
		
		If your application has a component that needs to issue the same query
		multiple times, you should know about <b>QueryArrayCached</b>. This method is
		identical to <b>QueryArray</b>, except that the results of your query will be
		stored in the QCache object, and if there's a match, the query won't be
		sent to the database server - cached results will be used instead.<br /><br />
		
		Why is this cool? Because this can give you amazing performance improvements.
		What if your page has a list of product categories that rarely changes?
		And that list appears on a highly-trafficked page on your site? Just change
		your query calls to use <b>QueryArrayCached</b> method, and the rest will
		be taken care of for you.<br /><br />
		
		In the example below, pay attention to the Number of Queries performed at the very
		bottom - the first time you load this page, two queries; subsequent page reloads
		will not cause any queries at all. That is, of course, until you clear the cache - 
		you can do so by passing in a $blnForceUpdate parameter set to true to
		QueryArrayCached, or by using the <b>QCache::ClearNamespace()</b>
		method - see more in the sample code of this example.<br /><br />
		
		You should also be aware of persistent controls (<a href="../other_controls/persist.php">example</a>)
		- a higher-level abstraction that allows you to cache entire QControls.
	</div>
	
	<p><?php $this->btnReloadPage->Render() ?>&nbsp;&nbsp;&nbsp;
		<?php $this->btnClearCache->Render(); ?></p>
	<p><?php $this->lblMessage->Render(); ?></p>
	
	<?php 
		QApplication::$Database[1]->EnableProfiling();
		$arrObjects = Person::QueryArrayCached(QQ::All());
	?>

<b>Items in the Persons table:</b> <br>
<?php
	foreach($arrObjects as $person) {
		echo $person->FirstName . " " . $person->LastName . "<br>";        
	}
?>
<?php $this->RenderEnd(); ?><br />

<?php QApplication::$Database[1]->OutputProfiling();?>

<?php require('../includes/footer.inc.php'); ?>