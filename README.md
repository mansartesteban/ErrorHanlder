# Présentation

Cette librairie vous permet de gérer les Exceptions, et de créer des fichiers de log journalisés et triés dans des dossiers en fonction du type d'erreur.
ErrorHnalder fournit également un système de parsing des fichiers de logs pour un affichage html agréable.

# Documentation

## Différentes méthodes :

```php
ErrorHandler::init($options = []);
ErrorHandler::isCreated();
ErrorHandler::log($type = "", Exception $ex, mixed $additionalParameters = null);
```

## Options
```php 
ErrorHandler::init($options = []);
```
A l'initialisation, il est nécessaire de passer en paramètres un tableau d'options pour définir la façon dont vous allez utiliser cette classe, sans quoi une _LogicException_ sera lancée.

```php
$options = [
  "logDir" => "/log", // Le chemin du dossier contenant les logs
  "cssClass" => "errorHandler-log", // Nom de la classe CSS délimitant une section de log
];
 ```
`logDir`
 Chaîne de caractères selon votre choix (_Ex: "PDO", "PHP", "API_FACEBOOK", ..._)
 
 `cssClass`
 Chaîne de caractères selon votre choix (_Ex: "log", "error-log", "admin-log-section", ..._)
___________

```php
ErrorHandler::log($type = "", Exception $ex, $additionalParameters = null)
```
_$type_ est une chaîne de caractères définissant le type d'erreur. Est utilisé pour créer un sous-répertoire dans le dossier $options["logDir"].


## Exemples

### Initialisation

```php
//File : /src/config/initClasses.php

$optionsErrorHandler = [
  "logDir" => "/private/log"

if (!ErrorHandler::isCreated()) {
  ErrorHandler::init($optionsErrorHandler);
}
```

### Catch d'une Exception

```php
try {
  // your code here ...
} catch (PDOException $ex) {
  ErrorHandler::log("PDO", $ex, [$sqlReq, $sqlParams]);
}
```
