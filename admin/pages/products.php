<?php
ob_start(); // Çıktı tamponlamayı etkinleştir

// Ürün ekleme  
if(isset($_POST['add_product'])) {  
    $name = clean($_POST['name']);  
    $category_id = (int)$_POST['category_id'];  
    $price = (float)$_POST['price'];  
    $description = clean($_POST['description']);  
    $status = isset($_POST['status']) ? 1 : 0;  

    // Resim yükleme  
    $image = '';  
    if(isset($_FILES['image']) && $_FILES['image']['error'] === 0) {  
        $image = uploadFile($_FILES['image']);  
    }  

    try {  
        $stmt = $db->prepare("INSERT INTO products (name, category_id, price, description, image, status) VALUES (?, ?, ?, ?, ?, ?)");  
        $stmt->execute([$name, $category_id, $price, $description, $image, $status]);  
        $_SESSION['success'] = "Ürün başarıyla eklendi.";  
    } catch(PDOException $e) {  
        $_SESSION['error'] = "Ürün eklenirken bir hata oluştu.";  
    }  

    header("Location: ?page=products");  
    exit;  
}  
// Veritabanından ayarları çek
$settingsQuery = $db->query("SELECT * FROM settings LIMIT 1");
$settings = $settingsQuery->fetch(PDO::FETCH_ASSOC);

// Kategorileri al  
$categories = $db->query("SELECT * FROM categories WHERE status = 1")->fetchAll(PDO::FETCH_ASSOC);  

// Ürünleri listele
$search = isset($_GET['search']) ? clean($_GET['search']) : '';
$searchQuery = $search ? "AND p.name LIKE '%$search%'" : '';

