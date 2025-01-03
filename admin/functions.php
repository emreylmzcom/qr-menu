<?php  

include_once 'config.php';

// Güvenli string temizleme fonksiyonu  
if (!function_exists('clean')) {
    function clean($string) {  
        return htmlspecialchars(trim($string), ENT_QUOTES, 'UTF-8');  
    }
}



// Başarı mesajı oluşturma
if (!function_exists('successMessage')) {
    function successMessage($message) {  
        return '<div class="alert alert-success">' . clean($message) . '</div>';  
    }
}

// Hata mesajı oluşturma
if (!function_exists('errorMessage')) {
    function errorMessage($message) {  
        return '<div class="alert alert-danger">' . clean($message) . '</div>';  
    }
}

// Oturum kontrolü
if (!function_exists('checkSession')) {
    function checkSession() {  
        if(!isset($_SESSION['admin'])) {  
            header('Location: login.php');  
            exit;  
        }  
    }
}

// Para formatı
if (!function_exists('formatPrice')) {
    function formatPrice($price) {  
        return number_format($price, 2, ',', '.');  
    }
}




// Dosya yükleme fonksiyonu    
function uploadFile($file, $allowed_types = ['jpg', 'jpeg', 'png']) {    
    if($file['error'] === 0) {    
        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));    

        if(in_array($ext, $allowed_types)) {    
            // Benzersiz dosya adı oluştur  
            $filename = uniqid() . '.' . $ext;    
            $upload_path = SYSTEM_UPLOAD_DIR . $filename;    

            // Yükleme dizini yoksa oluştur  
            if (!file_exists(SYSTEM_UPLOAD_DIR)) {  
                mkdir(SYSTEM_UPLOAD_DIR, 0777, true);  
            }  

            if(move_uploaded_file($file['tmp_name'], $upload_path)) {    
                return $filename;    
            } else {  
                echo "Dosya yüklenirken bir hata oluştu.";  
            }    
        } else {  
            echo "Geçersiz dosya türü.";  
        }  
    } else {  
        echo "Dosya yükleme hatası: " . $file['error'];  
    }    
    return false;    
}  


// Veritabanından ayarları çek
$settingsQuery = $db->query("SELECT * FROM settings LIMIT 1");
$settings = $settingsQuery->fetch(PDO::FETCH_ASSOC);

// Kategori bilgilerini al
$category = $db->prepare("SELECT * FROM categories WHERE id = ?");
$category = $category->fetch(PDO::FETCH_ASSOC);






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


// Önerilen ürünleri çek
$recommended_query = "
    SELECT p.* 
    FROM products p
    INNER JOIN recommended_products rp ON p.id = rp.product_id
    WHERE p.status = 1 AND rp.is_recommended = 1
";

// Yeni ürünleri çek
$new_products_query = "
    SELECT p.* 
    FROM products p
    INNER JOIN recommended_products rp ON p.id = rp.product_id
    WHERE p.status = 1 AND rp.is_new = 1
";


// kategorileri çek
$categories = $db->query("SELECT * FROM categories WHERE status = 1")->fetchAll(PDO::FETCH_ASSOC);

// ayarları çek
$settingsQuery = $db->query("SELECT * FROM settings LIMIT 1");
$settings = $settingsQuery->fetch(PDO::FETCH_ASSOC);


// Önerilen ürünleri göster
$recommended_products = $db->query($recommended_query)->fetchAll(PDO::FETCH_ASSOC);

function getCategoryAndProducts($db, $category_id) {
    // Kategori bilgilerini al
    $category = $db->prepare("SELECT * FROM categories WHERE id = ?");
    $category->execute([$category_id]);
    $category = $category->fetch(PDO::FETCH_ASSOC);

    // Kategoriye ait ürünleri al
    $products = $db->prepare("SELECT * FROM products WHERE category_id = ? AND status = 1");
    $products->execute([$category_id]);
    $products = $products->fetchAll(PDO::FETCH_ASSOC);

    return [$category, $products];
}

function fetchCategoryAndProducts($db) {
    // Kategori ID'sini URL'den al
    $category_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

    list($category, $products) = getCategoryAndProducts($db, $category_id);

    if (!$category) {
        echo "Kategori bulunamadı.";
        exit;
    }

    return [$category, $products];
}

function getVeganProducts($db) {
    $vegan_query = "
        SELECT p.* 
        FROM products p
        INNER JOIN recommended_products rp ON p.id = rp.product_id
        WHERE p.status = 1 AND rp.is_vegan = 1
    ";
    return $db->query($vegan_query)->fetchAll(PDO::FETCH_ASSOC);
}



?>  