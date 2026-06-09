<?php
require_once 'config.php';
checkLogin();

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = sanitize($_POST['name']);
    $species = sanitize($_POST['species']);
    $age = intval($_POST['age']);
    $gender = sanitize($_POST['gender']);
    $cage_number = sanitize($_POST['cage_number']);
    $health_status = sanitize($_POST['health_status']);
    $feeding_schedule = sanitize($_POST['feeding_schedule']);
    $notes = sanitize($_POST['notes']);
    $image_url = null;

    // Validate inputs
    if (empty($name) || empty($species) || empty($cage_number) || empty($feeding_schedule)) {
        $error = 'Lütfen gerekli alanları (Hayvan Adı, Tür, Kafes No, Beslenme Düzeni) doldurunuz.';
    } elseif ($age < 0) {
        $error = 'Yaş değeri negatif olamaz.';
    } else {
        // Image Upload Logic
        if (isset($_FILES['animal_image']) && $_FILES['animal_image']['error'] === UPLOAD_ERR_OK) {
            $file_tmp = $_FILES['animal_image']['tmp_name'];
            $file_name = $_FILES['animal_image']['name'];
            $file_size = $_FILES['animal_image']['size'];
            $file_type = $_FILES['animal_image']['type'];
            
            $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
            $allowed_exts = ['jpg', 'jpeg', 'png', 'webp'];
            
            if (!in_array($file_ext, $allowed_exts)) {
                $error = 'Yalnızca JPG, JPEG, PNG ve WEBP formatında görseller kabul edilmektedir.';
            } elseif ($file_size > 5 * 1024 * 1024) { // 5MB limit
                $error = 'Görsel boyutu en fazla 5MB olabilir.';
            } else {
                // Ensure uploads directory exists
                if (!is_dir('uploads')) {
                    mkdir('uploads', 0777, true);
                }
                
                // Create unique file name
                $new_file_name = uniqid('animal_', true) . '.' . $file_ext;
                $dest_path = 'uploads/' . $new_file_name;
                
                if (move_uploaded_file($file_tmp, $dest_path)) {
                    $image_url = $dest_path;
                } else {
                    $error = 'Görsel yüklenirken bir hata oluştu.';
                }
            }
        }

        // If no errors so far, proceed to insert
        if (empty($error)) {
            try {
                $stmt = $db->prepare("INSERT INTO animals (user_id, name, species, age, gender, cage_number, health_status, feeding_schedule, notes, image_url) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
                $stmt->execute([
                    $_SESSION['user_id'],
                    $name,
                    $species,
                    $age,
                    $gender,
                    $cage_number,
                    $health_status,
                    $feeding_schedule,
                    $notes,
                    $image_url
                ]);
                
                header("Location: index.php?success=" . urlencode("Hayvan kaydı başarıyla eklendi."));
                exit;
            } catch (PDOException $e) {
                $error = 'Veritabanına kaydedilirken hata oluştu: ' . $e->getMessage();
            }
        }
    }
}
?>

<?php include 'header.php'; ?>

