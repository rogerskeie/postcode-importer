A Laravel console command to import postcode data from https://www.postcode.nl

An example file called pcdata.csv is included, as well as a description of the columns in pcdata.schema.

To see an example of this script in action, first make sure to run the migration with "php artisan migrate", then run "php artisan import:postcodes pcdata.csv" to import all postcodes for the province Noord-Holland.