<?php

require_once 'header.php';  


?>

<div class="hero-section">
    <div class="hero-content">
        <?php if (!empty($settings['logo'])): ?>
            <img src="<?php echo UPLOAD_DIR . $settings['logo']; ?>" alt="Site Logo" class="hero-logo">
        <?php endif; ?>
        <h1 class="hero-title"><?php echo $settings['site_title'] ?? 'QR Menü Sistemi'; ?></h1>
        <p class="hero-subtitle"><?php echo $settings['site_description'] ?? 'Dijital menü yönetim sistemi'; ?></p>
    </div>
</div>



<div class="category-container">
    <div class="category-header">
        <h2 class="category-title">Önerilen</h2>
    </div>
    <div class="recommended-products-container">
        <div class="products-carousel">
            <?php
            if ($recommended_products):
                foreach ($recommended_products as $product):
                    $image = !empty($product['image']) ? UPLOAD_DIR . $product['image'] : (isset($settings['logo']) ? UPLOAD_DIR . $settings['logo'] : 'assets/img/no-image.jpg');
            ?>
            <div class="product-card-home"> <!-- product-card yerine product-card-home kullanıldı -->
                <div class="product-image-container">
                    <img src="<?php echo $image; ?>" class="product-image" alt="<?php echo htmlspecialchars($product['name']); ?>">
                    <div class="price-tag"><?php echo number_format($product['price'], 2); ?> ₺</div>
                </div>
                <div class="product-info">
                    <h3 class="product-title"><?php echo htmlspecialchars($product['name']); ?></h3>
                </div>
            </div>
            <?php endforeach; else: ?>
            <p>Önerilen ürün bulunamadı.</p>
            <?php endif; ?>
        </div>
    </div>
</div>





<div class="category-container">
        <div class="category-header">
            <h2 class="category-title">Kategoriler</h2>
        </div>
    
    <div class="categories-container">
        <?php foreach($categories as $category): ?>
            <a href="category.php?id=<?php echo $category['id']; ?>" class="category-box">
                <div class="category-name"><?php echo $category['name']; ?></div>
            </a>
        <?php endforeach; ?>
    </div>
</div>


    <div class="container main-content" id="products">
        <?php
        // Önerilen ürünleri göster
        $recommended_products = $db->query($recommended_query)->fetchAll(PDO::FETCH_ASSOC);
        if(count($recommended_products) > 0):
        ?>
        <div class="category-container">
            <div class="category-header">
                <h2 class="category-title">Önerilen</h2>
            </div>
            <div class="row g-4">
                <?php foreach($recommended_products as $product): 
                    $image = !empty($product['image']) ? UPLOAD_DIR . $product['image'] : (isset($settings['logo']) ? UPLOAD_DIR . $settings['logo'] : 'assets/img/no-image.jpg');
                ?>
                <div class="col-lg-4 col-md-6">
                    <div class="product-card">
                        <div class="recommended-badge">Önerilen</div>
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
            </div>
        </div>
        <?php endif; ?>

        <?php
        // Yeni ürünleri göster
        $new_products = $db->query($new_products_query)->fetchAll(PDO::FETCH_ASSOC);
        if(count($new_products) > 0):
        ?>
        <div class="category-container">
            <div class="category-header">
                <h2 class="category-title">Yeni</h2>
            </div>
            <div class="row g-4">
                <?php foreach($new_products as $product): 
                    $image = !empty($product['image']) ? UPLOAD_DIR . $product['image'] : (isset($settings['logo']) ? UPLOAD_DIR . $settings['logo'] : 'assets/img/no-image.jpg');
                ?>
                <div class="col-lg-4 col-md-6">
                    <div class="product-card">
                        <div class="new-badge">Yeni</div>
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
            </div>
        </div>
        <?php endif; ?>

        <?php
        // Normal kategorileri göster
        
        $categories = $db->query("SELECT * FROM categories WHERE status = 1")->fetchAll(PDO::FETCH_ASSOC);

        foreach($categories as $category):
            $products = $db->prepare("SELECT * FROM products WHERE status = 1 AND category_id = ?");
            $products->execute([$category['id']]);
            $products = $products->fetchAll(PDO::FETCH_ASSOC);

            if(count($products) > 0):
        ?>
        
 
        <?php 
            endif;
        endforeach; 
        ?>
    </div>

<?php
require_once 'footer.php'; 

?>

<script>
document.addEventListener("DOMContentLoaded", function () {
    const carousel = document.querySelector(".products-carousel");
    const dotsContainer = document.querySelector(".carousel-dots");
    let isDown = false;
    let startX;
    let scrollLeft;

    // Mouse ile sürükleme
    carousel.addEventListener("mousedown", (e) => {
        isDown = true;
        startX = e.pageX - carousel.offsetLeft;
        scrollLeft = carousel.scrollLeft;
        carousel.style.cursor = "grabbing";
    });

    carousel.addEventListener("mouseleave", () => {
        isDown = false;
        carousel.style.cursor = "grab";
    });

    carousel.addEventListener("mouseup", () => {
        isDown = false;
        carousel.style.cursor = "grab";
    });

    carousel.addEventListener("mousemove", (e) => {
        if (!isDown) return;
        e.preventDefault();
        const x = e.pageX - carousel.offsetLeft;
        const walk = (x - startX) * 2; // Kaydırma hassasiyeti
        carousel.scrollLeft = scrollLeft - walk;
    });

    // Dokunmatik cihazlar için
    let touchStartX = 0;
    carousel.addEventListener("touchstart", (e) => {
        touchStartX = e.touches[0].clientX;
    });

    carousel.addEventListener("touchmove", (e) => {
        const touchX = e.touches[0].clientX;
        const walk = touchStartX - touchX;
        carousel.scrollLeft += walk * 2;
        touchStartX = touchX;
    });



});

const carousel = document.querySelector('.products-carousel');
let scrollAmount = 0;
const scrollStep = 300; // Her kaydırmada ne kadar ilerleyecek

function autoScroll() {
    if (scrollAmount >= carousel.scrollWidth - carousel.offsetWidth) {
        scrollAmount = 0; // Baştan başla
    } else {
        scrollAmount += scrollStep;
    }
    carousel.scrollTo({
        left: scrollAmount,
        behavior: 'smooth'
    });
}

setInterval(autoScroll, 3000); // 3 saniyede bir otomatik kaydır

</script>


<script>
document.addEventListener("DOMContentLoaded", function() {
    let carousel = document.querySelector(".products-carousel");
    let scrollAmount = 0;
    let scrollMax = carousel.scrollWidth - carousel.clientWidth;
    
    function autoScroll() {
        if (scrollAmount >= scrollMax) {
            scrollAmount = 0;
            carousel.scrollTo({ left: 0, behavior: "smooth" });
        } else {
            scrollAmount += 150; // Bir ürün genişliği kadar kaydır
            carousel.scrollBy({ left: 150, behavior: "smooth" });
        }
    }

    let interval = setInterval(autoScroll, 2000); // 2 saniyede bir kaydır
    
    // Kullanıcı manuel kaydırırsa durdur ve tekrar başlat
    carousel.addEventListener("mouseenter", () => clearInterval(interval));
    carousel.addEventListener("mouseleave", () => interval = setInterval(autoScroll, 2000));
});
</script>