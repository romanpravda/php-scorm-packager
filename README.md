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
    "metadataDescription" => "",
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
   
* metadataDescription

    Metadata description. Default - build date and technology
    
## If you need SCORM 2004 4th Edition

#### You must pass the metadata config as the second parameter

```php
$config = [
    ...
];

$metadataConfig = [
    "entryIdentifier" => "",
    "catalogValue" => "",
    "lifeCycleVersion" => "",
    "classification" => "",
];

$packager = new \Romanpravda\Scormpackager\Packager($config, $metadataConfig);
$packager->buildPackage();
```

## Parameters in metadata config
* entryIdentifier 

   Metadata entry identifier. Default - "1"
   
* catalogValue

   Metadata catalog value. Default - "Catalog"
   
* lifeCycleVersion

   LifeCycle version of Metadata. Default - "1"
   
* classification

   Metadata classification. Default - "educational objective"
 