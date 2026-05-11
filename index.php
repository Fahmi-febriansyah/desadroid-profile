<?php
require_once 'config/db.php';

// Get projects from database
try {
    $projects_query = $pdo->query('SELECT * FROM projects ORDER BY order_num ASC, created_at DESC LIMIT 3');
    $projects = $projects_query->fetchAll();
} catch (Exception $e) {
    $projects = [];
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

$pageTitle = 'Desadroid - Strategic IT Consulting';
$metaDescription = 'Professional IT consulting and digital transformation services.';
include 'partials/header.php';
?>

<!-- Hero Section V3 (Light Mode) -->
<section class="hero-v3" id="home">
    <div class="container">
        <span class="badge reveal-text">Innovative IT Solutions</span>
        <h1 class="reveal-text">Partnering for Your <br><span class="gradient-text">Digital Success</span></h1>
        <p class="reveal-text">We provide expert IT consulting and build high-quality digital products to help your business scale and thrive in a competitive landscape.</p>
        <div class="hero-actions-v3 reveal-text">
            <a href="#projects" class="btn-premium primary">View Our Work</a>
            <a href="#contact" class="btn-premium secondary">Consult Now</a>
        </div>
    </div>
</section>

<!-- Services Section V3 -->
<section class="section-v3" id="services" style="background-color: var(--bg-light);">
    <div class="container">
        <div class="section-title-v3">
            <h2>Our Expertise</h2>
            <p style="color: var(--text-muted); max-width: 600px; margin: 0 auto;">Tailored technology solutions designed to solve complex business challenges.</p>
        </div>
        <div class="grid-v3">
            <?php 
            $icons = ['💻', '📱', '🎨', '🚀', '📊', '🛡️'];
            foreach ($services as $i => $s): ?>
                <div class="card-v3 reveal-card">
                    <span class="icon"><?= $icons[$i % count($icons)] ?></span>
                    <h3><?= htmlspecialchars($s['name']) ?></h3>
                    <p><?= htmlspecialchars($s['description']) ?></p>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Projects Section V3 -->
<section class="section-v3" id="projects">
    <div class="container">
        <div class="section-title-v3" style="text-align: left;">
            <h2>Recent Projects</h2>
        </div>
        <div class="project-list-v3">
            <?php foreach ($projects as $p): ?>
                <div class="project-card-v3 reveal-project">
                    <div class="project-img-v3">
                        <img src="<?= !empty($p['image_url']) ? $p['image_url'] : 'https://images.unsplash.com/photo-1460925895917-afdab827c52f?w=800' ?>" alt="<?= htmlspecialchars($p['title']) ?>">
                    </div>
                    <div class="project-info-v3">
                        <span style="color: var(--primary-color); font-weight: 700; text-transform: uppercase; font-size: 0.8rem; letter-spacing: 1px;"><?= htmlspecialchars($p['category']) ?></span>
                        <h3 style="font-size: 2rem; margin: 1rem 0;"><?= htmlspecialchars($p['title']) ?></h3>
                        <p style="color: var(--text-muted); margin-bottom: 2rem;"><?= htmlspecialchars(mb_strimwidth(strip_tags($p['description']), 0, 150, '...')) ?></p>
                        <a href="proyek/<?= $p['slug'] ?>" class="btn-premium secondary" style="margin-left: 0;">Explore Details</a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<script>
// GSAP Initialization
gsap.registerPlugin(ScrollTrigger);

// Reveal Animations
gsap.from('.reveal-text', {
    y: 30,
    opacity: 0,
    duration: 1,
    stagger: 0.15,
    ease: 'power3.out'
});

// Scroll Triggers
gsap.utils.toArray('.reveal-card').forEach(card => {
    gsap.from(card, {
        scrollTrigger: {
            trigger: card,
            start: 'top 90%'
        },
        y: 40,
        opacity: 0,
        duration: 0.8,
        ease: 'power2.out'
    });
});

gsap.utils.toArray('.reveal-project').forEach(project => {
    gsap.from(project, {
        scrollTrigger: {
            trigger: project,
            start: 'top 85%'
        },
        y: 60,
        opacity: 0,
        duration: 1,
        ease: 'power2.out'
    });
});

// Navbar effect
window.addEventListener('scroll', () => {
    const nav = document.querySelector('.navbar');
    if (window.scrollY > 40) {
        nav.classList.add('scrolled');
    } else {
        nav.classList.remove('scrolled');
    }
});
</script>

<?php include 'partials/footer.php'; ?>
