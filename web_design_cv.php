<?php
// Selalu letakkan ini di baris paling atas untuk menampilkan error jika ada
ini_set('display_errors', 1);
error_reporting(E_ALL);

// --- Konfigurasi Database ---
$servername = "localhost";
$username = "zacky"; // Ganti jika username Anda berbeda
$password = "12345"; // Ganti jika password Anda berbeda
$dbname = "pwdkampus";

// Inisialisasi variabel untuk menampung hasil atau error
$error_message = null;
$result = null;

try {
    // Membuat koneksi
    $conn = new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error) {
        throw new Exception("Koneksi ke database gagal: " . $conn->connect_error);
    }
    
    // Query untuk mengambil ID dan nama lengkap dari semua CV
    $query = "SELECT id, nama_lengkap FROM personal_info ORDER BY nama_lengkap ASC";
    $result = $conn->query($query);
    
    if ($result === false) {
        // Jika query gagal
        throw new Exception("Error dalam query: " . $conn->error);
    }

} catch (Exception $e) {
    // Menangkap dan menyimpan pesan error untuk ditampilkan di HTML
    $error_message = $e->getMessage();
}

?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Homepage CV Kelompok P2 Boyyy</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;700&display=swap" rel="stylesheet">
    <style>
        :root {
            /* Light Mode */
            --bg-color: #f4f7f9;
            --card-bg-color: rgba(255, 255, 255, 0.7);
            --text-color: #2d3748;
            --heading-color: #1a202c;
            --subtle-text-color: #718096;
            --border-color: rgba(0, 0, 0, 0.08);
            --accent-color: #4f46e5;
            --shadow-color: rgba(0, 0, 0, 0.05);
            --toggle-bg: #cbd5e0;
            --toggle-fg: #ffffff;
        }

        html.dark-mode {
            /* Dark Mode */
            --bg-color: #1a202c;
            --card-bg-color: rgba(26, 32, 44, 0.7);
            --text-color: #e2e8f0;
            --heading-color: #ffffff;
            --subtle-text-color: #a0aec0;
            --border-color: rgba(255, 255, 255, 0.1);
            --accent-color: #818cf8;
            --shadow-color: rgba(0, 0, 0, 0.2);
            --toggle-bg: #4a5568;
            --toggle-fg: #1a202c;
        }

        /* --- Gaya Dasar & Latar Belakang --- */
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--bg-color);
            color: var(--text-color);
            transition: background-color 0.3s ease, color 0.3s ease;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            padding: 2rem;
            background-image: 
                radial-gradient(circle at 1% 1%, var(--subtle-text-color) 1px, transparent 1px),
                radial-gradient(circle at 99% 99%, var(--subtle-text-color) 1px, transparent 1px);
            background-size: 30px 30px;
        }

        /* --- Kontainer Utama (Efek Kaca) --- */
        .main-container {
            width: 100%;
            max-width: 700px;
            background-color: var(--card-bg-color);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border-radius: 20px;
            border: 1px solid var(--border-color);
            padding: 2.5rem 3rem;
            box-shadow: 0 8px 32px 0 var(--shadow-color);
            transition: background-color 0.3s ease, border 0.3s ease;
        }

        /* --- PENAMBAHAN FOTO --- */
        .group-photo {
            width: 100%;
            height: auto;
            border-radius: 12px;
            margin-top: 1.5rem; /* Memberi jarak dari header */
            box-shadow: 0 4px 12px 0 var(--shadow-color);
            object-fit: cover;
        }

        /* --- Header & Dark Mode Toggle --- */
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1rem; /* Mengurangi margin bawah untuk foto */
        }

        .title-group h1 {
            font-size: 2.25rem;
            font-weight: 700;
            color: var(--heading-color);
            margin: 0;
        }

        .title-group p {
            font-size: 1rem;
            color: var(--subtle-text-color);
            margin-top: 0.25rem;
        }

        .theme-switch-wrapper {
            display: flex;
            align-items: center;
        }

        .theme-switch {
            display: inline-block;
            height: 28px;
            position: relative;
            width: 50px;
        }

        .theme-switch input {
            display:none;
        }

        .slider {
            background-color: var(--toggle-bg);
            bottom: 0;
            cursor: pointer;
            left: 0;
            position: absolute;
            right: 0;
            top: 0;
            transition: .4s;
            border-radius: 28px;
        }

        .slider:before {
            background-color: var(--toggle-fg);
            bottom: 4px;
            content: "";
            height: 20px;
            left: 4px;
            position: absolute;
            transition: .4s;
            width: 20px;
            border-radius: 50%;
        }

        input:checked + .slider {
            background-color: var(--toggle-bg);
        }

        input:checked + .slider:before {
            transform: translateX(22px);
        }

        /* --- Daftar CV --- */
        .cv-list {
            list-style: none;
            padding: 0;
            margin-top: 1.5rem;
        }

        .cv-list li {
            margin-bottom: 1rem;
        }

        .cv-link {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 1.25rem 1.5rem;
            background-color: transparent;
            border: 1px solid var(--border-color);
            border-radius: 12px;
            text-decoration: none;
            color: var(--text-color);
            transition: transform 0.2s ease, background-color 0.2s ease, box-shadow 0.2s ease;
        }

        .cv-link:hover {
            transform: translateY(-4px);
            background-color: var(--card-bg-color);
            box-shadow: 0 4px 12px 0 var(--shadow-color);
            border-color: var(--accent-color);
        }

        .cv-link span {
            font-size: 1.1rem;
            font-weight: 500;
        }

        .cv-link .arrow {
            font-size: 1.5rem;
            color: var(--accent-color);
            transition: transform 0.2s ease;
        }

        .cv-link:hover .arrow {
            transform: translateX(5px);
        }

        /* --- Pesan Error & Notifikasi --- */
        .message-box {
            padding: 1rem 1.5rem;
            border-radius: 12px;
            text-align: center;
            font-weight: 500;
        }

        .error {
            background-color: rgba(239, 68, 68, 0.1);
            color: #ef4444;
            border: 1px solid rgba(239, 68, 68, 0.3);
        }

        .info {
            background-color: rgba(59, 130, 246, 0.1);
            color: #3b82f6;
            border: 1px solid rgba(59, 130, 246, 0.3);
        }

        /* --- Responsif --- */
        @media (max-width: 640px) {
            body {
                padding: 1rem;
            }
            .main-container {
                padding: 2rem 1.5rem;
            }
            .header {
                flex-direction: column;
                align-items: flex-start;
                gap: 1rem;
            }
            .title-group h1 {
                font-size: 1.8rem;
            }
        }

    </style>
