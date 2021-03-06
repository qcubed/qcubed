#Models, Nodes and QQuery
Most of what you need to know about Models, Nodes and QQuery can be learned from the Examples and Tutorial website. Here is a quick overview.

##Database Adapters
While there is a "standard" SQL, most SQL databases still do many things differently from each other, and these differences are abstracted in the database adapters. These adapters are located in the *vendor/qcubed/qcubed/includes/database* directory. You specify which adapter you want to use in the database adapter settings in your *configuration.inc.php* file.

As of version 3.1, the database adapters still have a ways to go before they completely abstract away the differences in SQL between SQL databases. For the most part, they are helpers in the codegen process, enabling the code generator to examine the schema of the database.

##QQuery
The QQuery class encapsulates functions that help you build SQL statements to execute against the database, without actually having to write any SQL. QQuery interacts with QQNodes to traverse the database structure, and to build fragements of SQL. For the most part, you will only interact directly with QQuery when creating logic for conditions when selecting from the database, and when specifying additional clauses for ordering or joining tables.

##QQNodes
A QQNode represents a table or a column in the database and the relationships that connect them. QQNodes are generated by the code generator. QQNodes make it easy to join tables and follow foreign key chains.

A node starts with a reference to a table. You then select a field in a related table by using standard PHP properties. For example, the following node refers to the first name of a manager in a project, and automatically joins the Project and Person table together to do that:

```QQN::Project()->Manager->FirstName```

For more detail on using nodes, see the Examples and Tutorial website.

##Models
Model objects are created by the code generator. Each object represents a table in the database.

To get an object, you can use one of the following static functions located in the object:

* **Load**. The basic object loader that gets an object from the database based on its unique id.
* **QuerySingle**. Also gets a single object, but lets you specify custom conditions on how to find the object. If your conditions select more than one object, it will return the first one found.
* **QueryArray**. Lets you get an array of objects based on a condition you specify.
* **QueryCursor**. Similar to QueryArray, but lets you step through the results using a cursor rather than getting them all at once.

Once you have an object, you can use the generated getters and setters to access the individual fields in the object. After changing an object, use the **Save** function on the object to write it back to the database.

To create an object, simply use the PHP **new** keyword, and fill in the fields with setters. Then call **Save**.

To delete an object, first load it using one of the functions above, and then call **Delete** on the object.

You can use an object to get other objects from the database just by referring to them through their foreign keys. For example:

```
$objProject = Project::Load(1);
$objManagerPerson = $objProject->Manager;
```

Will get a Person object that is the manager of the  project with id of 1, provide the Project table has a *manager_id* field that is a foreign key pointing to a Person record in the database.

See the Examples and Tutorial website for more detailed information on how to use models, nodes and QQuery to select specific information from the database, join tables, sort and limit results, and optimize your queries.