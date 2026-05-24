<?php

$config = require __DIR__ . "/config.php";

$error = null;
$success = false;

if ($_SERVER["REQUEST_METHOD"] === "POST") {

  $token = $_POST["token"] ?? "";

  if (!hash_equals($config["editor_token"], $token)) {

    $error = "Invalid token.";

  } else {

    $slug = trim($_POST["slug"] ?? "");
    $content = trim($_POST["content"] ?? "");

    if ($slug === "" || $content === "") {

      $error = "All fields are required.";

    } else {

      $slug = strtolower($slug);

      $slug = preg_replace(
        '/[^a-z0-9-]/',
        '-',
        $slug
      );

      $slug = preg_replace('/-+/', '-', $slug);

      $posts_dir = $config["posts_dir"];

      if (!is_dir($posts_dir)) {
        mkdir(
          $posts_dir,
          0755,
          true
        );
      }

      $path =
        $config["posts_dir"]
      . "/"
      . $slug
      . ".md";

      if (file_exists($path)) {

        $error = "Post already exists.";

      } else {

        file_put_contents($path, $content);

        $success = true;

        // redirect to read.php
        header("Location: read.php?slug=" . urlencode($slug));
        exit;
      }
    }
  }
}

?>

<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>New Post</title>
</head>
<body>

<h1>New Post</h1>

<?php if ($error): ?>
    <p><?= htmlspecialchars($error) ?></p>
<?php endif; ?>

<?php if ($success): ?>
  <p>Post saved.</p>
<?php endif; ?>

<form method="post">

  <input
    id="notetokenid"
    name="token"
    placeholder="Editor token"
    required
  >
  <button type="button" onclick="savetoken()">Save token</button>

  <br><br>

    <input
        type="text"
        name="slug"
        placeholder="Slug"
        required
    >

    <br><br>

    <textarea style='width: 90vw; margin: 0 0.5rem; height: 70vh;'
              name="content"
              placeholder="Markdown..."
              required
    ></textarea>

    <br><br>

    <button type="submit">
      Save
    </button>

</form>

<script>
  // load token
  document.getElementById("notetokenid").value = window.localStorage.getItem("phpblognotestoken");

  function savetoken() {
  window.localStorage.setItem("phpblognotestoken", document.getElementById("notetokenid").value);
  }
</script>
</body>
</html>