$products = $db->query("
    SELECT p.*, c.name as category_name
    FROM products p
    JOIN categories c ON p.category_id = c.id
    WHERE 1=1 $searchQuery
    ORDER BY p.id DESC
")->fetchAll(PDO::FETCH_ASSOC);
?>  

<div class="container-fluid">  
    <?php  
    if(isset($_SESSION['success'])) {  
        echo successMessage($_SESSION['success']);  
        unset($_SESSION['success']);  
    }  
    if(isset($_SESSION['error'])) {  
        echo errorMessage($_SESSION['error']);  
        unset($_SESSION['error']);  
    }  
    ?>  

    <!-- Ürün Ekleme Butonu -->  
    <div class="d-flex justify-content-between align-items-center mb-4">  
        <h4>Ürünler</h4>  
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addProductModal">  
            <i class="fas fa-plus me-2"></i>Yeni Ürün Ekle  
        </button>  
    </div>  

    <!-- Filtreler -->  
    <div class="card mb-4">  
        <div class="card-body">  
            <form method="get" class="row">  
                <input type="hidden" name="page" value="products">
                <div class="col-md-8">  
                    <input type="text" name="search" class="form-control" placeholder="Ürün Ara..." value="<?php echo clean($search); ?>">  
                </div>  
                <div class="col-md-4">  
                    <button type="submit" class="btn btn-primary w-100">Ara</button>  
                </div>  
            </form>  
        </div>  
    </div>  

    <!-- Ürünler Tablosu -->  
    <div class="card">  
        <div class="card-body">  
            <table class="table">  
                <thead>  
                    <tr>  
                        <th>Görsel</th>  
                        <th>Ürün Adı</th>  
                        <th>Kategori</th>  
                        <th>Fiyat</th>  
                        <th>Durum</th>  
                        <th>İşlemler</th>  
                    </tr>  
                </thead>  
                <tbody>  
                    <?php foreach($products as $product): ?>  
                    <tr>  
                        <td>  
                            <?php if($product['image']): ?>  
                            <img src="<?php echo SITE_URL .  UPLOAD_DIR . $product['image']; ?>"   
                                 alt="<?php echo clean($product['name']); ?>"  
                                 width="50" height="50" class="rounded">  
                            <?php else: ?>
                                <img src="<?php echo SITE_URL . UPLOAD_DIR . $settings['logo']; ?>" alt="Site Logo"  width="50" height="50" class="rounded">
                            <?php endif; ?>  
                        </td>  
                        <td><?php echo clean($product['name']); ?></td>  
                        <td><?php echo clean($product['category_name']); ?></td>  
                        <td><?php echo formatPrice($product['price']); ?></td>  
                        <td>  
                            <span class="badge bg-<?php echo $product['status'] ? 'success' : 'danger'; ?>">  
                                <?php echo $product['status'] ? 'Aktif' : 'Pasif'; ?>  
                            </span>  
                        </td>  
                        <td>  
                            <button class="btn btn-sm btn-primary"   
                                    data-bs-toggle="modal"   
                                    data-bs-target="#editProductModal<?php echo $product['id']; ?>">  
                                <i class="fas fa-edit"></i>  
                            </button>  
                            <a href="?page=products&action=delete&id=<?php echo $product['id']; ?>"   
                               class="btn btn-sm btn-danger delete-btn">  
                                <i class="fas fa-trash"></i>  
                            </a>  
                        </td>  
                    </tr>  

                    <!-- Düzenleme Modal -->  
                    <div class="modal fade" id="editProductModal<?php echo $product['id']; ?>">  
                        <div class="modal-dialog modal-lg">  
                            <div class="modal-content">  
                                <div class="modal-header">  
                                    <h5 class="modal-title">Ürün Düzenle</h5>  
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>  
                                </div>  
                                <form action="?page=products&action=edit" method="post" enctype="multipart/form-data">  
                                    <div class="modal-body">  
                                        <input type="hidden" name="id" value="<?php echo $product['id']; ?>">  
                                        <div class="row">  
                                            <div class="col-md-6">  
                                                <div class="mb-3">  
                                                    <label class="form-label">Ürün Adı</label>  
                                                    <input type="text" class="form-control" name="name"   
                                                           value="<?php echo clean($product['name']); ?>" required>  
                                                </div>  
                                                <div class="mb-3">  
                                                    <label class="form-label">Kategori</label>  
                                                    <select name="category_id" class="form-select" required>  
                                                        <?php foreach($categories as $category): ?>  
                                                        <option value="<?php echo $category['id']; ?>"   
                                                                <?php echo $category['id'] == $product['category_id'] ? 'selected' : ''; ?>>  
                                                            <?php echo clean($category['name']); ?>  
                                                        </option>  
                                                        <?php endforeach; ?>  
                                                    </select>  
                                                </div>  
                                                <div class="mb-3">  
                                                    <label class="form-label">Fiyat</label>  
                                                    <input type="number" step="0.01" class="form-control" name="price"   
                                                           value="<?php echo $product['price']; ?>" required>  
                                                </div>  
                                            </div>  
                                            <div class="col-md-6">  
                                                <div class="mb-3">  
                                                    <label class="form-label">Ürün Görseli</label>  
                                                    <input type="file" class="form-control" name="image">  
                                                    <?php if($product['image']): ?>  
                                                    <img src="<?php echo SITE_URL . UPLOAD_DIR . $product['image']; ?>"   
                                                         alt="<?php echo clean($product['name']); ?>"  
                                                         class="mt-2" width="100">  
                                                    <?php endif; ?>  
                                                </div>  
                                                <div class="mb-3">  
                                                    <label class="form-label">Açıklama</label>  
                                                    <textarea class="form-control" name="description"   
                                                              rows="4"><?php echo clean($product['description']); ?></textarea>  
                                                </div>  
                                                <div class="mb-3">  
                                                    <div class="form-check">  
                                                        <input class="form-check-input" type="checkbox" name="status"   
                                                               <?php echo $product['status'] ? 'checked' : ''; ?>>  
                                                               <label class="form-check-label">Aktif</label>  
                                                    </div>  
                                                </div>  
                                            </div>  
                                        </div>  
                                    </div>  
                                    <div class="modal-footer">  
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">İptal</button>  
                                        <button type="submit" name="edit_product" class="btn btn-primary">Güncelle</button>  
                                    </div>  
                                </form>  
                            </div>  
                        </div>  
                    </div>  
                    <?php endforeach; ?>  
                </tbody>  
            </table>  
        </div>  
    </div>  
</div>  

<!-- Yeni Ürün Ekleme Modal -->  
<div class="modal fade" id="addProductModal">  
    <div class="modal-dialog modal-lg">  
        <div class="modal-content">  
            <div class="modal-header">  
                <h5 class="modal-title">Yeni Ürün Ekle</h5>  
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>  
            </div>  
            <form action="?page=products" method="post" enctype="multipart/form-data">  
                <div class="modal-body">  
                    <div class="row">  
                        <div class="col-md-6">  
                            <div class="mb-3">  
                                <label class="form-label">Ürün Adı</label>  
                                <input type="text" class="form-control" name="name" required>  
                            </div>  
                            <div class="mb-3">  
                                <label class="form-label">Kategori</label>  
                                <select name="category_id" class="form-select" required>  
                                    <option value="">Seçiniz...</option>  
                                    <?php foreach($categories as $category): ?>  
                                    <option value="<?php echo $category['id']; ?>">  
                                        <?php echo clean($category['name']); ?>  
                                    </option>  
                                    <?php endforeach; ?>  
                                </select>  
                            </div>  
                            <div class="mb-3">  
                                <label class="form-label">Fiyat</label>  
                                <input type="number" step="0.01" class="form-control" name="price" required>  
                            </div>  
                        </div>  
                        <div class="col-md-6">  
                            <div class="mb-3">  
                                <label class="form-label">Ürün Görseli</label>  
                                <input type="file" class="form-control" name="image">  
                            </div>  
                            <div class="mb-3">  
                                <label class="form-label">Açıklama</label>  
                                <textarea class="form-control" name="description" rows="4"></textarea>  
                            </div>  
                            <div class="mb-3">  
                                <div class="form-check">  
                                    <input class="form-check-input" type="checkbox" name="status" checked>  
                                    <label class="form-check-label">Aktif</label>  
                                </div>  
                            </div>  
                        </div>  
                    </div>  
                </div>  
                <div class="modal-footer">  
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">İptal</button>  
                    <button type="submit" name="add_product" class="btn btn-primary">Ekle</button>  
                </div>  
            </form>  
        </div>  
    </div>  
</div>  

<?php  

// Ürün silme işlemi  
if(isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['id'])) {  
    $id = (int)$_GET['id'];  

    try {  
        // Önce ürüne ait görseli sil  
        $product = $db->query("SELECT image FROM products WHERE id = $id")->fetch(PDO::FETCH_ASSOC);  
        if ($product && $product['image'] && file_exists(UPLOAD_DIR . $product['image'])) {  
            unlink(UPLOAD_DIR . $product['image']);  
        }  

        // Sonra ürünü sil  
        $db->exec("DELETE FROM products WHERE id = $id");  
        $_SESSION['success'] = "Ürün başarıyla silindi.";  
    } catch(PDOException $e) {  
        $_SESSION['error'] = "Ürün silinirken bir hata oluştu: " . $e->getMessage();  
    }  

    header("Location: ?page=products");  
    exit;  
}  

