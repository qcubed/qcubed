<?php require('../includes/header.inc.php'); ?>
<?php $this->RenderBegin(); ?>

<div id="instructions">
	<h1>Blocking Unwanted Events</h1>

	<p>The nature of Web programming is that events are generally asynchronous. Asynchronous events
    communicate between a browser and server without waiting for previous events to complete. The advantage
    of this is that it can make the browser appear more responsive. The browser will not "freeze" up
        waiting for the response of one event before allowing the user to do another action. The disadvantage
    of this mechanism is that it can be difficult to manage events that depend on the response of previous events,
    since it allows an event to be sent to the server before previous events have completed.

    <p>QCubed's event mechanism queues and synchronizes events, so that the results of each event are available
    to the next event. This allows the browser to be responsive while also allowing events to perform
    as expected in the program.</p>

        <p>However, there are times when you may want an event to be a one-time event, preventing
            all other events from even being added to the queue until a particular event is done
            processing. The classic example of this is a submit button that allows a customer to process a payment for
    a product. If the user accidentally presses the submit button more than once very quickly, multiple
    submits will be sent to the server and the customer might then be charged more than once for the
    same product.</p>

    <p>You can tell an event to block other events by passing "true" to the $blnBlockOtherEvents parameter.
    If the user tries to perform actions while the event is processed, the user's actions will be ignored
    until the first event completes and a response is received by the browser.</p>

    <p>This example here gives you two buttons, a regular button and a blocking button. Click each
    button as quickly as you can to see how many events are processed by the server before the
    server disables the button. To restart the demonstration, reload the page.</p>


</div>

<div id="demoZone">
    <p><?= $this->btnRegular->Render();?></p>
    <p>The regular button was clicked <?= $this->lblRegular->Render() ?> times.</p>
    <p><?= $this->btnBlocking->Render();?></p>
    <p>The blocking button was clicked <?= $this->lblBlocking->Render() ?> times.</p>
</div>

<?php $this->RenderEnd(); ?>
<?php require('../includes/footer.inc.php'); ?>