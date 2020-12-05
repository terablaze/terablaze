# TeraBlaze change log

## v0.1.5 (24-february-2019)
#### Bugs fixed
* Other bug fixes
#### Changes
* Adds changes to ensure file existence is verified before including or requiring in some core TeraBlaze scripts
#### Addition
* Added is_https() function and log_error() function

## v0.1.4 (1-september-2018)
#### Bugs fixed
* Fixed delay experienced in some setups when using memcached as the session storage 
* Other bug fixes
#### Changes
* Changed the Memcached session library to support both memcache and memcached when using the memcached server for session storage
#### Addition
* Added support for the php_memcached module, separating Memcached from Memcached when interacting with the memcached server

## v0.1.3 (12-july-2018)
#### Bugs fixed
* Fixes to prevent PHP 7.2 from reporting errors when using array functions on null data 
* Other bug fixes
#### Changes
* Changed the \TeraBlaze\Base::load_view() method to support for a view to be loaded into a variable, useful when using load_view() for mail templates
#### Additions
* Added a FTP library
* Added a Memcached session driver
* Added Several functions in the terablaze/Functions.php file

## v0.1.2 (03-march-2018)
#### Bugs fixed
* Fixed the 'site_url()' function to work properly whether the configuration parameter 'index_script' is set or not

## v0.1.1 (24-january-2018)
#### Bugs fixed
* Fixed issues with url routing when url ends with 'index.php'
#### Changes
* Changed the way default 'controller' and 'action' is gotten from the configuration file
#### Additions
* Added a changelog.md file


## v0.1.0 (14-january-2018)
#### Initial release
