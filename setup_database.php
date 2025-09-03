<?php
// Selalu tampilkan error untuk debugging
ini_set('display_errors', 1);
error_reporting(E_ALL);

// --- Konfigurasi Database ---
$servername = "localhost";
$username = "zacky"; // Ganti jika username Anda berbeda
$password = "12345"; // Ganti jika password Anda berbeda
$dbname = "pwdkampus";

$conn = null;
try {
    $conn = new mysqli($servername, $username, $password);
    if ($conn->connect_error) {
        throw new Exception("Koneksi ke server MySQL gagal: " . $conn->connect_error);
    }
    
    $conn->query("CREATE DATABASE IF NOT EXISTS $dbname");
    $conn->select_db($dbname);
    echo "Database `{$dbname}` siap digunakan.<br>";

    $conn->query("DROP TABLE IF EXISTS bahasa, sertifikasi, keahlian, penghargaan, pengalaman, pendidikan, personal_info");
    echo "Tabel lama berhasil dihapus.<br>";

    // Fungsi executeQuery sekarang akan digunakan untuk SEMUA perintah
    function executeQuery($conn, $sql, $actionMessage) {
        if ($conn->query($sql) === FALSE) {
            throw new Exception("Gagal saat {$actionMessage}: " . $conn->error);
        }
    }

    // --- Membuat Struktur Tabel (Dengan Error Checking) ---
    executeQuery($conn, "CREATE TABLE personal_info ( id INT AUTO_INCREMENT PRIMARY KEY, nama_lengkap VARCHAR(100) NOT NULL, lokasi VARCHAR(100), email VARCHAR(100), linkedin VARCHAR(255), ringkasan TEXT )", "membuat tabel personal_info");
    executeQuery($conn, "CREATE TABLE pendidikan ( id INT AUTO_INCREMENT PRIMARY KEY, personal_info_id INT, institusi VARCHAR(100), gelar VARCHAR(100), ipk DECIMAL(3,2) )", "membuat tabel pendidikan");
    executeQuery($conn, "CREATE TABLE pengalaman ( id INT AUTO_INCREMENT PRIMARY KEY, personal_info_id INT, posisi VARCHAR(100), perusahaan VARCHAR(100), tanggal_mulai DATE, tanggal_selesai DATE, deskripsi TEXT )", "membuat tabel pengalaman");
    executeQuery($conn, "CREATE TABLE penghargaan ( id INT AUTO_INCREMENT PRIMARY KEY, personal_info_id INT, judul VARCHAR(255), deskripsi TEXT )", "membuat tabel penghargaan");
    executeQuery($conn, "CREATE TABLE keahlian ( id INT AUTO_INCREMENT PRIMARY KEY, personal_info_id INT, kategori VARCHAR(100), skills TEXT )", "membuat tabel keahlian");
    executeQuery($conn, "CREATE TABLE sertifikasi ( id INT AUTO_INCREMENT PRIMARY KEY, personal_info_id INT, nama_sertifikasi VARCHAR(200), penerbit VARCHAR(100) )", "membuat tabel sertifikasi");
    executeQuery($conn, "CREATE TABLE bahasa ( id INT AUTO_INCREMENT PRIMARY KEY, personal_info_id INT, nama_bahasa VARCHAR(50), tingkat_kemahiran VARCHAR(50) )", "membuat tabel bahasa");
    echo "Struktur tabel baru berhasil dibuat.<br>";

    // --- [ID 1] Memasukkan Data Zacky Muhammad Dinata ---
    executeQuery($conn, "INSERT INTO personal_info (id, nama_lengkap, lokasi, email, linkedin, ringkasan) VALUES (1, 'Zacky Muhammad Dinata', 'Cirebon, Jawa Barat, Indonesia', 'zmdinata@gmail.com', 'https://www.linkedin.com/in/zacky-muhammad-dinata-463995280/', 'Junior Analis Data dengan latar belakang Sistem Informasi, saat ini sedang meningkatkan keahlian dalam SQL, Python, dan berbagai perangkat data. Berpengalaman dalam proyek digital kreatif, kini fokus untuk memanfaatkan keterampilan analitis guna memperoleh wawasan dari data. Memiliki semangat untuk menerapkan perpaduan kemampuan teknis dan kreatif guna mendukung pengambilan keputusan yang didasarkan pada data.')", "memasukkan data personal_info ID 1");
    executeQuery($conn, "INSERT INTO pendidikan (personal_info_id, institusi, gelar, ipk) VALUES (1, 'STMIK IKMI Cirebon', 'S1 Sistem Informasi', 3.33)", "memasukkan data pendidikan ID 1");
    executeQuery($conn, "INSERT INTO pengalaman (personal_info_id, posisi, perusahaan, tanggal_mulai, tanggal_selesai, deskripsi) VALUES (1, 'Junior Data Analyst', 'Self-Learning (Personal Project/Self-Learning)', '2024-01-01', NULL, '• Melakukan riset mandiri untuk memahami pasar, dengan fokus pada perilaku konsumen, dinamika penawaran-permintaan, dan metrik pertumbuhan.\n• Menganalisis beragam pola data untuk mengevaluasi potensi tren dan mengidentifikasi peluang pasar pada tahap awal menggunakan pendekatan kualitatif dan kuantitatif.\n• Mengembangkan studi kasus riset yang komprehensif dan diterbitkan sebagai portofolio pendukung.\n• Menerapkan temuan analisis untuk menyempurnakan proses pengambilan keputusan pribadi dan memperluas kapabilitas profesional dalam analisis data.\n• Membangun fondasi yang kuat untuk transisi karier ke dalam bidang analisis data melalui eksplorasi dan pembelajaran mandiri.')", "memasukkan data pengalaman ID 1");
    executeQuery($conn, "INSERT INTO pengalaman (personal_info_id, posisi, perusahaan, tanggal_mulai, tanggal_selesai, deskripsi) VALUES (1, 'NFT & Design Artist', 'Freelance', '2021-12-01', NULL, '• Menyelesaikan lebih dari 60 karya seni NFT individual untuk koleksi Floky Ape NFT.\n• Merancang lebih dari 30 item trait dalam format berlapis yang terinspirasi oleh model gaya Azuki.\n• Membuat 1 karya seni spesial eksklusif bergaya Azuki.\n• Berkontribusi pada kesuksesan proyek NFT Floky Ape dan gaya Azuki, serta mendapatkan pengakuan positif dari komunitas.\n• Merancang lebih dari 25 lapisan *trait* untuk proyek NFT bertema kucing yang disesuaikan untuk aset generatif.\n• Menghasilkan lebih dari 20 potret digital vektor/vexel untuk keperluan pribadi.')", "memasukkan data pengalaman ID 1");
    executeQuery($conn, "INSERT INTO penghargaan (personal_info_id, judul, deskripsi) VALUES (1, 'Pembicara dalam Webinar \"From Scratch to Smart: Bisnis dan Blockchain untuk Generasi Digital\"', 'Diakui sebagai pembicara dalam webinar yang diselenggarakan oleh Upkraf Digital School, dengan membawakan sesi berjudul \"Unlocking the Future: Blockchain & Web3 untuk Generasi Digital\". Materi yang dibawakan meliputi fundamental BlockChain, konsep Web3, kriptografi, studi kasus di dunia nyata, dan peluang masa depan, yang bertujuan menginspirasi audiens Gen Z dan milenial untuk bereksplorasi dan berkarya di era digital.')", "memasukkan data penghargaan ID 1");
    executeQuery($conn, "INSERT INTO keahlian (personal_info_id, kategori, skills) VALUES (1, 'Data & Database', 'Data Analysis, SQL Queries, MySQL, SQL, Database, Tableau, Python, Oracle Database')", "memasukkan data keahlian ID 1");
    executeQuery($conn, "INSERT INTO keahlian (personal_info_id, kategori, skills) VALUES (1, 'Design Ilustrator', 'Adobe Photoshop, Figma, UI Design, Grapich Design, Ilustrator')", "memasukkan data keahlian ID 1");
    executeQuery($conn, "INSERT INTO sertifikasi (personal_info_id, nama_sertifikasi, penerbit) VALUES (1, 'Fundamental SQL Using FUNCTION and GROUP BY', 'DQLab'), (1, 'Fundamental SQL Using INNER JOIN and UNION', 'DQLab'), (1, 'Fundamental SQL Using SELECT Statement', 'DQLab'), (1, 'Python for Data Professional Beginner - Part 1', 'DQLab'), (1, 'Fundamental Excel', 'Coding Studio'), (1, 'Fundamental Database MySQL', 'Coding Studio'), (1, 'CryptoCurrency Deep Dive', 'Binance Academy'), (1, 'NFT Deep Dive', 'Binance Academy'), (1, 'Crypto Trading Deep Dive', 'Binance Academy'), (1, 'Conversation For Business: Elementary Level', 'LB LIA Cirebon'), (1, 'STEM Education and the Importance of Digital Literacy for the Young Generation', 'STMIK IKMI Cirebon'), (1, 'How to Build Personal Branding in Social Media', 'STMIK IKMI Cirebon'), (1, 'UI/UX Skills for The Modern Design', 'Android Community - STMIK IKMI Cirebon')", "memasukkan data sertifikasi ID 1");
    executeQuery($conn, "INSERT INTO bahasa (personal_info_id, nama_bahasa, tingkat_kemahiran) VALUES (1, 'Bahasa Indonesia', 'Penutur Asli atau Setara'), (1, 'Bahasa Inggris', 'Pre - Intermediated')", "memasukkan data bahasa ID 1");
    echo "Data untuk Zacky Muhammad Dinata (ID 1) berhasil dimasukkan.<br>";

    // --- [ID 2] Memasukkan Data Tion Hermawan ---
    executeQuery($conn, "INSERT INTO personal_info (id, nama_lengkap, lokasi, email, linkedin, ringkasan) VALUES (2, 'Tion Hermawan', 'Brebes, Jawa Tengah, Indonesia', 'tionhermawn28@gmail.com', 'https://www.linkedin.com/in/tion-hermawan', 'Dengan latar belakang di Sistem Informasi, saya terlatih untuk memahami bagaimana teknologi dapat memecahkan masalah bisnis. Kini, saya mendedikasikan fokus saya pada data analisis, di mana saya bisa menggabungkan pemahaman teknis tersebut dengan kemampuan analisis untuk menemukan pola dan wawasan tersembunyi. Saya secara aktif mengasah kemampuan saya dalam SQL untuk menarik data secara efisien dan Python untuk membersihkan, menganalisis, serta memvisualisasikannya. Pengalaman saya di dunia kreatif digital mengajarkan saya pentingnya presentasi data yang menarik dan mudah dipahami. Saya antusias untuk membawa perpaduan unik antara kreativitas dan analisis data ini untuk mendukung strategi bisnis yang didorong oleh data.')", "memasukkan data personal_info ID 2");
    executeQuery($conn, "INSERT INTO pendidikan (personal_info_id, institusi, gelar, ipk) VALUES (2, 'STMIK IKMI Cirebon', 'S1, Sistem Informasi', 3.48)", "memasukkan data pendidikan ID 2");
    executeQuery($conn, "INSERT INTO pengalaman (personal_info_id, posisi, perusahaan, tanggal_mulai, tanggal_selesai, deskripsi) VALUES (2, 'Data Analyst', 'Self-Learning', '2024-06-01', NULL, '• Melakukan riset mendalam terhadap perilaku konsumen dan dinamika penawaran-permintaan.\n• Menganalisis pola data untuk mengevaluasi tren dan menemukan potensi pasar pada tahap awal.\n• Mengembangkan studi kasus riset yang komprehensif sebagai portofolio untuk menunjukkan kemampuan analisis saya.')", "memasukkan data pengalaman ID 2");
    executeQuery($conn, "INSERT INTO keahlian (personal_info_id, kategori, skills) VALUES (2, 'Data & Database', 'Data Analysis, Python, SQL, Excel, Oracle Database')", "memasukkan data keahlian ID 2");
    executeQuery($conn, "INSERT INTO sertifikasi (personal_info_id, nama_sertifikasi, penerbit) VALUES (2, 'Study Case Bootcamp Data Analyst with Excel', 'DQLab'), (2, 'Study Case Bootcamp Data Analyst with Python', 'DQLab'), (2, 'Study Case Bootcamp Data Analyst with SQL', 'DQLab'), (2, 'Python Essentials 1', 'STMIK IKMI Cirebon')", "memasukkan data sertifikasi ID 2");
    executeQuery($conn, "INSERT INTO bahasa (personal_info_id, nama_bahasa, tingkat_kemahiran) VALUES (2, 'Bahasa Indonesia', 'Penutur Asli atau Setara')", "memasukkan data bahasa ID 2");
    echo "Data untuk Tion Hermawan (ID 2) berhasil dimasukkan.<br>";
    
    // --- [ID 3] Memasukkan Data Dikri Fauzan Anawawi ---
    executeQuery($conn, "INSERT INTO personal_info (id, nama_lengkap, lokasi, email, linkedin, ringkasan) VALUES (3, 'DIKRI FAUZAN ANAWAWI', 'Tasikmalaya, Jawa Barat, Indonesia', 'zanc3790@gmail.com', NULL, 'Saya adalah seorang mahasiswa Sistem Informasi di STMIK IKMI Cirebon yang memiliki minat besar pada bidang jaringan komputer. Ketertarikan ini mendorong saya untuk terus mengembangkan pemahaman mengenai infrastruktur jaringan, manajemen sistem, serta teknologi pendukung lainnya. Dengan latar belakang akademik di bidang sistem informasi, saya berkomitmen untuk mengasah kemampuan analisis, problem solving, dan penguasaan teknologi informasi, khususnya dalam aspek jaringan komputer, guna mendukung karier di dunia IT.')", "memasukkan data personal_info ID 3");
    executeQuery($conn, "INSERT INTO pendidikan (personal_info_id, institusi, gelar, ipk) VALUES (3, 'STMIK IKMI Cirebon', 'S1, Sistem Informasi', 4.0)", "memasukkan data pendidikan ID 3");
    executeQuery($conn, "INSERT INTO pengalaman (personal_info_id, posisi, perusahaan, tanggal_mulai, tanggal_selesai, deskripsi) VALUES (3, 'Network Engineering', 'Toko Komputer', '2023-06-01', '2023-08-01', '• Integrasi Jaringan & Database\n• Instalasi Sofware\n• Pemeliharaan & Update Software\n• Dukungan Teknis (Technical Support)')", "memasukkan data pengalaman ID 3");
    executeQuery($conn, "INSERT INTO keahlian (personal_info_id, kategori, skills) VALUES (3, 'Network Engineering', 'Memahami TCP/IP, OSI Layer, IP Addressing, Subnetting., Routing & Switching dasar., Konfigurasi Perangkat Jaringan, Konfigurasi WLAN., Manajemen LAN & WAN.')", "memasukkan data keahlian ID 3");
    executeQuery($conn, "INSERT INTO bahasa (personal_info_id, nama_bahasa, tingkat_kemahiran) VALUES (3, 'Bahasa Indonesia', 'Penutur Asli atau Setara')", "memasukkan data bahasa ID 3");
    echo "Data untuk DIKRI FAUZAN ANAWAWI (ID 3) berhasil dimasukkan.<br>";

    // --- [ID 4] Memasukkan Data Muhammad Hisyam Nudin ---
    executeQuery($conn, "INSERT INTO personal_info (id, nama_lengkap, lokasi, email, linkedin, ringkasan) VALUES (4, 'Muhammad Hisyam Nudin', 'Kota cirebon, jawa barat', 'hisyamnudin882@gmail.com', NULL, 'Saya adalah seorang Pemain Sepakbola Profesional yang berdedikasi, dengan pengalaman lebih dari dua tahun berkompetisi di level profesional. Memiliki kemampuan beradaptasi di berbagai posisi line up, saya dikenal sebagai pemain yang fleksibel, disiplin, dan berkomitmen tinggi terhadap performa tim.')", "memasukkan data personal_info ID 4");
    executeQuery($conn, "INSERT INTO pendidikan (personal_info_id, institusi, gelar, ipk) VALUES (4, 'STMIK IKMI Cirebon', 'S1, Sistem Informasi', 3.28)", "memasukkan data pendidikan ID 4");
    executeQuery($conn, "INSERT INTO pengalaman (personal_info_id, posisi, perusahaan, tanggal_mulai, tanggal_selesai, deskripsi) VALUES (4, 'Pemain Sepak Bola Profesional', 'Karier Profesional', '2024-06-01', NULL, '• Melakukan riset performa\n• Menganalisis pola permainan serta kondisi fisik\n• Mengembangkan semua project dengan stabil')", "memasukkan data pengalaman ID 4");
    executeQuery($conn, "INSERT INTO keahlian (personal_info_id, kategori, skills) VALUES (4, 'Skill Pemain', 'jugling 95%, dribling 90%, sprinter90%, kerja sama tim 90%')", "memasukkan data keahlian ID 4");
    executeQuery($conn, "INSERT INTO sertifikasi (personal_info_id, nama_sertifikasi, penerbit) VALUES (4, 'liga 4 jawabarat', 'PSSI liga4'), (4, 'piala mada jabar', 'PSSI kota cirebon'), (4, 'Persik kediri', 'PSSI profesional')", "memasukkan data sertifikasi ID 4");
    executeQuery($conn, "INSERT INTO bahasa (personal_info_id, nama_bahasa, tingkat_kemahiran) VALUES (4, 'Bahasa Indonesia', 'Penutur Asli')", "memasukkan data bahasa ID 4");
    echo "Data untuk Muhammad Hisyam Nudin (ID 4) berhasil dimasukkan.<br>";

    // --- [ID 5] Memasukkan Data Muhammad Zhaky Albaihaqy ---
    executeQuery($conn, "INSERT INTO personal_info (id, nama_lengkap, lokasi, email, linkedin, ringkasan) VALUES (5, 'Muhammad Zhaky Albaihaqy', 'indramayu city, Jawa Barat, Indonesia', 'amzhaky@gmail.com', NULL, 'Saya adalah seorang mahasiswa Sistem Informasi di STMIK IKMI Cirebon yang memiliki minat dalam bidang bisnis dan teknologi. Ketertarikan saya pada dunia bisnis mendorong saya untuk mempelajari bagaimana teknologi informasi, khususnya sistem informasi, dapat digunakan untuk mendukung strategi bisnis, mengelola data, serta meningkatkan efisiensi dan inovasi.')", "memasukkan data personal_info ID 5");
    executeQuery($conn, "INSERT INTO pendidikan (personal_info_id, institusi, gelar, ipk) VALUES (5, 'STMIK IKMI Cirebon', 'S1, Sistem Informasi', NULL)", "memasukkan data pendidikan ID 5");
    executeQuery($conn, "INSERT INTO pengalaman (personal_info_id, posisi, perusahaan, tanggal_mulai, tanggal_selesai, deskripsi) VALUES (5, 'Bisnis Digital', 'Pribadi', '2024-09-01', '2024-12-01', '• Mengelola toko online di marketplace (Shopee, Tokopedia, dll.) termasuk upload produk, optimasi deskripsi, dan pengelolaan stok\n• Melakukan analisis penjualan digital menggunakan data dari marketplace atau social media insight.\n• Membuat konten digital untuk promosi produk')", "memasukkan data pengalaman ID 5");
    executeQuery($conn, "INSERT INTO bahasa (personal_info_id, nama_bahasa, tingkat_kemahiran) VALUES (5, 'Bahasa Indonesia', 'Penutur Asli')", "memasukkan data bahasa ID 5");
    echo "Data untuk Muhammad Zhaky Albaihaqy (ID 5) berhasil dimasukkan.<br>";

    // --- [ID 6] Memasukkan Data Tio Pramudya Berliana ---
    executeQuery($conn, "INSERT INTO personal_info (id, nama_lengkap, lokasi, email, linkedin, ringkasan) VALUES (6, 'Tio Pramudya Berliana', 'Cirebon, Jawa Barat, Indonesia', 'tiopramudyaberliana@gmail.com', NULL, 'Saya adalah seorang mahasiswa yang saat ini menempuh pendidikan di STMIK MI Cirebon, mengambil jurusan Sistem Informasi. Saya memiliki minat yang tinggi dalam bidang teknologi informasi, khususnya dalam hal pengembangan sistem, manajemen data, dan pemanfaatan teknologi untuk mendukung proses bisnis.')", "memasukkan data personal_info ID 6");
    executeQuery($conn, "INSERT INTO pendidikan (personal_info_id, institusi, gelar, ipk) VALUES (6, 'STMIK IKMI Cirebon', 'S1, Sistem Informasi', 3.30)", "memasukkan data pendidikan ID 6");
    executeQuery($conn, "INSERT INTO sertifikasi (personal_info_id, nama_sertifikasi, penerbit) VALUES (6, 'Python Essentials 1', 'STMIK IKMI Cirebon'), (6, 'C++', 'STMIK IKMI Cirebon'), (6, 'CCNA', 'STMIK IKMI Cirebon')", "memasukkan data sertifikasi ID 6");
    executeQuery($conn, "INSERT INTO bahasa (personal_info_id, nama_bahasa, tingkat_kemahiran) VALUES (6, 'Bahasa Indonesia', 'Penutur Asli')", "memasukkan data bahasa ID 6");
    echo "Data untuk Tio Pramudya Berliana (ID 6) berhasil dimasukkan.<br>";
    
    // --- [ID 7] Memasukkan Data Abdul Rohman ---
    executeQuery($conn, "INSERT INTO personal_info (id, nama_lengkap, lokasi, email, linkedin, ringkasan) VALUES (7, 'Abdul Rohman', 'Cirebon, Jawa Barat, Indonesia', 'abdurrohman131102@gmail.com', NULL, 'Saya adalah seorang mahasiswa aktif di STMIK IKMI Cirebon yang sedang menempuh pendidikan di jurusan Sistem Informasi. Saya memiliki minat yang kuat dalam dunia teknologi informasi, terutama dalam pengembangan sistem dan pemanfaatan data untuk mendukung pengambilan keputusan.')", "memasukkan data personal_info ID 7");
    executeQuery($conn, "INSERT INTO pendidikan (personal_info_id, institusi, gelar, ipk) VALUES (7, 'STMIK IKMI Cirebon', 'S1, Sistem Informasi', 3.30)", "memasukkan data pendidikan ID 7");
    executeQuery($conn, "INSERT INTO sertifikasi (personal_info_id, nama_sertifikasi, penerbit) VALUES (7, 'Python Essentials 1', 'STMIK IKMI Cirebon'), (7, 'C++', 'STMIK IKMI Cirebon'), (7, 'CCNA', 'STMIK IKMI Cirebon')", "memasukkan data sertifikasi ID 7");
    executeQuery($conn, "INSERT INTO bahasa (personal_info_id, nama_bahasa, tingkat_kemahiran) VALUES (7, 'Bahasa Indonesia', 'Penutur Asli')", "memasukkan data bahasa ID 7");
    echo "Data untuk Abdul Rohman (ID 7) berhasil dimasukkan.<br>";


    echo "<br><strong style='color:green;'>✅ Setup database berhasil!</strong> Semua data lengkap berhasil dimasukkan.";

} catch (Exception $e) {
    die("<br><strong style='color:red;'>❌ Error:</strong> " . $e->getMessage());
} finally {
    if (isset($conn)) {
        $conn->close();
    }
}
?>

