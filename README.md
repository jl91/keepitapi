### Before start application run composer install

#### To run api run:
`php -S 0.0.0.0:8080 -t public public/index.php`

To sort fields just put ``sort`` param on query string
passing what field you need to sort.

e.g 
######/?sort=id <- it will sort by id asc

######/sort=-id < - it will sort by id desc