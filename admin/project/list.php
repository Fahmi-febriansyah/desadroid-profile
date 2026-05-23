<?php
require_once "../../config/db.php";
require_once "../includes/header.php";
?>

<div class="content">
    <div style="max-width: 600px; margin: 3rem auto; text-align: center; background: #e3f2fd; padding: 3rem; border-radius: 12px; border-left: 4px solid #2196F3;">
        <div style="font-size: 3rem; margin-bottom: 1rem;">??</div>
        <h2 style="font-size: 1.8rem; margin-bottom: 1rem; color: #1565c0;">Manajemen Proyek Dipindahkan</h2>
        <p style="font-size: 1rem; margin-bottom: 1.5rem; line-height: 1.6; color: #424242;">
            Semua fitur manajemen proyek telah dipindahkan ke platform khusus kami di <strong>project.desadroid.shop</strong>. 
            Silakan akses panel admin proyek melalui link berikut:
        </p>
        <div style="margin: 2rem 0;">
            <p style="font-size: 0.95rem; color: #666; margin-bottom: 1.5rem;">
                Anda akan dialihkan dalam <strong id="countdown">5</strong> detik...
            </p>
        </div>
        <a href="https://project.desadroid.shop/admin" style="display: inline-block; padding: 0.8rem 2rem; background: #2196F3; color: white; text-decoration: none; border-radius: 6px; font-weight: 600; font-size: 1rem; transition: all 0.3s; border: none; cursor: pointer;" onmouseover="this.style.background='#1976D2'; this.style.transform='translateY(-2px)';" onmouseout="this.style.background='#2196F3'; this.style.transform='translateY(0)';">
            ?? Buka Admin Proyek ?
        </a>
        <p style="margin-top: 2rem; font-size: 0.9rem; color: #666;">
            Jika tidak dialihkan otomatis, klik tombol di atas
        </p>
    </div>
</div>

<script>
let countdown = 5;
const countdownEl = document.getElementById("countdown");
const interval = setInterval(() => {
    countdown--;
    countdownEl.textContent = countdown;
    if (countdown <= 0) {
        clearInterval(interval);
        window.location.href = "https://project.desadroid.shop/admin";
    }
}, 1000);
</script>
