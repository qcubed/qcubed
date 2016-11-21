# Script to locally simulate a travis build from within your project's environment.
# To use this:
#	1) create a 'qcubed' database and run the corresponding examples script to load it with the example data
# 	2) cd to your DOCROOT
# 	3) run this script from the DOCROOT directory
# For example, from the default root location after installation, you would run vendor/qcubed/qcubed/travis.sh
export DB=mysql
`dirname $0`/travis/test.php `dirname $0`
