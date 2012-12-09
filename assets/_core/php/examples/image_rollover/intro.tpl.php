<?php require('../includes/header.inc.php'); ?>
	<?php $this->RenderBegin(); ?>
	
	<div class="instructions">
		<h1 class="instruction_title">Rollover Images using QImageRollover</h1>
		A frequent component of a web application is an image control that changes as
		the user hovers over it with their mouse. You can find examples of this pattern
		in navigational menus that highlight when you hover over the menu items: each 
		item is implemented as an image that has a mouse hover behavior implemented that
		swaps this image out for another one. That other image is basically identical, except
		that it has a "highlight" effect imlemented.<br /><br />
		
		It's really easy to implement functionality described above with a <b>QImageRollover</b> 
		control. Simply provide the URL's to the two images (standard and "on hover"), and QCubed 
		will do the rest.<br /><br />
		
		To see it in action, just mouse over the image below. 
	</div>

	<p><?php $this->imgMyRolloverImage->Render(); ?></p> 

	<?php $this->RenderEnd(); ?>
<?php require('../includes/footer.inc.php'); ?>