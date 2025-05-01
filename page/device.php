<?php
$page = $_GET['page'];
$insert = false;
$edit_success = false;
$edit_data = null;

// Koneksi database diharapkan sudah tersedia melalui $conn

// Proses insert
if (isset($_POST['serial_number']) && !isset($_POST['edit_mode'])) {
  $serial_number = $_POST['serial_number'];
  $controller_type = $_POST['controller'];
  $location = $_POST['location'];
  $status = $_POST['status'];

  $sql_insert = "INSERT INTO devices (serial_number, mcu_type, location, active) VALUES ('$serial_number', '$controller_type', '$location', '$status')";
  if (mysqli_query($conn, $sql_insert)) {
    $insert = true;
  }
}

// Proses edit
if (isset($_POST['serial_number']) && isset($_POST['edit_mode'])) {
  $serial_number = $_POST['serial_number'];
  $controller_type = $_POST['controller'];
  $location = $_POST['location'];
  $status = $_POST['status'];

  $sql_edit = "UPDATE devices SET mcu_type = '$controller_type', location = '$location', active = '$status' WHERE serial_number = '$serial_number'";
  if (mysqli_query($conn, $sql_edit)) {
    $edit_success = true;
  }
}

// Ambil data untuk ditampilkan
$sql = "SELECT * FROM devices";
$result = mysqli_query($conn, $sql);

// Ambil data untuk edit jika edit mode aktif
if (isset($_GET['edit'])) {
  $id = $_GET['edit'];
  $sql_edit = "SELECT * FROM devices WHERE serial_number = '$id' LIMIT 1";
  $res_edit = mysqli_query($conn, $sql_edit);
  $edit_data = mysqli_fetch_assoc($res_edit);
}
?>

<div class="content-wrapper">
  <div class="content-header">
    <div class="container-fluid">
      <h1 class="m-0">Perangkat</h1>
    </div>
  </div>

  <div class="content">
    <div class="container-fluid">

      <?php if ($insert) { alertSuccess("Data Berhasil Ditambahkan"); } ?>
      <?php if ($edit_success) { alertSuccess("Data Berhasil Diedit"); } ?>

      <div class="row">
        <div class="col-lg-12">

          <!-- TABEL DATA -->
          <div class="card card-primary">
            <div class="card-header">
              <h3 class="card-title">Perangkat Yang Terdaftar</h3>
            </div>
            <div class="card-body">
              <table id="example1" class="table table-bordered table-striped">
                <thead>
                  <tr>
                    <th>Serial Number</th>
                    <th>Tipe Kontroller</th>
                    <th>Lokasi</th>
                    <th>Waktu Ditambahkan</th>
                    <th>Status Aktif</th>
                    <th>Aksi</th>
                  </tr>
                </thead>
                <tbody>
                  <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                    <tr>
                      <td><?php echo $row['serial_number'] ?></td>
                      <td><?php echo $row['mcu_type'] ?></td>
                      <td><?php echo $row['location'] ?></td>
                      <td><?php echo $row['created_time'] ?></td>
                      <td><?php echo $row['active'] ?></td>
                      <td>
                        <a href="?page=<?php echo $page ?>&edit=<?php echo $row['serial_number'] ?>">
                          <i class="fas fa-edit"></i>
                        </a>
                      </td>
                    </tr>
                  <?php } ?>
                </tbody>
              </table>
            </div>
          </div>

          <!-- FORM TAMBAH DATA -->
          <?php if (!$edit_data) { ?>
          <div class="card card-primary">
            <div class="card-header">
              <h3 class="card-title">Tambah Data</h3>
            </div>
            <form method="post" action="?page=<?php echo $page ?>">
              <div class="card-body">
                <div class="form-group">
                  <label>Serial Number</label>
                  <input type="text" class="form-control" name="serial_number" placeholder="Serial Number Tidak Boleh Sama" required>
                </div>
                <div class="form-group">
                  <label>Jenis Kontroller</label>
                  <input type="text" class="form-control" name="controller" required>
                </div>
                <div class="form-group">
                  <label>Lokasi</label>
                  <input type="text" class="form-control" name="location" required>
                </div>
                <div class="form-group">
                  <label>Status</label>
                  <select class="form-control" name="status" required>
                    <option value="Yes">Aktif</option>
                    <option value="No">Tidak Aktif</option>
                  </select>
                </div>
              </div>
              <div class="card-footer">
                <button type="submit" class="btn btn-primary">Tambahkan</button>
              </div>
            </form>
          </div>
          <?php } ?>

          <!-- FORM EDIT DATA -->
          <?php if ($edit_data) { ?>
          <div class="card card-warning">
            <div class="card-header">
              <h3 class="card-title">Edit Data</h3>
            </div>
            <form method="post" action="?page=<?php echo $page ?>">
              <input type="hidden" name="edit_mode" value="1">
              <div class="card-body">
                <div class="form-group">
                  <label>Serial Number</label>
                  <input type="text" class="form-control" name="serial_number" value="<?php echo $edit_data['serial_number'] ?>" readonly>
                </div>
                <div class="form-group">
                  <label>Jenis Kontroller</label>
                  <input type="text" class="form-control" name="controller" value="<?php echo $edit_data['mcu_type'] ?>" required>
                </div>
                <div class="form-group">
                  <label>Lokasi</label>
                  <input type="text" class="form-control" name="location" value="<?php echo $edit_data['location'] ?>" required>
                </div>
                <div class="form-group">
                  <label>Status</label>
                  <select class="form-control" name="status" required>
                    <option value="Yes" <?php echo ($edit_data['active'] == 'Yes') ? 'selected' : '' ?>>Aktif</option>
                    <option value="No" <?php echo ($edit_data['active'] == 'No') ? 'selected' : '' ?>>Tidak Aktif</option>
                  </select>
                </div>
              </div>
              <div class="card-footer">
                <button type="submit" class="btn btn-warning">Simpan Perubahan</button>
              </div>
            </form>
          </div>
          <?php } ?>

        </div>
      </div>

    </div>
  </div>
</div>
