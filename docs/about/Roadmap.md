#Roadmap
##v3.2
No specific plans are laid out for v3.2, but the general idea is major improvements and minor fixes, that hopefully will not impact v4. This may include updating various dependencies, like JQuery, JQuery UI, and Bootstrap as new versions emerge. 

One potential enhancement would be to look into how PubNub could be used by the Watcher engine to trigger UI updates when the database changes. This would be easier than implementing a WebSocket, since PHP doesn't currently do asynchronous operations very well.

##v4
This is mainly some much needed restructuring. We hope to accomplish the following:

* Namespacing the code and the implementation of PSRs 1,2 and 4 at a minimum. Hopefully this will include a tool to help automatically convert existing code to the new namespaces.
* Restructuring the directory structure and breaking the code base into self-sustainable parts. In particular, we would like to separate out the code generator, the database engine, the QQuery engine, and the forms engine.
* Sails-like implementation of QQuery. This will enable being able to use QQuery to do more customized updates and deletes (primarily since these operations are much more database dependent). This will also enable the ability to use NoSQL databases against a QQuery. Potentially create database adapters for Amazon and Google database offerings.
* Generate PHP7 type annotations (and possibly Hack native code too), from the code generator
* Move to an object-oriented version of qcubed.js, using ES6 and/or Typescript. Remove dependency on JQuery and JQuery UI, since ES6 solves many of the problems we were trying to solve there. Hopefully finally remove "eval" from the javascript in qcubed.js.

##Future
* Port to Go?
* Code generate ReactJs, Angular or Polymer forms
* Polymer widgets