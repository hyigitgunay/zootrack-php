<?php
require_once 'config.php';
checkLogin(); // Only authenticated users can access the dashboard

$success_msg = isset($_GET['success']) ? sanitize($_GET['success']) : '';
$error_msg = isset($_GET['error']) ? sanitize($_GET['error']) : '';

// Search and Filter parameters
$search = isset($_GET['search']) ? sanitize($_GET['search']) : '';
$filter_status = isset($_GET['status']) ? sanitize($_GET['status']) : '';
$filter_species = isset($_GET['species']) ? sanitize($_GET['species']) : '';

// Base query
$query = "SELECT a.*, u.username FROM animals a JOIN users u ON a.user_id = u.id WHERE 1=1";
$params = [];

if (!empty($search)) {
    $query .= " AND (a.name LIKE ? OR a.species LIKE ? OR a.cage_number LIKE ?)";
    $search_param = "%$search%";
    $params[] = $search_param;
    $params[] = $search_param;
    $params[] = $search_param;
}

if (!empty($filter_status)) {
    $query .= " AND a.health_status = ?";
    $params[] = $filter_status;
}

if (!empty($filter_species)) {
    $query .= " AND a.species = ?";
    $params[] = $filter_species;
}

$query .= " ORDER BY a.created_at DESC";

try {
    $stmt = $db->prepare($query);
    $stmt->execute($params);
    $animals = $stmt->fetchAll();

    // Get statistics
    $stat_total = $db->query("SELECT COUNT(*) FROM animals")->fetchColumn();
    $stat_healthy = $db->query("SELECT COUNT(*) FROM animals WHERE health_status = 'Healthy'")->fetchColumn();
    $stat_sick = $db->query("SELECT COUNT(*) FROM animals WHERE health_status = 'Sick'")->fetchColumn();
    $stat_treatment = $db->query("SELECT COUNT(*) FROM animals WHERE health_status = 'Under Treatment'")->fetchColumn();
    $stat_quarantine = $db->query("SELECT COUNT(*) FROM animals WHERE health_status = 'Quarantine'")->fetchColumn();
    
    // Get unique species for the filter dropdown
    $species_list = $db->query("SELECT DISTINCT species FROM animals ORDER BY species ASC")->fetchAll(PDO::FETCH_COLUMN);

} catch (PDOException $e) {
    die("Sorgu hatası: " . $e->getMessage());
}
?>

<?php include 'header.php'; ?>

