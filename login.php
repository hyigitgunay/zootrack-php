<?php
require_once 'config.php';

// If already logged in, redirect to dashboard
if (isLoggedIn()) {
    header("Location: index.php");
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $login_input = sanitize($_POST['login_input']); // can be username or email
    $password = $_POST['password'];

    if (empty($login_input) || empty($password)) {
        $error = 'Lütfen tüm alanları doldurunuz.';
    } else {
        try {
            // Find user by username or email
            $stmt = $db->prepare("SELECT * FROM users WHERE username = ? OR email = ?");
            $stmt->execute([$login_input, $login_input]);
            $user = $stmt->fetch();

            if ($user && password_verify($password, $user['password'])) {
                // Set session variables - Session security check
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['email'] = $user['email'];

                header("Location: index.php");
                exit;
            } else {
                $error = 'Hatalı kullanıcı adı/e-posta veya şifre.';
            }
        } catch (PDOException $e) {
            $error = 'Bir hata oluştu: ' . $e->getMessage();
        }
    }
}
?>

<?php include 'header.php'; ?>

<div class="container">
    <div class="auth-container">
        <div class="glass-card">
            <div class="text-center mb-4">
                <i class="bi bi-shield-lock-fill text-emerald fs-1"></i>
                <h2 class="auth-title mt-3">Giriş Yap</h2>
                <p class="text-muted small">ZooTrack Paneline erişmek için oturum açın</p>
            </div>

            <?php if (!empty($error)): ?>
                <div class="alert alert-danger d-flex align-items-center" role="alert">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i>
                    <div><?php echo $error; ?></div>
                </div>
            <?php endif; ?>

            <form action="login.php" method="POST">
                <div class="mb-3">
                    <label for="login_input" class="form-label">Kullanıcı Adı veya E-posta</label>
                    <div class="input-group">
                        <span class="input-group-text bg-transparent border-end-0 border-secondary-subtle text-light opacity-50"><i class="bi bi-person"></i></span>
                        <input type="text" class="form-control border-start-0" id="login_input" name="login_input" value="<?php echo isset($login_input) ? htmlspecialchars($login_input) : ''; ?>" required>
                    </div>
                </div>

                <div class="mb-4">
                    <label for="password" class="form-label">Şifre</label>
                    <div class="input-group">
                        <span class="input-group-text bg-transparent border-end-0 border-secondary-subtle text-light opacity-50"><i class="bi bi-lock"></i></span>
                        <input type="password" class="form-control border-start-0" id="password" name="password" required>
                    </div>
                </div>

                <button type="submit" class="btn btn-emerald w-100 py-2.5 mb-3">
                    <i class="bi bi-box-arrow-in-right me-1"></i> Oturum Aç
                </button>

                <div class="text-center">
                    <span class="text-muted small">Hesabınız yok mu? <a href="register.php" class="text-emerald text-decoration-none fw-semibold">Hemen Kayıt Olun</a></span>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>
