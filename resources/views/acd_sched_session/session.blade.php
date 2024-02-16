<?php
$q = array();
foreach ($query as $value) {
  $q[] = $value->Order_Id;
}
?>

{!! Form::label('', 'Sesi', ['class' => 'col-md-4 col-xs-12']) !!}
<select  class="form-control" name="Faculty_Id">
  <option value="">Pilih Sesi </option>
  <?php
  for ($i=1; $i <= 20; $i++) {
    if (!in_array($i , $q)) {
      ?>
      <option value="{{ $i }}">{{ $i }}</option>
      <?php
    }
  }
  ?>
</select>
