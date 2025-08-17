<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QrLab - Códigos QR Personalizados</title>
    <!-- Bootstrap 5 CSS CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            margin: 0;
            padding: 0;
        }
        .navbar {
            background-color: white;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }
        .hero {
            background-image: url('https://images.pexels.com/photos/3761509/pexels-photo-3761509.jpeg?_gl=1*4af852*_ga*ODExODgzOTA1LjE3NTU0MTA2NjE.*_ga_8JE65Q40S6*czE3NTU0MTA2NjAkbzEkZzEkdDE3NTU0MTA3NDQkajM2JGwwJGgw');
            background-attachment: fixed;
            background-size: cover;
            color: white;
            height: 100vh;
            display: flex;
            flex-direction: column;
            justify-content: center;
            text-align: center;
        }
        .parallax {
            background-image: url('https://images.pexels.com/photos/7688336/pexels-photo-7688336.jpeg?_gl=1*e8r57x*_ga*ODExODgzOTA1LjE3NTU0MTA2NjE.*_ga_8JE65Q40S6*czE3NTU0MTA2NjAkbzEkZzEkdDE3NTU0MTEyMDUkajE4JGwwJGgw');
            background-attachment: fixed;
            background-size: cover;
            padding: 60px 0;
            color: white;
        }
        .benefits-icon {
            font-size: 50px;
            color: #0d6efd;
        }
    </style>
</head>
<body>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light fixed-top">
        <div class="container">
            <a class="navbar-brand" href="#">QrLab</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="#benefits">Beneficios</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#contact">Contacto</a>
                    </li>
                </ul>
                <a href="/login" class="btn btn-outline-primary me-2">Login</a>
                <a href="/register" class="btn btn-primary">Register</a>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <header class="hero">
        <div class="container">
            <h1 class="display-4">QrLab: Lleva tus QR al siguiente nivel</h1>
            <p class="lead">Códigos QR con logos personalizados para tu marca, eventos y productos.</p>
            <a href="#form" class="btn btn-primary btn-lg">Empieza Gratis</a>
        </div>
    </header>

    <!-- Slider (Carousel) -->
    <div id="carouselExample" class="carousel slide my-5" data-bs-ride="carousel">
        <div class="carousel-inner">
            <div class="carousel-item active">
                <img src="https://source.unsplash.com/800x400/?qr-code,branding" class="d-block w-100" alt="QR para tu marca">
                <div class="carousel-caption d-none d-md-block">
                    <h5>QR para tu marca</h5>
                </div>
            </div>
            <div class="carousel-item">
                <img src="https://source.unsplash.com/800x400/?product,qr-code" class="d-block w-100" alt="QR en productos">
                <div class="carousel-caption d-none d-md-block">
                    <h5>QR en productos</h5>
                </div>
            </div>
            <div class="carousel-item">
                <img src="https://source.unsplash.com/800x400/?event,qr-code" class="d-block w-100" alt="QR en eventos">
                <div class="carousel-caption d-none d-md-block">
                    <h5>QR en eventos</h5>
                </div>
            </div>
        </div>
        <button class="carousel-control-prev" type="button" data-bs-target="#carouselExample" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Previous</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#carouselExample" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Next</span>
        </button>
    </div>

    <!-- Sección Beneficios -->
    <section id="benefits" class="container my-5">
        <h2 class="text-center mb-4">Beneficios de QrLab</h2>
        <div class="row text-center">
            <div class="col-md-3">
                <div class="benefits-icon">📈</div>
                <h4>Marketing</h4>
                <p>Utiliza códigos QR para campañas publicitarias efectivas y medibles.</p>
            </div>
            <div class="col-md-3">
                <div class="benefits-icon">⚡</div>
                <h4>Versatilidad</h4>
                <p>Personaliza tus códigos QR para diferentes usos y eventos.</p>
            </div>
            <div class="col-md-3">
                <div class="benefits-icon">⏱️</div>
                <h4>Rapidez</h4>
                <p>Genera códigos QR en segundos y compártelos al instante.</p>
            </div>
            <div class="col-md-3">
                <div class="benefits-icon">🎨</div>
                <h4>Personalización</h4>
                <p>Agrega tu logo y colores para que se adapten a tu marca.</p>
            </div>
        </div>
    </section>

    <!-- Formulario de Leads -->
    <section id="form" class="parallax text-center">
        <div class="container">
            <h2 class="mb-4">¡Prueba QrLab!</h2>
            <form action="#" method="post">
                <div class="mb-3">
                    <input type="text" class="form-control" placeholder="Nombre" required>
                </div>
                <div class="mb-3">
                    <input type="email" class="form-control" placeholder="Email" required>
                </div>
                <button type="submit" class="btn btn-primary">Quiero probar QrLab</button>
            </form>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-light text-center py-4">
        <div class="container">
            <p class="mb-0">
                <a href="#">Home</a> | 
                <a href="/login">Login</a> | 
                <a href="/register">Register</a>
            </p>
            <p>© QrLab 2025</p>
        </div>
    </footer>

    <!-- Bootstrap 5 JS and dependencies -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>
</body>
</html>
