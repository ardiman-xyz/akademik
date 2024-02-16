<?php
    $no = 1;
?>
        <div class="row">
          <div class="col-lg-12">
            <div class="table-responsive">
              <table class="table table-striped table-bordered table-hover table-sm table-font-sm">
                <thead class="thead-default thead-green">
                  <tr>
                    <th>No</th>
                    <th>Th Akademik</th>
                    <th>Semester</th>
                    <th>Nama Biaya</th>
                    <th>Biaya</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                  foreach ($Fnc_Student_Payment as $data_payment) {
                    ?>
                    <tr>
                      <td><?php echo $no ?></td>
                      <td align="center">
                        <?php echo $data_payment->Year_Id ?>
                      </td>
                      <td align="center">
                        <?php echo $data_payment->Term_Name ?>
                      </td>
                      <td>
                        <?php echo $data_payment->Cost_Item_Name ?>
                      </td>
                      <td align="right">
                        <?php echo number_format($data_payment->Payment_Amount,'0',',','.') ?>
                      </td>
                    </tr>
                    <?php
                    $no++;
                  }
                  ?>
                </tbody>

              </table>
            </div>
          </div>
        </div>
