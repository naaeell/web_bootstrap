<?php
// Start the session
session_start();

include 'auth.php'; // Make sure this file connects to the database

// Define variables and initialize with empty values
$username = $password = "";
$username_err = $password_err = "";

// Process form data when submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate username
    if (empty(trim($_POST["username"]))) {
        $username_err = "Mohon masukkan username.";
    } else {
        $username = trim($_POST["username"]);
    }

    // Validate password
    if (empty(trim($_POST["password"]))) {
        $password_err = "Mohon masukkan password.";
    } else {
        $password = trim($_POST["password"]);
    }

    // Check credentials
    if (empty($username_err) && empty($password_err)) {
        // Prepare a select statement
        $sql = "SELECT username, password FROM akun WHERE username = :username";
        
        if ($stmt = $pdo->prepare($sql)) {
            // Bind variables to the prepared statement
            $stmt->bindParam(":username", $username, PDO::PARAM_STR);
            
            // Execute the statement
            if ($stmt->execute()) {
                if ($stmt->rowCount() == 1) {
                    // Fetch the row
                    $row = $stmt->fetch(PDO::FETCH_ASSOC);
                    
                    // Check if the password matches
                    if (password_verify($password, $row['password'])) {
                        // Password is correct, start a new session
                        $_SESSION['loggedin'] = true;
                        $_SESSION['username'] = $username;
                        header('Location: dashboard.php'); // Redirect to the dashboard
                        exit;
                    } else {
                        $password_err = "Username atau password salah";
                    }
                } else {
                    $username_err = "Username atau password salah";
                }
            } else {
                echo "Error executing statement.";
            }
        }
    }
    
    // Close the statement
    unset($stmt);
}

// Close the connection
unset($pdo);
?>

<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Login</title>
  <link rel="stylesheet" href="../assets/css/styles.min.css" />
  <script>
    function togglePassword() {
      const passwordField = document.getElementById("password");
      const checkBox = document.getElementById("flexCheckChecked");
      if (checkBox.checked) {
        passwordField.type = "text"; // Show password
      } else {
        passwordField.type = "password"; // Hide password
      }
    }
  </script>
</head>
<body>
  <div class="page-wrapper" id="main-wrapper" data-layout="vertical" data-navbarbg="skin6" data-sidebartype="full"
    data-sidebar-position="fixed" data-header-position="fixed">
    <div class="position-relative overflow-hidden radial-gradient min-vh-100 d-flex align-items-center justify-content-center">
      <div class="d-flex align-items-center justify-content-center w-100">
        <div class="row justify-content-center w-100">
          <div class="col-md-8 col-lg-6 col-xxl-3">
            <div class="card mb-0">
              <div class="card-body">
                <a href="./index.php" class="text-nowrap logo-img text-center d-block py-3 w-100">
                <h3 class="text-center">Webnya fadel</h3>
                </a>
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                  <div class="mb-3">
                    <label for="username" class="form-label">Username</label>
                    <input type="text" name="username" class="form-control" id="username" value="<?php echo htmlspecialchars($username); ?>">
                    <span class="text-danger"><?php echo $username_err; ?></span>
                  </div>
                  <div class="mb-4">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" name="password" class="form-control" id="password">
                    <span class="text-danger"><?php echo $password_err; ?></span>
                  </div>
                  <div class="d-flex align-items-center justify-content-between mb-4">
                    <div class="form-check">
                      <input class="form-check-input primary" type="checkbox" id="flexCheckChecked" onclick="togglePassword()">
                      <label class="form-check-label text-dark" for="flexCheckChecked">
                        Tampilkan password
                      </label>
                    </div>
                  </div>
                  <button type="submit" class="btn btn-primary w-100 py-8 fs-4 mb-4">Masuk</button>
                  <div class="d-flex align-items-center justify-content-center">
                    <p class="fs-4 mb-0 fw-bold">Tidak punya akun?</p>
                    <a class="text-primary fw-bold ms-2" href="./register.php">Buat akun</a>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <script src="../assets/libs/jquery/dist/jquery.min.js"></script>
  <script src="../assets/libs/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/iconify-icon@1.0.8/dist/iconify-icon.min.js"></script>
</body>
</html>
