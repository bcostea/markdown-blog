<?php
require_once 'postRenderer.php';

// Check if we're running locally in the development server
$supportsPathVariables = true;
if (isset($_SERVER['SERVER_SOFTWARE']) && strpos($_SERVER['SERVER_SOFTWARE'], "Development Server") > -1) {
  $supportsPathVariables = false;
}

$pageTitle = "Startup Factory";
$isPost = false;

if (isset($_GET['page']) && !is_null($_GET['page'])) {
  $isPost = true;
  $postSlug = $_GET['page'];

  if ($supportsPathVariables) {
    $postSlug = explode("/", $postSlug)[2];
  }

  $page = 'posts/' . $postSlug . ".md";
  if (file_exists($page)) {
    $markdown = file_get_contents($page);
    $pageTitle = getPostTitle($markdown) . " - " . $pageTitle;
  } else {
    $markdown = "# 404 <br/> Post '$postSlug' not found 😢 ";
    $pageTitle = 'Blog post not found!';
  }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <title><?php echo $pageTitle ?></title>
  <base href="/" />

  <link rel="stylesheet" href="index.css">

  <meta name="viewport" content="width=device-width, initial-scale=1">
</head>

<body>
  <main class="page">

    <?php if (!$isPost) {
      $path = './posts';
      $files = array_slice(scandir($path), 2);
    ?>
      <span class="logo">Startup Factory</span>
      <h1>Table of contents</h1>
      <br />
      <?php
      foreach ($files as $file) {
        $md = file_get_contents($path . '/' . $file);
        // Get only summary (first lines of post)
        $md = getFirstLines($md, 3);
        $md = str_replace("#", "*", $md);
        $md = addTitleHref($md, $file, $supportsPathVariables);
      ?>

        <article class="blog-post">
          <?php echo renderMarkdown($md); ?>
          <?php echo postHref($file, "Read post", $supportsPathVariables); ?>
        </article>
      <?php }
    } else { ?>
      <span class="logo">Startup Factory</span>
      <a class="back-btn" href='/'>&lt;</a>

      <div class='markdown post'>
        <?php echo renderMarkdown($markdown); ?>
      </div>
    <?php } ?>
  </main>

</body>

</html>