</head>
<body>

    <div class="main-container">
        <header class="header">
            <div class="title-group">
                <h1>P2 Boyyy</h1>
                <p>Web ini berisi list data CV dari kelompok P2 Boyyy</p>
            </div>
            <div class="theme-switch-wrapper">
                <label class="theme-switch" for="checkbox">
                    <input type="checkbox" id="checkbox" />
                    <div class="slider round"></div>
                </label>
            </div>
        </header>
        
        <!-- PERUBAHAN DI SINI: Memanggil file gambar langsung -->
        <img src="P2.jpeg" alt="Foto Kelompok P2 Boyyy" class="group-photo">

        <main>
            <ul class="cv-list">
                <?php if ($error_message): ?>
                    <li class="message-box error">
                        ⚠️ <?php echo htmlspecialchars($error_message); ?>
                    </li>
                <?php elseif ($result && $result->num_rows > 0): ?>
                    <?php while($row = $result->fetch_assoc()): ?>
                        <li>
                            <a href="tampil_cv.php?id=<?php echo $row['id']; ?>" class="cv-link">
                                <span><?php echo htmlspecialchars($row['nama_lengkap']); ?></span>
                                <span class="arrow">→</span>
                            </a>
                        </li>
                    <?php endwhile; ?>
                <?php else: ?>
                    <li class="message-box info">
                        📝 Belum ada data CV yang ditemukan. Silakan jalankan `setup_database.php` terlebih dahulu.
                    </li>
                <?php endif; ?>
            </ul>
        </main>
    </div>

    <script>
        // --- Logika untuk Dark Mode Toggle ---
        (function() {
            const toggle = document.getElementById('checkbox');
            const currentTheme = localStorage.getItem('theme');

            // Cek tema yang tersimpan saat halaman dimuat
            if (currentTheme) {
                document.documentElement.setAttribute('class', currentTheme);
                if (currentTheme === 'dark-mode') {
                    toggle.checked = true;
                }
            } else {
                 // Cek preferensi sistem operasi pengguna
                const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
                if (prefersDark) {
                    document.documentElement.setAttribute('class', 'dark-mode');
                    toggle.checked = true;
                    localStorage.setItem('theme', 'dark-mode');
                }
            }

            // Listener untuk saat toggle di-klik
            toggle.addEventListener('change', function() {
                if (this.checked) {
                    document.documentElement.setAttribute('class', 'dark-mode');
                    localStorage.setItem('theme', 'dark-mode');
                } else {
                    document.documentElement.setAttribute('class', '');
                    localStorage.setItem('theme', 'light-mode');
                }
            });
        })();
    </script>
</body>
</html>
<?php
// Menutup koneksi hanya jika koneksi berhasil dibuat
if (isset($conn) && !$conn->connect_error) {
    $conn->close();
}
?>

