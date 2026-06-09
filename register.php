<?php
require_once 'config.php';

// If already logged in, redirect to index
if (isLoggedIn()) {
    header("Location: index.php");
    exit;
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = sanitize($_POST['username']);
    $email = sanitize($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Basic Validations
    if (empty($username) || empty($email) || empty($password) || empty($confirm_password)) {
        $error = 'Lütfen tüm alanları doldurunuz.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Geçersiz e-posta adresi.';
    } elseif (strlen($password) < 6) {
        $error = 'Şifre en az 6 karakter olmalıdır.';
    } elseif ($password !== $confirm_password) {
        $error = 'Şifreler uyuşmuyor.';
    } else {
        try {
            // Check if username or email already exists
            $stmt = $db->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
            $stmt->execute([$username, $email]);
            if ($stmt->fetch()) {
                $error = 'Kullanıcı adı veya e-posta adresi zaten kullanımda.';
            } else {
                // Hash the password using password_hash() as required
                $hashed_password = password_hash($password, PASSWORD_BCRYPT);
                
                // Insert new user
                $stmt = $db->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
                $stmt->execute([$username, $email, $hashed_password]);
                
                $success = 'Kayıt işleminiz başarıyla gerçekleşti! Giriş yapabilirsiniz.';
                // Redirect after 2 seconds
                header("Refresh: 2; url=login.php");
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
                <i class="bi bi-person-plus-fill text-emerald fs-1"></i>
                <h2 class="auth-title mt-3">Kayıt Ol</h2>
                <p class="text-muted small">ZooTrack Hayvan Takip Sistemine katılın</p>
            </div>

            <?php if (!empty($error)): ?>
                <div class="alert alert-danger d-flex align-items-center" role="alert">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i>
                    <div><?php echo $error; ?></div>
                </div>
            <?php endif; ?>

            <?php if (!empty($success)): ?>
                <div class="alert alert-success d-flex align-items-center" role="alert">
                    <i class="bi bi-check-circle-fill me-2"></i>
                    <div><?php echo $success; ?></div>
                </div>
            <?php endif; ?>

            <form action="register.php" method="POST">
                <div class="mb-3">
                    <label for="username" class="form-label">Kullanıcı Adı</label>
                    <div class="input-group">
                        <span class="input-group-text bg-transparent border-end-0 border-secondary-subtle text-light opacity-50"><i class="bi bi-person"></i></span>
                        <input type="text" class="form-control border-start-0" id="username" name="username" value="<?php echo isset($username) ? htmlspecialchars($username) : ''; ?>" required>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="email" class="form-label">E-posta Adresi</label>
                    <div class="input-group">
                        <span class="input-group-text bg-transparent border-end-0 border-secondary-subtle text-light opacity-50"><i class="bi bi-envelope"></i></span>
                        <input type="email" class="form-control border-start-0" id="email" name="email" value="<?php echo isset($email) ? htmlspecialchars($email) : ''; ?>" required>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label">Şifre (Min. 6 Karakter)</label>
                    <div class="input-group">
                        <span class="input-group-text bg-transparent border-end-0 border-secondary-subtle text-light opacity-50"><i class="bi bi-lock"></i></span>
                        <input type="password" class="form-control border-start-0" id="password" name="password" required>
                    </div>
                </div>

                <div class="mb-4">
                    <label for="confirm_password" class="form-label">Şifre Tekrarı</label>
                    <div class="input-group">
                        <span class="input-group-text bg-transparent border-end-0 border-secondary-subtle text-light opacity-50"><i class="bi bi-lock-fill"></i></span>
                        <input type="password" class="form-control border-start-0" id="confirm_password" name="confirm_password" required>
                    </div>
                </div>

                <button type="submit" class="btn btn-emerald w-100 py-2.5 mb-3">
                    <i class="bi bi-person-check-fill me-1"></i> Hesap Oluştur
                </button>

                <div class="text-center">
                    <span class="text-muted small">Zaten hesabınız var mı? <a href="login.php" class="text-emerald text-decoration-none fw-semibold">Giriş Yapın</a></span>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>
