<?php

use Kreait\Firebase\ServiceAccount;
use Kreait\Firebase\Factory;
use Kreait\Firebase\Http\RequestWithSubRequests;

require_once __DIR__.'/vendor/autoload.php';
//path to key 
$serviceAccountPath = 'keys\infoact-b0cbe-firebase-adminsdk-rpiwx-70526df18a.json';

$serviceAccountPathJson = file_get_contents($serviceAccountPath);


$serviceAccount = ServiceAccount::fromValue($serviceAccountPathJson);


$factory = (new Factory)->withServiceAccount('keys/infoact-b0cbe-firebase-adminsdk-rpiwx-70526df18a.json');
$auth = $factory->createAuth();
$firestore = $factory->createFirestore();

if($_SERVER['REQUEST_METHOD']==='POST'){


$username =$_POST['username'];
$password =$_POST['password'];
$email =$_POST['email'];

$newUser = [
    'email'=>$email,
    'password'=>$password
    

];
try{

$user = $auth->createUser($newUser);
$firestore->database()->collection('users')->document($user->uid)->set([
    'email'=>$email,
    'username'=>$username,
    
]);
header('location:login.php');
} catch (Exception $e) {
    echo $e->getMessage();
}
    
}else{

    header("location: login.php ");

}