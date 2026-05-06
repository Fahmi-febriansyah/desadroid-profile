

function togglePassword() {
    const passwordInput = document.getElementById('password');
    const toggleBtn = event.target.closest('.toggle-password');
    togglePassword_toggle(passwordInput, toggleBtn);
}

function toggleRegPassword() {
    const passwordInput = document.getElementById('regPassword');
    const toggleBtn = event.target.closest('.toggle-password');
    togglePassword_toggle(passwordInput, toggleBtn);
}

function toggleConfirmPassword() {
    const passwordInput = document.getElementById('confirmPassword');
    const toggleBtn = event.target.closest('.toggle-password');
    togglePassword_toggle(passwordInput, toggleBtn);
}

function togglePassword_toggle(input, btn) {
    if (input.type === 'password') {
        input.type = 'text';
        btn.innerHTML = '<i class="fas fa-eye-slash"></i>';
    } else {
        input.type = 'password';
        btn.innerHTML = '<i class="fas fa-eye"></i>';
    }
}

const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
const phoneRegex = /^(\+62|62|0)[0-9]{9,12}$/;
const passwordRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[a-zA-Z\d@$!%*?&]{8,}$/;

const loginForm = document.getElementById('loginForm');
if (loginForm) {
    loginForm.addEventListener('submit', function(e) {
        e.preventDefault();

        window.location.href = './user/index.html';
    });
}

const registerForm = document.getElementById('registerForm');
if (registerForm) {
    registerForm.addEventListener('submit', function(e) {
        e.preventDefault();

        window.location.href = './login.html';
    });
}

function showError(fieldId, message) {
    const errorElement = document.getElementById(fieldId + 'Error');
    const formGroup = document.getElementById(fieldId).closest('.form-group');

    if (errorElement) {
        errorElement.textContent = message;
        errorElement.classList.add('show');
        formGroup.classList.add('error');
    }
}

function clearError(fieldId) {
    const errorElement = document.getElementById(fieldId + 'Error');
    const formGroup = document.getElementById(fieldId).closest('.form-group');

    if (errorElement) {
        errorElement.textContent = '';
        errorElement.classList.remove('show');
        formGroup.classList.remove('error');
    }
}

function calculateAge(birthDate) {
    const today = new Date();
    let age = today.getFullYear() - birthDate.getFullYear();
    const monthDiff = today.getMonth() - birthDate.getMonth();

    if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < birthDate.getDate())) {
        age--;
    }

    return age;
}

function generatePatientId() {
    const timestamp = Date.now().toString().slice(-8);
    const random = Math.floor(Math.random() * 1000).toString().padStart(3, '0');
    return 'PSY' + timestamp + random;
}

document.addEventListener('DOMContentLoaded', function() {

    if (loginForm) {
        const inputs = loginForm.querySelectorAll('input');
        inputs.forEach(input => {
            input.addEventListener('input', function() {
                const fieldId = this.id;
                clearError(fieldId);
            });
        });
    }

    if (registerForm) {
        const inputs = registerForm.querySelectorAll('input, select');
        inputs.forEach(input => {
            input.addEventListener('input', function() {
                const fieldId = this.id;
                clearError(fieldId);
            });
            input.addEventListener('change', function() {
                const fieldId = this.id;
                clearError(fieldId);
            });
        });
    }
});

function initSampleData() {
    if (!localStorage.getItem('users')) {
        const sampleUsers = [
            {
                id: 'PSY20260502001',
                firstName: 'Budi',
                lastName: 'Santoso',
                email: 'budi@example.com',
                phone: '081234567890',
                birthDate: '1990-05-15',
                gender: 'male',
                registeredAt: '2024-01-15T10:00:00Z'
            },
            {
                id: 'PSY20260502002',
                firstName: 'Siti',
                lastName: 'Nurhaliza',
                email: 'siti@example.com',
                phone: '082987654321',
                birthDate: '1992-08-20',
                gender: 'female',
                registeredAt: '2024-01-20T14:30:00Z'
            },
            {
                id: 'PSY20260502003',
                firstName: 'Ahmad',
                lastName: 'Ridho',
                email: 'ahmad@example.com',
                phone: '085678901234',
                birthDate: '1988-03-10',
                gender: 'male',
                registeredAt: '2024-02-01T09:15:00Z'
            }
        ];
        localStorage.setItem('users', JSON.stringify(sampleUsers));
        console.log('Sample data initialized:', sampleUsers);
    }
}

document.addEventListener('DOMContentLoaded', initSampleData);

function demoLogin() {
    const users = JSON.parse(localStorage.getItem('users')) || [];
    if (users.length > 0) {
        const user = users[0];
        document.getElementById('email').value = user.email;
        document.getElementById('password').value = 'Demo@12345';
    }
}

