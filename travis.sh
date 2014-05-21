# Script to test the travis build.
# Run this from DOCROOT
# For example, from the default location, you would run vendor/qcubed/framework/travis.sh
export DB=mysql
#echo `dirname $0`/travis/test.php
`dirname $0`/travis/test.php `dirname $0`
