<?php require('../includes/header.inc.php'); ?>
<?php $this->RenderBegin(); ?>

<div id="instructions">
    <h1>Advanced Localization Techniques</h1>
    <p>QCubed allows you to store your translations in any format that works for you. If you
        already have a database with translated terms in a given schema, you probably don't want 
        to have to generate .po files, right? Or, if you have a custom XML file that defines the
        translations - you want to keep using it, understandably.</p>

    <p>QCubed allows you to do just that. It's actually really easy - just define your own class that 
        implements the <strong>QTranslationBase</strong> interface. In that class, you specify how 
        exactly the translations should be retrieved. There are static methods that you need
        to implement:</p>
    <ol>
        <li><code>public static function Initialize()</code>
            <p>In this factory method, your class is 
                supposed to read <code>QApplication::$LanguageCode</code> and 
                <code>QApplication::$CountryCode</code> settings and  based on these settings, 
                initialize itself, returning a new instance (usually by calling <code>self::Load()</code>)</p>
        </li>
        <li><code>public static function Load ($strLanguageCode = null, $strCountryCode = null)</code> 
            <p>In this factory method, your class is supposed to load up everything it needs to later on spit
                out translations really quickly. If you store translations in the database, load them 
                here and cache them. Just like any factory method, this methoid is supposed to return an 
                instance of your translator class.</p>
        </li>
        <li><code>public function TranslateToken ($strToken)</code>
            <p>Just like you'd expect, after everything is initialized, you can do the actual translation :-). This method is called every 
                time something is to be translated in the user interface - for example, when 
                <code>QApplication::Translate()</code> is called. Remember that this method is NOT supposed
                to include any long-running operations - those are supposed to be done in <code>Load()</code>.
        </li>
    </ol>
    <p>Take a look at the implementation of the example QSampleTranslation class in View Source - 
        it does something very simple.It loads up the translations from a pre-written array 
        (which could have as easily been a database) in <code>Load()</code>, and once 
        that's done, quickly translates everything in <code>TranslateToken()</code>.</p> 
</div>

<div id="demoZone">
	<h2>Translations made using the custom QSampleTranslation class</h2>
    <h3>French <small>(default set in <code>Form_Create()</code>)</small></h3>
    <ul>
        <li>Required -> <?php _t('Required'); ?></li>
        <li>Optional -> <?php _t('Optional'); ?></li>
    </ul>
    <h3><strong>Spanish</strong></h3>
    <?php $i18n = QI18n::Load('es'); ?>
    <ul>
        <li>Required -> <?= $i18n->TranslateToken('Required'); ?></li>
        <li>Optional -> <?= $i18n->TranslateToken('Optional'); ?></li>
    </ul>
</div>

<?php $this->RenderEnd(); ?>
<?php require('../includes/footer.inc.php'); ?>