<?php
session_start();

if (!isset($_SESSION['id_user'])) {
  header("Location: index.html");
  exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Home Login - ClickSpace</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>

<header>
  <div class="logo">ClickSpace</div>

  <nav>
    <a href="home-login.php">Home</a>
    <a href="layanan-login.php">Layanan</a>
    <a href="booking-form.php">Booking</a>
    <a href="status.php">Riwayat</a>
    <a href="logout.php" class="btn-login">Logout</a>
  </nav>
</header>

<main>

  <section class="hero">
    <h1>Selamat Datang di ClickSpace</h1>
    <p>Kamu sudah login. Sekarang kamu bisa melakukan booking studio atau melihat riwayat booking.</p>

    <div class="hero-actions">
      <a href="booking-form.php" class="button">Booking Sekarang</a>
      <a href="layanan-login.php" class="button second">Lihat Layanan</a>
    </div>
  </section>

  <section class="section favorite-gallery-section">
    <p class="gallery-label">LAYANAN FAVORIT</p>

    <h2 class="gallery-title">Pilihan Studio Favorit</h2>

    <p class="gallery-subtitle">
      Pilih layanan foto terbaik untuk mengabadikan momen spesialmu di ClickSpace.
    </p>

    <div class="gallery-tabs">
      <input type="radio" name="service" id="tab-self" checked>
      <input type="radio" name="service" id="tab-family">
      <input type="radio" name="service" id="tab-photobooth">
      <input type="radio" name="service" id="tab-prewed">
      <input type="radio" name="service" id="tab-graduation">
      <input type="radio" name="service" id="tab-studio">

      <div class="gallery-tags">
        <label for="tab-self" class="gallery-tag">Self Studio</label>
        <label for="tab-family" class="gallery-tag">Foto Keluarga</label>
        <label for="tab-photobooth" class="gallery-tag">Photobooth</label>
        <label for="tab-prewed" class="gallery-tag">Prewedding</label>
        <label for="tab-graduation" class="gallery-tag">Graduation</label>
        <label for="tab-studio" class="gallery-tag">Sewa Studio</label>
      </div>

      <div class="gallery-panels">

        <div class="favorite-gallery gallery-panel panel-self">
          <div class="gallery-card mini-card mini-left">
            <img src="images/studio.jpg" alt="Sewa Studio">
          </div>

          <div class="gallery-card side-card left-card">
            <img src="images/foto keluarga.jpg" alt="Foto Keluarga">
          </div>

          <div class="gallery-card main-card">
            <img src="images/self studio.jpg" alt="Self Studio">
            <div class="gallery-card-info">
              <h3>Self Studio</h3>
              <p>Foto mandiri dengan konsep simple dan modern.</p>
              <span>Mulai Rp50.000</span>
            </div>
          </div>

          <div class="gallery-card side-card right-card">
            <img src="images/prewed.jpg" alt="Prewedding">
          </div>

          <div class="gallery-card mini-card mini-right">
            <img src="images/graduation.jpg" alt="Graduation">
          </div>

          <div class="gallery-nav">
            <label for="tab-studio">←</label>
            <label for="tab-family">→</label>
          </div>
        </div>

        <div class="favorite-gallery gallery-panel panel-family">
          <div class="gallery-card mini-card mini-left">
            <img src="images/self studio.jpg" alt="Self Studio">
          </div>

          <div class="gallery-card side-card left-card">
            <img src="images/fotobooth.jpg" alt="Photobooth">
          </div>

          <div class="gallery-card main-card">
            <img src="images/foto keluarga.jpg" alt="Foto Keluarga">
            <div class="gallery-card-info">
              <h3>Foto Keluarga</h3>
              <p>Abadikan momen keluarga dengan suasana nyaman.</p>
              <span>Mulai Rp150.000</span>
            </div>
          </div>

          <div class="gallery-card side-card right-card">
            <img src="images/prewed.jpg" alt="Prewedding">
          </div>

          <div class="gallery-card mini-card mini-right">
            <img src="images/graduation.jpg" alt="Graduation">
          </div>

          <div class="gallery-nav">
            <label for="tab-self">←</label>
            <label for="tab-photobooth">→</label>
          </div>
        </div>

        <div class="favorite-gallery gallery-panel panel-photobooth">
          <div class="gallery-card mini-card mini-left">
            <img src="images/foto keluarga.jpg" alt="Foto Keluarga">
          </div>

          <div class="gallery-card side-card left-card">
            <img src="images/self studio.jpg" alt="Self Studio">
          </div>

          <div class="gallery-card main-card">
            <img src="images/fotobooth.jpg" alt="Photobooth">
            <div class="gallery-card-info">
              <h3>Photobooth</h3>
              <p>Layanan foto cepat untuk acara dan kebutuhan pribadi.</p>
              <span>Mulai Rp75.000</span>
            </div>
          </div>

          <div class="gallery-card side-card right-card">
            <img src="images/graduation.jpg" alt="Graduation">
          </div>

          <div class="gallery-card mini-card mini-right">
            <img src="images/studio.jpg" alt="Sewa Studio">
          </div>

          <div class="gallery-nav">
            <label for="tab-family">←</label>
            <label for="tab-prewed">→</label>
          </div>
        </div>

        <div class="favorite-gallery gallery-panel panel-prewed">
          <div class="gallery-card mini-card mini-left">
            <img src="images/fotobooth.jpg" alt="Photobooth">
          </div>

          <div class="gallery-card side-card left-card">
            <img src="images/foto keluarga.jpg" alt="Foto Keluarga">
          </div>

          <div class="gallery-card main-card">
            <img src="images/prewed.jpg" alt="Prewedding">
            <div class="gallery-card-info">
              <h3>Prewedding</h3>
              <p>Foto pasangan dengan konsep elegan dan romantis.</p>
              <span>Mulai Rp300.000</span>
            </div>
          </div>

          <div class="gallery-card side-card right-card">
            <img src="images/graduation.jpg" alt="Graduation">
          </div>

          <div class="gallery-card mini-card mini-right">
            <img src="images/studio.jpg" alt="Sewa Studio">
          </div>

          <div class="gallery-nav">
            <label for="tab-photobooth">←</label>
            <label for="tab-graduation">→</label>
          </div>
        </div>

        <div class="favorite-gallery gallery-panel panel-graduation">
          <div class="gallery-card mini-card mini-left">
            <img src="images/prewed.jpg" alt="Prewedding">
          </div>

          <div class="gallery-card side-card left-card">
            <img src="images/fotobooth.jpg" alt="Photobooth">
          </div>

          <div class="gallery-card main-card">
            <img src="images/graduation.jpg" alt="Graduation">
            <div class="gallery-card-info">
              <h3>Graduation</h3>
              <p>Foto wisuda untuk mengabadikan momen kelulusan.</p>
              <span>Mulai Rp100.000</span>
            </div>
          </div>

          <div class="gallery-card side-card right-card">
            <img src="images/self studio.jpg" alt="Self Studio">
          </div>

          <div class="gallery-card mini-card mini-right">
            <img src="images/foto keluarga.jpg" alt="Foto Keluarga">
          </div>

          <div class="gallery-nav">
            <label for="tab-prewed">←</label>
            <label for="tab-studio">→</label>
          </div>
        </div>

        <div class="favorite-gallery gallery-panel panel-studio">
          <div class="gallery-card mini-card mini-left">
            <img src="images/graduation.jpg" alt="Graduation">
          </div>

          <div class="gallery-card side-card left-card">
            <img src="images/prewed.jpg" alt="Prewedding">
          </div>

          <div class="gallery-card main-card">
            <img src="images/studio.jpg" alt="Sewa Studio">
            <div class="gallery-card-info">
              <h3>Sewa Studio</h3>
              <p>Sewa studio dengan fasilitas lengkap dan nyaman.</p>
              <span>Mulai Rp75.000/jam</span>
            </div>
          </div>

          <div class="gallery-card side-card right-card">
            <img src="images/self studio.jpg" alt="Self Studio">
          </div>

          <div class="gallery-card mini-card mini-right">
            <img src="images/fotobooth.jpg" alt="Photobooth">
          </div>

          <div class="gallery-nav">
            <label for="tab-graduation">←</label>
            <label for="tab-self">→</label>
          </div>
        </div>

      </div>
    </div>
  </section>

</main>

<footer>
  <p>© 2026 ClickSpace</p>
</footer>

</body>
</html>
