<?php
	// This is the HTML template include file for intro.php
	// Here is where you specify any HTML that you want rendered in the form, and here
	// is where you can specify which controls you want rendered and where.
?>
<?php require('../includes/header.inc.php'); ?>
<?php $this->RenderBegin(); ?>

<div id="instructions">
	<h1><?php _t('Internationalization and Translation') ?></h1>
	<p><?php _t('
		QCubed offers internationalization support via <b>QApplication::Translate()</b> (which internally
		will use the <b>QI18n</b> class). Default language and country
		settings can be setup in <b>prepend.inc.php</b>.  Out of the box, QCubed will check the session to determine
		which language and country is currently being used, but it is really up to the developer to
		determine how you want the language and country codes get discovered (e.g., via the URL, via
		GET arguments, etc.)'); ?>
	</p>
	<p><?php _t('If at any point, you wish to translate to a different language than the one set by the session,
		you can use the <b>QI18n::Load()</b> function to create a unique I18n object, and call <b>TranslateToken()</b>
		on it.'); ?>
	</p>

	<p><?php _t('Language files are in the GNU PO format (see'); ?>
		<a href="http://www.gnu.org/software/gettext/manual/html_node/gettext_9.html" class="bodyLink">http://www.gnu.org/software/gettext/manual/html_node/gettext_9.html</a>
		<?php _t('for more information), and are placed in the <b>' . __QI18N_PO_PATH__ . '</b> directory. Note that 
		you can modify the location of the PO files by changing the <b>__QI18N_PO_PATH__</b> constant in 
		your configuration.inc.php file.'); ?>
	</p>

	<p><?php _t('
		To translate any piece of text, simply use <b>QApplication::Translate(xxx)</b>. Or as a shortcut,
		if you want to do a PHP <b>print()</b> of any translated text in your template, you can use
		the QCubed printing shortcut <b>_t(xxx)</b> -- this does the equivalent of
		<b>print(QApplication::Translate(xxx))</b>.'); ?>
	</p>

	<p><?php _t('
		Note that generated Forms and the QControls are all I18n aware -- they will translate themselves
		based on the selected language (as long as the appropriate language file exists).  QCubed-specific
		langauge files are part of QCubed core, and exist in <b>' . __QI18N_PO_PATH__ . '</b>.
		we are desparately in need of more language files. If you are able to contribute, please take
		the current en.po file and translate it to any currently unsupported language and feel free to
		submit it.  Also note that the Spanish translation (es.po) language files (both in the example
		and in QCubed core) need to be corrected.'); ?>
	</p>

	<p><?php _t('
		Finally, due to the heavy processing of PO parsing, the results of the PO parsing are cached
		using QCache, and cached files are stored in <b>' . __FILE_CACHE__ . '/i18n</b>.'); ?>
	</p>
</div>

<div id="demoZone">
	<h2><?php _t('Internationalization Example'); ?></h2>
	<p>
		<?php _t('Current Language'); ?>: 
		<strong><?php _p(QApplication::$LanguageCode ? QApplication::$LanguageCode : 'none'); ?></strong>
	</p>

	<?php $this->btnEn->Render('Text="' . QApplication::Translate('Switch to') . ' en"'); ?>
	<?php $this->btnEs->Render('Text="' . QApplication::Translate('Switch to') . ' es"'); ?>

	<p><?php _t('To view the People form translated into the selected language, go to'); ?>
		<a href="<?php _p(__VIRTUAL_DIRECTORY__ . __FORMS__); ?>/person_list.php" class="bodyLink"><?php _p(__VIRTUAL_DIRECTORY__ . __FORMS__); ?>/person_list.php</a>
	</p>
	<p><?php _t('We used the <b>QI18n::Load</b> approach to ensure the following is always Spanish:'); ?><br/>
		<?php
			$i18n = QI18n::Load('es');
			echo $i18n->TranslateToken('Internationalization Example');
		?>
	</p>
</div>

<?php $this->RenderEnd(); ?>
<?php require('../includes/footer.inc.php'); ?>
