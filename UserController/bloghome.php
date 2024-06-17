<?php
include '../connection.php'; // Include the connection file

// Determine the current page number from the URL parameter, default is 1
$page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
$limit = 4; // Number of articles per page
$offset = ($page - 1) * $limit; // Calculate the offset

// Capture search term
$search = isset($_GET['search']) ? $conn->real_escape_string($_GET['search']) : '';

// Capture category filter
$category = isset($_GET['kategori']) ? $conn->real_escape_string($_GET['kategori']) : '';

// Query to get the total number of articles
if (!empty($search)) {
    $total_query = "SELECT COUNT(*) as total FROM artikel WHERE judul LIKE '%$search%'";
} elseif (!empty($category)) {
    $total_query = "SELECT COUNT(*) as total FROM artikel 
                    JOIN kategori ON artikel.id_kategori = kategori.id_kategori 
                    WHERE kategori.nama_kategori = '$category'";
} else {
    $total_query = "SELECT COUNT(*) as total FROM artikel";
}
$total_result = $conn->query($total_query);
$total_row = $total_result->fetch_assoc();
$total_articles = $total_row['total'];
$total_pages = ceil($total_articles / $limit); // Calculate total pages

// Query to get articles with limit, offset, and category filter
if (!empty($search)) {
    $query = "SELECT artikel.*, kategori.nama_kategori 
              FROM artikel 
              JOIN kategori ON artikel.id_kategori = kategori.id_kategori 
              WHERE artikel.judul LIKE '%$search%' 
              ORDER BY artikel.tanggal DESC 
              LIMIT $limit OFFSET $offset";
} elseif (!empty($category)) {
    $query = "SELECT artikel.*, kategori.nama_kategori 
              FROM artikel 
              JOIN kategori ON artikel.id_kategori = kategori.id_kategori 
              WHERE kategori.nama_kategori = '$category'
              ORDER BY artikel.tanggal DESC 
              LIMIT $limit OFFSET $offset";
} else {
    $query = "SELECT artikel.*, kategori.nama_kategori 
              FROM artikel 
              JOIN kategori ON artikel.id_kategori = kategori.id_kategori 
              ORDER BY artikel.tanggal DESC 
              LIMIT $limit OFFSET $offset";
}
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>Blog Home - Start Bootstrap Template</title>
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

    <!-- Page header with logo and tagline-->
    <header class="py-5 border-bottom mb-4 bg-success">
        <div class="container">
            <div class="text-center my-5">
                <h1 class="fw-bolder">Selamat Datang di blog bola lokal</h1>
                <p class="lead mb-0">Update berita bola lokal setiap harinya</p>
            </div>
        </div>
    </header>
    <!-- Page content-->
    <div class="container">
        <div class="row">
            <!-- Blog entries-->
            <div class="col-lg-8">
                <?php
                $featured = true;
                $post_counter = 0;
                while ($row = $result->fetch_assoc()):
                    if ($featured): ?>
                        <!-- Featured blog post-->
                        <div class="card mb-4">
                            <a href="blogpost.php?id=<?= $row['id_artikel'] ?>"><img class="card-img-top"
                                    src="data:image/jpeg;base64,<?= base64_encode($row['gambar']) ?>" alt="..." width="850"
                                    height="350" /></a>
                            <div class="card-body">
                                <div class="small text-muted"><?= ($row['tanggal']) ?></div>
                                <h2 class="card-title"><?= $row['judul'] ?></h2>
                                <p class="card-text"><?= substr($row['isi'], 0, 200) ?>...</p>
                                <a class="btn btn-primary" href="blogpost.php?id=<?= $row['id_artikel'] ?>">Lebih banyak →</a>
                            </div>
                        </div>
                        <?php
                        $featured = false;
                    else: ?>
                        <!-- Blog post-->
                        <div class="card mb-4">
                            <a href="blogpost.php?id=<?= $row['id_artikel'] ?>"><img class="card-img-top"
                                    src="data:image/jpeg;base64,<?= base64_encode($row['gambar']) ?>" alt="..." width="850"
                                    height="350" /></a>
                            <div class="card-body">
                                <div class="small text-muted"><?= ($row['tanggal']) ?></div>
                                <h2 class="card-title h4"><?= $row['judul'] ?></h2>
                                <p class="card-text"><?= substr($row['isi'], 0, 200) ?>...</p>
                                <a class="btn btn-primary" href="blogpost.php?id=<?= $row['id_artikel'] ?>">Lebih banyak →</a>
                            </div>
                        </div>
                        <?php
                        $post_counter++;
                        if ($post_counter % 2 == 0):
                            echo '<div class="row">';
                        endif;
                    endif;
                endwhile;
                if ($post_counter % 2 != 0):
                    echo '</div>'; // Close the last row if it's not closed
                endif;
                ?>
                <!-- Pagination-->
                <nav aria-label="Pagination">
                    <hr class="my-0" />
                    <ul class="pagination justify-content-center my-4">
                        <?php if ($page > 1): ?>
                            <li class="page-item"><a class="page-link" href="?page=<?= $page - 1 ?>">Newer</a></li>
                        <?php else: ?>
                            <li class="page-item disabled"><a class="page-link" href="#" tabindex="-1"
                                    aria-disabled="true">Newer</a></li>
                        <?php endif; ?>

                        <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                            <li class="page-item <?= $i == $page ? 'active' : '' ?>"><a class="page-link"
                                    href="?page=<?= $i ?>"><?= $i ?></a></li>
                        <?php endfor; ?>

                        <?php if ($page < $total_pages): ?>
                            <li class="page-item"><a class="page-link" href="?page=<?= $page + 1 ?>">Older</a></li>
                        <?php else: ?>
                            <li class="page-item disabled"><a class="page-link" href="#">Older</a></li>
                        <?php endif; ?>
                    </ul>
                </nav>
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