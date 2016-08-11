# About `tests` directory

This directory contains the tests used to test the stability of QCubed.

To create a new unit test suite:

1) Create a php file in qcubed-unit.
2) In the php file, create a subclass of QUnitTestCaseBase
3) Create tests that are public methods and that start with the word "test".

All methods that start with "test" in each of the files will be run by the tester.

Submissions can be sent via pull requests