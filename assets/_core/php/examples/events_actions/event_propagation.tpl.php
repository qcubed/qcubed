<?php require('../includes/header.inc.php'); ?>
	<?php $this->RenderBegin(); ?>

	<div class="instructions">
		<h1 class="instruction_title">Event Propagation</h1>
		Somtimes we want events to stop bubbling up the DOM tree.  To do this we use <b>QStopPropagationAction</b>.<br/><br/>

		Below are two examples.  The first example shows event bubbling.  The second example shows how to stop the bubbling effect using
        <b>QStopPropagationAction</b>.
	</div>

	<style>
        .container {
            padding: 20px;
            background-color: #f3f3f3;
            border: 1px solid #bbb;
        }
    </style>

        <h5>Example with event bubbling</h5>
        <?php $this->objPanel1->Render(); ?>


        <h5>Example without event bubbling</h5>
        <?php $this->objPanel3->Render(); ?>

	<?php $this->RenderEnd(); ?>
<?php require('../includes/footer.inc.php'); ?>