<?php

$config = require __DIR__ . "/config.php";

$slug = $_GET["slug"] ?? "";

$slug = strtolower($slug);

$slug = preg_replace(
    '/[^a-z0-9-]/',
    '',
    $slug
);

$path =
    $config["posts_dir"]
    . "/"
    . $slug
    . ".md";

if (!file_exists($path)) {

    http_response_code(404);

    die("Post not found.");
}

$content = file_get_contents($path);

?>

<!DOCTYPE html>
<html>
  <head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= htmlspecialchars($slug) ?></title>

    <script src="marked.min.js"></script>

    <style>
        body {
            max-width: 800px;
            margin: 40px auto;
            font-family: sans-serif;
            line-height: 1.6;
        }

        pre {
            background: #eee;
            padding: 12px;
            overflow-x: auto;
        }

        code {
            font-family: monospace;
        }

    #content {
      margin: 1rem;
    }

    </style>
</head>
<body>

  <div>
    <a href="index.php">All posts</a>
  </div>

  <h2><?= $slug ?></h2>

  <div>
    <a id="editlink" href="">Edit post</a>
  </div>

  <div id="content"></div>

  <script>

  const markdown =
    <?= json_encode($content) ?>;

  document.getElementById("content").innerHTML =
    marked.parse(markdown);

  document.getElementById('editlink').href = "edit.php?slug=" +
                                             encodeURIComponent(
                                               new URLSearchParams(window.location.search).get("slug")
                                             );
  </script>

</body>
</html>
