<!DOCTYPE html>
<html lang="en">

<head>

  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="WEB pendaftaran Siswa SMP.">

  <title>Login Pendaftaran Siswa</title>

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
                    <img src="assets/img/logodinas.png" alt="Logo sekolah" class="logo-login">

                    <h1 class="h4 text-gray-900">Pendaftaran Siswa</h1>
                    <h1 class="h4 text-gray-900 mb-4"><b>SMP Slawe</b></h1>

                    <?php 
                    session_start();
                    
                    if(isset($_SESSION['pesan_registrasi'])) { ?>

                    <div class="alert alert-success">
                      <?= $_SESSION['pesan_registrasi'] ?>
                    </div>
 
                    <?php } 
                    
                    if(isset($_SESSION['login_error'])) { ?>

                    <div class="alert alert-danger">
                      <?= $_SESSION['login_error'] ?>
                    </div>

                    <?php } 
                    
                    session_destroy();
                    
                    ?>

                  </div>
                  <form class="user" action="login_control.php" method="POST">
                    <div class="form-group">
                      <input type="text" name="username" class="form-control form-control-user" id="username" placeholder="Masukkan Email">
                    </div>
                    <div class="form-group">
                      <input type="password" name="password" class="form-control form-control-user" id="exampleInputPassword" placeholder="Password">
                    </div>
                    <button type="submit" name="btn_login" value="login" href="" class="btn btn-primary btn-user btn-block">
                      Login
                    </button>
                  </form>
                  <hr>
                  <div class="text-center">
                    <a class="small" href="registrasi.php">Registrasi Siswa Baru!</a>
                  </div>
                   <div class="text-left">
                    <a class="small" href="index.php"><- Home</a>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
    

</body>
</html>
