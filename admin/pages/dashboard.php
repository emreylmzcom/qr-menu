<?php  
// İstatistikleri al  
$stats = [  
    'categories' => $db->query("SELECT COUNT(*) FROM categories")->fetchColumn(),  
    'products' => $db->query("SELECT COUNT(*) FROM products")->fetchColumn(),  
    'active_products' => $db->query("SELECT COUNT(*) FROM products WHERE status = 1")->fetchColumn(),  
    'inactive_products' => $db->query("SELECT COUNT(*) FROM products WHERE status = 0")->fetchColumn()  
];  

// Son eklenen ürünler  
$latest_products = $db->query("  
    SELECT p.*, c.name as category_name   
    FROM products p   
    JOIN categories c ON p.category_id = c.id   
    ORDER BY p.id DESC   
    LIMIT 5  
")->fetchAll(PDO::FETCH_ASSOC);  

// Son eklenen kategoriler  
$latest_categories = $db->query("  
    SELECT * FROM categories   
    ORDER BY id DESC   
    LIMIT 5  
")->fetchAll(PDO::FETCH_ASSOC);  
?>  

<div class="container-fluid">  
    <!-- Stats Cards -->  
    <div class="row mb-4">  
        <div class="col-md-3">  
            <div class="stats-card">  
                <h6>Toplam Kategori</h6>  
                <h3><?php echo $stats['categories']; ?></h3>  
                <div class="progress" style="height: 5px;">  
                    <div class="progress-bar bg-primary" style="width: 70%"></div>  
                </div>  
            </div>  
        </div>  
        <div class="col-md-3">  
            <div class="stats-card">  
                <h6>Toplam Ürün</h6>  
                <h3><?php echo $stats['products']; ?></h3>  
                <div class="progress" style="height: 5px;">  
                    <div class="progress-bar bg-success" style="width: 80%"></div>  
                </div>  
            </div>  
        </div>  
        <div class="col-md-3">  
            <div class="stats-card">  
                <h6>Aktif Ürünler</h6>  
                <h3><?php echo $stats['active_products']; ?></h3>  
                <div class="progress" style="height: 5px;">  
                    <div class="progress-bar bg-info" style="width: 60%"></div>  
                </div>  
            </div>  
        </div>  
        <div class="col-md-3">  
            <div class="stats-card">  
                <h6>Pasif Ürünler</h6>  
                <h3><?php echo $stats['inactive_products']; ?></h3>  
                <div class="progress" style="height: 5px;">  
                    <div class="progress-bar bg-danger" style="width: 40%"></div>  
                </div>  
            </div>  
        </div>  
    </div>  

    <!-- Recent Items Table -->  
    <div class="card mb-4">  
        <div class="card-header">  
            <h5 class="card-title mb-0">Son Eklenen Ürünler</h5>  
        </div>  
        <div class="card-body">  
            <table class="table">  
                <thead>  
                    <tr>  
                        <th>Ürün Adı</th>  
                        <th>Kategori</th>  
                        <th>Fiyat</th>  
                        <th>Durum</th>  
                        <th>İşlemler</th>  
                    </tr>  
                </thead>  
                <tbody>  
                    <?php foreach($latest_products as $product): ?>  
                    <tr>  
                        <td><?php echo clean($product['name']); ?></td>  
                        <td><?php echo clean($product['category_name']); ?></td>  
                        <td><?php echo formatPrice($product['price']); ?></td>  
                        <td>  
                            <span class="badge bg-<?php echo $product['status'] ? 'success' : 'danger'; ?>">  
                                <?php echo $product['status'] ? 'Aktif' : 'Pasif'; ?>  
                            </span>  
                        </td>  
                        <td>  
                            <a href="?page=products&action=edit&id=<?php echo $product['id']; ?>"   
                               class="btn btn-sm btn-primary">  
                                <i class="fas fa-edit"></i>  
                            </a>  
                            <a href="?page=products&action=delete&id=<?php echo $product['id']; ?>"   
                               class="btn btn-sm btn-danger delete-btn">  
                                <i class="fas fa-trash"></i>  
                            </a>  
                        </td>  
                    </tr>  
                    <?php endforeach; ?>  
                </tbody>  
            </table>  
        </div>  
    </div>  

    <!-- Recent Categories Table -->  
    <div class="card">  
        <div class="card-header">  
            <h5 class="card-title mb-0">Son Eklenen Kategoriler</h5>  
        </div>  
        <div class="card-body">  
            <table class="table">  
                <thead>  
                    <tr>  
                        <th>Kategori Adı</th>  
                        <th>Durum</th>  
                    </tr>  
                </thead>  
                <tbody>  
                    <?php foreach($latest_categories as $category): ?>  
                    <tr>  
                        <td><?php echo clean($category['name']); ?></td>  
                        <td>  
                            <span class="badge bg-<?php echo $category['status'] ? 'success' : 'danger'; ?>">  
                                <?php echo $category['status'] ? 'Aktif' : 'Pasif'; ?>  
                            </span>  
                        </td>  
                    </tr>  
                    <?php endforeach; ?>  
                </tbody>  
            </table>  
        </div>  
    </div>  
</div>