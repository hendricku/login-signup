<?php
use Google\Cloud\Firestore\FirestoreClient;
require_once __DIR__.'/vendor/autoload.php';

$db= new FirestoreClient([
    'keyFilePath'=>'keys\infoact-b0cbe-firebase-adminsdk-rpiwx-70526df18a.json',
    'projectId' =>'infoact-b0cbe'

]);
$id = $_GET['id'];
$postref=$db->collection('posts');
$snap=$postref->document($id)->snapshot();
$inc = $snap['reactions'];
$status =$snap['status'];
if($status == 'unlike' && $inc <= 0){
    $postref->document($id)->set([
'reactions'=> ++$inc,
'status'=>'like'
],['merge' =>true]);
}
elseif($status == 'like' && $inc >0){
    $postref->document($id)->set([
        'reactions'=>--$inc,
        'status'=>'unlike'
        ],['merge' =>true]);
        
}
header('location:index.php');
?>