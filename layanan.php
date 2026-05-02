<?php
require_once 'config/db.php';
?>
<?php include 'partials/header.php'; ?>

    <section class="services" style="padding:5rem 0;" data-reveal>
        <div class="container">
            <h2>Layanan Kami</h2>
            <div class="service-cards">
                <?php
                try {
                    $services_query = $pdo->query('SELECT * FROM services ORDER BY id ASC');
                    $services = $services_query->fetchAll();
                } catch (Exception $e) {
                    $services = [];
                }

                // Map service names to simple icons (emoji) or use generic icon
                $icons = [
                    'Web Development' => '🌐',
                    'Mobile App' => '📱',
                    'UX/UI Design' => '🎨',
                    'Backend Development' => '⚙️',
                    'E-Commerce' => '🛒',
                    'Consulting' => '💼'
                ];
                ?>

                <?php if (!empty($services)): ?>
                    <?php $delay = 100; foreach ($services as $service): ?>
                    <div class="card" data-reveal data-reveal-delay="<?= $delay ?>">
                        <div class="icon" style="font-size: 2rem; margin-bottom: 1rem;"><?= htmlspecialchars($icons[$service['name']] ?? '✨') ?></div>
                        <h3><?= htmlspecialchars($service['name']) ?></h3>
                        <p><?= htmlspecialchars($service['description']) ?></p>
                    </div>
                    <?php $delay += 100; endforeach; ?>
                <?php else: ?>
                    <div class="card" data-reveal data-reveal-delay="100">
                        <h3>Pengembangan Web</h3>
                        <p>Membangun situs responsif dan dapat diakses dengan teknologi modern.</p>
                    </div>
                    <div class="card" data-reveal data-reveal-delay="200">
                        <h3>Pengembangan Aplikasi Mobile</h3>
                        <p>Aplikasi native dan cross-platform dengan UI yang menyenangkan.</p>
                    </div>
                    <div class="card" data-reveal data-reveal-delay="300">
                        <h3>Desain UI/UX</h3>
                        <p>Solusi desain berfokus pengguna yang meningkatkan keterlibatan dan konversi.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </section>

<?php include 'partials/footer.php'; ?>
