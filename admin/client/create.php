<?php
$page_title = 'Tambah Klien';
require_once '../../config/db.php';
require_once '../includes/header.php';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $client_name = trim($_POST['client_name'] ?? '');
    $company = trim($_POST['company'] ?? '');
    $message = trim($_POST['message'] ?? '');
    $rating = intval($_POST['rating'] ?? 5);
    $image_url = '';

    if (empty($client_name)) {
        $error = 'Nama klien harus diisi!';
    } else {

        try {

            if (!empty($_FILES['image']['name'])) {

                $uploadDir = "../../uploads/testimonials/";

                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0777, true);
                }

                $filename = time().'_'.basename($_FILES['image']['name']);
                $target = $uploadDir.$filename;

                if (move_uploaded_file($_FILES['image']['tmp_name'], $target)) {
                    $image_url = "/uploads/testimonials/".$filename;
                }
            }

            $stmt = $pdo->prepare("
                INSERT INTO testimonials 
                (client_name, company, message, rating, image_url) 
                VALUES (?, ?, ?, ?, ?)
            ");

            $stmt->execute([
                $client_name,
                $company,
                $message,
                $rating,
                $image_url
            ]);

            $success = "Klien berhasil ditambahkan!";
            header("Refresh:2; url=list.php");

        } catch(Exception $e){
            $error = $e->getMessage();
        }
    }
}
?>

<div class="page-header">
<h2>Tambah Testimonial Klien</h2>
<a href="list.php" class="btn btn-secondary">Kembali</a>
</div>

<?php if($success): ?>
<div class="alert success"><?= $success ?></div>
<?php endif; ?>

<?php if($error): ?>
<div class="alert error"><?= $error ?></div>
<?php endif; ?>

<div class="form-card">

<form method="POST" enctype="multipart/form-data">

<div class="grid">

<div class="form-group">
<label>Nama Klien *</label>
<input type="text" name="client_name" required>
</div>

<div class="form-group">
<label>Perusahaan</label>
<input type="text" name="company">
</div>

</div>

<div class="grid">

<div class="form-group">
<label>Rating</label>
<select name="rating">
<option value="5">★★★★★ (Excellent)</option>
<option value="4">★★★★ (Very Good)</option>
<option value="3">★★★ (Good)</option>
<option value="2">★★ (Fair)</option>
<option value="1">★ (Poor)</option>
</select>
</div>

<div class="form-group">
<label>Foto Klien</label>
<input type="file" name="image">
</div>

</div>

<div class="form-group">
<label>Pesan Testimonial</label>
<textarea name="message" rows="5"></textarea>
</div>

<div class="btn-group">
<button type="submit" class="btn primary">Simpan Testimonial</button>
<a href="list.php" class="btn secondary">Batal</a>
</div>

</form>
</div>

<style>

.form-card{
background:white;
padding:30px;
border-radius:10px;
box-shadow:0 4px 15px rgba(0,0,0,0.08);
max-width:900px;
margin:auto;
}

.grid{
display:grid;
grid-template-columns:1fr 1fr;
gap:20px;
margin-bottom:20px;
}

.form-group{
display:flex;
flex-direction:column;
}

label{
font-weight:600;
margin-bottom:6px;
color:#333;
}

input,select,textarea{
padding:12px;
border:1px solid #ddd;
border-radius:6px;
font-size:14px;
transition:0.2s;
}

input:focus,select:focus,textarea:focus{
border-color:#007bff;
outline:none;
box-shadow:0 0 0 2px rgba(0,123,255,0.1);
}

.btn-group{
display:flex;
gap:15px;
margin-top:20px;
}

.btn{
padding:12px 18px;
border:none;
border-radius:6px;
cursor:pointer;
text-decoration:none;
font-weight:600;
}

.btn.primary{
background:#007bff;
color:white;
}

.btn.secondary{
background:#e5e7eb;
color:#333;
}

.alert{
padding:12px;
margin-bottom:20px;
border-radius:6px;
}

.alert.success{
background:#d1fae5;
color:#065f46;
}

.alert.error{
background:#fee2e2;
color:#991b1b;
}

/* MOBILE */

@media (max-width:768px){

.form-card{
padding:20px;
}

.grid{
grid-template-columns:1fr;
}

.btn-group{
flex-direction:column;
}

.btn{
width:100%;
text-align:center;
}

input,select,textarea{
font-size:16px;
}

}

</style>

<?php require_once '../includes/footer.php'; ?>