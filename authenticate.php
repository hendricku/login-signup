<?php
session_start();

use Kreait\Firebase\Exception\Auth\InvalidPassword;
use Kreait\Firebase\Exception\Auth\UserNotFound;
use Kreait\Firebase\Factory;
require_once __DIR__.'/vendor/autoload.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $password = $_POST['password'];
    $email = $_POST['email'];

    $factory = (new Factory)->withServiceAccount('keys/infoact-b0cbe-firebase-adminsdk-rpiwx-70526df18a.json');
    $auth = $factory->createAuth();

    try {
        $attemptSignIn = $auth->signInWithEmailAndPassword($email, $password);
        $_SESSION['user_id'] = $attemptSignIn->firebaseUserId();
        $_SESSION['email'] = $email;

        header('location:index.php');

    } catch (InvalidPassword $e) {
        echo "Invalid Credentials";
    }catch(UserNotFound $e){
        echo "Invalid Credentions";
    }catch (Exception $e){
        echo"Error:". $e->getMessage();
    }
} else {
    header("location: login.php");
}
