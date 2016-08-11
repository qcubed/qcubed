# About `includes` directory

This directory contains most of the functional part of the QCubed framework. You can read more about the code in the README files in the respective directories. If you want to learn more about how the code works, go through the code documentations (Doc Comments of various methods).

## Brief description of Subdirectories

  - `_devtools`: Contains the CLI tools for code generation.
  - `base_controls`: Contains the base QControl classes from which main QControls are derived
  - `codegen`: Contains the classes and default templates for code generation. In other words, the code generator lies here.
  - `database`: Contains database adapter classes.
  - `db_ext`: Contains database-specific classes for handling DB-specific behavior (e.g. special data-types) 
  - `fonts`: Fonts for use by the `QImageLabel` control.
  - `framework`: Framework base classes to handle the interactions amongst various modules while providing support for base operations (Type Handling, HTML, Caching, Application base etc.). 
  - `i18n`: Transalation files used by the framework for generated content reside here. 
  - `qform_state_handlers`: FormState Handlers are stored here.
  - `tests`: QCubed unit tests reside here.
  - `watchers`: Database watcher class files are stored here.
  
  