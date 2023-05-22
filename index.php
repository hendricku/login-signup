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

  <div class="container my-4">
    <div class="row justify-content-center">
      <div class="col-8">
        <form>
          <div class="input-group">
            <input type="text" name="search" class="form-control" placeholder="Search">
            <button class="btn btn-outline-primary">Search</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <?php
  use Google\Cloud\Firestore\FirestoreClient;

  require_once __DIR__.'/vendor/autoload.php';

  $db = new FirestoreClient([
      'keyFilePath' => 'keys/infoact-b0cbe-firebase-adminsdk-rpiwx-70526df18a.json',
      'projectId' => 'infoact-b0cbe'
  ]);

  $postCol = $db->collection('posts');

  $posts = $postCol->documents();

  $search = isset($_GET['search']) ? $_GET['search'] : "";
  $filtered = array();

  foreach ($posts as $post) {
      $postc = strtolower($post['title']);
      $searchc = strtolower($search);
      if (strpos($postc, $searchc) !== false) {
          $filtered[] = $post;
      }
  }

 // echo "Searching for: " . $search . "<br>";

  if (empty($filtered)) {
      echo "No results found.";
  } else {
      foreach ($filtered as $post) {
          echo '<div class="card m-5 mt-5">
                  <h5 class="card-header">' . $post['title'] . '</h5>
                  <div class="card-body">
                    <h5 class="card-title">' . $post['body'] . '</h5>
                    <p class="card-text">Reactions: ' . $post['reactions'] . '</p>
                    <a href="comment.php?id=' . $post->id() . '" class="btn btn-link">Comments: ' . $post['comments'] . '</a>
                    <a href="increment.php?id=' . $post->id() . '" class="';
          if ($post['status'] == 'unlike') {
              echo "btn btn-outline-danger float-end";
          } elseif ($post['status'] == 'like') {
              echo "btn btn-danger float-end";
          }
          echo '"><i class="bi bi-suit-heart-fill"></i></a>
          </div>
          </div>';
      }
  }
  ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz
