<?php
use Google\Cloud\Firestore\FirestoreClient;

require_once __DIR__ . '/vendor/autoload.php';

$db = new FirestoreClient([
    'keyFilePath' => 'keys\infoact-b0cbe-firebase-adminsdk-rpiwx-70526df18a.json',
    'projectId' => 'infoact-b0cbe'
]);

if ($_SERVER['REQUEST_METHOD'] === "POST") {
    $id = $_GET['id'];
    $content = $_POST['comment-input'];

    if ($content != "") {
        $post = $db->collection('posts')->document($id)->snapshot();
        $db->collection('comments')->add([
            'post-id' => $id,
            'user' => "Anonymous",
            'content' => $content
        ]);
        $db->collection('posts')->document($id)->set([
            'comments' => $post->get('comments') + 1
        ], ['merge' => true]);

        echo "Added Comment!";
        header("Location: comment.php?id=" . $id);
        exit();
    } else {
        echo "Empty!";
    }
} else {
    $id = $_GET['id'];
    $post = $db->collection('posts')->document($id)->snapshot();
    if (!$post->exists()) {
        echo "Post not found.";
        exit();
    }
    $commentsRef = $db->collection('comments')->where('post-id', '=', $id)->documents();
    $comments = [];
    foreach ($commentsRef as $comment) {
        $comments[] = $comment->data();
    }
}
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Add Comment</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
</head>
<body>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Facebook Lite</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.3.0/font/bootstrap-icons.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
</head>
<body>
  <nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container">
      <a class="navbar-brand" href="index.php">Facebook Lite</a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav">
          <li class="nav-item">
            <a class="nav-link" href="#">Home</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#">About</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#">Contact</a>
          </li>
        </ul>
      </div>
      <div class="navbar-nav">
        <?php
        session_start();
        if (isset($_SESSION['user_id'])) {
          echo '<a class="nav-link" href="logout.php">Logout</a>';
        } else {
          echo '<a class="nav-link" href="signup.php">Register</a>
                <a class="nav-link" href="login.php">Login</a>';
        }
        ?>
      </div>
    </div>
  </nav>





  <div class="container">

    <div class="card mt-5">
      <div class="card-header">
        <?php echo $post['title']; ?>
      </div>
      <div class="card-body">
        <h5 class="card-title"><?php echo $post['body']; ?></h5>
        <p class="card-text">Reactions: <?php echo $post['reactions']; ?></p>
        <p class="card-text">Comments: <?php echo $post['comments']; ?></p>
      </div>
    </div>
    <form action="" method="POST">
      <div class="mb-1">
        <label for="comment-input" class="form-label">Comment:</label>
        <textarea class="form-control" id="comment-input" name="comment-input" rows="1"></textarea>
      </div>
      <button type="submit" class="btn btn-primary">Submit</button>
    </form>
    <div class="mt-5">
      <h3>Comments</h3>
      <?php if (empty($comments)) { ?>
        <p>No comments yet.</p>
      <?php } else { ?>
        <?php foreach ($comments as $comment) { ?>
          <div class="card mt-3">
            <div class="card-body">
              <h5 class="card-title"><?php echo $comment['user']; ?></h5>
              <p class="card-text"><?php echo $comment['content']; ?></p>
            </div>
          </div>
        <?php } ?>
      <?php } ?>
    </div>
  </div>
</body>
</html>
