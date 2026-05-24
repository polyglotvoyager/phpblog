<?php

$config = require __DIR__ . "/config.php";

$posts_dir = $config["posts_dir"];

$files = glob($posts_dir . "/*.md");

$posts = [];

foreach ($files as $file) {

    $slug = basename($file, ".md");

    $content = file_get_contents($file);

    $lines = explode("\n", $content);

  $title = $slug;

    if (isset($lines[0])) {

        $title = trim($lines[0]);

        $title = preg_replace('/^#\s*/', '', $title);
    }

    $posts[] = [

        "slug" => $slug,

        "title" => $title,

        "modified" => filemtime($file)

    ];
}

usort($posts, function ($a, $b) {

    return $b["modified"] <=> $a["modified"];
});

?>

<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Posts</title>

    <style>

    h1, h2, h3 {
      margin: 0.5rem 0;
    }

        body {
            max-width: 800px;
            margin: 40px auto;
            font-family: sans-serif;
        }

        .post {
            margin-bottom: 3rem;
        }

        .date {
            color: #666;
            font-size: 0.9rem;
        }

        a {
            text-decoration: none;
        }

    </style>
</head>
<body>

  <h1>Posts</h1>

<?php foreach ($posts as $post): ?>

    <div class="post">

        <h2>
          <a href="read.php?slug=<?= urlencode($post["slug"]) ?>">

            <?= htmlspecialchars($post["slug"]) ?>

          </a>
        </h2>

        <div class="date">

          <?= date(
            "Y-m-d H:i",
            $post["modified"]
          ) ?>

        </div>

        <p>
          <?= $post['title'] ?>
        </p>


    </div>

<?php endforeach; ?>

</body>
</html>
