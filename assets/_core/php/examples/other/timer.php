<?php require_once('../qcubed.inc.php'); ?>
<?php require('../includes/header.inc.php'); ?>
		<div class="instructions">
			<h1 class="instruction_title">Measuring Performance using QTimer</h1>

			<p>If you ever need to do light-weight profiling of your QCubed application, 
				you might find the <b>QTimer</b> class useful. It's really simple: you can start
				a named timer by doing <i>QTimer::start('timerName')</i>; you can stop it; 
				you can restart the timer later if you want to. When you're done measuring 
				a section of your code, just call <i>QTimer::getTime('timerName')</i>.
				If you had several timers running, an easy way to dump all the interesting 
				debug info is to call <i>QTimer::varDump().</i></p>
			
			<p>Each of the timers is internall maintained as a QTimer object. If you want to 
				know more about the timers - for example, the number of times the timer was 
				started - you can get the QTimer object instance, and then interrogating that
				instance:<br>
				<div style="padding-left: 50px;">
					<code>
						$objTimer = QTimer::GetTimer('timerName');<br>
						echo $objTimer->CountStarted;
					</code>
				</div>				
				
				<p>Take a look at a sample usage example below by clicking View Source.</p>
				
		</div>
		
		<?php 
			QTimer::start('longCalculation');
			for ($i = 0; $i < 1000000; $i++) {
				// do nothing - just loop a bunch of times
			}
			QTimer::stop('longCalculation');
			echo "Here's how long it took to execute the long calculation: " . QTimer::getTime('longCalculation') . "<br /><br />";
			
			QTimer::start('loadPersons');
			$arrPersons = Person::LoadAll(); 
			QTimer::stop('loadPersons');
			
			// resume the long calculation timer			
			QTimer::start('longCalculation');
			for ($i = 0; $i < 1000000; $i++) {
				// do nothing - just loop a bunch of times
			}
			QTimer::stop('longCalculation');

			echo "<b>Results of QTimer::varDump():</b><br>";
			QTimer::varDump();
		?>

<?php require('../includes/footer.inc.php'); ?>