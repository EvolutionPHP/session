# HTTP Session
PHP Sessions library

## Usage

```php
$config = [
    'name' => 'PHPSESSID', //Session Name
    'save_path' => __DIR__.'/session_directory'
];
$session = new \EvolutionPHP\Session\Session();
$session->start($config);
```

## Session functions

```php
$session = new \EvolutionPHP\Session\Session();
$session->start();

//Set
$session->set('user','root');
//Check if session exists
if($session->has('user')){
    echo 'Session exists';
}
//Unset
$session->remove('user');
//Unset all sessions
$session->clear();
//Destroy
$session->destroy();
```

## Flash Data

```php
$session = new \EvolutionPHP\Session\Session();
$session->start();

//Set
$session->setFlash('user', 'root');
//Check if session exists
if($session->hasFlash('user')){
    echo 'Session exists';
}
//Check if session exists and remove it immediately
if($session->hasFlash('user', true)){
    echo 'Session exists';
}
```