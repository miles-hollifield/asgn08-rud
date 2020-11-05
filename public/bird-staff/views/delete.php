<?php

require_once('../../../private/initialize.php');

if(!isset($_GET['id'])) {
  redirect_to(url_for('/bird-staff/views/index.php'));
}
$id = $_GET['id'];

if(is_post_request()) {

  // Delete bicycle

  $_SESSION['message'] = 'The bicycle was deleted successfully.';
  redirect_to(url_for('/bird-staff/views/index.php'));

} else {
  // Display form
}

?>

<?php $page_title = 'Delete Bird'; ?>
<?php include(SHARED_PATH . '/bird-staff-header.php'); ?>

<div id="content">

  <a class="back-link" href="<?php echo url_for('/bird-staff/views/index.php'); ?>">&laquo; Back to List</a>

  <div class="bicycle delete">
    <h1>Delete Bird</h1>
    <p>Are you sure you want to delete this bird?</p>
    <p class="item"><?php echo h('Bird Name'); ?></p>

    <form action="<?php echo url_for('/bird-staff/views/delete.php?id=' . h(u($id))); ?>" method="post">
      <div id="operations">
        <input type="submit" name="commit" value="Delete Bird" />
      </div>
    </form>
  </div>

</div>

<?php include(SHARED_PATH . '/bird-staff-footer.php'); ?>
