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
  <div class="page">

    <?php if (!$isPost) {
      $path = './posts';
      $files = array_slice(scandir($path), 2);

      foreach ($files as $file) {
        $md = file_get_contents($path . '/' . $file);
        // Get only summary (first lines of post)
        $md = getFirstLines($md, 3);
        $md = addTitleHref($md, $file, $supportsPathVariables);
    ?>
        <div class="blog-post">
          <?php echo renderMarkdown($md); ?>
          <?php echo postHref($file, "Read post", $supportsPathVariables); ?>
        </div>
      <?php }
    } else { ?>
      <div class='markdown post'>
        <?php echo renderMarkdown($markdown); ?>
      </div>
    <?php } ?>
  </div>

</body>

</html>