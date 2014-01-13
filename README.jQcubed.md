# Welcom to jQCubed

jQCubed is derived from the [QCubed](http:qcu.be) framework with the goal of providing better scaffolding for usability, customizability and look-and-feel.

Here are the features a jQCubed auto-generated application adds on top of a regular QCubed application:

*   _Nested forms:_ One should be able to insert/edit any entity and it's relationships from one screen without jumping through tons of pages.
*   _Better Object Selectors:_ when selecting objects for relationships, search as you type must be available in object selectors.
*   _Generic searches:_ we should provide generic searches that work out of box for all the entities.
*   _Easily customizable searches_
*   _Better data grids with better filters:_ we should auto-generate better data grids with search-as-you-type filters, multi-column sorting, row clicking, etc.
* _Use [jQuery UI theme](http://jqueryui.com/themeroller/) styles_ everywhere that's reasonable, allowing users to plugin other themes.
* _Use [less](http://lesscss.org/ "less css") instead of css_.

To make the aut-generated application easy to customize, jQCubed is guided by the principle that it's easier to remove/disable things than to add them. For searches, data grids, or forms, we should auto-generate controls where all the columns are already setup. It's much easier to customize them by removing the ones that are not needed, than manually adding them.

It turns out, thanks to QCubed's powerfull code generator, we can accomplish all this (and more) with a few plugins and with QCubed's code generator.
Here are some new classes I created that are central for the implementation of all the features above

* ```QCallback``` - this class tries to provide a consistent interface to the many ways PHP (and Qcubed) does callbacks, closures, user function calls, etc.
* ```QGenericSearchOptions```, ```SearchPanel``` - provide the generic search capabilities mentioned above

The following new classes are auto-generated in jQCubed to provide all the functionality described above

* ```[Model]ObjectSelector``` - provide the object selection feature
* ```[Model]DataTable``` - provide the new data grids
* ```[Model]SearchPanel``` - provide the generic search feature
* ```[Model]Popup``` - factory classes for various popup dialogs for creating, editing, searching etc.
* ```[Model]Toolbar``` - a container for various controls and buttons to load, create, edit, search, delete, etc.
* ```[Model]ViewPanel``` - panel for showing the entity
* ```[Model]ViewWithRelationships``` - view panel also showing the relationships of the entity
* ```[Model]ViewWithToolbar``` - view panel combined with the toolbar control above
* ```[Model]UpdatePanel``` - panel for updating the entity
* ```[Model]ListDetailView``` - provide the classic List-Detail, where the list panel contains a search and a data table, and the Detail panel contains a view panel with the toolbar (and relationships).

In addition all the controls are generated to use jQuery UI theme classes. The default theme included in jQCubed is the sunny theme.

### QCubed Plugins required by jQCubed
The following plugins are included as part of jQCubed

* [QDataTables](https://github.com/qcubed/plugins/tree/master/QDataTables)
* [QSelect2ListBox](https://github.com/qcubed/plugins/tree/master/QSelect2ListBox)
* [QJqDateTimePicker](https://github.com/qcubed/plugins/tree/master/QJqDateTimePicker)
* [QDateRangePicker](https://github.com/qcubed/plugins/tree/master/QDateRangePicker)

## Installation
Since jQCubed is derived from QCubed, the installation procedure is exactly the same as for QCubed.
After downloading the jQCubed package, please follow the installation instructions for QCubed.

## Customizing your jQCubed application
Even though jQCubed tries hard to auto-generate a good looking and usable application out of the box, you will almost certainly need to customize it to your needs. Fortunately jQCubed makes the process very easy.
The principle is the same as it always was in QCubed: customize the auto-generated subclasses and templates.
Below are more details for the most commonly needed customizations

* UI and layout:
 1. In the auto-generated subclass of the corresponding panel class (e.g. PersonViewPanel), create a constructor and point the view template to your own:
```$this->strTemplate = __APP_INCLUDES__ . '/templates/MyPersonView.tpl.php'```
 1. copy the auto-generated template into ```app_includes/templates/```
 1. modify the new template as desired

* DataTables:

   In the auto-generated subclass (e.g. PersonDataTable), create a constructor and remove or add new columns, or change sorting preferences
  * To remove columns use any of the "RemoveColumn" methods in QSimpleTable
  * To reposition columns use any of the "MoveColumn" method in QSimpleTable
  * To add new columns use any of the "AddColumn" or "CreateColumn" methods in QSimpleTable. Note that this allows adding columns that are not necessarily database fields, but could be any calculated expressions. See the QSimpleTable examples for more details.
  * To rename a column use QSimpleTable::RenameColumn
  * To post process the value that appears in the data table cells set the PostCallback property of the column object. For example:
```
$this->GetColumnByName("Street")->PostCallback = 'ReformatAddress';
```
  * To hide columns, use the ColumnDefs property of QDataTable
  * To pre-sort the table, use the Sorting property of QDataTable.
* Searches
 * To modify the search behaviour of the data tables' built-in search, set the ```$objSearchOptions``` member variable of the data table class to a new ```QGenericSearchOptions``` and set its properties as desired. For example, in the PersonDataTable constructor:
```
$this->objSearchOptions = new QGenericSearchOptions();
$this->objSearchOptions->ExcludeProperties = array("Id");
$this->objSearchOptions->StringComparisonMode = array("FirstName", QStringComparisonMode::startsWith);
```
 * Similarly, to modify the search behaviour of the ObjectSelector's, create a constructor in the auto-generated subclass (e.g. PersonObjectSelector), and set its ```$objSearchOptions``` member variable with appropriate options.

### Note about the "jQCubed" name
Using a different name for this project is in no way an indication of any intentation to fork the QCubed project or its user base. In fact, I have made every effort (and succeeded) to not modify any QCubed core files. Thus you can get jQCubed by just adding to the stock QCubed package.

The name "jQCubed" is an homage to both jQuery and QCubed. If and when this projects merges back into QCubed core, this name will disappear and no longer be used.

## Screenshoots

Here is how the standard QCubed dashboard looks with jQCubed (and the sunny jQuery theme):

![jQCubed dashboard](http://i.troll.ws/f24eafce.png)
