<?php
// Selalu tampilkan error untuk debugging
ini_set('display_errors', 1);
error_reporting(E_ALL);

// --- Konfigurasi Database ---
$servername = "localhost";
$username = "zacky"; // Ganti jika username Anda berbeda
$password = "12345"; // Ganti jika password Anda berbeda
$dbname = "pwdkampus";

// --- Logika Navigasi & Pengambilan Data ---
$conn = null;
try {
    $conn = new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error) {
        throw new Exception("Koneksi gagal: " . $conn->connect_error);
    }

    // 1. Dapatkan ID CV yang sedang ditampilkan dari URL
    $current_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
    if ($current_id === 0) {
        throw new Exception("Error: ID CV tidak valid atau tidak ditemukan di URL.");
    }

    // 2. Ambil semua ID CV yang ada di database untuk navigasi
    $all_ids_res = $conn->query("SELECT id FROM personal_info ORDER BY id ASC");
    $all_ids = [];
    while($row = $all_ids_res->fetch_assoc()) {
        $all_ids[] = $row['id'];
    }

    // 3. Cari tahu ID sebelumnya dan berikutnya
    $current_index = array_search($current_id, $all_ids);
    $previous_id = null;
    $next_id = null;

    if ($current_index !== false) {
        if (isset($all_ids[$current_index - 1])) {
            $previous_id = $all_ids[$current_index - 1];
        }
        if (isset($all_ids[$current_index + 1])) {
            $next_id = $all_ids[$current_index + 1];
        }
    }

    // 4. Ambil semua data untuk CV yang sedang ditampilkan
    function fetchData($conn, $query, $id) {
        $stmt = $conn->prepare($query);
        if ($stmt === false) throw new Exception("Prepare statement gagal: " . $conn->error);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result();
    }

    $personal_info_res = fetchData($conn, "SELECT * FROM personal_info WHERE id = ?", $current_id);
    $personal_info = $personal_info_res->fetch_assoc();

    if (!$personal_info) {
        throw new Exception("CV dengan ID " . htmlspecialchars($current_id) . " tidak ditemukan.");
    }

    $pendidikan = fetchData($conn, "SELECT * FROM pendidikan WHERE personal_info_id = ?", $current_id);
    $pengalaman = fetchData($conn, "SELECT * FROM pengalaman WHERE personal_info_id = ? ORDER BY tanggal_mulai DESC", $current_id);
    $penghargaan = fetchData($conn, "SELECT * FROM penghargaan WHERE personal_info_id = ?", $current_id);
    $keahlian = fetchData($conn, "SELECT * FROM keahlian WHERE personal_info_id = ? ORDER BY kategori", $current_id);
    $sertifikasi = fetchData($conn, "SELECT * FROM sertifikasi WHERE personal_info_id = ?", $current_id);
    $bahasa = fetchData($conn, "SELECT * FROM bahasa WHERE personal_info_id = ?", $current_id);

} catch (Exception $e) {
    die("<div style='font-family: sans-serif; padding: 2rem; border: 2px solid red; background: #ffe3e3;'>" . $e->getMessage() . "</div>");
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CV - <?php echo htmlspecialchars($personal_info['nama_lengkap']); ?></title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Lora:wght@400;600&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --bg-color: #f3f4f6;
            --paper-bg: #ffffff;
            --text-color: #333;
            --heading-color: #000;
            --subtle-text-color: #555;
            --accent-color: #4f46e5;
            --border-color: #e5e7eb;
        }
        
        /* --- Dark Mode Variables --- */
        body.dark-mode {
            --bg-color: #111827;
            --paper-bg: #1f2937;
            --text-color: #d1d5db;
            --heading-color: #ffffff;
            --subtle-text-color: #9ca3af;
            --accent-color: #818cf8;
            --border-color: #374151;
        }

        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--bg-color);
            color: var(--text-color);
            line-height: 1.7;
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 2rem;
            min-height: 100vh;
            transition: background-color 0.3s, color 0.3s;
        }
        
        /* --- Navigasi Atas --- */
        .cv-navigation {
            width: 100%;
            max-width: 850px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
        }
        .nav-button {
            font-family: 'Inter', sans-serif;
            font-size: 0.9rem;
            font-weight: 500;
            color: var(--subtle-text-color);
            background-color: var(--paper-bg);
            padding: 0.6rem 1.2rem;
            border-radius: 8px;
            text-decoration: none;
            border: 1px solid var(--border-color);
            box-shadow: 0 1px 2px rgba(0,0,0,0.05);
            transition: all 0.2s ease;
            cursor: pointer;
        }
        .nav-button:hover {
            color: var(--accent-color);
            border-color: var(--accent-color);
        }
        .nav-button.disabled {
            opacity: 0.5;
            cursor: not-allowed;
            border-color: var(--border-color);
            color: var(--subtle-text-color);
        }
        .nav-button#theme-toggle {
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 0.6rem;
        }
        .nav-group {
            display: flex;
            gap: 0.75rem;
        }

        /* --- Kertas CV --- */
        .cv-paper {
            width: 100%;
            max-width: 850px;
            background-color: var(--paper-bg);
            padding: 3rem 4rem;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            transition: background-color 0.3s;
        }
        
        .header {
            text-align: center;
            margin-bottom: 2.5rem;
            padding-bottom: 1.5rem;
            border-bottom: 2px solid var(--border-color);
        }
        .header h1 {
            font-family: 'Lora', serif;
            font-size: 2.8rem;
            font-weight: 600;
            color: var(--heading-color);
            margin-bottom: 0.5rem;
        }
        .contact-info { font-size: 0.95rem; color: var(--subtle-text-color); }
        .contact-info a { color: var(--accent-color); text-decoration: none; }
        .contact-info a:hover { text-decoration: underline; }
        .contact-info span { margin: 0 0.75rem; }

        .section { margin-bottom: 2rem; }
        .section-title {
            font-family: 'Lora', serif;
            font-size: 1.5rem;
            font-weight: 600;
            color: var(--heading-color);
            border-bottom: 2px solid var(--accent-color);
            padding-bottom: 0.5rem;
            margin-bottom: 1.5rem;
            display: inline-block;
        }
        
        .job { margin-bottom: 1.5rem; }
        .job-header { display: flex; justify-content: space-between; align-items: baseline; margin-bottom: 0.25rem; }
        .job-title { font-size: 1.1rem; font-weight: 600; color: var(--heading-color); }
        .job-date { font-style: italic; color: var(--subtle-text-color); font-size: 0.9rem; }
        .job-company { font-weight: 500; color: var(--subtle-text-color); margin-bottom: 0.75rem; }
        .job-description { padding-left: 1.25rem; list-style-type: disc; }
        ul.grid-list { list-style: none; display: grid; grid-template-columns: 1fr 1fr; gap: 0.5rem 1.5rem; }

        @media (max-width: 768px) {
            body { padding: 1rem; }
            .cv-paper { padding: 2rem; }
            .header h1 { font-size: 2.2rem; }
            .job-header { flex-direction: column; align-items: flex-start; }
            ul.grid-list { grid-template-columns: 1fr; }
            .cv-navigation { flex-direction: column; gap: 1rem; }
        }
    </style>
