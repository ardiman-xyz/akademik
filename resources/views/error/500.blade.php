<!DOCTYPE html>
<html>
<head>
<title>error</title>
</head>
<body>

  <?php
  try {
    DB::connection()->getPdo();
  } catch (\Exception $e) {
    ?>
    <div class="col-sm-12 alert alert-danger"><center>
      <b>Sambungan Gagal !</b><br>
      Tidak dapat tersambung ke database, pesan error :<br>
      <?php echo $e->getMessage(); ?>
    </div>
    <?php
  }
  ?>

</body>
</html>
