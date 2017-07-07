#Forms, Dialogs and Panels
The code generator creates default forms and dialogs for you to list and edit tables in the database. However, you can create you own forms and dialogs to do pretty much whatever you want.

The QForm is the base class of a form and is the entry point for a URL that accesses your application. Each form represents a URL path. A form class works with a template file to output the HTML for a web page, including a form tag, and all the internal contents. A form object works together with control objects contained in the form to coordinate the processing of user input, and outputs any changes to a web page as a result of user input.

##Generated List Forms
The code generator creates a default form to list the contents of a table. The form itself is pretty sparse, and simply creates a navigation panel, and a list panel. The actual work of listing the contents of a database table is done inside the list panel.

##Generated Edit Forms and Edit Dialogs
Codegen will create both forms and dialogs to edit a record in the database. You can choose which one to use for any particular case. 

Like the list forms, the edit forms and dialogs are fairly sparse, and delegate most of the work of displaying the individual controls to an edit panel.

The exception is that the edit forms and dialogs do create and manage the buttons that let the user specify whether to save or cancel an edit operation, and if editing a previously created record, whether to delete the record.

##Generated Panels
A panel is a QControl that is a container for other QControls, and wraps them in a div tag. Codegen will create default panels to list records in a database and edit individual records. These panels, by default, are included in the list and edit forms, but you can customize a form to include numerous list panels and edit dialogs, depending on your needs.

Like forms, Panels can have templates that specify just how the controls in the panel are laid out. See the Examples and Tutorial website for examples of panels.

##QForm Lifecycle
Your form is a class that extends the abstract QForm class, and then defines certain functions in the class.

You bootstrap the form, and get the whole application started by calling the static Run function on the form, like this:

```MyForm::Run('MyForm', 'my_template.tpl.php');```

Where MyForm is the name of the QForm class you just declared, and my_template.tpl.php is the name of the template file associated with the form. You can leave off the template file, and QCubed will try to find a template file in the search path that has the same name as the current file, but with the 'tpl.php' suffix.

When the form is being called for the first time, the following functions will be called in order:

* **Form_Run**. Override this to do things that need to be done whenever a form runs, whether new or one we are returning to after an action. One important thing to do here is to authenticate the user if the web page should only be accessed by authorized users.
* **Form_Create**. Override this to create the controls that will be in the form and assign the form as the parent.
* **Form_Initialize**. Override this to initialize your form or controls, if needed. One use for this is to initialize controls based on saved settings.
* **Form_Prerender**. Do anything you need to do just before rendering the form. You will not likely need to override this function.
* **Form_Exit**. Do whatever cleanup you need to do just before returning control back to the webserver. You will not likely need to override this function.

When a user performs an action that you are looking for, like clicking a button, QCubed's javascript file will call the form again and initialize all the controls in the form with their previous state. The effect is similar to a desktop application, where you don't have to recreate the user interface after every user interaction. When the form is called this way, you will see the following functions called.

* **Form_Run**. Like before, you can check if the user is authorized here.
* **Form_Load**. Do anything you need to do just after loading a serialized form, but before any actions have executed. You will not usually override this function.
* * **Form_Prerender**. Do anything you need to do just before rendering the form, but after actions have been executed. You will not usually override this function.
* **Form_Exit**.

As you can see, some functions execute every time the form is entered, and others only in certain situations. The most important function to override is the Form_Create function. Its possible that this will be the only function you need to override.

##The FormState
Generally you will not need to worry about how QCubed works, but knowing about the formstate might help you in certain situations.

After a form loads and initializes all its controls, it serializes itself and saves that serialized version in a formstate variable. When the user executes an action that is sent back to the form, the entire form gets deserialized before the actions are executed, to give the effect that is similar to a desktop application, that the form didn't change between user actions.

###Formstate Handlers
Since a busy system may have many formstates that are active, its important for you to know how the states are saved. 

QCubed has a number of formstate handlers that you can use to control how the formstate is saved. You can use a database, in memory caches, or the session. See the configuration.inc.php file for how to set this up.

###Memory Considerations
 Since the entire form and all its controls are serialized, you must be careful about what you save as a member variable in a form or control. Saving large data objects will slow down the serialization process and unnecessarily use memory.

If you need to make a large amount of information available to the user, consider creating or querying for that information just before you draw, and then make sure you empty that information after drawing so it doesn't get saved. This is how the QDataGrid object draws an html table.
