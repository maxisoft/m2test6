<?php
require_once "autoload.php";

function register() {
    $user = new \website\model\User();
    $user->login = $_POST['login'];
    $user->password = \website\utils\Password::hash($_POST['password']);
    $user->role = $_POST['role'];
    $user->first_name = $_POST['first_name'];
    $user->last_name = $_POST['last_name'];
    isset($_POST['date_of_birth']) and ($user->date_of_birth = $_POST['date_of_birth']); //optional field

    return $user->save();
}

if (isset($_POST['login'])){ //TODO Faire isset pour tout les autres champs requis
    try {
       register();
       echo "ok"; //TODO remove
   }
   catch(\website\utils\PasswordException $e) {
        // TODO gestions d'erreurs PASSWORD
        echo 'Bad password: ',  $e->getMessage(), "\n";
   }
   catch (PDOException $e) {
        // TODO gestions d'erreurs DB
        echo 'Caught database exception: ',  $e->getMessage(), "\n";
   }
   catch(exception $e){
        echo 'Caught unexpected exception: ',  $e->getMessage(), "\n";
   }

} else{
    // TODO gestions des messages d'erreurs (ie isset is false)
}


