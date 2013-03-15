<?php require('../includes/header.inc.php'); ?>
<style>
#dtgPersons tr.selectedStyle, #dtgPersonsDelegated tr.selectedStyle {
	background-color: #ffaacc !important;
	cursor: pointer;
}

div.col {
   display: inline-block;
   width: 45%;
   padding:1%;
   vertical-align:top;
}

div.table, div.code {
	max-height: 400px;
	overflow: auto;
	width: auto;

}

#dtgPersons tr.newperson, #dtgPersonsDelegated tr.newperson {
	background-color: greenyellow;
}
</style>
	<?php $this->RenderBegin(); ?>

	<div class="instructions">
		<h1 class="instruction_title">Event Delegation with QOnEvent</h1>
		This example shows how to use event delegation with <b>QOnEvent</b>.<br>
		
		Event delegation is the process of binding actions to events of child elements
		to the parent element. This is useful for the following reasons:
		<ul>
			<li> It can reduce the produced JavaScript code to a minimum,</li>
			
			<li> Binding to many elements is more expensive than using the native 
				event bubbling (which is used for the event delegation) </li>
			<li> You do not have to bind actions to dynamically inserted child elements
				That means that the delegation also works for child elements that get
				inserted into the parent element after the event/action was bound (delegated)
				to the parent.
			</li>
		</ul>
		<p>
			The following code renders 2 QDataGrids that show a hover effect and respond to
			to mouse clicks anywhere in the row. Additionally clicks on
			delete buttons are handled
		</p>
		<p>
		The first data grid called "dtgPersons" adds a row action to every
		row and uses a <b>QControlProxy</b> for handling the delete button clicks, 
		while the second grid "dtgPersonsDelegated" uses the <b>QOnEvent</b>
		to delegate mouseover/mouseout and click events on tr elements and on the remove
		button to the datagrid.
		</p>
		
		<p>
		To get a feeling how incredibly much JavaScript code is sent to
		the client (or how much you can save using event delegation) for such a simple task, the returned JavaScript code is shown
		right next to the table it was created for.
		</p>
		
		<p>
			Try clicking the add buttons of both data grids. You will notice, that
			all the hover effects and delete button clicking on dynamically added
			rows works for the data grid using delegation. But for the data grid
			not using delegation it is not working.
		</p>
	
		<p>
		The <b>QOnEvent</b>'s first parameter defines the event we want
		to respond to (i.e.: "click"). The second parameter is a
		<a href="#" onclick="window.open('http://api.jquery.com/category/selectors/','_newtab')">JQuery selector</a>
		to filter events delegated from child elements to the parent where the
		event is bound (Actually the event sources are filtered).
		In this example the selectors are tr elements, the rows of the data grid
		and the delete buttons.
		</p>
		
		<p>
		Standard <b>QEvent</b>s have event delegation too. Pass the selector as the
		third parameter to any QEvent.
		</p>
		
		
		
	</div>
