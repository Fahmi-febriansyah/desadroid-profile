// ===== NAVBAR SCROLL EFFECT =====
window.addEventListener('scroll', function() {
    const navbar = document.getElementById('navbar');
    if (window.scrollY > 50) {
        navbar.classList.add('scrolled');
    } else {
        navbar.classList.remove('scrolled');
    }
    updateActiveNavLink();
});

// ===== ACTIVE NAV LINK =====
function updateActiveNavLink() {
    const sections = document.querySelectorAll('section[id]');
    const navLinks = document.querySelectorAll('.nav-link');
    let currentSection = '';

    sections.forEach(section => {
        const sectionTop = section.offsetTop - 120;
        if (window.scrollY >= sectionTop) {
            currentSection = section.getAttribute('id');
        }
    });

    navLinks.forEach(link => {
        link.classList.remove('active');
        if (link.getAttribute('href') === '#' + currentSection) {
            link.classList.add('active');
        }
    });
}

// ===== SMOOTH SCROLL =====
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function(e) {
        e.preventDefault();
        const target = document.querySelector(this.getAttribute('href'));
        if (target) {
            target.scrollIntoView({ behavior: 'smooth', block: 'start' });
            // Close mobile menu if open
            const navMenu = document.getElementById('navMenu');
            if (navMenu) navMenu.classList.remove('active');
        }
    });
});

// ===== HAMBURGER MENU =====
document.addEventListener('DOMContentLoaded', function() {
    const hamburger = document.getElementById('hamburger');
    const navMenu = document.getElementById('navMenu');

    if (hamburger && navMenu) {
        hamburger.addEventListener('click', function() {
            navMenu.classList.toggle('active');
            this.classList.toggle('active');
        });
    }
});

// ===== PROFILE DROPDOWN =====
document.addEventListener('DOMContentLoaded', function() {
    loadUserProfile();

    const profileBtn = document.getElementById('profileBtn');
    const dropdownMenu = document.getElementById('dropdownMenu');

    if (profileBtn && dropdownMenu) {
        profileBtn.addEventListener('click', function(e) {
            e.stopPropagation();
            profileBtn.classList.toggle('active');
            dropdownMenu.classList.toggle('active');
        });

        // Close dropdown when clicking outside
        document.addEventListener('click', function(e) {
            if (!e.target.closest('.profile-dropdown')) {
                profileBtn.classList.remove('active');
                dropdownMenu.classList.remove('active');
            }
        });
    }

    // Logout handler
    const logoutBtn = document.getElementById('logoutBtn');
    if (logoutBtn) {
        logoutBtn.addEventListener('click', function(e) {
            e.preventDefault();
            logout();
        });
    }
});

// ===== LOAD USER PROFILE =====
function loadUserProfile() {
    let currentUser = localStorage.getItem('currentUser');

    // BYPASS AUTH: Provide dummy user if not logged in
    if (!currentUser) {
        const dummyUser = {
            firstName: 'Guest',
            lastName: 'User',
            email: 'guest@example.com'
        };
        currentUser = JSON.stringify(dummyUser);
    }

    try {
        const user = JSON.parse(currentUser);
        const profileName = document.getElementById('profileName');
        const profileAvatar = document.getElementById('profileAvatar');
        const headerName = document.getElementById('headerName');
        const headerEmail = document.getElementById('headerEmail');

        if (profileName) profileName.textContent = user.firstName;
        if (headerName) headerName.textContent = user.firstName + ' ' + (user.lastName || '');
        if (headerEmail) headerEmail.textContent = user.email;

        const initial = user.firstName.charAt(0).toUpperCase();
        if (profileAvatar) profileAvatar.innerHTML = initial;
    } catch (error) {
        console.error('Error loading user profile:', error);
    }
}

// ===== LOGOUT =====
function logout() {
    const confirmed = confirm('Apakah Anda yakin ingin keluar?');
    if (confirmed) {
        localStorage.removeItem('currentUser');
        window.location.href = '../login.html';
    }
}

// ===== SCROLL ANIMATIONS =====
document.addEventListener('DOMContentLoaded', function() {
    updateActiveNavLink();

    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };

    const observer = new IntersectionObserver(function(entries) {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.opacity = '1';
                entry.target.style.transform = 'translateY(0)';
            }
        });
    }, observerOptions);

    document.querySelectorAll('.service-card, .testimonial-card, .team-card, .stat-card, .contact-card').forEach(card => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(30px)';
        card.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
        observer.observe(card);
    });
});

console.log('Psikologi Kita - Page Loaded');
