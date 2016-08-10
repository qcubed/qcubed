# About `includes` directory

This directory contains the most important part of your application apart from the framework. 
 
It is worth keeping in mind that QCubed does a lot of code generation to ease your pain when dealing with databases on various levels. Every time the code generator runs, it updates the files it generated the last time. Most of the classes being built by code generator have a sub-class corresponding to them. During a re-run, the code generator updates the base class but does not ***modify*** the sub-classes. 

The `includes` directory is supposed to have the part of the application which is under your control and is not tinkered around by the code generator. Having said that, we would like to add that the code generator *will create* any files which are supposed to be present but will not alter/overwrite them in case they are already present, thus putting you in control of these files.

If you have to read about what each directory contains, go through the README file inside them.