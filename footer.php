<?php

// Veritabanından ayarları çek
$settingsQuery = $db->query("SELECT * FROM settings LIMIT 1");
$settings = $settingsQuery->fetch(PDO::FETCH_ASSOC);

?>
<footer class="footer">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <h4 class="footer-title"><?= $settings['site_title'] ?? 'QR Menü Sistemi'; ?></h4>
                    <p><?= $settings['footer_text'] ?? ''; ?></p>
                </div>
                <div class="col-md-6">
                    <h4 class="footer-title">İletişim</h4>
                    <ul class="footer-contact">
                        <?php if(!empty($settings['contact_phone'])): ?>
                        <li>
                            <i class="fas fa-phone"></i>
                            <span><?= $settings['contact_phone']; ?></span>
                        </li>
                        <?php endif; ?>
                        <?php if(!empty($settings['contact_email'])): ?>
                        <li>
                            <i class="fas fa-envelope"></i>
                            <span><?= $settings['contact_email']; ?></span>
                        </li>
                        <?php endif; ?>
                        <?php if(!empty($settings['address'])): ?>
                        <li>
                            <i class="fas fa-map-marker-alt"></i>
                            <span><?= $settings['address']; ?></span>
                        </li>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>
            <div class="copyright">
                <p>© <?= date('Y'); ?> <?= $settings['site_title'] ?? 'QR Menü Sistemi'; ?>. Tüm hakları saklıdır. <br> Yapım: <a href="https://yilmazemre.tr" target="_blank">EY</a></p>

            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>