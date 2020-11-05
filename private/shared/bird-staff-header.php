<?php
  if(!isset($page_title)) { $page_title = 'Bird Staff Area'; }
?>

<!doctype html>

<html lang="en">
  <head>
    <title>WNC Birds - <?php echo h($page_title); ?></title>
    <meta charset="utf-8">
    <link rel="stylesheet" media="all" href="<?php echo url_for('/stylesheets/staff.css'); ?>" />
  </head>

  <body>
    <header>
      <h1>Bird Staff Area</h1>
    </header>

    <navigation>
      <ul>
        <li><a href="<?php echo url_for('/bird-staff/index.php'); ?>">Menu</a></li>
      </ul>
    </navigation>

    <?php echo display_session_message(); ?>
