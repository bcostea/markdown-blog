<?php
require_once 'postRenderer.php';

$pageTitle = "Blog";

$isPost = false;
if (isset($_GET['page']) && !is_null($_GET['page'])) {
  $isPost = true;
  $postSlug = $_GET['page'];
  $page = 'posts/' . $postSlug;
  if (file_exists($page)) {
    $markdown = file_get_contents($page);
    $pageTitle = getPostTitle($markdown);
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
  <link rel="stylesheet" href="blog.css">
  <meta name="viewport" content="width=device-width, initial-scale=1">
</head>

<body>
  <div class="blog">

    <?php if (!$isPost) {
      $path = './posts';
      $files = array_slice(scandir($path), 2);

      foreach ($files as $file) {
        $md = file_get_contents($path . '/' . $file);
        // Get only summary (first lines of post)
        $md = getFirstLines($md, 3);
        $md = addTitleHref($md, $file);
    ?>
        <div class="blog-post">
          <?php echo renderMarkdown($md); ?>
          <a href="<?php echo explode('.', $file)[0] ?>">Read post</a>
        </div>
      <?php }
    } else { ?>
      <div class='markdown'>
        <?php echo renderMarkdown($markdown); ?>
      </div>
    <?php } ?>
  </div>

</body>

</html>