<div>
	<div class="col">
		<h2>The datagrid <b>without</b> event delegation</h2>
		<p><?php $this->btnAddNewPerson->Render();?></p>
		<div class="table">
			<?php $this->dtgPersons->Render(); ?>
		</div>
	</div>
	<div class="col">
		<h2>Resulting javascript sent to the client</h2>
		<p>About <b>900</b> lines of JavaScript code!</p>
		<div class="code">
								$j("#delete_1").on("click", function(event, ui){
									qc.pA('ExampleForm', 'c3', 'QClickEvent#a1', '1', ''); event.preventDefault(); event.stopPropagation();
									}); $j("#dtgPersonsrow0").on("mouseover", function(event, ui){
								qc.getC('dtgPersonsrow0').className = 'selectedStyle';
								});
								$j("#dtgPersonsrow0").on("mouseout", function(event, ui){
								qc.getC('dtgPersonsrow0').className = '';
								});
								$j("#dtgPersonsrow0").on("click", function(event, ui){
								qc.pA('ExampleForm', 'dtgPersonsrow0', 'QClickEvent#a2', '1', '');
								}); $j("#delete_2").on("click", function(event, ui){
									qc.pA('ExampleForm', 'c3', 'QClickEvent#a1', '2', ''); event.preventDefault(); event.stopPropagation();
									}); $j("#dtgPersonsrow1").on("mouseover", function(event, ui){
								qc.getC('dtgPersonsrow1').className = 'selectedStyle';
								});
								$j("#dtgPersonsrow1").on("mouseout", function(event, ui){
								qc.getC('dtgPersonsrow1').className = '';
								});
								$j("#dtgPersonsrow1").on("click", function(event, ui){
								qc.pA('ExampleForm', 'dtgPersonsrow1', 'QClickEvent#a3', '2', '');
								}); $j("#delete_3").on("click", function(event, ui){
									qc.pA('ExampleForm', 'c3', 'QClickEvent#a1', '3', ''); event.preventDefault(); event.stopPropagation();
									}); $j("#dtgPersonsrow2").on("mouseover", function(event, ui){
								qc.getC('dtgPersonsrow2').className = 'selectedStyle';
								});
								$j("#dtgPersonsrow2").on("mouseout", function(event, ui){
								qc.getC('dtgPersonsrow2').className = '';
								});
								$j("#dtgPersonsrow2").on("click", function(event, ui){
								qc.pA('ExampleForm', 'dtgPersonsrow2', 'QClickEvent#a4', '3', '');
								}); $j("#delete_4").on("click", function(event, ui){
									qc.pA('ExampleForm', 'c3', 'QClickEvent#a1', '4', ''); event.preventDefault(); event.stopPropagation();
									}); $j("#dtgPersonsrow3").on("mouseover", function(event, ui){
								qc.getC('dtgPersonsrow3').className = 'selectedStyle';
								});
								$j("#dtgPersonsrow3").on("mouseout", function(event, ui){
								qc.getC('dtgPersonsrow3').className = '';
								});
								$j("#dtgPersonsrow3").on("click", function(event, ui){
								qc.pA('ExampleForm', 'dtgPersonsrow3', 'QClickEvent#a5', '4', '');
								}); $j("#delete_5").on("click", function(event, ui){
									qc.pA('ExampleForm', 'c3', 'QClickEvent#a1', '5', ''); event.preventDefault(); event.stopPropagation();
									}); $j("#dtgPersonsrow4").on("mouseover", function(event, ui){
								qc.getC('dtgPersonsrow4').className = 'selectedStyle';
								});
								$j("#dtgPersonsrow4").on("mouseout", function(event, ui){
								qc.getC('dtgPersonsrow4').className = '';
								});
								$j("#dtgPersonsrow4").on("click", function(event, ui){
								qc.pA('ExampleForm', 'dtgPersonsrow4', 'QClickEvent#a6', '5', '');
								}); $j("#delete_6").on("click", function(event, ui){
									qc.pA('ExampleForm', 'c3', 'QClickEvent#a1', '6', ''); event.preventDefault(); event.stopPropagation();
									}); $j("#dtgPersonsrow5").on("mouseover", function(event, ui){
								qc.getC('dtgPersonsrow5').className = 'selectedStyle';
								});
								$j("#dtgPersonsrow5").on("mouseout", function(event, ui){
								qc.getC('dtgPersonsrow5').className = '';
								});
								$j("#dtgPersonsrow5").on("click", function(event, ui){
								qc.pA('ExampleForm', 'dtgPersonsrow5', 'QClickEvent#a7', '6', '');
								}); $j("#delete_7").on("click", function(event, ui){
									qc.pA('ExampleForm', 'c3', 'QClickEvent#a1', '7', ''); event.preventDefault(); event.stopPropagation();
									}); $j("#dtgPersonsrow6").on("mouseover", function(event, ui){
								qc.getC('dtgPersonsrow6').className = 'selectedStyle';
								});
								$j("#dtgPersonsrow6").on("mouseout", function(event, ui){
								qc.getC('dtgPersonsrow6').className = '';
								});
								$j("#dtgPersonsrow6").on("click", function(event, ui){
								qc.pA('ExampleForm', 'dtgPersonsrow6', 'QClickEvent#a8', '7', '');
								}); $j("#delete_8").on("click", function(event, ui){
									qc.pA('ExampleForm', 'c3', 'QClickEvent#a1', '8', ''); event.preventDefault(); event.stopPropagation();
									}); $j("#dtgPersonsrow7").on("mouseover", function(event, ui){
								qc.getC('dtgPersonsrow7').className = 'selectedStyle';
								});
								$j("#dtgPersonsrow7").on("mouseout", function(event, ui){
								qc.getC('dtgPersonsrow7').className = '';
								});
								$j("#dtgPersonsrow7").on("click", function(event, ui){
								qc.pA('ExampleForm', 'dtgPersonsrow7', 'QClickEvent#a9', '8', '');
								}); $j("#delete_9").on("click", function(event, ui){
									qc.pA('ExampleForm', 'c3', 'QClickEvent#a1', '9', ''); event.preventDefault(); event.stopPropagation();
									}); $j("#dtgPersonsrow8").on("mouseover", function(event, ui){
								qc.getC('dtgPersonsrow8').className = 'selectedStyle';
								});
								$j("#dtgPersonsrow8").on("mouseout", function(event, ui){
								qc.getC('dtgPersonsrow8').className = '';
								});
								$j("#dtgPersonsrow8").on("click", function(event, ui){
								qc.pA('ExampleForm', 'dtgPersonsrow8', 'QClickEvent#a10', '9', '');
								}); $j("#delete_10").on("click", function(event, ui){
									qc.pA('ExampleForm', 'c3', 'QClickEvent#a1', '10', ''); event.preventDefault(); event.stopPropagation();
									}); $j("#dtgPersonsrow9").on("mouseover", function(event, ui){
								qc.getC('dtgPersonsrow9').className = 'selectedStyle';
								});
								$j("#dtgPersonsrow9").on("mouseout", function(event, ui){
								qc.getC('dtgPersonsrow9').className = '';
								});
								$j("#dtgPersonsrow9").on("click", function(event, ui){
								qc.pA('ExampleForm', 'dtgPersonsrow9', 'QClickEvent#a11', '10', '');
								}); $j("#delete_11").on("click", function(event, ui){
									qc.pA('ExampleForm', 'c3', 'QClickEvent#a1', '11', ''); event.preventDefault(); event.stopPropagation();
									}); $j("#dtgPersonsrow10").on("mouseover", function(event, ui){
								qc.getC('dtgPersonsrow10').className = 'selectedStyle';
								});
								$j("#dtgPersonsrow10").on("mouseout", function(event, ui){
								qc.getC('dtgPersonsrow10').className = '';
								});
								$j("#dtgPersonsrow10").on("click", function(event, ui){
								qc.pA('ExampleForm', 'dtgPersonsrow10', 'QClickEvent#a12', '11', '');
								}); $j("#delete_12").on("click", function(event, ui){
									qc.pA('ExampleForm', 'c3', 'QClickEvent#a1', '12', ''); event.preventDefault(); event.stopPropagation();
									}); $j("#dtgPersonsrow11").on("mouseover", function(event, ui){
								qc.getC('dtgPersonsrow11').className = 'selectedStyle';
								});
								$j("#dtgPersonsrow11").on("mouseout", function(event, ui){
								qc.getC('dtgPersonsrow11').className = '';
								});
								$j("#dtgPersonsrow11").on("click", function(event, ui){
								qc.pA('ExampleForm', 'dtgPersonsrow11', 'QClickEvent#a13', '12', '');
								}); $j("#delete_13").on("click", function(event, ui){
									qc.pA('ExampleForm', 'c3', 'QClickEvent#a1', '13', ''); event.preventDefault(); event.stopPropagation();
									}); $j("#dtgPersonsrow12").on("mouseover", function(event, ui){
								qc.getC('dtgPersonsrow12').className = 'selectedStyle';
								});
								$j("#dtgPersonsrow12").on("mouseout", function(event, ui){
								qc.getC('dtgPersonsrow12').className = '';
								});
								$j("#dtgPersonsrow12").on("click", function(event, ui){
								qc.pA('ExampleForm', 'dtgPersonsrow12', 'QClickEvent#a14', '13', '');
								}); $j("#delete_14").on("click", function(event, ui){
									qc.pA('ExampleForm', 'c3', 'QClickEvent#a1', '14', ''); event.preventDefault(); event.stopPropagation();
									}); $j("#dtgPersonsrow13").on("mouseover", function(event, ui){
								qc.getC('dtgPersonsrow13').className = 'selectedStyle';
								});
								$j("#dtgPersonsrow13").on("mouseout", function(event, ui){
								qc.getC('dtgPersonsrow13').className = '';
								});
								$j("#dtgPersonsrow13").on("click", function(event, ui){
								qc.pA('ExampleForm', 'dtgPersonsrow13', 'QClickEvent#a15', '14', '');
								}); $j("#delete_15").on("click", function(event, ui){
									qc.pA('ExampleForm', 'c3', 'QClickEvent#a1', '15', ''); event.preventDefault(); event.stopPropagation();
									}); $j("#dtgPersonsrow14").on("mouseover", function(event, ui){
								qc.getC('dtgPersonsrow14').className = 'selectedStyle';
								});
								$j("#dtgPersonsrow14").on("mouseout", function(event, ui){
								qc.getC('dtgPersonsrow14').className = '';
								});
								$j("#dtgPersonsrow14").on("click", function(event, ui){
								qc.pA('ExampleForm', 'dtgPersonsrow14', 'QClickEvent#a16', '15', '');
								}); $j("#delete_16").on("click", function(event, ui){
									qc.pA('ExampleForm', 'c3', 'QClickEvent#a1', '16', ''); event.preventDefault(); event.stopPropagation();
									}); $j("#dtgPersonsrow15").on("mouseover", function(event, ui){
								qc.getC('dtgPersonsrow15').className = 'selectedStyle';
								});
								$j("#dtgPersonsrow15").on("mouseout", function(event, ui){
								qc.getC('dtgPersonsrow15').className = '';
								});
								$j("#dtgPersonsrow15").on("click", function(event, ui){
								qc.pA('ExampleForm', 'dtgPersonsrow15', 'QClickEvent#a17', '16', '');
								}); $j("#delete_17").on("click", function(event, ui){
									qc.pA('ExampleForm', 'c3', 'QClickEvent#a1', '17', ''); event.preventDefault(); event.stopPropagation();
									}); $j("#dtgPersonsrow16").on("mouseover", function(event, ui){
								qc.getC('dtgPersonsrow16').className = 'selectedStyle';
								});
								$j("#dtgPersonsrow16").on("mouseout", function(event, ui){
								qc.getC('dtgPersonsrow16').className = '';
								});
								$j("#dtgPersonsrow16").on("click", function(event, ui){
								qc.pA('ExampleForm', 'dtgPersonsrow16', 'QClickEvent#a18', '17', '');
								}); $j("#delete_18").on("click", function(event, ui){
									qc.pA('ExampleForm', 'c3', 'QClickEvent#a1', '18', ''); event.preventDefault(); event.stopPropagation();
									}); $j("#dtgPersonsrow17").on("mouseover", function(event, ui){
								qc.getC('dtgPersonsrow17').className = 'selectedStyle';
								});
								$j("#dtgPersonsrow17").on("mouseout", function(event, ui){
								qc.getC('dtgPersonsrow17').className = '';
								});
								$j("#dtgPersonsrow17").on("click", function(event, ui){
								qc.pA('ExampleForm', 'dtgPersonsrow17', 'QClickEvent#a19', '18', '');
								}); $j("#delete_19").on("click", function(event, ui){
									qc.pA('ExampleForm', 'c3', 'QClickEvent#a1', '19', ''); event.preventDefault(); event.stopPropagation();
									}); $j("#dtgPersonsrow18").on("mouseover", function(event, ui){
								qc.getC('dtgPersonsrow18').className = 'selectedStyle';
								});
								$j("#dtgPersonsrow18").on("mouseout", function(event, ui){
								qc.getC('dtgPersonsrow18').className = '';
								});
								$j("#dtgPersonsrow18").on("click", function(event, ui){
								qc.pA('ExampleForm', 'dtgPersonsrow18', 'QClickEvent#a20', '19', '');
								}); $j("#delete_20").on("click", function(event, ui){
									qc.pA('ExampleForm', 'c3', 'QClickEvent#a1', '20', ''); event.preventDefault(); event.stopPropagation();
									}); $j("#dtgPersonsrow19").on("mouseover", function(event, ui){
								qc.getC('dtgPersonsrow19').className = 'selectedStyle';
								});
								$j("#dtgPersonsrow19").on("mouseout", function(event, ui){
								qc.getC('dtgPersonsrow19').className = '';
								});
								$j("#dtgPersonsrow19").on("click", function(event, ui){
								qc.pA('ExampleForm', 'dtgPersonsrow19', 'QClickEvent#a21', '20', '');
								}); $j("#delete_21").on("click", function(event, ui){
									qc.pA('ExampleForm', 'c3', 'QClickEvent#a1', '21', ''); event.preventDefault(); event.stopPropagation();
									}); $j("#dtgPersonsrow20").on("mouseover", function(event, ui){
								qc.getC('dtgPersonsrow20').className = 'selectedStyle';
								});
								$j("#dtgPersonsrow20").on("mouseout", function(event, ui){
								qc.getC('dtgPersonsrow20').className = '';
								});
								$j("#dtgPersonsrow20").on("click", function(event, ui){
								qc.pA('ExampleForm', 'dtgPersonsrow20', 'QClickEvent#a22', '21', '');
								}); $j("#delete_22").on("click", function(event, ui){
									qc.pA('ExampleForm', 'c3', 'QClickEvent#a1', '22', ''); event.preventDefault(); event.stopPropagation();
									}); $j("#dtgPersonsrow21").on("mouseover", function(event, ui){
								qc.getC('dtgPersonsrow21').className = 'selectedStyle';
								});
								$j("#dtgPersonsrow21").on("mouseout", function(event, ui){
								qc.getC('dtgPersonsrow21').className = '';
								});
								$j("#dtgPersonsrow21").on("click", function(event, ui){
								qc.pA('ExampleForm', 'dtgPersonsrow21', 'QClickEvent#a23', '22', '');
								}); $j("#delete_23").on("click", function(event, ui){
									qc.pA('ExampleForm', 'c3', 'QClickEvent#a1', '23', ''); event.preventDefault(); event.stopPropagation();
									}); $j("#dtgPersonsrow22").on("mouseover", function(event, ui){
								qc.getC('dtgPersonsrow22').className = 'selectedStyle';
								});
								$j("#dtgPersonsrow22").on("mouseout", function(event, ui){
								qc.getC('dtgPersonsrow22').className = '';
								});
								$j("#dtgPersonsrow22").on("click", function(event, ui){
								qc.pA('ExampleForm', 'dtgPersonsrow22', 'QClickEvent#a24', '23', '');
								}); $j("#delete_24").on("click", function(event, ui){
									qc.pA('ExampleForm', 'c3', 'QClickEvent#a1', '24', ''); event.preventDefault(); event.stopPropagation();
									}); $j("#dtgPersonsrow23").on("mouseover", function(event, ui){
								qc.getC('dtgPersonsrow23').className = 'selectedStyle';
								});
								$j("#dtgPersonsrow23").on("mouseout", function(event, ui){
								qc.getC('dtgPersonsrow23').className = '';
								});
								$j("#dtgPersonsrow23").on("click", function(event, ui){
								qc.pA('ExampleForm', 'dtgPersonsrow23', 'QClickEvent#a25', '24', '');
								}); $j("#delete_25").on("click", function(event, ui){
									qc.pA('ExampleForm', 'c3', 'QClickEvent#a1', '25', ''); event.preventDefault(); event.stopPropagation();
									}); $j("#dtgPersonsrow24").on("mouseover", function(event, ui){
								qc.getC('dtgPersonsrow24').className = 'selectedStyle';
								});
								$j("#dtgPersonsrow24").on("mouseout", function(event, ui){
								qc.getC('dtgPersonsrow24').className = '';
								});
								$j("#dtgPersonsrow24").on("click", function(event, ui){
								qc.pA('ExampleForm', 'dtgPersonsrow24', 'QClickEvent#a26', '25', '');
								}); $j("#delete_26").on("click", function(event, ui){
									qc.pA('ExampleForm', 'c3', 'QClickEvent#a1', '26', ''); event.preventDefault(); event.stopPropagation();
									}); $j("#dtgPersonsrow25").on("mouseover", function(event, ui){
								qc.getC('dtgPersonsrow25').className = 'selectedStyle';
								});
								$j("#dtgPersonsrow25").on("mouseout", function(event, ui){
								qc.getC('dtgPersonsrow25').className = '';
								});
								$j("#dtgPersonsrow25").on("click", function(event, ui){
								qc.pA('ExampleForm', 'dtgPersonsrow25', 'QClickEvent#a27', '26', '');
								}); $j("#delete_27").on("click", function(event, ui){
									qc.pA('ExampleForm', 'c3', 'QClickEvent#a1', '27', ''); event.preventDefault(); event.stopPropagation();
									}); $j("#dtgPersonsrow26").on("mouseover", function(event, ui){
								qc.getC('dtgPersonsrow26').className = 'selectedStyle';
								});
								$j("#dtgPersonsrow26").on("mouseout", function(event, ui){
								qc.getC('dtgPersonsrow26').className = '';
								});
								$j("#dtgPersonsrow26").on("click", function(event, ui){
								qc.pA('ExampleForm', 'dtgPersonsrow26', 'QClickEvent#a28', '27', '');
								}); $j("#delete_28").on("click", function(event, ui){
									qc.pA('ExampleForm', 'c3', 'QClickEvent#a1', '28', ''); event.preventDefault(); event.stopPropagation();
									}); $j("#dtgPersonsrow27").on("mouseover", function(event, ui){
								qc.getC('dtgPersonsrow27').className = 'selectedStyle';
								});
								$j("#dtgPersonsrow27").on("mouseout", function(event, ui){
								qc.getC('dtgPersonsrow27').className = '';
								});
								$j("#dtgPersonsrow27").on("click", function(event, ui){
								qc.pA('ExampleForm', 'dtgPersonsrow27', 'QClickEvent#a29', '28', '');
								}); $j("#delete_29").on("click", function(event, ui){
									qc.pA('ExampleForm', 'c3', 'QClickEvent#a1', '29', ''); event.preventDefault(); event.stopPropagation();
									}); $j("#dtgPersonsrow28").on("mouseover", function(event, ui){
								qc.getC('dtgPersonsrow28').className = 'selectedStyle';
								});
								$j("#dtgPersonsrow28").on("mouseout", function(event, ui){
								qc.getC('dtgPersonsrow28').className = '';
								});
								$j("#dtgPersonsrow28").on("click", function(event, ui){
								qc.pA('ExampleForm', 'dtgPersonsrow28', 'QClickEvent#a30', '29', '');
								}); $j("#delete_30").on("click", function(event, ui){
									qc.pA('ExampleForm', 'c3', 'QClickEvent#a1', '30', ''); event.preventDefault(); event.stopPropagation();
									}); $j("#dtgPersonsrow29").on("mouseover", function(event, ui){
								qc.getC('dtgPersonsrow29').className = 'selectedStyle';
								});
								$j("#dtgPersonsrow29").on("mouseout", function(event, ui){
								qc.getC('dtgPersonsrow29').className = '';
								});
								$j("#dtgPersonsrow29").on("click", function(event, ui){
								qc.pA('ExampleForm', 'dtgPersonsrow29', 'QClickEvent#a31', '30', '');
								}); $j("#delete_31").on("click", function(event, ui){
									qc.pA('ExampleForm', 'c3', 'QClickEvent#a1', '31', ''); event.preventDefault(); event.stopPropagation();
									}); $j("#dtgPersonsrow30").on("mouseover", function(event, ui){
								qc.getC('dtgPersonsrow30').className = 'selectedStyle';
								});
								$j("#dtgPersonsrow30").on("mouseout", function(event, ui){
								qc.getC('dtgPersonsrow30').className = '';
								});
								$j("#dtgPersonsrow30").on("click", function(event, ui){
								qc.pA('ExampleForm', 'dtgPersonsrow30', 'QClickEvent#a32', '31', '');
								}); $j("#delete_32").on("click", function(event, ui){
									qc.pA('ExampleForm', 'c3', 'QClickEvent#a1', '32', ''); event.preventDefault(); event.stopPropagation();
									}); $j("#dtgPersonsrow31").on("mouseover", function(event, ui){
								qc.getC('dtgPersonsrow31').className = 'selectedStyle';
								});
								$j("#dtgPersonsrow31").on("mouseout", function(event, ui){
								qc.getC('dtgPersonsrow31').className = '';
								});
								$j("#dtgPersonsrow31").on("click", function(event, ui){
								qc.pA('ExampleForm', 'dtgPersonsrow31', 'QClickEvent#a33', '32', '');
								}); $j("#delete_33").on("click", function(event, ui){
									qc.pA('ExampleForm', 'c3', 'QClickEvent#a1', '33', ''); event.preventDefault(); event.stopPropagation();
									}); $j("#dtgPersonsrow32").on("mouseover", function(event, ui){
								qc.getC('dtgPersonsrow32').className = 'selectedStyle';
								});
								$j("#dtgPersonsrow32").on("mouseout", function(event, ui){
								qc.getC('dtgPersonsrow32').className = '';
								});
								$j("#dtgPersonsrow32").on("click", function(event, ui){
								qc.pA('ExampleForm', 'dtgPersonsrow32', 'QClickEvent#a34', '33', '');
								}); $j("#delete_34").on("click", function(event, ui){
									qc.pA('ExampleForm', 'c3', 'QClickEvent#a1', '34', ''); event.preventDefault(); event.stopPropagation();
									}); $j("#dtgPersonsrow33").on("mouseover", function(event, ui){
								qc.getC('dtgPersonsrow33').className = 'selectedStyle';
								});
								$j("#dtgPersonsrow33").on("mouseout", function(event, ui){
								qc.getC('dtgPersonsrow33').className = '';
								});
								$j("#dtgPersonsrow33").on("click", function(event, ui){
								qc.pA('ExampleForm', 'dtgPersonsrow33', 'QClickEvent#a35', '34', '');
								}); $j("#delete_35").on("click", function(event, ui){
									qc.pA('ExampleForm', 'c3', 'QClickEvent#a1', '35', ''); event.preventDefault(); event.stopPropagation();
									}); $j("#dtgPersonsrow34").on("mouseover", function(event, ui){
								qc.getC('dtgPersonsrow34').className = 'selectedStyle';
								});
								$j("#dtgPersonsrow34").on("mouseout", function(event, ui){
								qc.getC('dtgPersonsrow34').className = '';
								});
								$j("#dtgPersonsrow34").on("click", function(event, ui){
								qc.pA('ExampleForm', 'dtgPersonsrow34', 'QClickEvent#a36', '35', '');
								}); $j("#delete_36").on("click", function(event, ui){
									qc.pA('ExampleForm', 'c3', 'QClickEvent#a1', '36', ''); event.preventDefault(); event.stopPropagation();
									}); $j("#dtgPersonsrow35").on("mouseover", function(event, ui){
								qc.getC('dtgPersonsrow35').className = 'selectedStyle';
								});
								$j("#dtgPersonsrow35").on("mouseout", function(event, ui){
								qc.getC('dtgPersonsrow35').className = '';
								});
								$j("#dtgPersonsrow35").on("click", function(event, ui){
								qc.pA('ExampleForm', 'dtgPersonsrow35', 'QClickEvent#a37', '36', '');
								}); $j("#delete_37").on("click", function(event, ui){
									qc.pA('ExampleForm', 'c3', 'QClickEvent#a1', '37', ''); event.preventDefault(); event.stopPropagation();
									}); $j("#dtgPersonsrow36").on("mouseover", function(event, ui){
								qc.getC('dtgPersonsrow36').className = 'selectedStyle';
								});
								$j("#dtgPersonsrow36").on("mouseout", function(event, ui){
								qc.getC('dtgPersonsrow36').className = '';
								});
								$j("#dtgPersonsrow36").on("click", function(event, ui){
								qc.pA('ExampleForm', 'dtgPersonsrow36', 'QClickEvent#a38', '37', '');
								}); $j("#delete_38").on("click", function(event, ui){
									qc.pA('ExampleForm', 'c3', 'QClickEvent#a1', '38', ''); event.preventDefault(); event.stopPropagation();
									}); $j("#dtgPersonsrow37").on("mouseover", function(event, ui){
								qc.getC('dtgPersonsrow37').className = 'selectedStyle';
								});
								$j("#dtgPersonsrow37").on("mouseout", function(event, ui){
								qc.getC('dtgPersonsrow37').className = '';
								});
								$j("#dtgPersonsrow37").on("click", function(event, ui){
								qc.pA('ExampleForm', 'dtgPersonsrow37', 'QClickEvent#a39', '38', '');
								}); $j("#delete_39").on("click", function(event, ui){
									qc.pA('ExampleForm', 'c3', 'QClickEvent#a1', '39', ''); event.preventDefault(); event.stopPropagation();
									}); $j("#dtgPersonsrow38").on("mouseover", function(event, ui){
								qc.getC('dtgPersonsrow38').className = 'selectedStyle';
								});
								$j("#dtgPersonsrow38").on("mouseout", function(event, ui){
								qc.getC('dtgPersonsrow38').className = '';
								});
								$j("#dtgPersonsrow38").on("click", function(event, ui){
								qc.pA('ExampleForm', 'dtgPersonsrow38', 'QClickEvent#a40', '39', '');
								}); $j("#delete_40").on("click", function(event, ui){
									qc.pA('ExampleForm', 'c3', 'QClickEvent#a1', '40', ''); event.preventDefault(); event.stopPropagation();
									}); $j("#dtgPersonsrow39").on("mouseover", function(event, ui){
								qc.getC('dtgPersonsrow39').className = 'selectedStyle';
								});
								$j("#dtgPersonsrow39").on("mouseout", function(event, ui){
								qc.getC('dtgPersonsrow39').className = '';
								});
								$j("#dtgPersonsrow39").on("click", function(event, ui){
								qc.pA('ExampleForm', 'dtgPersonsrow39', 'QClickEvent#a41', '40', '');
								}); $j("#delete_41").on("click", function(event, ui){
									qc.pA('ExampleForm', 'c3', 'QClickEvent#a1', '41', ''); event.preventDefault(); event.stopPropagation();
									}); $j("#dtgPersonsrow40").on("mouseover", function(event, ui){
								qc.getC('dtgPersonsrow40').className = 'selectedStyle';
								});
								$j("#dtgPersonsrow40").on("mouseout", function(event, ui){
								qc.getC('dtgPersonsrow40').className = '';
								});
								$j("#dtgPersonsrow40").on("click", function(event, ui){
								qc.pA('ExampleForm', 'dtgPersonsrow40', 'QClickEvent#a42', '41', '');
								}); $j("#delete_42").on("click", function(event, ui){
									qc.pA('ExampleForm', 'c3', 'QClickEvent#a1', '42', ''); event.preventDefault(); event.stopPropagation();
									}); $j("#dtgPersonsrow41").on("mouseover", function(event, ui){
								qc.getC('dtgPersonsrow41').className = 'selectedStyle';
								});
								$j("#dtgPersonsrow41").on("mouseout", function(event, ui){
								qc.getC('dtgPersonsrow41').className = '';
								});
								$j("#dtgPersonsrow41").on("click", function(event, ui){
								qc.pA('ExampleForm', 'dtgPersonsrow41', 'QClickEvent#a43', '42', '');
								}); $j("#delete_43").on("click", function(event, ui){
									qc.pA('ExampleForm', 'c3', 'QClickEvent#a1', '43', ''); event.preventDefault(); event.stopPropagation();
									}); $j("#dtgPersonsrow42").on("mouseover", function(event, ui){
								qc.getC('dtgPersonsrow42').className = 'selectedStyle';
								});
								$j("#dtgPersonsrow42").on("mouseout", function(event, ui){
								qc.getC('dtgPersonsrow42').className = '';
								});
								$j("#dtgPersonsrow42").on("click", function(event, ui){
								qc.pA('ExampleForm', 'dtgPersonsrow42', 'QClickEvent#a44', '43', '');
								}); $j("#delete_44").on("click", function(event, ui){
									qc.pA('ExampleForm', 'c3', 'QClickEvent#a1', '44', ''); event.preventDefault(); event.stopPropagation();
									}); $j("#dtgPersonsrow43").on("mouseover", function(event, ui){
								qc.getC('dtgPersonsrow43').className = 'selectedStyle';
								});
								$j("#dtgPersonsrow43").on("mouseout", function(event, ui){
								qc.getC('dtgPersonsrow43').className = '';
								});
								$j("#dtgPersonsrow43").on("click", function(event, ui){
								qc.pA('ExampleForm', 'dtgPersonsrow43', 'QClickEvent#a45', '44', '');
								}); $j("#delete_45").on("click", function(event, ui){
									qc.pA('ExampleForm', 'c3', 'QClickEvent#a1', '45', ''); event.preventDefault(); event.stopPropagation();
									}); $j("#dtgPersonsrow44").on("mouseover", function(event, ui){
								qc.getC('dtgPersonsrow44').className = 'selectedStyle';
								});
								$j("#dtgPersonsrow44").on("mouseout", function(event, ui){
								qc.getC('dtgPersonsrow44').className = '';
								});
								$j("#dtgPersonsrow44").on("click", function(event, ui){
								qc.pA('ExampleForm', 'dtgPersonsrow44', 'QClickEvent#a46', '45', '');
								}); $j("#delete_46").on("click", function(event, ui){
									qc.pA('ExampleForm', 'c3', 'QClickEvent#a1', '46', ''); event.preventDefault(); event.stopPropagation();
									}); $j("#dtgPersonsrow45").on("mouseover", function(event, ui){
								qc.getC('dtgPersonsrow45').className = 'selectedStyle';
								});
								$j("#dtgPersonsrow45").on("mouseout", function(event, ui){
								qc.getC('dtgPersonsrow45').className = '';
								});
								$j("#dtgPersonsrow45").on("click", function(event, ui){
								qc.pA('ExampleForm', 'dtgPersonsrow45', 'QClickEvent#a47', '46', '');
								}); $j("#delete_47").on("click", function(event, ui){
									qc.pA('ExampleForm', 'c3', 'QClickEvent#a1', '47', ''); event.preventDefault(); event.stopPropagation();
									}); $j("#dtgPersonsrow46").on("mouseover", function(event, ui){
								qc.getC('dtgPersonsrow46').className = 'selectedStyle';
								});
								$j("#dtgPersonsrow46").on("mouseout", function(event, ui){
								qc.getC('dtgPersonsrow46').className = '';
								});
								$j("#dtgPersonsrow46").on("click", function(event, ui){
								qc.pA('ExampleForm', 'dtgPersonsrow46', 'QClickEvent#a48', '47', '');
								}); $j("#delete_48").on("click", function(event, ui){
									qc.pA('ExampleForm', 'c3', 'QClickEvent#a1', '48', ''); event.preventDefault(); event.stopPropagation();
									}); $j("#dtgPersonsrow47").on("mouseover", function(event, ui){
								qc.getC('dtgPersonsrow47').className = 'selectedStyle';
								});
								$j("#dtgPersonsrow47").on("mouseout", function(event, ui){
								qc.getC('dtgPersonsrow47').className = '';
								});
								$j("#dtgPersonsrow47").on("click", function(event, ui){
								qc.pA('ExampleForm', 'dtgPersonsrow47', 'QClickEvent#a49', '48', '');
								}); $j("#delete_49").on("click", function(event, ui){
									qc.pA('ExampleForm', 'c3', 'QClickEvent#a1', '49', ''); event.preventDefault(); event.stopPropagation();
									}); $j("#dtgPersonsrow48").on("mouseover", function(event, ui){
								qc.getC('dtgPersonsrow48').className = 'selectedStyle';
								});
								$j("#dtgPersonsrow48").on("mouseout", function(event, ui){
								qc.getC('dtgPersonsrow48').className = '';
								});
								$j("#dtgPersonsrow48").on("click", function(event, ui){
								qc.pA('ExampleForm', 'dtgPersonsrow48', 'QClickEvent#a50', '49', '');
								}); $j("#delete_50").on("click", function(event, ui){
									qc.pA('ExampleForm', 'c3', 'QClickEvent#a1', '50', ''); event.preventDefault(); event.stopPropagation();
									}); $j("#dtgPersonsrow49").on("mouseover", function(event, ui){
								qc.getC('dtgPersonsrow49').className = 'selectedStyle';
								});
								$j("#dtgPersonsrow49").on("mouseout", function(event, ui){
								qc.getC('dtgPersonsrow49').className = '';
								});
								$j("#dtgPersonsrow49").on("click", function(event, ui){
								qc.pA('ExampleForm', 'dtgPersonsrow49', 'QClickEvent#a51', '50', '');
								}); $j("#delete_51").on("click", function(event, ui){
									qc.pA('ExampleForm', 'c3', 'QClickEvent#a1', '51', ''); event.preventDefault(); event.stopPropagation();
									}); $j("#dtgPersonsrow50").on("mouseover", function(event, ui){
								qc.getC('dtgPersonsrow50').className = 'selectedStyle';
								});
								$j("#dtgPersonsrow50").on("mouseout", function(event, ui){
								qc.getC('dtgPersonsrow50').className = '';
								});
								$j("#dtgPersonsrow50").on("click", function(event, ui){
								qc.pA('ExampleForm', 'dtgPersonsrow50', 'QClickEvent#a52', '51', '');
								}); $j("#delete_52").on("click", function(event, ui){
									qc.pA('ExampleForm', 'c3', 'QClickEvent#a1', '52', ''); event.preventDefault(); event.stopPropagation();
									}); $j("#dtgPersonsrow51").on("mouseover", function(event, ui){
								qc.getC('dtgPersonsrow51').className = 'selectedStyle';
								});
								$j("#dtgPersonsrow51").on("mouseout", function(event, ui){
								qc.getC('dtgPersonsrow51').className = '';
								});
								$j("#dtgPersonsrow51").on("click", function(event, ui){
								qc.pA('ExampleForm', 'dtgPersonsrow51', 'QClickEvent#a53', '52', '');
								}); $j("#delete_53").on("click", function(event, ui){
									qc.pA('ExampleForm', 'c3', 'QClickEvent#a1', '53', ''); event.preventDefault(); event.stopPropagation();
									}); $j("#dtgPersonsrow52").on("mouseover", function(event, ui){
								qc.getC('dtgPersonsrow52').className = 'selectedStyle';
								});
								$j("#dtgPersonsrow52").on("mouseout", function(event, ui){
								qc.getC('dtgPersonsrow52').className = '';
								});
								$j("#dtgPersonsrow52").on("click", function(event, ui){
								qc.pA('ExampleForm', 'dtgPersonsrow52', 'QClickEvent#a54', '53', '');
								}); $j("#delete_54").on("click", function(event, ui){
									qc.pA('ExampleForm', 'c3', 'QClickEvent#a1', '54', ''); event.preventDefault(); event.stopPropagation();
									}); $j("#dtgPersonsrow53").on("mouseover", function(event, ui){
								qc.getC('dtgPersonsrow53').className = 'selectedStyle';
								});
								$j("#dtgPersonsrow53").on("mouseout", function(event, ui){
								qc.getC('dtgPersonsrow53').className = '';
								});
								$j("#dtgPersonsrow53").on("click", function(event, ui){
								qc.pA('ExampleForm', 'dtgPersonsrow53', 'QClickEvent#a55', '54', '');
								}); $j("#delete_55").on("click", function(event, ui){
									qc.pA('ExampleForm', 'c3', 'QClickEvent#a1', '55', ''); event.preventDefault(); event.stopPropagation();
									}); $j("#dtgPersonsrow54").on("mouseover", function(event, ui){
								qc.getC('dtgPersonsrow54').className = 'selectedStyle';
								});
								$j("#dtgPersonsrow54").on("mouseout", function(event, ui){
								qc.getC('dtgPersonsrow54').className = '';
								});
								$j("#dtgPersonsrow54").on("click", function(event, ui){
								qc.pA('ExampleForm', 'dtgPersonsrow54', 'QClickEvent#a56', '55', '');
								}); $j("#delete_56").on("click", function(event, ui){
									qc.pA('ExampleForm', 'c3', 'QClickEvent#a1', '56', ''); event.preventDefault(); event.stopPropagation();
									}); $j("#dtgPersonsrow55").on("mouseover", function(event, ui){
								qc.getC('dtgPersonsrow55').className = 'selectedStyle';
								});
								$j("#dtgPersonsrow55").on("mouseout", function(event, ui){
								qc.getC('dtgPersonsrow55').className = '';
								});
								$j("#dtgPersonsrow55").on("click", function(event, ui){
								qc.pA('ExampleForm', 'dtgPersonsrow55', 'QClickEvent#a57', '56', '');
								}); $j("#delete_57").on("click", function(event, ui){
									qc.pA('ExampleForm', 'c3', 'QClickEvent#a1', '57', ''); event.preventDefault(); event.stopPropagation();
									}); $j("#dtgPersonsrow56").on("mouseover", function(event, ui){
								qc.getC('dtgPersonsrow56').className = 'selectedStyle';
								});
								$j("#dtgPersonsrow56").on("mouseout", function(event, ui){
								qc.getC('dtgPersonsrow56').className = '';
								});
								$j("#dtgPersonsrow56").on("click", function(event, ui){
								qc.pA('ExampleForm', 'dtgPersonsrow56', 'QClickEvent#a58', '57', '');
								}); $j("#delete_58").on("click", function(event, ui){
									qc.pA('ExampleForm', 'c3', 'QClickEvent#a1', '58', ''); event.preventDefault(); event.stopPropagation();
									}); $j("#dtgPersonsrow57").on("mouseover", function(event, ui){
								qc.getC('dtgPersonsrow57').className = 'selectedStyle';
								});
								$j("#dtgPersonsrow57").on("mouseout", function(event, ui){
								qc.getC('dtgPersonsrow57').className = '';
								});
								$j("#dtgPersonsrow57").on("click", function(event, ui){
								qc.pA('ExampleForm', 'dtgPersonsrow57', 'QClickEvent#a59', '58', '');
								}); $j("#delete_59").on("click", function(event, ui){
									qc.pA('ExampleForm', 'c3', 'QClickEvent#a1', '59', ''); event.preventDefault(); event.stopPropagation();
									}); $j("#dtgPersonsrow58").on("mouseover", function(event, ui){
								qc.getC('dtgPersonsrow58').className = 'selectedStyle';
								});
								$j("#dtgPersonsrow58").on("mouseout", function(event, ui){
								qc.getC('dtgPersonsrow58').className = '';
								});
								$j("#dtgPersonsrow58").on("click", function(event, ui){
								qc.pA('ExampleForm', 'dtgPersonsrow58', 'QClickEvent#a60', '59', '');
								}); $j("#delete_60").on("click", function(event, ui){
									qc.pA('ExampleForm', 'c3', 'QClickEvent#a1', '60', ''); event.preventDefault(); event.stopPropagation();
									}); $j("#dtgPersonsrow59").on("mouseover", function(event, ui){
								qc.getC('dtgPersonsrow59').className = 'selectedStyle';
								});
								$j("#dtgPersonsrow59").on("mouseout", function(event, ui){
								qc.getC('dtgPersonsrow59').className = '';
								});
								$j("#dtgPersonsrow59").on("click", function(event, ui){
								qc.pA('ExampleForm', 'dtgPersonsrow59', 'QClickEvent#a61', '60', '');
								}); $j("#delete_61").on("click", function(event, ui){
									qc.pA('ExampleForm', 'c3', 'QClickEvent#a1', '61', ''); event.preventDefault(); event.stopPropagation();
									}); $j("#dtgPersonsrow60").on("mouseover", function(event, ui){
								qc.getC('dtgPersonsrow60').className = 'selectedStyle';
								});
								$j("#dtgPersonsrow60").on("mouseout", function(event, ui){
								qc.getC('dtgPersonsrow60').className = '';
								});
								$j("#dtgPersonsrow60").on("click", function(event, ui){
								qc.pA('ExampleForm', 'dtgPersonsrow60', 'QClickEvent#a62', '61', '');
								}); $j("#delete_62").on("click", function(event, ui){
									qc.pA('ExampleForm', 'c3', 'QClickEvent#a1', '62', ''); event.preventDefault(); event.stopPropagation();
									}); $j("#dtgPersonsrow61").on("mouseover", function(event, ui){
								qc.getC('dtgPersonsrow61').className = 'selectedStyle';
								});
								$j("#dtgPersonsrow61").on("mouseout", function(event, ui){
								qc.getC('dtgPersonsrow61').className = '';
								});
								$j("#dtgPersonsrow61").on("click", function(event, ui){
								qc.pA('ExampleForm', 'dtgPersonsrow61', 'QClickEvent#a63', '62', '');
								}); $j("#delete_63").on("click", function(event, ui){
									qc.pA('ExampleForm', 'c3', 'QClickEvent#a1', '63', ''); event.preventDefault(); event.stopPropagation();
									}); $j("#dtgPersonsrow62").on("mouseover", function(event, ui){
								qc.getC('dtgPersonsrow62').className = 'selectedStyle';
								});
								$j("#dtgPersonsrow62").on("mouseout", function(event, ui){
								qc.getC('dtgPersonsrow62').className = '';
								});
								$j("#dtgPersonsrow62").on("click", function(event, ui){
								qc.pA('ExampleForm', 'dtgPersonsrow62', 'QClickEvent#a64', '63', '');
								}); $j("#delete_64").on("click", function(event, ui){
									qc.pA('ExampleForm', 'c3', 'QClickEvent#a1', '64', ''); event.preventDefault(); event.stopPropagation();
									}); $j("#dtgPersonsrow63").on("mouseover", function(event, ui){
								qc.getC('dtgPersonsrow63').className = 'selectedStyle';
								});
								$j("#dtgPersonsrow63").on("mouseout", function(event, ui){
								qc.getC('dtgPersonsrow63').className = '';
								});
								$j("#dtgPersonsrow63").on("click", function(event, ui){
								qc.pA('ExampleForm', 'dtgPersonsrow63', 'QClickEvent#a65', '64', '');
								}); $j("#delete_65").on("click", function(event, ui){
									qc.pA('ExampleForm', 'c3', 'QClickEvent#a1', '65', ''); event.preventDefault(); event.stopPropagation();
									}); $j("#dtgPersonsrow64").on("mouseover", function(event, ui){
								qc.getC('dtgPersonsrow64').className = 'selectedStyle';
								});
								$j("#dtgPersonsrow64").on("mouseout", function(event, ui){
								qc.getC('dtgPersonsrow64').className = '';
								});
								$j("#dtgPersonsrow64").on("click", function(event, ui){
								qc.pA('ExampleForm', 'dtgPersonsrow64', 'QClickEvent#a66', '65', '');
								}); $j("#delete_66").on("click", function(event, ui){
									qc.pA('ExampleForm', 'c3', 'QClickEvent#a1', '66', ''); event.preventDefault(); event.stopPropagation();
									}); $j("#dtgPersonsrow65").on("mouseover", function(event, ui){
								qc.getC('dtgPersonsrow65').className = 'selectedStyle';
								});
								$j("#dtgPersonsrow65").on("mouseout", function(event, ui){
								qc.getC('dtgPersonsrow65').className = '';
								});
								$j("#dtgPersonsrow65").on("click", function(event, ui){
								qc.pA('ExampleForm', 'dtgPersonsrow65', 'QClickEvent#a67', '66', '');
								}); $j("#delete_67").on("click", function(event, ui){
									qc.pA('ExampleForm', 'c3', 'QClickEvent#a1', '67', ''); event.preventDefault(); event.stopPropagation();
									}); $j("#dtgPersonsrow66").on("mouseover", function(event, ui){
								qc.getC('dtgPersonsrow66').className = 'selectedStyle';
								});
								$j("#dtgPersonsrow66").on("mouseout", function(event, ui){
								qc.getC('dtgPersonsrow66').className = '';
								});
								$j("#dtgPersonsrow66").on("click", function(event, ui){
								qc.pA('ExampleForm', 'dtgPersonsrow66', 'QClickEvent#a68', '67', '');
								}); $j("#delete_68").on("click", function(event, ui){
									qc.pA('ExampleForm', 'c3', 'QClickEvent#a1', '68', ''); event.preventDefault(); event.stopPropagation();
									}); $j("#dtgPersonsrow67").on("mouseover", function(event, ui){
								qc.getC('dtgPersonsrow67').className = 'selectedStyle';
								});
								$j("#dtgPersonsrow67").on("mouseout", function(event, ui){
								qc.getC('dtgPersonsrow67').className = '';
								});
								$j("#dtgPersonsrow67").on("click", function(event, ui){
								qc.pA('ExampleForm', 'dtgPersonsrow67', 'QClickEvent#a69', '68', '');
								}); $j("#delete_69").on("click", function(event, ui){
									qc.pA('ExampleForm', 'c3', 'QClickEvent#a1', '69', ''); event.preventDefault(); event.stopPropagation();
									}); $j("#dtgPersonsrow68").on("mouseover", function(event, ui){
								qc.getC('dtgPersonsrow68').className = 'selectedStyle';
								});
								$j("#dtgPersonsrow68").on("mouseout", function(event, ui){
								qc.getC('dtgPersonsrow68').className = '';
								});
								$j("#dtgPersonsrow68").on("click", function(event, ui){
								qc.pA('ExampleForm', 'dtgPersonsrow68', 'QClickEvent#a70', '69', '');
								}); $j("#delete_70").on("click", function(event, ui){
									qc.pA('ExampleForm', 'c3', 'QClickEvent#a1', '70', ''); event.preventDefault(); event.stopPropagation();
									}); $j("#dtgPersonsrow69").on("mouseover", function(event, ui){
								qc.getC('dtgPersonsrow69').className = 'selectedStyle';
								});
								$j("#dtgPersonsrow69").on("mouseout", function(event, ui){
								qc.getC('dtgPersonsrow69').className = '';
								});
								$j("#dtgPersonsrow69").on("click", function(event, ui){
								qc.pA('ExampleForm', 'dtgPersonsrow69', 'QClickEvent#a71', '70', '');
								}); $j("#delete_71").on("click", function(event, ui){
									qc.pA('ExampleForm', 'c3', 'QClickEvent#a1', '71', ''); event.preventDefault(); event.stopPropagation();
									}); $j("#dtgPersonsrow70").on("mouseover", function(event, ui){
								qc.getC('dtgPersonsrow70').className = 'selectedStyle';
								});
								$j("#dtgPersonsrow70").on("mouseout", function(event, ui){
								qc.getC('dtgPersonsrow70').className = '';
								});
								$j("#dtgPersonsrow70").on("click", function(event, ui){
								qc.pA('ExampleForm', 'dtgPersonsrow70', 'QClickEvent#a72', '71', '');
								}); $j("#delete_72").on("click", function(event, ui){
									qc.pA('ExampleForm', 'c3', 'QClickEvent#a1', '72', ''); event.preventDefault(); event.stopPropagation();
									}); $j("#dtgPersonsrow71").on("mouseover", function(event, ui){
								qc.getC('dtgPersonsrow71').className = 'selectedStyle';
								});
								$j("#dtgPersonsrow71").on("mouseout", function(event, ui){
								qc.getC('dtgPersonsrow71').className = '';
								});
								$j("#dtgPersonsrow71").on("click", function(event, ui){
								qc.pA('ExampleForm', 'dtgPersonsrow71', 'QClickEvent#a73', '72', '');
								}); $j("#delete_73").on("click", function(event, ui){
									qc.pA('ExampleForm', 'c3', 'QClickEvent#a1', '73', ''); event.preventDefault(); event.stopPropagation();
									}); $j("#dtgPersonsrow72").on("mouseover", function(event, ui){
								qc.getC('dtgPersonsrow72').className = 'selectedStyle';
								});
								$j("#dtgPersonsrow72").on("mouseout", function(event, ui){
								qc.getC('dtgPersonsrow72').className = '';
								});
								$j("#dtgPersonsrow72").on("click", function(event, ui){
								qc.pA('ExampleForm', 'dtgPersonsrow72', 'QClickEvent#a74', '73', '');
								}); $j("#delete_74").on("click", function(event, ui){
									qc.pA('ExampleForm', 'c3', 'QClickEvent#a1', '74', ''); event.preventDefault(); event.stopPropagation();
									}); $j("#dtgPersonsrow73").on("mouseover", function(event, ui){
								qc.getC('dtgPersonsrow73').className = 'selectedStyle';
								});
								$j("#dtgPersonsrow73").on("mouseout", function(event, ui){
								qc.getC('dtgPersonsrow73').className = '';
								});
								$j("#dtgPersonsrow73").on("click", function(event, ui){
								qc.pA('ExampleForm', 'dtgPersonsrow73', 'QClickEvent#a75', '74', '');
								}); $j("#delete_75").on("click", function(event, ui){
									qc.pA('ExampleForm', 'c3', 'QClickEvent#a1', '75', ''); event.preventDefault(); event.stopPropagation();
									}); $j("#dtgPersonsrow74").on("mouseover", function(event, ui){
								qc.getC('dtgPersonsrow74').className = 'selectedStyle';
								});
								$j("#dtgPersonsrow74").on("mouseout", function(event, ui){
								qc.getC('dtgPersonsrow74').className = '';
								});
								$j("#dtgPersonsrow74").on("click", function(event, ui){
								qc.pA('ExampleForm', 'dtgPersonsrow74', 'QClickEvent#a76', '75', '');
								}); $j("#delete_76").on("click", function(event, ui){
									qc.pA('ExampleForm', 'c3', 'QClickEvent#a1', '76', ''); event.preventDefault(); event.stopPropagation();
									}); $j("#dtgPersonsrow75").on("mouseover", function(event, ui){
								qc.getC('dtgPersonsrow75').className = 'selectedStyle';
								});
								$j("#dtgPersonsrow75").on("mouseout", function(event, ui){
								qc.getC('dtgPersonsrow75').className = '';
								});
								$j("#dtgPersonsrow75").on("click", function(event, ui){
								qc.pA('ExampleForm', 'dtgPersonsrow75', 'QClickEvent#a77', '76', '');
								}); $j("#delete_77").on("click", function(event, ui){
									qc.pA('ExampleForm', 'c3', 'QClickEvent#a1', '77', ''); event.preventDefault(); event.stopPropagation();
									}); $j("#dtgPersonsrow76").on("mouseover", function(event, ui){
								qc.getC('dtgPersonsrow76').className = 'selectedStyle';
								});
								$j("#dtgPersonsrow76").on("mouseout", function(event, ui){
								qc.getC('dtgPersonsrow76').className = '';
								});
								$j("#dtgPersonsrow76").on("click", function(event, ui){
								qc.pA('ExampleForm', 'dtgPersonsrow76', 'QClickEvent#a78', '77', '');
								}); $j("#delete_78").on("click", function(event, ui){
									qc.pA('ExampleForm', 'c3', 'QClickEvent#a1', '78', ''); event.preventDefault(); event.stopPropagation();
									}); $j("#dtgPersonsrow77").on("mouseover", function(event, ui){
								qc.getC('dtgPersonsrow77').className = 'selectedStyle';
								});
								$j("#dtgPersonsrow77").on("mouseout", function(event, ui){
								qc.getC('dtgPersonsrow77').className = '';
								});
								$j("#dtgPersonsrow77").on("click", function(event, ui){
								qc.pA('ExampleForm', 'dtgPersonsrow77', 'QClickEvent#a79', '78', '');
								}); $j("#delete_79").on("click", function(event, ui){
									qc.pA('ExampleForm', 'c3', 'QClickEvent#a1', '79', ''); event.preventDefault(); event.stopPropagation();
									}); $j("#dtgPersonsrow78").on("mouseover", function(event, ui){
								qc.getC('dtgPersonsrow78').className = 'selectedStyle';
								});
								$j("#dtgPersonsrow78").on("mouseout", function(event, ui){
								qc.getC('dtgPersonsrow78').className = '';
								});
								$j("#dtgPersonsrow78").on("click", function(event, ui){
								qc.pA('ExampleForm', 'dtgPersonsrow78', 'QClickEvent#a80', '79', '');
								}); $j("#delete_80").on("click", function(event, ui){
									qc.pA('ExampleForm', 'c3', 'QClickEvent#a1', '80', ''); event.preventDefault(); event.stopPropagation();
									}); $j("#dtgPersonsrow79").on("mouseover", function(event, ui){
								qc.getC('dtgPersonsrow79').className = 'selectedStyle';
								});
								$j("#dtgPersonsrow79").on("mouseout", function(event, ui){
								qc.getC('dtgPersonsrow79').className = '';
								});
								$j("#dtgPersonsrow79").on("click", function(event, ui){
								qc.pA('ExampleForm', 'dtgPersonsrow79', 'QClickEvent#a81', '80', '');
								}); $j("#delete_81").on("click", function(event, ui){
									qc.pA('ExampleForm', 'c3', 'QClickEvent#a1', '81', ''); event.preventDefault(); event.stopPropagation();
									}); $j("#dtgPersonsrow80").on("mouseover", function(event, ui){
								qc.getC('dtgPersonsrow80').className = 'selectedStyle';
								});
								$j("#dtgPersonsrow80").on("mouseout", function(event, ui){
								qc.getC('dtgPersonsrow80').className = '';
								});
								$j("#dtgPersonsrow80").on("click", function(event, ui){
								qc.pA('ExampleForm', 'dtgPersonsrow80', 'QClickEvent#a82', '81', '');
								}); $j("#delete_82").on("click", function(event, ui){
									qc.pA('ExampleForm', 'c3', 'QClickEvent#a1', '82', ''); event.preventDefault(); event.stopPropagation();
									}); $j("#dtgPersonsrow81").on("mouseover", function(event, ui){
								qc.getC('dtgPersonsrow81').className = 'selectedStyle';
								});
								$j("#dtgPersonsrow81").on("mouseout", function(event, ui){
								qc.getC('dtgPersonsrow81').className = '';
								});
								$j("#dtgPersonsrow81").on("click", function(event, ui){
								qc.pA('ExampleForm', 'dtgPersonsrow81', 'QClickEvent#a83', '82', '');
								}); $j("#delete_83").on("click", function(event, ui){
									qc.pA('ExampleForm', 'c3', 'QClickEvent#a1', '83', ''); event.preventDefault(); event.stopPropagation();
									}); $j("#dtgPersonsrow82").on("mouseover", function(event, ui){
								qc.getC('dtgPersonsrow82').className = 'selectedStyle';
								});
								$j("#dtgPersonsrow82").on("mouseout", function(event, ui){
								qc.getC('dtgPersonsrow82').className = '';
								});
								$j("#dtgPersonsrow82").on("click", function(event, ui){
								qc.pA('ExampleForm', 'dtgPersonsrow82', 'QClickEvent#a84', '83', '');
								}); $j("#delete_84").on("click", function(event, ui){
									qc.pA('ExampleForm', 'c3', 'QClickEvent#a1', '84', ''); event.preventDefault(); event.stopPropagation();
									}); $j("#dtgPersonsrow83").on("mouseover", function(event, ui){
								qc.getC('dtgPersonsrow83').className = 'selectedStyle';
								});
								$j("#dtgPersonsrow83").on("mouseout", function(event, ui){
								qc.getC('dtgPersonsrow83').className = '';
								});
								$j("#dtgPersonsrow83").on("click", function(event, ui){
								qc.pA('ExampleForm', 'dtgPersonsrow83', 'QClickEvent#a85', '84', '');
								}); $j("#delete_85").on("click", function(event, ui){
									qc.pA('ExampleForm', 'c3', 'QClickEvent#a1', '85', ''); event.preventDefault(); event.stopPropagation();
									}); $j("#dtgPersonsrow84").on("mouseover", function(event, ui){
								qc.getC('dtgPersonsrow84').className = 'selectedStyle';
								});
								$j("#dtgPersonsrow84").on("mouseout", function(event, ui){
								qc.getC('dtgPersonsrow84').className = '';
								});
								$j("#dtgPersonsrow84").on("click", function(event, ui){
								qc.pA('ExampleForm', 'dtgPersonsrow84', 'QClickEvent#a86', '85', '');
								}); $j("#delete_86").on("click", function(event, ui){
									qc.pA('ExampleForm', 'c3', 'QClickEvent#a1', '86', ''); event.preventDefault(); event.stopPropagation();
									}); $j("#dtgPersonsrow85").on("mouseover", function(event, ui){
								qc.getC('dtgPersonsrow85').className = 'selectedStyle';
								});
								$j("#dtgPersonsrow85").on("mouseout", function(event, ui){
								qc.getC('dtgPersonsrow85').className = '';
								});
								$j("#dtgPersonsrow85").on("click", function(event, ui){
								qc.pA('ExampleForm', 'dtgPersonsrow85', 'QClickEvent#a87', '86', '');
								}); $j("#delete_87").on("click", function(event, ui){
									qc.pA('ExampleForm', 'c3', 'QClickEvent#a1', '87', ''); event.preventDefault(); event.stopPropagation();
									}); $j("#dtgPersonsrow86").on("mouseover", function(event, ui){
								qc.getC('dtgPersonsrow86').className = 'selectedStyle';
								});
								$j("#dtgPersonsrow86").on("mouseout", function(event, ui){
								qc.getC('dtgPersonsrow86').className = '';
								});
								$j("#dtgPersonsrow86").on("click", function(event, ui){
								qc.pA('ExampleForm', 'dtgPersonsrow86', 'QClickEvent#a88', '87', '');
								}); $j("#delete_88").on("click", function(event, ui){
									qc.pA('ExampleForm', 'c3', 'QClickEvent#a1', '88', ''); event.preventDefault(); event.stopPropagation();
									}); $j("#dtgPersonsrow87").on("mouseover", function(event, ui){
								qc.getC('dtgPersonsrow87').className = 'selectedStyle';
								});
								$j("#dtgPersonsrow87").on("mouseout", function(event, ui){
								qc.getC('dtgPersonsrow87').className = '';
								});
								$j("#dtgPersonsrow87").on("click", function(event, ui){
								qc.pA('ExampleForm', 'dtgPersonsrow87', 'QClickEvent#a89', '88', '');
								}); $j("#delete_89").on("click", function(event, ui){
									qc.pA('ExampleForm', 'c3', 'QClickEvent#a1', '89', ''); event.preventDefault(); event.stopPropagation();
									}); $j("#dtgPersonsrow88").on("mouseover", function(event, ui){
								qc.getC('dtgPersonsrow88').className = 'selectedStyle';
								});
								$j("#dtgPersonsrow88").on("mouseout", function(event, ui){
								qc.getC('dtgPersonsrow88').className = '';
								});
								$j("#dtgPersonsrow88").on("click", function(event, ui){
								qc.pA('ExampleForm', 'dtgPersonsrow88', 'QClickEvent#a90', '89', '');
								}); $j("#delete_90").on("click", function(event, ui){
									qc.pA('ExampleForm', 'c3', 'QClickEvent#a1', '90', ''); event.preventDefault(); event.stopPropagation();
									}); $j("#dtgPersonsrow89").on("mouseover", function(event, ui){
								qc.getC('dtgPersonsrow89').className = 'selectedStyle';
								});
								$j("#dtgPersonsrow89").on("mouseout", function(event, ui){
								qc.getC('dtgPersonsrow89').className = '';
								});
								$j("#dtgPersonsrow89").on("click", function(event, ui){
								qc.pA('ExampleForm', 'dtgPersonsrow89', 'QClickEvent#a91', '90', '');
								}); $j("#delete_91").on("click", function(event, ui){
									qc.pA('ExampleForm', 'c3', 'QClickEvent#a1', '91', ''); event.preventDefault(); event.stopPropagation();
									}); $j("#dtgPersonsrow90").on("mouseover", function(event, ui){
								qc.getC('dtgPersonsrow90').className = 'selectedStyle';
								});
								$j("#dtgPersonsrow90").on("mouseout", function(event, ui){
								qc.getC('dtgPersonsrow90').className = '';
								});
								$j("#dtgPersonsrow90").on("click", function(event, ui){
								qc.pA('ExampleForm', 'dtgPersonsrow90', 'QClickEvent#a92', '91', '');
								}); $j("#delete_92").on("click", function(event, ui){
									qc.pA('ExampleForm', 'c3', 'QClickEvent#a1', '92', ''); event.preventDefault(); event.stopPropagation();
									}); $j("#dtgPersonsrow91").on("mouseover", function(event, ui){
								qc.getC('dtgPersonsrow91').className = 'selectedStyle';
								});
								$j("#dtgPersonsrow91").on("mouseout", function(event, ui){
								qc.getC('dtgPersonsrow91').className = '';
								});
								$j("#dtgPersonsrow91").on("click", function(event, ui){
								qc.pA('ExampleForm', 'dtgPersonsrow91', 'QClickEvent#a93', '92', '');
								}); $j("#delete_93").on("click", function(event, ui){
									qc.pA('ExampleForm', 'c3', 'QClickEvent#a1', '93', ''); event.preventDefault(); event.stopPropagation();
									}); $j("#dtgPersonsrow92").on("mouseover", function(event, ui){
								qc.getC('dtgPersonsrow92').className = 'selectedStyle';
								});
								$j("#dtgPersonsrow92").on("mouseout", function(event, ui){
								qc.getC('dtgPersonsrow92').className = '';
								});
								$j("#dtgPersonsrow92").on("click", function(event, ui){
								qc.pA('ExampleForm', 'dtgPersonsrow92', 'QClickEvent#a94', '93', '');
								}); $j("#delete_94").on("click", function(event, ui){
									qc.pA('ExampleForm', 'c3', 'QClickEvent#a1', '94', ''); event.preventDefault(); event.stopPropagation();
									}); $j("#dtgPersonsrow93").on("mouseover", function(event, ui){
								qc.getC('dtgPersonsrow93').className = 'selectedStyle';
								});
								$j("#dtgPersonsrow93").on("mouseout", function(event, ui){
								qc.getC('dtgPersonsrow93').className = '';
								});
								$j("#dtgPersonsrow93").on("click", function(event, ui){
								qc.pA('ExampleForm', 'dtgPersonsrow93', 'QClickEvent#a95', '94', '');
								}); $j("#delete_95").on("click", function(event, ui){
									qc.pA('ExampleForm', 'c3', 'QClickEvent#a1', '95', ''); event.preventDefault(); event.stopPropagation();
									}); $j("#dtgPersonsrow94").on("mouseover", function(event, ui){
								qc.getC('dtgPersonsrow94').className = 'selectedStyle';
								});
								$j("#dtgPersonsrow94").on("mouseout", function(event, ui){
								qc.getC('dtgPersonsrow94').className = '';
								});
								$j("#dtgPersonsrow94").on("click", function(event, ui){
								qc.pA('ExampleForm', 'dtgPersonsrow94', 'QClickEvent#a96', '95', '');
								}); $j("#delete_96").on("click", function(event, ui){
									qc.pA('ExampleForm', 'c3', 'QClickEvent#a1', '96', ''); event.preventDefault(); event.stopPropagation();
									}); $j("#dtgPersonsrow95").on("mouseover", function(event, ui){
								qc.getC('dtgPersonsrow95').className = 'selectedStyle';
								});
								$j("#dtgPersonsrow95").on("mouseout", function(event, ui){
								qc.getC('dtgPersonsrow95').className = '';
								});
								$j("#dtgPersonsrow95").on("click", function(event, ui){
								qc.pA('ExampleForm', 'dtgPersonsrow95', 'QClickEvent#a97', '96', '');
								}); $j("#delete_97").on("click", function(event, ui){
									qc.pA('ExampleForm', 'c3', 'QClickEvent#a1', '97', ''); event.preventDefault(); event.stopPropagation();
									}); $j("#dtgPersonsrow96").on("mouseover", function(event, ui){
								qc.getC('dtgPersonsrow96').className = 'selectedStyle';
								});
								$j("#dtgPersonsrow96").on("mouseout", function(event, ui){
								qc.getC('dtgPersonsrow96').className = '';
								});
								$j("#dtgPersonsrow96").on("click", function(event, ui){
								qc.pA('ExampleForm', 'dtgPersonsrow96', 'QClickEvent#a98', '97', '');
								}); $j("#delete_98").on("click", function(event, ui){
									qc.pA('ExampleForm', 'c3', 'QClickEvent#a1', '98', ''); event.preventDefault(); event.stopPropagation();
									}); $j("#dtgPersonsrow97").on("mouseover", function(event, ui){
								qc.getC('dtgPersonsrow97').className = 'selectedStyle';
								});
								$j("#dtgPersonsrow97").on("mouseout", function(event, ui){
								qc.getC('dtgPersonsrow97').className = '';
								});
								$j("#dtgPersonsrow97").on("click", function(event, ui){
								qc.pA('ExampleForm', 'dtgPersonsrow97', 'QClickEvent#a99', '98', '');
								}); $j("#delete_99").on("click", function(event, ui){
									qc.pA('ExampleForm', 'c3', 'QClickEvent#a1', '99', ''); event.preventDefault(); event.stopPropagation();
									}); $j("#dtgPersonsrow98").on("mouseover", function(event, ui){
								qc.getC('dtgPersonsrow98').className = 'selectedStyle';
								});
								$j("#dtgPersonsrow98").on("mouseout", function(event, ui){
								qc.getC('dtgPersonsrow98').className = '';
								});
								$j("#dtgPersonsrow98").on("click", function(event, ui){
								qc.pA('ExampleForm', 'dtgPersonsrow98', 'QClickEvent#a100', '99', '');
								}); $j("#delete_100").on("click", function(event, ui){
									qc.pA('ExampleForm', 'c3', 'QClickEvent#a1', '100', ''); event.preventDefault(); event.stopPropagation();
									}); $j("#dtgPersonsrow99").on("mouseover", function(event, ui){
								qc.getC('dtgPersonsrow99').className = 'selectedStyle';
								});
								$j("#dtgPersonsrow99").on("mouseout", function(event, ui){
								qc.getC('dtgPersonsrow99').className = '';
								});
								$j("#dtgPersonsrow99").on("click", function(event, ui){
								qc.pA('ExampleForm', 'dtgPersonsrow99', 'QClickEvent#a101', '100', '');
								});				
		</div>	
	</div>
