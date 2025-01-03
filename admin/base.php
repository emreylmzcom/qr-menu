<?php  
checkSession(); 
 

function isActive($page) {
    return isset($_GET['page']) && $_GET['page'] === $page ? 'active' : '';
}

$page = $_GET['page'] ?? 'dashboard';  
$title = ucfirst($page) . ' - QR Menü Admin';  

// Sayfa içeriğini belirle  
switch($page) {  
    case 'categories':  
        $content = 'pages/categories.php';  
        break;  
    case 'products':  
        $content = 'pages/products.php';  
        break;  
    case 'qr':  
        $content = 'pages/qr-codes.php';  
        break;  
    case 'settings':  
        $content = 'pages/settings.php';  
        break;  
    default:  
        $content = 'pages/dashboard.php';  
        break;
    case 'recommended_products':
        $content = 'pages/recommended_products.php';
        break;
    case 'profile':
        $content = 'pages/profile.php';
        
        
}  
?>  
<!DOCTYPE html>  
<html lang="tr">  
<head>  
    <meta charset="UTF-8">  
    <meta name="viewport" content="width=device-width, initial-scale=1.0">  
    <title><?php echo $title; ?></title>  
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">  
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">  
    <link href="assets/css/style.css" rel="stylesheet">  
</head>  
<body>  
    <div class="container-fluid">  
        <div class="row">  
            <!-- Sidebar -->  
            <div class="col-md-3 col-lg-2 sidebar p-0">  
                <div class="d-flex flex-column p-3">  
                    <h4 class="text-center mb-4">QR Menü Admin</h4>  
                    <nav class="nav flex-column">  
                        <a class="nav-link <?php echo isActive('dashboard'); ?>" href="?page=dashboard">  
                            <i class="fas fa-home me-2"></i> Dashboard  
                        </a>  
                        <a class="nav-link <?php echo isActive('categories'); ?>" href="?page=categories">  
                            <i class="fas fa-list me-2"></i> Kategoriler  
                        </a>  
                        <a class="nav-link <?php echo isActive('products'); ?>" href="?page=products">  
                            <i class="fas fa-utensils me-2"></i> Ürünler  
                        </a> 
                        <a class="nav-link <?php echo isActive('recommended_products'); ?>" href="?page=recommended_products">  
                            <i class="fas fa-star me-2"></i> Önerilen Ürünler
                        </a> 
                        <a class="nav-link <?php echo isActive('qr'); ?>" href="?page=qr">  
                            <i class="fas fa-qrcode me-2"></i> QR Kod
                        </a>  
                        <a class="nav-link <?php echo isActive('settings'); ?>" href="?page=settings">  
                            <i class="fas fa-cog me-2"></i> Ayarlar  
                        </a>
                        <a class="nav-link <?php echo isActive('profile'); ?>" href="?page=profile">  
                            <i class="fas fa-user me-2"></i> Profil
                        </a>  
                    </nav>  
                </div>  
            </div>  

            <!-- Main Content -->  
            <div class="col-md-9 col-lg-10 main-content">  
                <!-- Top Navbar -->  
                <nav class="navbar navbar-expand-lg mb-4">  
                    <div class="container-fluid">  
                        <button class="navbar-toggler" type="button">  
                            <span class="navbar-toggler-icon"></span>  
                        </button>  
                        <div class="d-flex align-items-center">  
                            <div class="dropdown">  
                                <button class="btn btn-link dropdown-toggle text-dark" type="button" id="userDropdown" data-bs-toggle="dropdown">  
                                    <i class="fas fa-user me-2"></i><?php echo $_SESSION['admin']['username']; ?>  
                                </button>  
                                <ul class="dropdown-menu">  
                                    <li><a class="dropdown-item" href="?page=profile"><i class="fas fa-user-cog me-2"></i>Profil</a></li>  
                                    <li><a class="dropdown-item" href="logout.php"><i class="fas fa-sign-out-alt me-2"></i>Çıkış</a></li>  
                                </ul>  
                            </div>  
                        </div>  
                    </div>  
                </nav>  

                <?php  
                if(file_exists($content)) {  
                    include $content;  
                } else {  
                    echo errorMessage("Sayfa bulunamadı!");  
                }  
                ?>  
            </div>  
        </div>  
    </div>  

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>  
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>  
    <script src="assets/js/main.js"></script>  
</body>  
</html>  