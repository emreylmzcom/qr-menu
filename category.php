<?php
require_once 'admin/functions.php';
require_once 'header.php';

list($category, $products) = fetchCategoryAndProducts($db);
$vegan_products = getVeganProducts($db);
?>

<div class="hero-section">
        <div class="hero-content">
            <h1 class="hero-title"><?php echo $settings['site_title'] ?? 'QR Menü Sistemi'; ?></h1>
            <p class="hero-subtitle"><?php echo $settings['site_description'] ?? 'Dijital menü yönetim sistemi'; ?></p>
        </div>
    </div>

<div class="category-container">
    <div class="category-header">
        <h2 class="category-title"><?php echo $category['name']; ?></h2>
    </div>

<div class="row g-2">
    <?php if (empty($products)): ?>
        <div class="col-12">
            <p class="text-center">Şu anda bu kategori boş.</p>
        </div>
    <?php else: ?>
        <?php foreach($products as $product): 
            $image = !empty($product['image']) ? UPLOAD_DIR . $product['image'] : (isset($settings['logo']) ? UPLOAD_DIR . $settings['logo'] : 'assets/img/no-image.jpg');
        ?>
        <div class="col-lg-4 col-md-6">
            <div class="product-card" 
                data-bs-toggle="modal" 
                data-bs-target="#productModal" 
                data-name="<?php echo $product['name']; ?>"
                data-image="<?php echo $image; ?>"
                data-price="<?php echo number_format($product['price'], 2); ?>"
                data-description="<?php echo !empty($product['description']) ? $product['description'] : 'Açıklama yok.'; ?>">
                
                <?php if (in_array($product, $vegan_products)): ?>
                    <div class="vegan-badge">Vegan</div>
                <?php endif; ?>
                
                <div class="product-image-container">
                    <img src="<?php echo $image; ?>" class="product-image" alt="<?php echo $product['name']; ?>">
                    <div class="price-tag">
                        <?php echo number_format($product['price'], 2); ?> ₺
                    </div>
                </div>

                <div class="product-info">
                    <h3 class="product-title"><?php echo $product['name']; ?></h3>
                    <?php if(!empty($product['description'])): ?>
                    <p class="product-description"><?php echo $product['description']; ?></p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>



</div>


<!-- Ürün Detay Modal -->
<div class="modal fade" id="productModal" tabindex="-1" aria-labelledby="productModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered"> <!-- Burada modal-dialog-centered eklendi -->
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="productModalLabel">Ürün Detayı</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <img id="modalProductImage" src="" class="img-fluid mb-3" alt="Ürün Resmi">
                <h3 id="modalProductName"></h3>
                <p id="modalProductDescription"></p>
                <h4 class="text-danger"><span id="modalProductPrice"></span> ₺</h4>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Kapat</button>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function() {
    var productModal = document.getElementById('productModal');

    productModal.addEventListener('show.bs.modal', function (event) {
        var button = event.relatedTarget;  // Tıklanan ürün kartı
        var name = button.getAttribute('data-name');
        var image = button.getAttribute('data-image');
        var price = button.getAttribute('data-price');
        var description = button.getAttribute('data-description');

        // Modal içindeki öğeleri güncelle
        document.getElementById('modalProductName').textContent = name;
        document.getElementById('modalProductImage').src = image;
        document.getElementById('modalProductPrice').textContent = price;
        document.getElementById('modalProductDescription').textContent = description;
    });
});
</script>



<?php require_once 'footer.php'; ?>