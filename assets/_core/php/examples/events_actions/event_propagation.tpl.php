<?php require('../includes/header.inc.php'); ?>
    <?php $this->RenderBegin(); ?>

    <div class="instructions">
        <h1 class="instruction_title">Event Propagation</h1>
        Whenever an event fires on a control inside an HTML document, it "bubbles" up to the parents - to allow the
	    parents to react to that event as well. This is a standard feature of all modern browsers; read more about it
	    on <a href="http://en.wikipedia.org/wiki/DOM_events">Wikipedia</a>.<br><br>

	    In QCubed, we sometimes may want events to stop bubbling up the DOM tree. To do this, we use <b>QStopPropagationAction</b>.<br/><br/>

        Below are two examples. In each, you'll see a panel that responds to click events. In the first example, both the inside panel
	    and the outside panel capture the click inside the innermost panel 2 - event bubbling in action.<br><br>

	    The second example shows how to stop the bubbling effect using <b>QStopPropagationAction</b>. When you click inside the innermost
	    panel 4, only panel 4 will respond to the click, and the click handler will never be called for panel 3. 
    </div>

    <style>
        .container {
            padding: 20px;
            background-color: #f3f3f3;
            border: 1px solid #bbb;
	        margin: 5px;
        }

	    .insidePanel {
		    background-color: #ddd;
	    }
    </style>

    <h5>Example with event bubbling (default)</h5>
    <?php $this->objPanel1->Render(); ?>

	<br>
    <h5>Example without event bubbling</h5>
    <?php $this->objPanel3->Render(); ?>

    <?php $this->RenderEnd(); ?>
<?php require('../includes/footer.inc.php'); ?>