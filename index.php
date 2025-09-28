<?php
// Selalu letakkan ini di baris paling atas untuk menampilkan error jika ada
ini_set('display_errors', 1);
error_reporting(E_ALL);

// --- Konfigurasi Database ---
$servername = "localhost";
$username = "zacky"; // Ganti jika username Anda berbeda
$password = "12345"; // Ganti jika password Anda berbeda
$dbname = "pwdkampus";

// Buat koneksi (tanpa error handling yang "agresif")
$conn = new mysqli($servername, $username, $password, $dbname);

// Ambil data jika koneksi berhasil
if (!$conn->connect_error) {
    $query = "SELECT id, nama_lengkap FROM personal_info ORDER BY nama_lengkap ASC";
    $result = $conn->query($query);
} else {
    // Jika koneksi gagal, siapkan pesan error
    $error_message = "Koneksi ke database gagal: " . $conn->connect_error;
    $result = false;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>P2 Boyyy - CV Collective</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@500;700&family=Roboto+Mono:wght@400;500&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --bg-color: #0d0c1d;
            --primary-glow: #00f6ff;
            --secondary-glow: #ff00c1;
            --text-color: #e0e0e0;
            --subtle-text-color: #a0a0a0;
            --surface-bg: rgba(22, 22, 44, 0.6);
            --border-color: rgba(0, 246, 255, 0.3);
        }
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: 'Roboto Mono', monospace;
            background-color: var(--bg-color);
            color: var(--text-color);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            padding: 4rem 2rem; /* Memberi ruang di atas dan bawah */
            background-image: 
                radial-gradient(circle at 15% 15%, rgba(0, 246, 255, 0.2), transparent 30%),
                radial-gradient(circle at 85% 85%, rgba(255, 0, 193, 0.15), transparent 30%);
            /* Dihapus: overflow: hidden; */
        }
        .container {
            width: 100%;
            max-width: 700px; /* Sedikit diperlebar untuk 2 kolom */
            background-color: var(--surface-bg);
            backdrop-filter: blur(12px);
            border: 1px solid var(--border-color);
            padding: 2.5rem 3rem;
            border-radius: 8px;
            box-shadow: 0 0 25px rgba(0, 246, 255, 0.1), 0 0 40px rgba(255, 0, 193, 0.1);
            position: relative;
            animation: fadeIn 1s ease-out;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .header-content {
            text-align: center;
            margin-bottom: 2.5rem;
        }
        .group-photo {
            width: 180px;  /* Diperbesar */
            height: 180px; /* Diperbesar */
            object-fit: cover;
            border: 2px solid var(--primary-glow);
            padding: 4px;
            box-shadow: 0 0 15px var(--primary-glow);
            margin: 0 auto 1.5rem auto;
            border-radius: 0; 
            animation: float 4s ease-in-out infinite;
        }
        @keyframes float {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-10px); }
        }
        h1 {
            font-family: 'Orbitron', sans-serif;
            font-size: 2.8rem;
            font-weight: 700;
            margin-bottom: 0.75rem;
            color: #fff;
            text-shadow: 0 0 5px var(--primary-glow), 0 0 10px var(--primary-glow);
        }
        p {
            color: var(--subtle-text-color);
            max-width: 450px;
            margin: 0 auto;
            font-size: 0.9rem;
            line-height: 1.6;
        }
        .cv-list {
            list-style: none;
            padding: 0;
            display: grid; /* Menggunakan grid */
            grid-template-columns: 1fr 1fr; /* Dua kolom */
            gap: 1rem; /* Jarak antar item */
        }
        .cv-list li a {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 1rem 1.5rem;
            border: 1px solid var(--border-color);
            text-decoration: none;
            color: var(--text-color);
            transition: all 0.3s ease;
            background-color: transparent;
            height: 100%; /* Memastikan tinggi item sama */
        }
        .cv-list li a:hover {
            background-color: rgba(0, 246, 255, 0.1);
            border-color: var(--primary-glow);
            color: #fff;
            transform: scale(1.02);
            box-shadow: 0 0 10px rgba(0, 246, 255, 0.3);
        }
        .cv-list .name { font-weight: 500; font-size: 0.9rem; } /* Ukuran font disesuaikan */
        .cv-list .arrow { 
            color: var(--primary-glow); 
            font-weight: bold; 
            transition: transform 0.3s ease;
        }
        .cv-list li a:hover .arrow {
            transform: translateX(5px);
        }
        .error-message {
            background-color: rgba(255, 0, 100, 0.1);
            color: #ff80ab;
            padding: 1rem;
            border-radius: 4px;
            border: 1px solid rgba(255, 0, 100, 0.5);
            text-align: center;
        }
        
        /* Responsif untuk layar kecil */
        @media (max-width: 640px) {
            .cv-list {
                grid-template-columns: 1fr; /* Satu kolom di layar kecil */
            }
            .container {
                padding: 2rem 1.5rem;
            }
            h1 {
                font-size: 2.2rem;
            }
            .group-photo {
                width: 140px;
                height: 140px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header-content">
            <img src="P2.jpeg" alt="Foto Kelompok P2 Boyyy" class="group-photo">
            <h1>P2 Boyyy</h1>
            <p>CV digital Kelompok P2 Boyyy.</p>
            <p>Pilih nama untuk mengakses profil detail.</p>
        </div>

        <?php if ($result && $result->num_rows > 0): ?>
            <ul class="cv-list">
                <?php while($row = $result->fetch_assoc()): ?>
                    <li>
                        <a href="tampil_cv.php?id=<?php echo $row['id']; ?>">
                            <span class="name"><?php echo htmlspecialchars($row['nama_lengkap']); ?></span>
                            <span class="arrow">></span>
                        </a>
                    </li>
                <?php endwhile; ?>
            </ul>
        <?php else: ?>
            <div class="error-message">
                <strong>// KONEKSI GAGAL //</strong><br>
                <?php 
                    if (isset($error_message)) {
                        echo htmlspecialchars($error_message);
                    } else {
                        echo "Data CV tidak dapat diinisialisasi dari database.";
                    }
                ?>
            </div>
        <?php endif; ?>
    </div>
    
</body>
</html>
<?php if(isset($conn) && !$conn->connect_error) { $conn->close(); } ?>
