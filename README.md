# PHP SCORM packager
Create SCORM Package

## Usage
```php
$config = [
    "title" => "",
    "identifier" => "",
    "version" => "",
    "source" => "",
    "destination" => "",
    "masteryScore" => "",
    "startingPage" => "",
    "organization" => "",
];

$packager = new \Romanpravda\Scormpackager\Packager($config);
$packager->buildPackage();
```

## Parameters in config
* title 

   Title of course
   
* identifier

   Course identifier
   
* version

   Version of SCORM package. Must be one of this:
   
   + 1.2
   + 2004 3th Edition
   + 2004 4th Edition
   
* source
 
   Path to directory with course package data
   
* destination

   Path to directory where ZIP archive with course package will be placed
   
* masteryScore

   Score for course passing. Default - 80
   
* startingPage

   Page that will open on course start. Default - index.html
   
* organization

   Name of organization. Default - empty