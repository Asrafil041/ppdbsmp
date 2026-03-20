<?php include('../config/auto_load.php'); ?>

<?php include('laporan_control.php'); ?>


<?php include('../topfoot/header.php'); ?>

<!-- Begin Page Content -->
<div class="container-fluid">

  <!-- Page Heading -->
  <h1 class="h3 mb-4 text-gray-800">Laporan Pendaftaran</h1>

  <div class="row">
    <div class="col-md-12">
      <table class="table table-bordered table-hover">
        <tr>
          <td>No</td>
          <td>Nama</td>
          <td>Alamat</td>
          <td>UTS</td>
          <td>UAS</td>
          <td>UN</td>
          <td>Rata-rata</td>
          <td>Status</td>
        </tr>

        <?php
        $no = 1;
        while($p = mysqli_fetch_array($all_pendaftar)) { ?>

        <tr>
          <td><?= $no++ ?></td>
          <td><?= $p['nama'] ?></td>
          <td><?= $p['alamat'] ?></td>
          <td><?= $p['nilai_uts_1'] ?></td>
          <td><?= $p['nilai_us'] ?></td>
          <td><?= $p['nilai_un'] ?></td>
          <td>
          <!-- number_format(value, 2) -->
            <?=  number_format(($p['nilai_uts_1'] + $p['nilai_us'] + $p['nilai_un']) / 3, 2) ?>
          </td>
          <?php

          if($p['status'] == 0) {
            $status = '<span class="badge badge-info">BARU</span>';

          } else if ($p['status'] == 1) {
            $status = '<span class="badge badge-success">LOLOS</span>';

          } else if ($p['status'] == 2) {
            $status = '<span class="badge badge-danger">TIDAK LOLOS</span>';
          }

          ?>
          <td><?= $status ?></td>
        </tr>

        <?php }
        
        
        if(mysqli_num_rows($all_pendaftar) == 0) {
          echo "<tr><td colspan='8' align='center'><b>Belum Ada pendaftar baru</b></td></tr>";
        }

        ?>

      </table>
    </div>
  </div>
</div>
<!-- /.container-fluid -->

<?php include('../topfoot/footer.php'); ?>