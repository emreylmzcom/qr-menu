<?php

require_once 'admin/functions.php';

?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $settings['site_title'] ?? 'QR Menü Sistemi'; ?></title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="admin/assets/css/front.css" rel="stylesheet">

<?php
$settingsQuery = $db->query("SELECT * FROM settings");
$settings = $settingsQuery->fetch(PDO::FETCH_ASSOC);
?>
<link rel="icon" href="<?php echo htmlspecialchars($settings['logo']); ?>" type="image/x-icon">
</head>
<body>
    <nav class="navbar navbar-expand-lg">
        <div class="container">
            <a class="btn btn-primary" href="index.php" style="background-color: #e67e22; border-color: #e67e22;">
                <i class="fa fa-home"></i>
            </a>
            <a class="navbar-brand" href="index.php">
                <?php echo $settings['site_title'] ?? 'QR Menü Sistemi'; ?>
            </a>
            <button class="btn btn-secondary" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasCategories" aria-controls="offcanvasCategories" style="background-color: #e67e22; border-color: #e67e22;">
                <i class="fa fa-bars"></i>
            </button>
        </div>
        <div class="gtranslate_wrapper"></div>
    </nav>

    <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasCategories" aria-labelledby="offcanvasCategoriesLabel">

        <div class="offcanvas-header">
            <h5 class="offcanvas-title" id="offcanvasCategoriesLabel">Kategoriler</h5>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body">
            <ul class="list-group">
                <?php
                $categoriesQuery = $db->query("SELECT * FROM categories");
                while ($category = $categoriesQuery->fetch(PDO::FETCH_ASSOC)) {
                    echo '<li class="list-group-item"><a class="kategori-liste-link" href="category.php?id=' . htmlspecialchars($category['id']) . '">' . htmlspecialchars($category['name']) . '</a></li>';
                }
                ?>
            </ul>
        </div>
    </div>
            
        </div>
    </nav>


    
<script>window.gtranslateSettings = {"default_language":"tr","detect_browser_language":true,"languages":["tr","en","de","ar"],"wrapper_selector":".gtranslate_wrapper"}</script>
<script src="https://cdn.gtranslate.net/widgets/latest/float.js" defer></script>