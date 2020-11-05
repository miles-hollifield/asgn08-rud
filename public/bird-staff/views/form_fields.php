<?php
// prevents this code from being loaded directly in the browser
// or without first setting the necessary object
if(!isset($bird)) {
  redirect_to(url_for('/bird-staff/views/index.php'));
}
?>

<dl>
  <dt>Name</dt>
  <dd><input type="text" name="common_name" value="" /></dd>
</dl>

<dl>
  <dt>Habitat</dt>
  <dd><input type="text" name="habitat" value="" /></dd>
</dl>

<dl>
  <dt>Food</dt>
  <dd><input type="text" name="food" value="" /></dd>
</dl>

<dl>
  <dt>Conservation Level</dt>
  <dd>
    <select name="conservation_id">
      <option value=""></option>
    <?php foreach(Bird::CONSERVATION_OPTIONS as $con_id => $con_name) { ?>
      <option value="<?php echo $con_id; ?>"><?php echo $con_name; ?></option>
    <?php } ?>
    </select>
  </dd>
</dl>


<dl>
  <dt>Backyard Tips</dt>
  <dd><input type="text" name="backyard_tips" value="" /></dd>
</dl>
