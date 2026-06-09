<?php
require_once 'config.php';
checkLogin();

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($id <= 0) {
    header("Location: index.php");
    exit;
}

try {
    // Fetch animal details to delete image file
    $stmt = $db->prepare("SELECT image_url FROM animals WHERE id = ?");
    $stmt->execute([$id]);
    $animal = $stmt->fetch();

    if ($animal) {
        // Delete image file if exists
        if (!empty($animal['image_url']) && file_exists($animal['image_url'])) {
            unlink($animal['image_url']);
        }

        // Delete database record
        $stmt = $db->prepare("DELETE FROM animals WHERE id = ?");
        $stmt->execute([$id]);

        header("Location: index.php?success=" . urlencode("Hayvan kaydı başarıyla silindi."));
        exit;
    } else {
        header("Location: index.php?error=" . urlencode("Silinmek istenen kayıt bulunamadı."));
        exit;
    }
} catch (PDOException $e) {
    header("Location: index.php?error=" . urlencode("Kayıt silinirken veritabanı hatası oluştu: " . $e->getMessage()));
    exit;
}
?>
