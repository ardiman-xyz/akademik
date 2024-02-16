<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">×</button>
            <h3>Edit Buku</h3>
        </div>
        <div class="modal-body">
        <form  action="proses/proses/edit.php" method="post" enctype="multipart/form-data">
          <input type="hidden" name="id_buku" value="">
          <label class="col-md-4 col-xs-1">Nama Buku</label><input required oninvalid="this.setCustomValidity('Data Tidak Boleh Kosong')" oninput="setCustomValidity('')" class="form-control" type="text" name="nama" value=""><br>
          <label class="col-md-4 col-xs-1">No ISBN</label><input required oninvalid="this.setCustomValidity('Data Tidak Boleh Kosong')" oninput="setCustomValidity('')" class="form-control" type="text" name="isbn" value=""><br>
          <label class="col-md-4 col-xs-1">No Rak</label><input required oninvalid="this.setCustomValidity('Data Tidak Boleh Kosong')" oninput="setCustomValidity('')" class="form-control" type="text" name="rak" value=""><br>
          <label class="col-md-4 col-xs-1">Kategori</label>
          <select class="form-control" name="kategori">
          </select>
          <br>
          <label class="col-md-4 col-xs-1">Penulis</label><input required oninvalid="this.setCustomValidity('Data Tidak Boleh Kosong')" oninput="setCustomValidity('')" class="form-control" type="text" name="penulis" value=""><br>
          <label class="col-md-4 col-xs-1">Penerbit</label><input required oninvalid="this.setCustomValidity('Data Tidak Boleh Kosong')" oninput="setCustomValidity('')" class="form-control" type="text" name="penerbit" value=""><br>
          <label class="col-md-4 col-xs-1">Tahun Terbit</label><input required oninvalid="this.setCustomValidity('Data Tidak Boleh Kosong')" oninput="setCustomValidity('')" class="form-control" type="number" name="tahun" value=""><br>
          <label class="col-md-4 col-xs-1">Harga Produk</label><input required oninvalid="this.setCustomValidity('Data Tidak Boleh Kosong')" oninput="setCustomValidity('')" class="form-control" type="text" name="hrg" value=""><br>
          <label class="col-md-4 col-xs-1">Harga Jual</label><input required oninvalid="this.setCustomValidity('Data Tidak Boleh Kosong')" oninput="setCustomValidity('')" class="form-control" type="text" name="jual" value=""><br>
          <label class="col-md-4 col-xs-1">PPN</label><input required oninvalid="this.setCustomValidity('Data Tidak Boleh Kosong')" oninput="setCustomValidity('')" class="form-control" type="text" name="ppn" value=""><br>
          <label class="col-md-4 col-xs-1">Diskon</label><input required oninvalid="this.setCustomValidity('Data Tidak Boleh Kosong')" oninput="setCustomValidity('')" class="form-control" type="text" name="diskon" value=""><br>
          <label class="col-md-4 col-xs-1">Sampul</label><input class="form-control" type="file" name="img" ><br>
          <button class="btn btn-primary" type="submit">OK</button>
        </form>
      </div>
    </div>
</div>
