#How to QCubed
An overview of the intended workflow of developing a website with QCubed, once you have it installed:

1. [Design the Database](Database.md)
2. Run the [code generator](Codegen.md)
3. Use the [Model Connector Editor]() to modify the generated edit and list panel base classes, and codegen again.
4. Possibly customize the code generation templates or control generators to further make modifications, and codegen again.
5. Modify the subclasses that extend the various generated base classes to build out your user interface and create your business logic. See below for more detail.
6. Possibly install qcubed plugins to use particular kinds of controls in certain situations. Possibly codegen again and repeat some of the above steps.
7. Move forms out of the project/forms directory and into a publicly available location to provide the actual entry points for your users.
8. Iterate.

## Where to Make Changes
###Models
Add business logic to your [models](Models.md). Think of the model classes in your project/includes/model directory as the abstraction between your database and your application. As much as possible, do not call QQuery functions, and definitely not SQL, from anywhere other than in your model overrides.

###Edit Connectors
[Edit Connectors](ModelConnector.md) are the glue between your Models and the individual controls in your edit forms. If you want to customize how an individual control interacts with your user, and saves its data to the database, do that in the Model Connector.

###Edit Panels
The edit panel is the container for all your edit controls, and makes calls to the Edit Connector to save or delete the currently viewed object. By default, it just draws all the objects it finds, but you can customize it in a few ways:

* You can use the Model Connector Editor to not generate code for particular fields in the database table.
* You can override the *CreateObjects* function to specify which objects to create and how to create them.
* You can create a template file and specify that template in your Edit Panel constructor to completely control what gets shown, along with any additional html to show. This template is PHP code, so there is no special template syntax to learn.

###List Connectors and List Panels
List Connectors are a little more tricky. The code generator will create both a List object that is a direct subclass of a QControl (a QDataGrid for example), and a List Panel, that includes this List object. Both the List object and the List Panel are capable of defining what columns are shown, and filtering the list of objects displayed. 

Think of the List object as your general purpose lister, and your List Panel as a particular view into the list. Make changes to the List object that will be universal to your application. For example, if you only want to list Projects related to the currently logged in User, you could override the constructor for the List object and set the local $objCondition member variable to a condition that selects just these objects. Make changes to the List Panel, and create other versions of the List Panel for particular situations. Either way, the generated objects give you a lot of flexibility in how to customize them.

###[Forms and Dialogs](Forms.md)
These are your controllers where you assemble the building blocks of what to display to your user. You can add additional controls here, and they also can have a template file so you can control what objects are shown, along with any html to show.

For the dialogs, you can override the generated dialogs to make changes. However, since the forms are the entry points for your users, they are designed to be copied and modified. You will find the default generated forms in the project/forms directory. You will likely want to drag the form out of this directory and into a location that is accessible to your users to make it available to them as you build out your application.

