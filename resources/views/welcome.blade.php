<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aplikasi Manajemen Keuangan</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f5f7;
        }
        .hero-section {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 50px 5%;
            background: #f4f5f7;
        }
        .hero-section .text-content {
            max-width: 50%;
        }
        .hero-section h1 {
            font-size: 3rem;
            font-weight: 600;
            color: #0047ab;
        }
        .hero-section p {
            font-size: 1.2rem;
            margin: 20px 0;
            color: #555;
        }
        .hero-section .btn {
            margin-right: 10px;
            padding: 10px 30px;
            font-size: 1rem;
            border-radius: 50px;
        }
        .btn-primary {
            background-color: #007bff;
            border: none;
        }
        .btn-primary:hover {
            background-color: #0056b3;
        }
        .btn-secondary {
            background-color: #6c757d;
            border: none;
            color: white;
        }
        .btn-secondary:hover {
            background-color: #565e64;
        }
        .hero-section img {
            max-width: 45%;
            height: auto;
            border-radius: 20px;
        }
        .features-section {
            background: #ffffff;
            padding: 50px 5%;
            text-align: center;
        }
        .features-section h2 {
            font-size: 2.5rem;
            margin-bottom: 30px;
            color: #0047ab;
        }
        .features-section .feature {
            margin-bottom: 30px;
        }
        .features-section .feature img {
            width: 100%;
            height: 200px;
            object-fit: cover;
            margin-bottom: 15px;
            border-radius: 10px; /* Menambahkan border-radius untuk gambar */
        }
        .features-section .feature h4 {
            font-size: 1.3rem;
            margin-bottom: 10px;
            color: #0047ab;
        }
        footer {
            background-color: #0047ab;
            color: #fff;
            padding: 20px 5%;
            text-align: center;
        }
        footer a {
            color: #fff;
            text-decoration: none;
        }
        footer a:hover {
            text-decoration: underline;
        }
        @media (max-width: 768px) {
            .hero-section {
                flex-direction: column;
                text-align: center;
            }
            .hero-section img {
                max-width: 80%;
                margin-top: 20px;
            }
        }
    </style>
</head>
<body>
    <header class="bg-white shadow-sm py-3">
        <nav class="container d-flex justify-content-between align-items-center">
            <div class="d-flex align-items-center">
                <img src="assets/images/yayat2.png" alt="Logo" style="width: 40px; height: auto; margin-right: 10px;">
                <h3 class="text-primary m-0">Yayat Money</h3>
            </div>
        </nav>
    </header>
    <section class="hero-section">
        <div class="text-content">
            <h1>Solusi Terbaik untuk UMKM</h1>
            <p>Meningkatkan efisiensi dan pengelolaan bisnis Anda dengan aplikasi keuangan modern.</p>
            <div>
                <a href="{{ url('/login') }}" class="btn btn-primary">Masuk Sekarang</a>
                <a href="/register" class="btn btn-secondary">Daftar Sekarang</a>
            </div>
        </div>
        <img src="assets/images/finance.jpg" alt="Ilustrasi Hero">
    </section>
    <section class="features-section">
        <h2>Kenapa Memilih Kami?</h2>
        <div class="row text-center">
            <div class="col-md-4 feature">
                <img src="assets/images/userfriendly.jpg" alt="Icon Mudah Digunakan">
                <h4>Mudah Digunakan</h4>
                <p>Antarmuka yang ramah pengguna untuk mempermudah pengelolaan keuangan Anda.</p>
            </div>
            <div class="col-md-4 feature">
                <img src="assets/images/keamanan.jpg" alt="Icon Keamanan">
                <h4>Keamanan Data</h4>
                <p>Sistem yang terjamin untuk melindungi data penting bisnis Anda.</p>
            </div>
            <div class="col-md-4 feature">
                <img src="assets/images/laporan.jpg" alt="Icon Laporan Lengkap">
                <h4>Laporan Lengkap</h4>
                <p>Analisis bisnis yang mendalam untuk membantu Anda membuat keputusan yang tepat.</p>
            </div>
        </div>
    </section>
    <footer>
        <p>&copy; 2024 Aplikasi Manajemen Keuangan. Dibuat dengan ❤️ oleh Tim Developer. <a href="#">Hubungi Kami</a></p>
    </footer>
</body>
</html>