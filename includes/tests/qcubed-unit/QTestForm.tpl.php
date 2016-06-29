<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title></title>
    </head>
    <body>
	<h1>QCubed Unit Tests</h1>
	<p>
		Welcome to the QCubed Unit Tests. The tests are designed to be run in a particular environment, using the
		examples SQL data, and certain options in the codegen_options.json file. These tests and are run
		automatically on GitHub whenever code is checked in to repository there. To run the tests locally,
		do the following:

		<ol>
		<li>Install the Examples SQL database found at vendor/qcubed/framework/assets/php/examples/ for your database,</li>
		<li>Copy the codegen_options.json file found at vendor/qcubed/framework/travis and put it in your project/includes/configuration directory,</li>
		<li>Generate the code by clicking on the Code Generator link from the QCubed startup page,</li>
		<li>Come back to this page, and click the Run Tests button below.</li>
		</ol>
	</p>
		<?php
		$this->RenderBegin();
		$this->btnRunTests->Render();
		$this->lblRunning->Render();
		$this->RenderEnd();
		?>
    </body>
</html>
