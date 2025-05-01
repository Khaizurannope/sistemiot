<?php

if($_SESSION['role'] != "Admin") {
  echo "<script> location.href='index.php' </script>";
}

$page = $_GET['page'];
$insert = false;
$edit_success = false;
$edit_data = null;

// Proses insert data
if (isset($_POST['username']) && !isset($_POST['edit_mode'])) {
  $username = $_POST['username'];
  $password = $_POST['password'];
  $fullname = $_POST['fullname'];
  $role = $_POST['role'];
  $hashed_password = password_hash($password, PASSWORD_DEFAULT);

  $sql_insert = "INSERT INTO user (username, password, fullname, role) 
                 VALUES ('$username', '$hashed_password', '$fullname', '$role')";
  if (mysqli_query($conn, $sql_insert)) {
    $insert = true;
  }
}

// Proses update/edit data
if (isset($_POST['username']) && isset($_POST['edit_mode'])) {
  $username = $_POST['username'];
  $fullname = $_POST['fullname'];
  $role = $_POST['role'];
  $status = $_POST['status'];

  if (!empty($_POST['password'])) {
    $password = $_POST['password'];
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    $sql_edit = "UPDATE user SET fullname = '$fullname', role = '$role', active = '$status', password = '$hashed_password' 
                 WHERE username = '$username'";
  } else {
    $sql_edit = "UPDATE user SET fullname = '$fullname', role = '$role', active = '$status' 
                 WHERE username = '$username'";
  }

  if (mysqli_query($conn, $sql_edit)) {
    $edit_success = true;
  }
}

// Ambil semua data user
$sql = "SELECT * FROM user";
$result = mysqli_query($conn, $sql);

// Ambil data user untuk diedit
if (isset($_GET['edit'])) {
  $id = $_GET['edit'];
  $sql_edit = "SELECT * FROM user WHERE username = '$id' LIMIT 1";
  $res_edit = mysqli_query($conn, $sql_edit);
  $edit_data = mysqli_fetch_assoc($res_edit);
}
?>

<div class="content-wrapper">
  <div class="content-header">
    <div class="container-fluid">
      <h1 class="m-0">Pengguna</h1>
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
              <h3 class="card-title">Pengguna Yang Terdaftar</h3>
            </div>
            <div class="card-body">
              <table id="example1" class="table table-bordered table-striped">
                <thead>
                  <tr>
                    <th>Username</th>
                    <th>Nama Lengkap</th>
                    <th>Hak Akses</th>
                    <th>Status Aktif</th>
                    <th>Aksi</th>
                  </tr>
                </thead>
                <tbody>
                  <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                    <tr>
                      <td><?= $row['username'] ?></td>
                      <td><?= $row['fullname'] ?></td>
                      <td><?= $row['role'] ?></td>
                      <td><?= $row['active'] ?></td>
                      <td>
                        <a href="?page=<?= $page ?>&edit=<?= $row['username'] ?>">
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
            <form method="post" action="?page=<?= $page ?>">
              <div class="card-body">
                <div class="form-group">
                  <label>Username</label>
                  <input type="text" class="form-control" name="username" placeholder="Username Tidak Boleh Sama" required>
                </div>
                <div class="form-group">
                  <label>Password</label>
                  <input type="password" class="form-control" name="password" required>
                </div>
                <div class="form-group">
                  <label>Nama Lengkap</label>
                  <input type="text" class="form-control" name="fullname" required>
                </div>
                <div class="form-group">
                  <label>Hak Akses</label>
                  <select class="form-control" name="role">
                    <option value="Admin">Admin</option>
                    <option value="User">Pengguna</option>
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
            <form method="post" action="?page=<?= $page ?>">
              <input type="hidden" name="edit_mode" value="1">
              <div class="card-body">
                <div class="form-group">
                  <label>Username</label>
                  <input type="text" class="form-control" name="username" value="<?= $edit_data['username'] ?>" readonly>
                </div>
                <div class="form-group">
                  <label>Nama Lengkap</label>
                  <input type="text" class="form-control" name="fullname" value="<?= $edit_data['fullname'] ?>" required>
                </div>
                <div class="form-group">
                  <label>Hak Akses</label>
                  <select class="form-control" name="role" required>
                    <option value="Admin" <?= ($edit_data['role'] == 'Admin') ? 'selected' : '' ?>>Admin</option>
                    <option value="User" <?= ($edit_data['role'] == 'User') ? 'selected' : '' ?>>Pengguna</option>
                  </select>
                </div>
                <div class="form-group">
                  <label>Status Aktif</label>
                  <select class="form-control" name="status" required>
                    <option value="Yes" <?= ($edit_data['active'] == 'Yes') ? 'selected' : '' ?>>Aktif</option>
                    <option value="No" <?= ($edit_data['active'] == 'No') ? 'selected' : '' ?>>Tidak Aktif</option>
                  </select>
                </div>
                <div class="form-group">
                  <label>Password (kosongkan jika tidak diubah)</label>
                  <input type="password" class="form-control" name="password">
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
