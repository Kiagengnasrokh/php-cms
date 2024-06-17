<?php
include '../connection.php'; // Include the connection file

// Get the article ID from the URL
$id_artikel = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Query to get the article
$query = "SELECT artikel.*, kategori.nama_kategori 
          FROM artikel 
          JOIN kategori ON artikel.id_kategori = kategori.id_kategori 
          WHERE artikel.id_artikel = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $id_artikel);
$stmt->execute();
$result = $stmt->get_result();
$article = $result->fetch_assoc();
$stmt->close();

if (!$article) {
    die("Artikel tidak ditemukan.");
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title><?= htmlspecialchars($article['judul']) ?> - Blog Post</title>
    <!-- Favicon-->
    <link rel="icon" type="image/x-icon" href="assets/favicon.ico" />
    <!-- Core theme CSS (includes Bootstrap)-->
    <link href="css/styles.css" rel="stylesheet" />
</head>

<body>
    <!-- Responsive navbar-->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="#!"><h1>Bola Lokal</h1></a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false"
                aria-label="Toggle navigation"><span class="navbar-toggler-icon"></span></button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                    <li class="nav-item"><a class="nav-link" href="bloghome.php">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="about.php">About</a></li>
                    <li class="nav-item"><a class="nav-link" href="#footer">Contact</a></li>
                </ul>
            </div>
        </div>
    </nav>
    <!-- Page content-->
    <div class="container mt-5">
        <div class="row">
            <div class="col-lg-8">
                <!-- Post content-->
                <article>
                    <!-- Post header-->
                    <header class="mb-4">
                        <div
                            style="border: 2px solid #000; padding: 20px; border-radius: 5px; background-color: #f0f0f0;">
                            <!-- Post title-->
                            <h1 class="fw-bolder mb-1"><?= htmlspecialchars($article['judul']) ?></h1>
                            <!-- Post meta content-->
                            <div class="text-muted fst-italic mb-2">
                                Posted on <?= htmlspecialchars($article['tanggal']) ?> by
                                <?= htmlspecialchars($article['penulis']) ?>
                            </div>
                            <!-- Post categories-->
                            <a class="badge bg-secondary text-decoration-none link-light" href="#!">
                                <?= htmlspecialchars($article['nama_kategori']) ?>
                            </a>
                        </div>
                    </header>

                    <!-- Preview image figure-->
                    <figure class="mb-4"><img class="img-fluid rounded"
                            src="data:image/jpeg;base64,<?= base64_encode($article['gambar']) ?>" alt="..." /></figure>
                    <!-- Post content-->
                    <section class="mb-5">
                        <div
                            style="border: 2px solid #000; padding: 20px; border-radius: 5px; background-color: #f0f0f0;">
                            <p class="fs-5 mb-4"><?= nl2br(htmlspecialchars($article['isi'])) ?></p>
                        </div>
                    </section>
                </article>
            </div>
            <!-- Side widgets-->
            <div class="col-lg-4">
                <!-- Search widget-->
                <div class="card mb-4">
                    <div class="card-header bg-dark text-white">Pencarian</div>
                    <div class="card-body">
                        <form method <form method="GET" action="bloghome.php">
                            <div class="input-group">
                                <input class="form-control" type="text" name="search" placeholder="Cari Artikel..."
                                    aria-label="Cari Artikel..." aria-describedby="button-search" />
                                <button class="btn btn-primary" id="button-search" type="submit">Cari!</button>
                            </div>
                        </form>
                    </div>
                </div>
                <!-- Categories widget-->
                <div class="card mb-4 bg-light">
                    <div class="card-header bg-dark text-white">Kategori</div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-6">
                                <ul class="list-unstyled mb-0">
                                    <li><a href="bloghome.php">Semua</a></li>
                                    <li><a href="bloghome.php?kategori=Bola Liga">Bola Liga</a></li>
                                    <li><a href="bloghome.php?kategori=Bola Timnas">Bola Timnas</a></li>
                                    <li><a href="bloghome.php?kategori=Profil Klub">Profil Klub</a></li>
                                    <li><a href="bloghome.php?kategori=Suporter">Suporter</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Side widget-->
                <div class="card mb-4 ">
                    <div class="card-header bg-dark text-white">Tentang</div>
                    <div class="card-body">Informasi lengkap berita bola dalam negeri
                        Mulai dari Timnas, Liga 1 hingga liga 3 nasional maupun regional selain itu
                        pada blog kami juga akan memberikan berita tentang profil klub sepak bola yang ada di indonesia
                        dan juga
                        kreativitas suporter di indonesia.
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Footer-->
    <footer id="footer" class="py-5 bg-dark">
        <div class="container">
            <p class="m-0 text-center text-white">Copyright &copy; Bola Lokal 2024</p>
            <br>
            <p class="m-0 text-center text-white">Contact : 225-6578-89</p>
            <p class="m-0 text-center text-white">Email : @bolalokal32gmail.com</p>
        </div>
    </footer>
    <!-- Bootstrap core JS-->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Core theme JS-->
    <script src="js/scripts.js"></script>
</body>

</html>