// Ürün düzenleme işlemi  
if(isset($_POST['edit_product'])) {  
    $id = (int)$_POST['id'];  
    $name = clean($_POST['name']);  
    $category_id = (int)$_POST['category_id'];  
    $price = (float)$_POST['price'];  
    $description = clean($_POST['description']);  
    $status = isset($_POST['status']) ? 1 : 0;  

    try {  
        // Resim yükleme  
        if(isset($_FILES['image']) && $_FILES['image']['error'] === 0) {  
            $image = uploadFile($_FILES['image']);  
            if($image) {  
                // Eski görseli sil  
                $old_image = $db->query("SELECT image FROM products WHERE id = $id")->fetchColumn();  
                if($old_image && file_exists(UPLOAD_DIR . $old_image)) {  
                    unlink(UPLOAD_DIR . $old_image);  
                }  

                // Yeni görseli güncelle  
                $stmt = $db->prepare("UPDATE products SET image = ? WHERE id = ?");  
                $stmt->execute([$image, $id]);  
            }  
        }  

        // Diğer bilgileri güncelle  
        $stmt = $db->prepare("UPDATE products SET name = ?, category_id = ?, price = ?, description = ?, status = ? WHERE id = ?");  
        $stmt->execute([$name, $category_id, $price, $description, $status, $id]);  

        $_SESSION['success'] = "Ürün başarıyla güncellendi.";  
    } catch(PDOException $e) {  
        $_SESSION['error'] = "Ürün güncellenirken bir hata oluştu: " . $e->getMessage();  
    }  

    header("Location: ?page=products");  
    exit;  
}  

ob_end_flush(); // Çıktı tamponlamayı kapat
?>