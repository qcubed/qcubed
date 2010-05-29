<?php require('../includes/header.inc.php'); ?>
	<?php $this->RenderBegin(); ?>

	<div class="instructions">
		<h1 class="instruction_title">Advanced Localization Techniques</h1>
		QCubed allows you to store your translations in any format that works for you. If you
		already have a database with translated terms in a given schema, you probably don't want 
		to have to generate .po files, right? Or, if you have a custom XML file that defines the
		translations - you want to keep using it, understandably.<br/><br/>
		
		QCubed allows you to do just that. It's actually really easy - just define your own class that 
		implements the <strong>QTranslationBase</strong> interface. In that class, you specify how 
		exactly the translations should be retrieved. There are static methods that you need
		to implement: 
		<ol>
			<li><strong>public static function Initialize()</strong>. In this factory method, your class is 
				supposed to read <strong>QApplication::$LanguageCode</strong> and 
				<strong>QApplication::$CountryCode</strong> settings and  based on these settings, 
				initialize itself, returning a new instance (usually by calling <strong>self::Load()</strong>)</li>
			<li><strong>public static function Load ($strLanguageCode = null, $strCountryCode = null)</strong>. 
				In this factory method, your class is supposed to load up everything it needs to later on spit
				out translations really quickly. If you store translations in the database, load them 
				here and cache them. Just like any factory method, this methoid is supposed to return an 
				instance of your translator class. </li>
			<li><strong>public function TranslateToken ($strToken)</strong>. Just like you'd expect, after 
				everything is initialized, you can do the actual translation :-). This method is called every 
				time something is to be translated in the user interface - for example, when 
				<strong>QApplication::Translate()</strong> is called. Remember that this method is NOT supposed
				to include any long-running operations - those are supposed to be done in <strong>Load()</strong>.
		</ol>
		
		Take a look at the implementation of the example QSampleTranslation class in View Source - 
		it does something very simple.It loads up the translations from a pre-written array 
		(which could have as easily been a database) in <strong>Load()</strong>, and once 
		that's done, quickly translates everything in <strong>TranslateToken()</strong>. 
	</div>

	<h2>Translations made using the custom QSampleTranslation class</h2>

	<div>
		<strong>French</strong> (default set in Form_Create())<br/>
		Required -> <?php _t('Required'); ?><br/>
		Optional -> <?php _t('Optional'); ?>
		<br/><br/>
		<strong>Spanish</strong><br/>
		<?php
			$i18n = QI18n::Load('es');
		?>
		Required -> <?php echo $i18n->TranslateToken('Required'); ?><br/>
		Optional -> <?php echo $i18n->TranslateToken('Optional'); ?>
	</div>

	<?php $this->RenderEnd(); ?>
<?php require('../includes/footer.inc.php'); ?>
