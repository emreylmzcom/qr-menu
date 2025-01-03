<?php  
// Veritabanı bağlantı bilgileri  
define('DB_HOST', 'localhost');  
define('DB_USER', 'root');  
define('DB_PASS', '');  
define('DB_NAME', 'qr_menu');  
  
// Veritabanı bağlantısı  
try {  
    $db = new PDO("mysql:host=".DB_HOST.";dbname=".DB_NAME, DB_USER, DB_PASS);  
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);  
    $db->exec("SET NAMES 'utf8'");  
} catch(PDOException $e) {  
    echo "Bağlantı hatası: " . $e->getMessage();  
    exit;  
}  
  
// Oturum başlat  
session_start();  
  
// Zaman dilimi ayarı  
date_default_timezone_set('Europe/Istanbul');  
  
// Site ayarları    
define('SITE_URL', 'http://localhost/qr-menu/');    
define('UPLOAD_DIR', 'admin/uploads/'); // Web URL'leri için    
define('SYSTEM_UPLOAD_DIR', __DIR__ . '/uploads/'); // Dosya sistemi işlemleri için


  
// Görsel URL'si oluşturma  
$image = !empty($product['image'])     
    ? SITE_URL . UPLOAD_DIR . $product['image']     
    : SITE_URL . 'assets/img/no-image.jpg';  
?>  