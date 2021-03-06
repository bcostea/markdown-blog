<?php
require_once 'Parsedown.php';
require_once 'ParsedownExtra.php';

$parseDown = new ParsedownExtra();

function renderMarkdown($markdown) {
  global $parseDown;
  return $parseDown->text($markdown);
}

function getPostSlug($fileName) {
  // Post slug is filename, without .md extension
  return substr($fileName, 0, -3);
}

function getPostTitle($postContent) {
  // Gets first # TITLE markdown and returns only text
  $titlePattern = '/^# (.*)/';
  preg_match($titlePattern, $postContent, $matches);
  return $matches[1];
}

function postHref($fileName, $linkText, $supportsPathVariables) {
  return '<a href="' . ($supportsPathVariables ? 'page/' : '?page=') . getPostSlug($fileName) . '">' . $linkText . '</a>';
}

function addTitleHref($postContent, $fileName, $supportsPathVariables) {
  // Make post title clickable (links to post slug)
  $firstLinePattern = '/^# (.*)(\r\n|\r|\n)$/m';
  $replacement  = '# [${1}](' . ($supportsPathVariables ? 'page/' : '?page=') . getPostSlug($fileName) . ')${2}'; // # [title](slug)NEW_LINE
  return preg_replace($firstLinePattern, $replacement, $postContent);
}

function getFirstLines($string, $count) {
  $lines = array_slice(explode(PHP_EOL, $string), 0, $count);
  return implode(PHP_EOL, $lines);
}