</head>
<body>

    <nav class="cv-navigation">
        <div class="nav-group">
            <a href="web_design_cv.php" class="nav-button">← Kembali ke Daftar</a>
            <button id="theme-toggle" class="nav-button" title="Ganti tema">
                <svg id="theme-icon-light" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="5"></circle><line x1="12" y1="1" x2="12" y2="3"></line><line x1="12" y1="21" x2="12" y2="23"></line><line x1="4.22" y1="4.22" x2="5.64" y2="5.64"></line><line x1="18.36" y1="18.36" x2="19.78" y2="19.78"></line><line x1="1" y1="12" x2="3" y2="12"></line><line x1="21" y1="12" x2="23" y2="12"></line><line x1="4.22" y1="19.78" x2="5.64" y2="18.36"></line><line x1="18.36" y1="5.64" x2="19.78" y2="4.22"></line></svg>
                <svg id="theme-icon-dark" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="display: none;"><path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"></path></svg>
            </button>
        </div>
        <div class="nav-group">
            <a href="<?php echo $previous_id ? 'tampil_cv.php?id=' . $previous_id : '#'; ?>" 
               class="nav-button <?php echo $previous_id ? '' : 'disabled'; ?>">
               Sebelumnya
            </a>
            <a href="<?php echo $next_id ? 'tampil_cv.php?id=' . $next_id : '#'; ?>" 
               class="nav-button <?php echo $next_id ? '' : 'disabled'; ?>">
               Berikutnya
            </a>
        </div>
    </nav>

    <main class="cv-paper">
        <header class="header">
            <h1><?php echo htmlspecialchars($personal_info['nama_lengkap']); ?></h1>
            <div class="contact-info">
                <span><?php echo htmlspecialchars($personal_info['lokasi']); ?></span>|
                <a href="mailto:<?php echo htmlspecialchars($personal_info['email']); ?>"><?php echo htmlspecialchars($personal_info['email']); ?></a>|
                <a href="<?php echo htmlspecialchars($personal_info['linkedin']); ?>" target="_blank" rel="noopener noreferrer">LinkedIn</a>
            </div>
        </header>

        <section class="section">
            <h2 class="section-title">Ringkasan</h2>
            <p><?php echo nl2br(htmlspecialchars($personal_info['ringkasan'])); ?></p>
        </section>

        <section class="section">
            <h2 class="section-title">Pengalaman</h2>
            <?php while($row = $pengalaman->fetch_assoc()): ?>
                <div class="job">
                    <div class="job-header">
                        <span class="job-title"><?php echo htmlspecialchars($row['posisi']); ?></span>
                        <span class="job-date">
                            <?php echo date('M Y', strtotime($row['tanggal_mulai'])); ?> - 
                            <?php echo $row['tanggal_selesai'] ? date('M Y', strtotime($row['tanggal_selesai'])) : 'Present'; ?>
                        </span>
                    </div>
                    <div class="job-company"><?php echo htmlspecialchars($row['perusahaan']); ?></div>
                    <ul class="job-description">
                        <?php
                            $points = explode("•", $row['deskripsi']);
                            foreach ($points as $point) {
                                $trimmed_point = trim($point);
                                if (!empty($trimmed_point)) echo "<li>" . htmlspecialchars($trimmed_point) . "</li>";
                            }
                        ?>
                    </ul>
                </div>
            <?php endwhile; ?>
        </section>

        <section class="section">
            <h2 class="section-title">Pendidikan</h2>
            <?php while($row = $pendidikan->fetch_assoc()): ?>
                <div class="job-header">
                     <span class="job-title"><?php echo htmlspecialchars($row['institusi']); ?></span>
                     <span class="job-date">Okt 2024 - 2028</span>
                </div>
                <p><?php echo htmlspecialchars($row['gelar']); ?> (IPK: <?php echo htmlspecialchars($row['ipk']); ?>)</p>
            <?php endwhile; ?>
        </section>
        
        <section class="section">
            <h2 class="section-title">Keahlian</h2>
            <?php while($row = $keahlian->fetch_assoc()): ?>
                <p><strong><?php echo htmlspecialchars($row['kategori']); ?>:</strong> <?php echo htmlspecialchars($row['skills']); ?></p>
            <?php endwhile; ?>
        </section>
        
        <section class="section">
            <h2 class="section-title">Sertifikasi</h2>
            <ul class="grid-list">
                 <?php while($row = $sertifikasi->fetch_assoc()): ?>
                    <li><strong><?php echo htmlspecialchars($row['nama_sertifikasi']); ?></strong> - <em><?php echo htmlspecialchars($row['penerbit']); ?></em></li>
                <?php endwhile; ?>
            </ul>
        </section>
        
        <section class="section">
            <h2 class="section-title">Bahasa</h2>
             <?php while($row = $bahasa->fetch_assoc()): ?>
                <p><strong><?php echo htmlspecialchars($row['nama_bahasa']); ?>:</strong> <?php echo htmlspecialchars($row['tingkat_kemahiran']); ?></p>
            <?php endwhile; ?>
        </section>
    </main>

    <script>
        const themeToggle = document.getElementById('theme-toggle');
        const lightIcon = document.getElementById('theme-icon-light');
        const darkIcon = document.getElementById('theme-icon-dark');
        const body = document.body;

        // Fungsi untuk menerapkan tema
        const applyTheme = (theme) => {
            if (theme === 'dark') {
                body.classList.add('dark-mode');
                lightIcon.style.display = 'none';
                darkIcon.style.display = 'block';
            } else {
                body.classList.remove('dark-mode');
                lightIcon.style.display = 'block';
                darkIcon.style.display = 'none';
            }
        };

        // Event listener untuk tombol toggle
        themeToggle.addEventListener('click', () => {
            const isDarkMode = body.classList.contains('dark-mode');
            const newTheme = isDarkMode ? 'light' : 'dark';
            localStorage.setItem('theme', newTheme);
            applyTheme(newTheme);
        });

        // Memeriksa tema saat halaman dimuat
        document.addEventListener('DOMContentLoaded', () => {
            const savedTheme = localStorage.getItem('theme');
            const prefersDark = window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches;
            
            if (savedTheme) {
                applyTheme(savedTheme);
            } else if (prefersDark) {
                applyTheme('dark');
            } else {
                applyTheme('light');
            }
        });
    </script>

</body>
</html>
<?php
$conn->close();
?>

