<?php require('../includes/header.inc.php'); ?>
<?php $this->RenderBegin(); ?>

<div id="instructions">
	<h1>Parsing using QLexer and QRegex</h1>

	<p>If you ever need to parse user input, or configuration files, <strong>QLexer</strong>
		is a QCubed component that may save you some time. QLexer allows you to
		create a simple parser using regular expressions.</p>

	<p>In this example, you will see a multiline <strong>QTextBox</strong>
		that accepts user input in the <a
			href="http://en.wikipedia.org/wiki/BBCode">BBCode format</a> - the
		format that's frequently used on bulletin boards to prevent script
		injection and arbitrary HTML input. When you press the button underneath
		the textbox, the BBCode input will be parsed and converted into
		corresponding HTML that's ready to be outputted on the site. What's
		really happening here is validation of user input - attempts to inject
		straight-up HTML tags like <strong>&lt;script&gt;</strong> do nothing -
		they don't pass through. Only a subset of HTML formatting options is
		thus accessible through this BBCode notation.</p>

	<p>Let's inspect how this is done. This example contains a custom class,
		<strong>BBCodeParser</strong>, that abstracts out the parsing logic. In
		the on-click handler for our button, we'll simply instantiate an object
		of that class, and pass the user input to it. We'll then get the HTML
		result of BBCode transormation from that object, and display it the <strong>QLabels</strong>
		of our form.</p>

	<p>Now, inspecting the <strong>BBCodeParser</strong> class: we first
		instantiate a <strong>QLexer</strong>, and then use the <strong>addEntryPattern()</strong>
		and <strong>addExitPattern()</strong> methods to define the regular
		expressions that will outline the tokens in the BBCode. Anything outside
		of the defined pattern list will be passed through <a
			href="http://www.php.net/htmlentities"> htmlentities()</a> and thus
		will be safe: this is how we're accomplishing the "no arbitrary HTML
		allowed" requirement.</p>

	<p>We then call the <strong>Tokenize()</strong> method on the <strong>QLexer</strong>
		object to perform the actual parsing based on the rules (patterns) we
		defined above. The result is an array of tokens; we'll then inspect
		these tokens in the <strong>Render()</strong> method of <strong>BBCodeParser</strong>.
		Each object of the array contains two elements: <strong>$objToken['token']</strong>
		contains the name of the matched token, based on the defined patterns; <strong>$objToken['raw']</strong>
		includes an array of elements that were matched in the input.</p>

	<p>As we loop through the tokens, we are inspecting the name of the
		matched token, and based on that, determine how to display the raw
		matched items. For example, if we are looking at a <strong>start_image</strong>
		token - which would match the <strong>[img]http://foo.com/a.jpg[/img]</strong>
		input - we would want to take the matched contents (<strong>http://foo.com/a.jpg</strong>),
		and place them into an image tag (<strong>&lt;img
			src="http://foo.com/a.jpg" /&gt;</strong>) . That's exactly what the <strong>renderImage()</strong>
		method of the <strong>BBCodeParser</strong> class does.</p>

	<p>Note that nested (recursive) parsing is currently not supported by
		QLexer: in the example below, inputting <strong>[b][i]Hello[/i]
			world[/b]</strong> will not generate the desired result. Adding support
		for recursive parsing to QLexer is something that the QCubed project is
		considering for the next release - if you happen to implement it, please
		do share your code!</p>
</div>

<div id="demoZone">
	<p>Input your BBCode here and click the button. Supported tags: [b],
		[i], [code], [url], [img].</p>
	<p><?php $this->txtInput->Render("Width=400"); ?></p>
	<p><?php $this->btnButton->Render(); ?></p>

	<p><strong>Raw HTML (htmlentities)</strong>:<br />
		<?php $this->lblResultRaw->Render(); ?></p>

	<p><strong>Formatted output</strong>:<br />
		<?php $this->lblResultFormatted->Render(); ?></p>
</div>

<?php $this->RenderEnd(); ?>
<?php require('../includes/footer.inc.php'); ?>