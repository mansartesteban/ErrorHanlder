# Langues

[English](../README.md)

# Sommaire

[Erreurs](

# Présentation

Cette librairie vous permet de gérer les Exceptions, et de créer des fichiers de log journalisés et triés dans des dossiers en fonction du type d'erreur.
ErrorHnalder fournit également un système de parsing des fichiers de logs pour un affichage html agréable.
Pratique pour les CRM, les API Rest ou tout autre type d'application n'ayant pas de retour visuel direct des erreurs générées par les requêtes et autre.

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
**`logDir`**
 Chaîne de caractères selon votre choix. Si un slash est renseigné en fin de chaîne, il sera ignoré (_Ex: "/private/log", "/log", "/engine/error/log/", ..._) 
 
 **`cssClass`**
 Chaîne de caractères selon votre choix (_Ex: "log", "error-log", "admin-log-section", ..._)
___________

```php
ErrorHandler::log($type = "", Exception $ex, $additionalParameters = null)
```
**`type`** est une _`String`_ définissant le type d'erreur. Est utilisé pour créer un sous-répertoire dans le dossier $options["logDir"]. (_Ex: "PDO", "PHP", "API_FACEBOOK", ..._)

**`ex`** est une _`Exception`_ que vous pouvez catch ou créer. Le type est _`Exception`_ vous, pouvez donc utiliser n'importe quelle Exception héritée


## Exemples

### Initialisation

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

### Catch d'une Exception

```php
// Exemple 1 :
try {
  // your code here ...
} catch (PDOException $ex) {
  ErrorHandler::log("PDO", $ex, [$sqlReq, $sqlParams]);
}

// Exemple 2 :
if ($url == null) {
  ErrorHandler::log("logic", new LogicExepction("L'url pour la requête cURL est nulle")
}
```
