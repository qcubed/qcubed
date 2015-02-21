This directory contains the code to generate the jQuery objects. It does this by scraping the jQuery documentation
website. To run it, make sure your base_controls directory is writable by your html server, and then load
jq_control_gen.php into your browser. It takes a couple of minutes to generate everything, so be patient.

It has dependencies on the following libraries, which are obtainable via composer:

html2text/html2text
shark/simple_html_dom