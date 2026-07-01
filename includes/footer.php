    </main>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="footer-grid">
                <div class="footer-col">
                    <a href="<?= BASE_URL ?>/" class="footer-logo">
                        <span class="logo-text">Kim<span>nest</span></span>
                    </a>
                    <p><?= htmlspecialchars(getSetting('site_tagline', 'We weave your property dream into reality. Transforming shipping containers into exceptional living, working, and business spaces.')) ?></p>
                    <div class="social-links">
                        <a href="<?= getSetting('facebook_url', '#') ?>" target="_blank"><i class="fab fa-facebook-f"></i></a>
                        <a href="<?= getSetting('instagram_url', '#') ?>" target="_blank"><i class="fab fa-instagram"></i></a>
                        <a href="<?= getSetting('linkedin_url', '#') ?>" target="_blank"><i class="fab fa-linkedin-in"></i></a>
                        <a href="<?= getSetting('tiktok_url', '#') ?>" target="_blank"><i class="fab fa-tiktok"></i></a>
                        <a href="<?= getSetting('youtube_url', '#') ?>" target="_blank"><i class="fab fa-youtube"></i></a>
                    </div>
                </div>
                
                <div class="footer-col">
                    <h3>Quick Links</h3>
                    <ul class="footer-links">
                        <li><a href="<?= BASE_URL ?>/">Home</a></li>
                        <li><a href="<?= BASE_URL ?>/about">About Us</a></li>
                        <li><a href="<?= BASE_URL ?>/services">Services</a></li>
                        <li><a href="<?= BASE_URL ?>/products">Products</a></li>
                        <li><a href="<?= BASE_URL ?>/projects">Projects</a></li>
                        <li><a href="<?= BASE_URL ?>/blog">Blog</a></li>
                    </ul>
                </div>
                
                <div class="footer-col">
                    <h3>Our Services</h3>
                    <ul class="footer-links">
                        <li><a href="<?= BASE_URL ?>/services#sales">Container Sales</a></li>
                        <li><a href="<?= BASE_URL ?>/services#fabrication">Container Fabrication</a></li>
                        <li><a href="<?= BASE_URL ?>/services#modifications">Modifications</a></li>
                        <li><a href="<?= BASE_URL ?>/services#homes">Container Homes</a></li>
                        <li><a href="<?= BASE_URL ?>/services#offices">Office Containers</a></li>
                        <li><a href="<?= BASE_URL ?>/services#delivery">Delivery & Installation</a></li>
                    </ul>
                </div>
                
                <div class="footer-col">
                    <h3>Contact Us</h3>
                    <ul class="footer-contact">
                        <li><i class="fas fa-phone"></i> <?= getSetting('site_phone', SITE_PHONE) ?></li>
                        <li><i class="fas fa-envelope"></i> <?= getSetting('site_email', SITE_EMAIL) ?></li>
                        <li><i class="fas fa-map-marker-alt"></i> <?= getSetting('site_address', 'Nairobi, Kenya') ?></li>
                        <li><i class="fas fa-clock"></i> Mon-Fri: 8AM - 5PM</li>
                    </ul>
                    
                    <div class="newsletter">
                        <h4>Newsletter</h4>
                        <form class="newsletter-form" id="newsletterForm" action="<?= BASE_URL ?>/api/newsletter.php">
                            <input type="email" name="email" placeholder="Your email" required>
                            <button type="submit"><i class="fas fa-paper-plane"></i></button>
                        </form>
                    </div>
                </div>
            </div>
            
            <div class="footer-bottom">
                <p>&copy; <?= date('Y') ?> <?= SITE_NAME ?>. All Rights Reserved.</p>
                <p>Designed to inspire. Built to last.</p>
            </div>
        </div>
    </footer>

    <!-- WhatsApp Float -->
    <a href="https://wa.me/<?= getSetting('site_whatsapp', SITE_WHATSAPP) ?>" class="whatsapp-float" target="_blank">
        <i class="fab fa-whatsapp"></i>
    </a>

    <!-- Back to Top -->
    <button class="back-to-top" id="backToTop">
        <i class="fas fa-arrow-up"></i>
    </button>

    <script src="<?= BASE_URL ?>/assets/js/main.js"></script>
</body>
</html>
