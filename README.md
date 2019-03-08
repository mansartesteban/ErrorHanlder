# Language

[Français](readme/README.fr.md)

# Presentation

This libs make you ablde to handle Exceptions. It create log files sorted by date and type.
ErrorHandler provide a parsing system for log file to display beautiful interface (Inprogress)

# Documentation

## Methods :

```php
ErrorHandler::init($options = []);
ErrorHandler::isCreated();
ErrorHandler::log($type = "", Exception $ex, mixed $additionalParameters = null);
```

## Options

```php 
ErrorHandler::init($options = []);
```
At initialization, you have to pass as parameter an options array to define the behavior of you handler, otherwise it will throw a _LogicException_

```php
$options = [
  "logDir" => "/log", // Path to directory which contains log files
  "cssClass" => "errorHandler-log", // CSS class name that delimitate a log section
];
 ```
**`logDir`** is a `string` you define. If it ends by "/", the "/" will be ignored (_Ex: "/private/log", "/log", "/engine/error/log/", ..._) 
 
 **`cssClass`** is `string`you define (_Ex: "log", "error-log", "admin-log-section", ..._)
___________

```php
ErrorHandler::log($type = "", Exception $ex, $additionalParameters = null)
```
**`type`** is a _`string`_ which define the error type. It is used to create sub-diirectory in $options["_logDir_"] (_Ex: "PDO", "PHP", "API_FACEBOOK", ..._)

**`ex`** is an _`Exception`_ that you catch or instanciate. You can pass any Exception type herited.


## Examples

### Initialization

```php
//File : /src/config/initClasses.php

$optionsErrorHandler = [
  "logDir" => "/private/log",
  "cssClass" => "log-error"
];

if (!ErrorHandler::isCreated()) {
  ErrorHandler::init($optionsErrorHandler);
}
```

### Log an Exception

```php
// Exemple 1 :
try {
  // your code here ...
} catch (PDOException $ex) {
  ErrorHandler::log("PDO", $ex, [$sqlReq, $sqlParams]);
}

// Exemple 2 :
if ($url == null) {
  ErrorHandler::log("logic", new LogicExepction("url for cURL request is null");
}
```
