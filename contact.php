<?php
include 'partials/header.php';
?>
<section class="article-wrap">
    <h1 class="article-title">Contact</h1>
    <div class="article-content">
        <p>If you have any questions, project inquiries, or want to collaborate, please reach out to us:</p>
        <ul>
            <li>Email: <a href="mailto:info@desadroid.shop">info@desadroid.shop</a></li>
            <li>Phone: <a href="tel:+6281234567890">+62 812-3456-7890</a></li>
            <li>Address: Jl. Contoh Alamat No. 123, Kota, Indonesia</li>
        </ul>
        <p>Or use the contact form below:</p>
        <form method="post" action="send_message.php">
            <input type="text" name="name" placeholder="Your Name" required><br><br>
            <input type="email" name="email" placeholder="Your Email" required><br><br>
            <textarea name="message" placeholder="Your Message" required></textarea><br><br>
            <button type="submit">Send Message</button>
        </form>
    </div>
</section>
<?php include 'partials/footer.php'; ?>