</div>
<div>
	<div class="col">
		<h2>The datagrid <b>with</b> event delegation</h2>
		<p><?php $this->btnAddNewPersonDelegated->Render();?></p>
		<div class="table">
			<?php $this->dtgPersonsDelegated->Render(); ?>
		</div>
	</div>
	<div class="col">
		<h2>Resulting javascript sent to the client</h2>
		<p><b>11</b> lines of JavaScript code!</p>
		<div class="code">
								$j("#dtgPersonsDelegated").on("mouseover","tr", function(event, ui){
								$j(event.currentTarget).toggleClass("selectedStyle");
								});
								$j("#dtgPersonsDelegated").on("mouseout","tr", function(event, ui){
								$j(event.currentTarget).toggleClass("selectedStyle");
								});
								$j("#dtgPersonsDelegated").on("click","tr", function(event, ui){
								qc.pA('ExampleForm', 'dtgPersonsDelegated', 'QOnEvent#a102', $j(event.currentTarget).children().first().text(), '');
								});
								$j("#dtgPersonsDelegated").on("click","button[id^='delete_']", function(event, ui){
								qc.pA('ExampleForm', 'dtgPersonsDelegated', 'QOnEvent#a103', (event.currentTarget.id).split("_")[1], ''); event.preventDefault(); event.stopPropagation();
								});
							
		</div>	
	</div>
</div>
	<?php $this->RenderEnd(); ?>
<?php require('../includes/footer.inc.php'); ?>
