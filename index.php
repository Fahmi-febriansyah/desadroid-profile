<?php
require_once 'config/db.php';

// Get projects from database
try {
    $projects_query = $pdo->query('SELECT * FROM projects ORDER BY order_num ASC, created_at DESC LIMIT 3');
    $projects = $projects_query->fetchAll();
} catch (Exception $e) {
    $projects = [];
}

// Get articles from database
try {
    $articles_query = $pdo->query('SELECT * FROM articles WHERE status = "published" ORDER BY published_date DESC LIMIT 3');
    $articles = $articles_query->fetchAll();
} catch (Exception $e) {
    $articles = [];
}

// Get services from database
try {
    $services_query = $pdo->query('SELECT * FROM services ORDER BY id ASC LIMIT 6');
    $services = $services_query->fetchAll();
} catch (Exception $e) {
    $services = [];
}

// Build canonical for homepage
$scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
$host = $_SERVER['HTTP_HOST'] ?? 'localhost';
$baseDir = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/\\');
if ($baseDir === '/') $baseDir = '';
$canonical = $scheme . '://' . $host . $baseDir . '/';

$pageTitle = 'Desadroid - IT Consulting & Digital Excellence';
$metaDescription = 'Premium IT consulting services specializing in modern web development, mobile apps, and digital transformation.';
include 'partials/header.php';
?>

<div class="custom-cursor"></div>

<!-- Hero Section V2 -->
<section class="hero-v2" id="home">
    <div class="hero-bg-mesh"></div>
    <div class="container">
        <div class="hero-content-v2">
            <span class="reveal-text" style="color: var(--accent); font-weight: 800; letter-spacing: 2px; text-transform: uppercase; font-size: 0.9rem; margin-bottom: 1rem; display: block;">IT Consulting & Strategy</span>
            <h1 class="reveal-text">Elevate Your <br><span class="gradient-text">Digital Vision</span></h1>
            <p class="reveal-text">We build high-performance digital products and provide strategic IT consulting to transform your business into a market leader.</p>
            <div class="hero-btns-v2 reveal-text">
                <a href="#projects" class="btn-premium primary">Our Projects</a>
                <a href="#contact" class="btn-premium secondary">Contact Us</a>
            </div>
        </div>
        <div class="hero-image-v2">
            <img src="src/img/hero.png" alt="Tech Vision" class="floating-img">
        </div>
    </div>
</section>

<!-- Services Section V2 -->
<section class="services-v2" id="services">
    <div class="container">
        <div class="section-header">
            <span>Our Services</span>
            <h2>Innovative Solutions</h2>
        </div>
        <div class="service-grid-v2">
            <?php 
            $icons = ['⚡', '📱', '🎨', '☁️', '🛒', '🔐'];
            foreach ($services as $i => $s): ?>
                <div class="service-card-v2 reveal-card">
                    <span class="icon"><?= $icons[$i % count($icons)] ?></span>
                    <h3><?= htmlspecialchars($s['name']) ?></h3>
                    <p><?= htmlspecialchars($s['description']) ?></p>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Projects Section V2 -->
<section class="projects-v2" id="projects">
    <div class="container">
        <div class="section-header" style="text-align: left;">
            <span>Case Studies</span>
            <h2>Recent Works</h2>
        </div>
        <div class="project-list-v2">
            <?php foreach ($projects as $p): ?>
                <div class="project-card-v2 reveal-project">
                    <div class="project-visual-v2">
                        <img src="<?= !empty($p['image_url']) ? $p['image_url'] : 'https://images.unsplash.com/photo-1460925895917-afdab827c52f?w=800' ?>" alt="<?= htmlspecialchars($p['title']) ?>">
                    </div>
                    <div class="project-info-v2">
                        <span class="project-tag"><?= htmlspecialchars($p['category']) ?></span>
                        <h3><?= htmlspecialchars($p['title']) ?></h3>
                        <p><?= htmlspecialchars(mb_strimwidth(strip_tags($p['description']), 0, 150, '...')) ?></p>
                        <a href="proyek/<?= $p['slug'] ?>" class="btn-premium secondary" style="margin-left: 0; margin-top: 2rem;">Explore Project</a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Footer V2 -->
<footer class="footer-v2">
    <div class="container">
        <div class="footer-grid">
            <div class="footer-about">
                <h4>desadroid.</h4>
                <p>Architecting the digital future with precision and creativity. Your premium partner in IT excellence.</p>
            </div>
            <div class="footer-links">
                <h5>Navigation</h5>
                <ul>
                    <li><a href="#home">Home</a></li>
                    <li><a href="#services">Services</a></li>
                    <li><a href="#projects">Projects</a></li>
                    <li><a href="#contact">Contact</a></li>
                </ul>
            </div>
            <div class="footer-links">
                <h5>Social</h5>
                <ul>
                    <li><a href="#">LinkedIn</a></li>
                    <li><a href="#">Twitter</a></li>
                    <li><a href="#">Instagram</a></li>
                </ul>
            </div>
        </div>
        <div class="footer-bottom" style="text-align: center; border-top: 1px solid var(--border); padding-top: 2rem; color: var(--text-secondary); font-size: 0.9rem;">
            &copy; <?= date('Y') ?> Desadroid. All rights reserved.
        </div>
    </div>
</footer>

<script>
// GSAP Animations
gsap.registerPlugin(ScrollTrigger);

// Initialize Lenis
const lenis = new Lenis();
function raf(time) {
  lenis.raf(time);
  requestAnimationFrame(raf);
}
requestAnimationFrame(raf);

// Cursor Animation
const cursor = document.querySelector('.custom-cursor');
document.addEventListener('mousemove', (e) => {
    gsap.to(cursor, {
        x: e.clientX - 10,
        y: e.clientY - 10,
        duration: 0.2
    });
});

// Reveal Animations
gsap.from('.reveal-text', {
    y: 50,
    opacity: 0,
    duration: 1.2,
    stagger: 0.2,
    ease: 'power4.out'
});

gsap.from('.floating-img', {
    x: 100,
    opacity: 0,
    duration: 1.5,
    ease: 'power3.out'
});

// Scroll Triggers
gsap.utils.toArray('.reveal-card').forEach(card => {
    gsap.from(card, {
        scrollTrigger: {
            trigger: card,
            start: 'top 85%'
        },
        y: 60,
        opacity: 0,
        duration: 1,
        ease: 'power3.out'
    });
});

gsap.utils.toArray('.reveal-project').forEach(project => {
    gsap.from(project, {
        scrollTrigger: {
            trigger: project,
            start: 'top 80%'
        },
        y: 100,
        opacity: 0,
        duration: 1.2,
        ease: 'power3.out'
    });
});

// Navbar Scroll Effect
window.addEventListener('scroll', () => {
    const nav = document.querySelector('.navbar');
    if (window.scrollY > 50) {
        nav.classList.add('scrolled');
    } else {
        nav.classList.remove('scrolled');
    }
});
</script>

<?php include 'partials/footer.php'; ?>
