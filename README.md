# Présentation

Cette librairie vous permet de gérer les Exceptions, et de créer des fichiers de log journalisés et triés dans des dossiers en fonction du type d'erreur.
ErrorHnalder fournit également un système de parsing des fichiers de logs pour un affichage html agréable.

# Documentation

##Différente méthodes :

```php
  ErrorHandler::init($options = []);
  ErrorHandler::isCreated();
  ErrorHandler::log($type = "", Exception $ex, $additionalParameters = null);
```

## Options

```php
ErrorHandler::init($options = []);
```
```php
$options = [
  "logDir" => "/log" //Le chemin du dossier contenant les logs
 ```
