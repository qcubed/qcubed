<?php require('../includes/header.inc.php'); ?>
	<?php $this->RenderBegin(); ?>

	<div id="instructions">
		<h1>The QDataRepeater</h1>

		<p>The main difference between a <strong>QDataGrid</strong> and a <strong>QDataRepeater</strong> is that while a
		<strong>QDataGrid</strong> is in a table
		and has structure to help define how that table should be rendered, a <strong>QDataRepeater</strong>
		lets you define your own structure for each item.  You have a few different ways to do this:
		<ul>
			<li>Specify a template file which will be rendered for each item visible.</li>
			<li>Subclass the QDataRepeater object and override the <strong>GetItemHtml</strong> method, or the
				<strong>GetItemAttributes</strong> and <strong>GetItemInnerHtml</strong> methods.</li>
			<li>Provide rendering callbacks, either with the <strong>ItemHtmlCallback</strong>, or the combination of the
				<strong>ItemAttributesCallback</strong> and  <strong>ItemInnerHtmlCallback</strong> attributes.</li>
		</ul>
		<p>The <strong>QDataRepeaters</strong> each have a <strong>QPaginator</strong> defined with them. Note that
		because the <strong>QPaginator</strong> is rendered by the <i>form</i> (as opposed to the example
		with <strong>QDataGrid</strong> where the <i>datagrid</i> rendered the paginator), we will set the <i>form</i>
		as the paginator's parent.</p>

		<p>Also, note that QDataRepeater allows you to set <i>two</i> paginators: a <strong>Paginator</strong> and a
		<strong>PaginatorAlternate</strong>.  This is to offer listing pages which have the paginator at the
		top and at the bottom of the page.

		<p>The variables <strong>$_FORM</strong>, <strong>$_CONTROL</strong> and <strong>$_ITEM</strong> are pre-defined
		for your template, and are set to the current <strong>QForm</strong>, the <strong>QDataRepeater</strong> object, and the data source item currently
			being drawn.</p>
	</div>

	<div id="demoZone">
		<div style="border:solid 1px gray">
			<?php $this->dtrPersons->Paginator->Render(); ?>

			<?php $this->dtrPersons->Render(); ?>

			<?php $this->dtrPersons->PaginatorAlternate->Render(); ?>
		</div>
		<br />
		<div style="border:solid 1px gray">
			<?php $this->dtrBig->Paginator->Render(); ?>
			<?php $this->dtrBig->Render(); ?>
		</div>

	</div>

	<?php $this->RenderEnd(); ?>
<?php require('../includes/footer.inc.php'); ?>