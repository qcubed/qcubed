<?php
	/**
	 * This file contains the QFontFamily class
	 * @filesource
	 */

	/**
	 * QFontFamily class is an abstract class which arranges the commonly used font families
	 * the the main font in the family
	 * @package Controls
	 */
	abstract class QFontFamily {
		// Sans-Serif Fonts
		/** Arial Family */
		const Arial = 'Arial, Helvetica, sans-serif';
		/** Helvetica Family */
		const Helvetica = 'Helvetica, Arial, sans-serif';
		/** Tahoma Family */
		const Tahoma = 'Tahoma, Arial, Helvetica, sans-serif';
		/** TrebuchetMs Family */
		const TrebuchetMs = "'Trebuchet MS', Arial, Helvetica, sans-serif";
		/** Verdana Family */
		const Verdana = 'Verdana, Arial, Helvetica, sans-serif';

		// Serif Fonts
		/** TimesNewRoman Family */
		const TimesNewRoman = "'Times New Roman', Times, serif";
		/** Georgia Family */
		const Georgia = "Georgia, 'Times New Roman', Times, serif";
		
		// Monospaced Fonts
		/** LucidaConsole Family */
		const LucidaConsole = "'Lucida Console', 'Courier New', Courier, monospaced";
		/** CourierNew Family */
		const CourierNew = "'Courier New', Courier, monospaced";
		/** Courier Family */
		const Courier = 'Courier, monospaced';
	}
?>