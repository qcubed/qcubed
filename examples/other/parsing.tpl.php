<?php require('../includes/header.inc.php'); ?>
	<?php $this->RenderBegin(); ?>

	<div class="instructions">
		<div class="instruction_title">Parsing using QLexer and QRegex</div>
		If you ever need to parse user input, or configuration files, <b>QLexer</b> is a
		QCubed component that may save you some time. QLexer allows you to create
		a simple parser using regular expressions.<br /><br />
		
		In this example, you will see a multiline <b>QTextBox</b> that accepts
		user input in the <a href="http://en.wikipedia.org/wiki/BBCode">BBCode
		format</a> - the format that's frequently used on bulletin boards to
		prevent script injection and arbitrary HTML input. When you press the
		button underneath the textbox, the BBCode input will be parsed and
		converted into corresponding HTML that's ready to be outputted on the site.
		What's really happening here is validation of user input - attempts to
		inject straight-up HTML tags like <b>&lt;script&gt;</b> do nothing -
		they don't pass through. Only a subset of HTML formatting options is thus
		accessible through this BBCode notation. <br /><br />
		
		Let's inspect how this is done. This example contains a custom class,
		<b>BBCodeParser</b>, that abstracts out the parsing logic. In the
		on-click handler for our button, we'll simply instantiate an object of
		that class, and pass the user input to it. We'll then get the HTML result
		of BBCode transormation from that object, and display it the <b>QLabels</b>
		of our form. <br /><br />
		
		Now, inspecting the <b>BBCodeParser</b> class: we first instantiate a
		<b>QLexer</b>, and then use the <b>addEntryPattern()</b> and
		<b>addExitPattern()</b> methods to define the regular expressions that
		will outline the tokens in the BBCode. Anything outside of the defined
		pattern list will be passed through <a href="http://www.php.net/htmlentities">
		htmlentities()</a> and thus will be safe: this is how we're accomplishing
		the "no arbitrary HTML allowed" requirement. <br /><br />
		
		We then call the <b>Tokenize()</b> method on the <b>QLexer</b> object to
		perform the actual parsing based on the rules (patterns) we defined above.
		The result is an array of tokens; we'll then inspect these tokens in the
		<b>Render()</b> method of <b>BBCodeParser</b>. Each object of the array
		contains two elements: <b>$objToken['token']</b> contains the name of the
		matched token, based on the defined patterns; <b>$objToken['raw']</b>
		includes an array of elements that were matched in the input.<br /><br />
		
		As we loop through the tokens, we are inspecting the name of the matched
		token, and based on that, determine how to display the raw matched items.
		For example, if we are looking at a <b>start_image</b> token - which would match
		the <b>[img]http://foo.com/a.jpg[/img]</b> input - we would want
		to take the matched contents (<b>http://foo.com/a.jpg</b>), and place them
		into an image tag (<b>&lt;img src="http://foo.com/a.jpg" /&gt;</b>)
		. That's exactly what the <b>renderImage()</b> method of the
		<b>BBCodeParser</b> class does.<br /><br />
		
		
		Note that nested (recursive) parsing is currently not supported by QLexer:
		in the example below, inputting <b>[b][i]Hello[/i] world[/b]</b> will not
		generate the desired result. Adding support for recursive parsing to
		QLexer is something that the QCubed project is considering for the next
		release - if you happen to implement it, please do share your code!
		
	</div>
	</div>

	<p>Input your BBCode here and click the button. Supported tags: [b], [i], [code], [url], [img].</p>
	<p><?php $this->txtInput->Render("Width=400"); ?></p>
	<p><?php $this->btnButton->Render(); ?></p>
	
	<p><b>Raw HTML (htmlentities)</b>:<br />
		<?php $this->lblResultRaw->Render(); ?>
	</p>

	<p><b>Formatted output</b>:<br />
		<?php $this->lblResultFormatted->Render(); ?>
	</p>
	

	<?php $this->RenderEnd(); ?>
<?php require('../includes/footer.inc.php'); ?>