<div class="container my-5" style="max-width: 800px;">
    <div class="glass-card">
        <div class="d-flex align-items-center mb-4">
            <a href="index.php" class="btn btn-outline-light me-3 btn-sm"><i class="bi bi-arrow-left"></i> Geri Dön</a>
            <h2 class="fw-bold mb-0"><i class="bi bi-plus-circle-fill text-emerald me-1"></i> Yeni Hayvan Ekle</h2>
        </div>

        <?php if (!empty($error)): ?>
            <div class="alert alert-danger d-flex align-items-center mb-4" role="alert">
                <i class="bi bi-exclamation-triangle-fill me-2 text-danger"></i>
                <div><?php echo $error; ?></div>
            </div>
        <?php endif; ?>

        <form action="add-animal.php" method="POST" enctype="multipart/form-data">
            
            <div class="row g-3">
                <div class="col-md-6">
                    <label for="name" class="form-label">Hayvan Adı *</label>
                    <input type="text" class="form-control" id="name" name="name" required value="<?php echo isset($name) ? htmlspecialchars($name) : ''; ?>" placeholder="Örn: Leo, Pamuk">
                </div>
                
                <div class="col-md-6">
                    <label for="species" class="form-label">Türü *</label>
                    <input type="text" class="form-control" id="species" name="species" required value="<?php echo isset($species) ? htmlspecialchars($species) : ''; ?>" placeholder="Örn: Aslan, Penguen, Zürafa">
                </div>

                <div class="col-md-4">
                    <label for="age" class="form-label">Yaş</label>
                    <input type="number" class="form-control" id="age" name="age" min="0" value="<?php echo isset($age) ? htmlspecialchars($age) : '0'; ?>">
                </div>

                <div class="col-md-4">
                    <label for="gender" class="form-label">Cinsiyet</label>
                    <select class="form-select" id="gender" name="gender">
                        <option value="Unknown" <?php echo isset($gender) && $gender === 'Unknown' ? 'selected' : ''; ?>>Bilinmiyor</option>
                        <option value="Male" <?php echo isset($gender) && $gender === 'Male' ? 'selected' : ''; ?>>Erkek</option>
                        <option value="Female" <?php echo isset($gender) && $gender === 'Female' ? 'selected' : ''; ?>>Dişi</option>
                    </select>
                </div>

                <div class="col-md-4">
                    <label for="cage_number" class="form-label">Kafes / Alan No *</label>
                    <input type="text" class="form-control" id="cage_number" name="cage_number" required value="<?php echo isset($cage_number) ? htmlspecialchars($cage_number) : ''; ?>" placeholder="Örn: Sera-A, Alan-3">
                </div>

                <div class="col-md-6">
                    <label for="health_status" class="form-label">Sağlık Durumu</label>
                    <select class="form-select" id="health_status" name="health_status">
                        <option value="Healthy" <?php echo isset($health_status) && $health_status === 'Healthy' ? 'selected' : ''; ?>>Sağlıklı (Healthy)</option>
                        <option value="Sick" <?php echo isset($health_status) && $health_status === 'Sick' ? 'selected' : ''; ?>>Hasta (Sick)</option>
                        <option value="Under Treatment" <?php echo isset($health_status) && $health_status === 'Under Treatment' ? 'selected' : ''; ?>>Tedavi Altında (Under Treatment)</option>
                        <option value="Quarantine" <?php echo isset($health_status) && $health_status === 'Quarantine' ? 'selected' : ''; ?>>Karantina (Quarantine)</option>
                    </select>
                </div>

                <div class="col-md-6">
                    <label for="feeding_schedule" class="form-label">Beslenme Düzeni *</label>
                    <input type="text" class="form-control" id="feeding_schedule" name="feeding_schedule" required value="<?php echo isset($feeding_schedule) ? htmlspecialchars($feeding_schedule) : ''; ?>" placeholder="Örn: Günde 2 kez sabah-akşam">
                </div>

                <div class="col-12">
                    <label for="animal_image" class="form-label">Hayvan Görseli (Opsiyonel)</label>
                    <input class="form-control" type="file" id="animal_image" name="animal_image" accept="image/*">
                    <div class="form-text text-light opacity-50 small">Desteklenen uzantılar: JPG, JPEG, PNG, WEBP. Maksimum dosya boyutu: 5MB.</div>
                </div>

                <div class="col-12">
                    <label for="notes" class="form-label">Ek Notlar / Tıbbi Geçmiş</label>
                    <textarea class="form-control" id="notes" name="notes" rows="4" placeholder="Hayvanın alerjileri, davranış notları veya özel tedavi detayları..."><?php echo isset($notes) ? htmlspecialchars($notes) : ''; ?></textarea>
                </div>
            </div>

            <hr class="border-secondary-subtle my-4">

            <div class="d-flex justify-content-end gap-2">
                <a href="index.php" class="btn btn-outline-light px-4">İptal</a>
                <button type="submit" class="btn btn-emerald px-5"><i class="bi bi-check-lg me-1"></i> Kaydet ve Ekle</button>
            </div>
        </form>
    </div>
</div>

<?php include 'footer.php'; ?>
