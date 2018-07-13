# TeraBlaze change log

## v0.1.3 (12-july-2018)
#### Bugs fixed
* Fixes to prevent PHP 7.2 from reporting errors when using array functions on null data 
* Other bug fixes
#### Changes
* Changed the \TeraBlaze\Base::load_view() method to support for a view to be loaded into a variable
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
