<!DOCTYPE html>
<html lang="en">

<head>

  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="WEB pendaftaran Siswa SMP">

  <title>Registrasi Pendaftaran Siswa</title>


  <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

  
  <link href="assets/css/sb-admin-2.min.css" rel="stylesheet">

  <style>
    .logo-login {
        max-height: 160px;
        margin-bottom: 20px;
    }
  </style>

</head>

<body class="bg-gradient-primary">

  <div class="container">

    <!-- Outer Row -->
    <div class="row justify-content-center">

      <div class="col-md-7">

        <div class="card o-hidden border-0 shadow-lg my-5">
          <div class="card-body p-0">
            <!-- Nested Row within Card Body -->
            <div class="row">
              <div class="col-md-12">
                <div class="p-5">
                  <div class="text-center">
                    <h1 class="h4 text-gray-900 mb-4">Registrasi Siswa Baru</h1>
                    <h1 class="h4 text-gray-900 mb-4"><b>SMP Slawe</b></h1>
                  </div>
                  <form class="user" action="registrasi_control.php" method="POST">
                    <div class="form-group">
                        <input type="text" class="form-control" id="nama" placeholder="Masukkan Nama" name="nama" required>
                    <small class="text-danger" id="namaError" style="display: none;">Nama wajib diisi!</small>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-6">
                            <label for="tempat_lahir">Tempat Lahir</label>
                           <input type="text" class="form-control" id="tempat_lahir" placeholder="Tempat lahir" name="tempat_lahir" required>
            <small class="text-danger" id="tempatLahirError" style="display: none;">Tempat lahir wajib diisi!</small>
        </div>
        <div class="col-md-6">
            <label for="tanggal_lahir">Tanggal Lahir</label>
            <input type="text" class="form-control datepicker" id="tanggal_lahir" placeholder="Tanggal Lahir" name="tanggal_lahir" required>
            <small class="text-danger" id="tanggalLahirError" style="display: none;">Tanggal lahir wajib diisi!</small>
        </div>
    </div>
    <div class="form-group row">
        <div class="col-md-6">
            <label>Jenis Kelamin</label>
            <div class="form-check">
                <input class="form-check-input" type="radio" name="jenis_kelamin" id="lk" value="L" required>
                <label class="form-check-label" for="lk">Laki Laki</label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="radio" name="jenis_kelamin" id="pr" value="P" required>
                <label class="form-check-label" for="pr">Perempuan</label>
            </div>
            <small class="text-danger" id="jenisKelaminError" style="display: none;">Jenis kelamin wajib dipilih!</small>
        </div>
        <div class="col-md-6">
            <label for="agama">Agama</label>
            <select name="agama" id="agama" class="form-control" required>
                <option value="">Pilih Agama</option>
                <option value="islam">Islam</option>
                <option value="kristen">Kristen</option>
                <option value="katolik">Katolik</option>
                <option value="hindu">Hindu</option>
                <option value="budha">Budha</option>
                <option value="Kong_Hu_Cu">Kong Hu Cu</option>
            </select>
            <small class="text-danger" id="agamaError" style="display: none;">Agama wajib dipilih!</small>
        </div>
    </div>
    <div class="form-group">
        <label for="alamat">Alamat</label>
        <textarea name="alamat" id="alamat" class="form-control" required></textarea>
        <small class="text-danger" id="alamatError" style="display: none;">Alamat wajib diisi!</small>
    </div>
    <div class="form-group row">
        <div class="col-md-6">
            <label for="email">Email</label>
            <input name="email" type="email" class="form-control" id="email" placeholder="Email" required>
            <small class="text-danger" id="emailError" style="display: none;">Email wajib diisi!</small>
        </div>
        <div class="col-md-6">
            <label for="telepon">Telepon</label>
            <input name="telepon" type="text" class="form-control" id="telepon" placeholder="Telepon" required>
            <small class="text-danger" id="teleponError" style="display: none;">Telepon wajib diisi!</small>
        </div>
    </div>
    <div class="form-group row">
        <div class="col-md-6">
            <label for="password">Password</label>
            <input name="password" type="password" class="form-control" id="password" placeholder="Password" required>
            <small class="text-danger" id="passwordError" style="display: none;">Password wajib diisi!</small>
        </div>
        <div class="col-md-6">
            <label for="ulangi_password">Ulangi Password</label>
            <input name="ulangi_password" type="password" class="form-control" id="ulangi_password" placeholder="Ulangi Password" required>
            <small class="text-danger" id="ulangiPasswordError" style="display: none;">Ulangi password wajib diisi!</small>
        </div>
    </div>

        <div class="col-md-6">
            <label for="foto_SKHU">Unggah Foto SKHU</label>
            <input type="file" class="form-control" id="foto_SKHU" name="foto_SKHU" accept="image/*" required>
            <small class="text-danger" id="fotoSKHUError" style="display: none;">Foto SKHU wajib diunggah!</small>
        </div>
    </div>

                    <button name="btn_registrasi" value="simpan" class="btn btn-primary btn-user btn-block">
                      Registrasi
                    </button>
                  </form>
                  <hr>
                  <div class="text-center">
                    <a class="small" href="login.php">Sudah punya akun ? Login!</a>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

      </div>

    </div>

  </div>
  <!-- Bootstrap core JavaScript-->
  <script src="assets/vendor/jquery/jquery.min.js"></script>
  <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

  <!-- Core plugin JavaScript-->
  <script src="assets/vendor/jquery-easing/jquery.easing.min.js"></script>

  <!-- Custom scripts for all pages-->
  <script src="assets/js/sb-admin-2.min.js"></script>

  <!-- js datepicker -->
  <script src="assets/vendor/datepicker/js/bootstrap-datepicker.min.js"></script>

  <script type="text/javascript">
    $(function(){
        $(".datepicker").datepicker({
            format: 'dd-mm-yyyy',
            autoclose: true,
            todayHighlight: true,
        });
    });
  </script>
  

</body>
</html>
