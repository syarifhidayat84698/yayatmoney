<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aplikasi Manajemen Keuangan</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.css" rel="stylesheet">
    <style>
        :root {
            --primary-color: #2563eb;
            --secondary-color: #1e40af;
            --accent-color: #60a5fa;
            --text-color: #1f2937;
            --light-bg: #f8fafc;
            --card-bg: #ffffff;
        }

        body {
            font-family: 'Poppins', sans-serif;
            margin: 0;
            padding: 0;
            background-color: var(--light-bg);
            color: var(--text-color);
            line-height: 1.6;
        }

        header {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            position: fixed;
            width: 100%;
            z-index: 1000;
            box-shadow: 0 2px 15px rgba(0, 0, 0, 0.04);
        }

        .hero-section {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 120px 8%;
            background: linear-gradient(135deg, var(--light-bg) 0%, #eef2ff 100%);
            position: relative;
            overflow: hidden;
        }

        .hero-section::before {
            content: '';
            position: absolute;
            width: 100%;
            height: 100%;
            background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%239BA4B5' fill-opacity='0.05'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
            opacity: 0.5;
            z-index: 0;
        }

        .hero-section .text-content {
            max-width: 50%;
            position: relative;
            z-index: 1;
        }

        .hero-section h1 {
            font-size: 3.5rem;
            font-weight: 700;
            color: var(--primary-color);
            margin-bottom: 1.5rem;
            line-height: 1.2;
            background: linear-gradient(to right, var(--primary-color), var(--secondary-color));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .hero-section p {
            font-size: 1.25rem;
            margin: 1.5rem 0;
            color: #4b5563;
            max-width: 90%;
        }

        .btn {
            padding: 0.8rem 2rem;
            font-size: 1rem;
            font-weight: 500;
            border-radius: 12px;
            transition: all 0.3s ease;
            text-transform: none;
            letter-spacing: 0.5px;
        }

        .btn-primary {
            background: var(--primary-color);
            border: none;
            box-shadow: 0 4px 6px -1px rgba(37, 99, 235, 0.2);
        }

        .btn-primary:hover {
            background: var(--secondary-color);
            transform: translateY(-2px);
            box-shadow: 0 6px 8px -1px rgba(37, 99, 235, 0.3);
        }

        .btn-secondary {
            background: #ffffff;
            color: var(--primary-color);
            border: 2px solid var(--primary-color);
        }

        .btn-secondary:hover {
            background: var(--primary-color);
            color: white;
            transform: translateY(-2px);
        }

        .hero-section img {
            max-width: 45%;
            height: auto;
            border-radius: 24px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease;
            position: relative;
            z-index: 1;
        }

        .hero-section img:hover {
            transform: translateY(-10px);
        }

        .features-section {
            background: var(--card-bg);
            padding: 100px 5%;
            text-align: center;
            position: relative;
        }

        .features-section h2 {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 3rem;
            color: var(--primary-color);
        }

        .feature {
            background: white;
            padding: 2rem;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
            transition: all 0.3s ease;
            height: 100%;
            margin-bottom: 30px;
        }

        .feature:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
        }

        .feature img {
            width: 100%;
            height: 200px;
            object-fit: cover;
            border-radius: 16px;
            margin-bottom: 1.5rem;
        }

        .feature h4 {
            font-size: 1.5rem;
            font-weight: 600;
            color: var(--primary-color);
            margin-bottom: 1rem;
        }

        .feature p {
            color: #6b7280;
            font-size: 1rem;
            line-height: 1.6;
        }

        footer {
            background: var(--primary-color);
            color: #fff;
            padding: 2rem 5%;
            text-align: center;
        }

        footer p {
            margin: 0;
            font-size: 1rem;
        }

        footer a {
            color: #fff;
            text-decoration: none;
            font-weight: 500;
            transition: opacity 0.3s ease;
        }

        footer a:hover {
            opacity: 0.8;
            text-decoration: none;
        }

        @media (max-width: 991px) {
            .hero-section {
                padding: 100px 5%;
            }

            .hero-section h1 {
                font-size: 2.8rem;
            }

            .hero-section p {
                font-size: 1.1rem;
            }
        }

        @media (max-width: 768px) {
            .hero-section {
                flex-direction: column;
                text-align: center;
                padding: 120px 5% 60px;
            }

            .hero-section .text-content {
                max-width: 100%;
                margin-bottom: 3rem;
            }

            .hero-section p {
                margin: 1.5rem auto;
            }

            .hero-section img {
                max-width: 90%;
            }

            .btn {
                margin: 0.5rem;
            }
        }

        .logo-container {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .logo-container img {
            width: 40px;
            height: 40px;
            object-fit: contain;
        }

        .logo-container h3 {
            font-weight: 600;
            color: var(--primary-color);
            margin: 0;
            font-size: 1.5rem;
        }

        .nav-container {
            max-width: 1280px;
            margin: 0 auto;
            padding: 1rem 2rem;
        }
    </style>
</head>
<body>
    <header>
        <nav class="nav-container d-flex justify-content-between align-items-center">
            <div class="logo-container">
                <img src="assets/images/yayat2.png" alt="Logo" data-aos="fade-right">
                <h3 data-aos="fade-right" data-aos-delay="100">Yayat Money</h3>
            </div>
        </nav>
    </header>

    <section class="hero-section">
        <div class="text-content" data-aos="fade-right" data-aos-duration="1000">
            <h1>Solusi Terbaik untuk UMKM</h1>
            <p>Tingkatkan efisiensi dan kelola keuangan bisnis Anda dengan sistem manajemen keuangan modern yang mudah digunakan.</p>
            <div>
                <a href="{{ url('/login') }}" class="btn btn-primary">Masuk Sekarang</a>
                <a href="/register" class="btn btn-secondary">Daftar Sekarang</a>
            </div>
        </div>
        <img src="assets/images/finance.jpg" alt="Ilustrasi Hero" data-aos="fade-left" data-aos-duration="1000">
    </section>

    <section class="features-section">
        <h2 data-aos="fade-up">Kenapa Memilih Kami?</h2>
        <div class="row">
            <div class="col-md-4" data-aos="fade-up" data-aos-delay="100">
                <div class="feature">
                <img src="assets/images/userfriendly.jpg" alt="Icon Mudah Digunakan">
                <h4>Mudah Digunakan</h4>
                    <p>Antarmuka yang intuitif dan ramah pengguna, dirancang khusus untuk mempermudah pengelolaan keuangan bisnis Anda.</p>
                </div>
            </div>
            <div class="col-md-4" data-aos="fade-up" data-aos-delay="200">
                <div class="feature">
                <img src="assets/images/keamanan.jpg" alt="Icon Keamanan">
                <h4>Keamanan Data</h4>
                    <p>Sistem keamanan tingkat tinggi untuk melindungi data penting bisnis Anda dengan enkripsi modern.</p>
                </div>
            </div>
            <div class="col-md-4" data-aos="fade-up" data-aos-delay="300">
                <div class="feature">
                <img src="assets/images/laporan.jpg" alt="Icon Laporan Lengkap">
                <h4>Laporan Lengkap</h4>
                    <p>Dapatkan insight bisnis yang mendalam melalui laporan keuangan terperinci dan visualisasi data yang informatif.</p>
                </div>
            </div>
        </div>
    </section>

    <footer>
        <p>&copy; 2024 Yayat Money - Aplikasi Manajemen Keuangan. Dibuat dengan ❤️ oleh Tim Developer. <a href="#">Hubungi Kami</a></p>
    </footer>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.js"></script>
    <script>
        AOS.init({
            duration: 800,
            once: true,
            offset: 100
        });
    </script>
</body>
</html>