<div class="container-fluid px-lg-5">
    
    <!-- Top Statistics Dashboard -->
    <div class="row g-4 mb-5">
        <div class="col-6 col-lg-3">
            <div class="stat-card glass-card">
                <div>
                    <h6 class="text-muted mb-1 small uppercase">Toplam Hayvan</h6>
                    <h2 class="fw-bold mb-0 text-white"><?php echo $stat_total; ?></h2>
                </div>
                <div class="stat-icon bg-secondary bg-opacity-25 text-white">
                    <i class="bi bi-hash"></i>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="stat-card glass-card">
                <div>
                    <h6 class="text-muted mb-1 small uppercase">Sağlıklı</h6>
                    <h2 class="fw-bold mb-0 text-success"><?php echo $stat_healthy; ?></h2>
                </div>
                <div class="stat-icon bg-success bg-opacity-15 text-success">
                    <i class="bi bi-heart-pulse-fill"></i>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="stat-card glass-card">
                <div>
                    <h6 class="text-muted mb-1 small uppercase">Tedavi Gören</h6>
                    <h2 class="fw-bold mb-0 text-info"><?php echo $stat_treatment; ?></h2>
                </div>
                <div class="stat-icon bg-info bg-opacity-15 text-info">
                    <i class="bi bi-prescription"></i>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="stat-card glass-card">
                <div>
                    <h6 class="text-muted mb-1 small uppercase">Karantinada</h6>
                    <h2 class="fw-bold mb-0 text-warning"><?php echo $stat_quarantine; ?></h2>
                </div>
                <div class="stat-icon bg-warning bg-opacity-15 text-warning">
                    <i class="bi bi-exclamation-octagon-fill"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Alert Messages -->
    <?php if (!empty($success_msg)): ?>
        <div class="alert alert-success alert-dismissible fade show d-flex align-items-center mb-4 glass-card border-success" role="alert">
            <i class="bi bi-check-circle-fill me-2 text-success"></i>
            <div><?php echo htmlspecialchars($success_msg); ?></div>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <?php if (!empty($error_msg)): ?>
        <div class="alert alert-danger alert-dismissible fade show d-flex align-items-center mb-4 glass-card border-danger" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2 text-danger"></i>
            <div><?php echo htmlspecialchars($error_msg); ?></div>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <!-- Controls Section: Search & Filters -->
    <div class="glass-card mb-5">
        <form method="GET" action="index.php" class="row g-3 align-items-end">
            <div class="col-12 col-md-4">
                <label for="search" class="form-label">Arama Terimi</label>
                <div class="input-group">
                    <span class="input-group-text bg-transparent border-end-0 border-secondary-subtle text-light opacity-50"><i class="bi bi-search"></i></span>
                    <input type="text" class="form-control border-start-0" id="search" name="search" placeholder="İsim, tür veya kafes no..." value="<?php echo htmlspecialchars($search); ?>">
                </div>
            </div>
            
            <div class="col-6 col-md-3">
                <label for="status" class="form-label">Sağlık Durumu</label>
                <select class="form-select" id="status" name="status">
                    <option value="">Tümü</option>
                    <option value="Healthy" <?php echo $filter_status === 'Healthy' ? 'selected' : ''; ?>>Sağlıklı (Healthy)</option>
                    <option value="Sick" <?php echo $filter_status === 'Sick' ? 'selected' : ''; ?>>Hasta (Sick)</option>
                    <option value="Under Treatment" <?php echo $filter_status === 'Under Treatment' ? 'selected' : ''; ?>>Tedavi Altında (Under Treatment)</option>
                    <option value="Quarantine" <?php echo $filter_status === 'Quarantine' ? 'selected' : ''; ?>>Karantina (Quarantine)</option>
                </select>
            </div>

            <div class="col-6 col-md-3">
                <label for="species" class="form-label">Tür</label>
                <select class="form-select" id="species" name="species">
                    <option value="">Tümü</option>
                    <?php foreach ($species_list as $spec): ?>
                        <option value="<?php echo htmlspecialchars($spec); ?>" <?php echo $filter_species === $spec ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($spec); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="col-12 col-md-2 d-flex gap-2">
                <button type="submit" class="btn btn-emerald w-100 py-2">
                    <i class="bi bi-funnel-fill me-1"></i> Filtrele
                </button>
                <a href="index.php" class="btn btn-outline-light w-100 py-2">
                    <i class="bi bi-x-circle me-1"></i> Sıfırla
                </a>
            </div>
        </form>
    </div>

    <!-- Animal Card Grid -->
    <h3 class="fw-bold mb-4 d-flex align-items-center">
        <i class="bi bi-list-stars text-emerald me-2"></i> Hayvan Envanteri
    </h3>

    <?php if (empty($animals)): ?>
        <div class="glass-card text-center py-5">
            <i class="bi bi-emoji-neutral text-muted display-4"></i>
            <h5 class="mt-3 text-muted">Kayıtlı hayvan bulunamadı.</h5>
            <p class="text-light opacity-50">Arama kriterlerinizi değiştirebilir veya yeni bir kayıt ekleyebilirsiniz.</p>
            <a href="add-animal.php" class="btn btn-emerald mt-2 px-4 py-2">
                <i class="bi bi-plus-circle me-1"></i> İlk Hayvanı Ekle
            </a>
        </div>
    <?php else: ?>
        <div class="row row-cols-1 row-cols-md-2 row-cols-xl-3 row-cols-xxl-4 g-4">
            <?php foreach ($animals as $animal): ?>
                <div class="col">
                    <div class="glass-card glass-card-hover h-100 p-3 d-flex flex-column justify-content-between">
                        <div>
                            <!-- Image section -->
                            <div class="animal-img-wrapper mb-3">
                                <?php if (!empty($animal['image_url']) && file_exists($animal['image_url'])): ?>
                                    <img src="<?php echo htmlspecialchars($animal['image_url']); ?>" alt="<?php echo htmlspecialchars($animal['name']); ?>" class="animal-img">
                                <?php else: ?>
                                    <div class="animal-placeholder">
                                        <i class="bi bi-paw-fill display-3 mb-2 opacity-50"></i>
                                        <span class="small font-monospace opacity-50"><?php echo htmlspecialchars($animal['species']); ?></span>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <!-- Title and Badge -->
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <h5 class="fw-bold mb-0 text-white"><?php echo htmlspecialchars($animal['name']); ?></h5>
                                <?php 
                                $status = $animal['health_status'];
                                $badge_class = 'badge-healthy';
                                $status_text = 'Sağlıklı';
                                if ($status === 'Sick') {
                                    $badge_class = 'badge-sick';
                                    $status_text = 'Hasta';
                                } elseif ($status === 'Under Treatment') {
                                    $badge_class = 'badge-treatment';
                                    $status_text = 'Tedavide';
                                } elseif ($status === 'Quarantine') {
                                    $badge_class = 'badge-quarantine';
                                    $status_text = 'Karantina';
                                }
                                ?>
                                <span class="badge badge-status <?php echo $badge_class; ?>">
                                    <?php echo $status_text; ?>
                                </span>
                            </div>

                            <p class="text-emerald fw-semibold small mb-3"><?php echo htmlspecialchars($animal['species']); ?></p>

                            <!-- Animal details list -->
                            <div class="mb-3 small">
                                <div class="d-flex justify-content-between border-bottom border-secondary-subtle py-1.5 opacity-75">
                                    <span><i class="bi bi-calendar-event me-1"></i> Yaş:</span>
                                    <span class="fw-medium text-white"><?php echo htmlspecialchars($animal['age']); ?> Yaşında</span>
                                </div>
                                <div class="d-flex justify-content-between border-bottom border-secondary-subtle py-1.5 opacity-75">
                                    <span><i class="bi bi-gender-ambiguous me-1"></i> Cinsiyet:</span>
                                    <span class="fw-medium text-white"><?php echo $animal['gender'] === 'Male' ? 'Erkek' : ($animal['gender'] === 'Female' ? 'Dişi' : 'Bilinmiyor'); ?></span>
                                </div>
                                <div class="d-flex justify-content-between border-bottom border-secondary-subtle py-1.5 opacity-75">
                                    <span><i class="bi bi-house-door me-1"></i> Kafes / Alan:</span>
                                    <span class="fw-medium text-white"><?php echo htmlspecialchars($animal['cage_number']); ?></span>
                                </div>
                                <div class="d-flex justify-content-between border-bottom border-secondary-subtle py-1.5 opacity-75">
                                    <span><i class="bi bi-clock-history me-1"></i> Beslenme Düzeni:</span>
                                    <span class="fw-medium text-white"><?php echo htmlspecialchars($animal['feeding_schedule']); ?></span>
                                </div>
                            </div>

                            <!-- Notes section -->
                            <?php if (!empty($animal['notes'])): ?>
                                <div class="p-2.5 rounded bg-white bg-opacity-5 mb-3">
                                    <small class="text-muted d-block mb-1 fw-semibold"><i class="bi bi-chat-square-text me-1"></i> Notlar:</small>
                                    <small class="text-light opacity-75 text-wrap"><?php echo nl2br(htmlspecialchars($animal['notes'])); ?></small>
                                </div>
                            <?php endif; ?>
                        </div>

                        <!-- Card footer action buttons & added by info -->
                        <div class="mt-auto">
                            <hr class="border-secondary-subtle my-2">
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="small text-muted opacity-75" style="font-size: 0.8rem;">
                                    <i class="bi bi-person-fill text-emerald"></i> <?php echo htmlspecialchars($animal['username']); ?> ekledi
                                </span>
                                <div class="d-flex gap-1">
                                    <a href="edit-animal.php?id=<?php echo $animal['id']; ?>" class="btn btn-outline-light btn-sm" title="Düzenle">
                                        <i class="bi bi-pencil-square"></i>
                                    </a>
                                    <!-- Delete trigger button -->
                                    <button type="button" class="btn btn-logout btn-sm" data-bs-toggle="modal" data-bs-target="#deleteModal<?php echo $animal['id']; ?>" title="Sil">
                                        <i class="bi bi-trash3-fill"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Delete Confirmation Modal -->
                <div class="modal fade" id="deleteModal<?php echo $animal['id']; ?>" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content bg-dark border-secondary-subtle text-light">
                            <div class="modal-header border-bottom border-secondary-subtle">
                                <h5 class="modal-title fw-bold" id="deleteModalLabel"><i class="bi bi-exclamation-triangle-fill text-danger me-2"></i> Kayıt Silme Onayı</h5>
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body text-center py-4">
                                <i class="bi bi-trash-fill text-danger display-4 mb-3 d-inline-block"></i>
                                <h5 class="fw-semibold"><strong><?php echo htmlspecialchars($animal['name']); ?></strong> kaydı silinecektir!</h5>
                                <p class="text-light opacity-50 mb-0">Bu işlem geri alınamaz. Devam etmek istiyor musunuz?</p>
                            </div>
                            <div class="modal-footer border-top border-secondary-subtle">
                                <button type="button" class="btn btn-outline-light" data-bs-dismiss="modal">İptal</button>
                                <a href="delete-animal.php?id=<?php echo $animal['id']; ?>" class="btn btn-danger px-4">Evet, Sil</a>
                            </div>
                        </div>
                    </div>
                </div>

            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<?php include 'footer.php'; ?>
