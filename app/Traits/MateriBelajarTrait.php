<?php

namespace App\Traits;

trait MateriBelajarTrait
{
    /**
     * Kumpulan Materi Dasar PHP Native (Cheat Sheet / Training Camp)
     */
    private function getMateriDasar()
    {
        return [
            [
                'id' => 'database',
                'judul' => 'Setup Database (SQL)',
                'icon' => 'fa-database',
                'is_locked_for_guest' => false,
                'penjelasan' => 'Sebelum mulai ngoding PHP, kita wajib membuat database dan tabelnya terlebih dahulu. Kamu bisa mengeksekusi sintaks SQL ini di menu <kbd class="bg-gray-800 text-white px-1 rounded">SQL</kbd> pada phpMyAdmin.',
                'kode' => "-- 1. Buat Database\nCREATE DATABASE perpustakaan;\nUSE perpustakaan;\n\n-- 2. Buat Tabel Buku\nCREATE TABLE buku (\n    id INT AUTO_INCREMENT PRIMARY KEY,\n    judul VARCHAR(255) NOT NULL,\n    penulis VARCHAR(255) NOT NULL,\n    tahun_terbit INT(4) NOT NULL,\n    stok INT DEFAULT 0\n);",
            ],
            [
                'id' => 'html_ui',
                'judul' => 'Basic HTML Form (UI)',
                'icon' => 'fa-file-code',
                'is_locked_for_guest' => false,
                'penjelasan' => 'PHP Native bertugas memproses data, tapi kita butuh HTML untuk membuat tampilannya. Perhatikan atribut <code>name="..."</code> pada input, ini harus sama persis dengan yang ditangkap oleh <code>$_POST[\'...\']</code> di file PHP nanti.',
                'kode' => "<!DOCTYPE html>\n<html>\n<head>\n    <title>Form Tambah Buku</title>\n</head>\n<body>\n\n    <h2>Form Tambah Buku</h2>\n    \n    <form action=\"simpan.php\" method=\"POST\">\n        \n        <label>Judul Buku:</label><br>\n        <input type=\"text\" name=\"judul\" required><br><br>\n\n        <label>Penulis:</label><br>\n        <input type=\"text\" name=\"penulis\" required><br><br>\n\n        <label>Tahun Terbit:</label><br>\n        <input type=\"number\" name=\"tahun_terbit\" required><br><br>\n\n        <label>Stok:</label><br>\n        <input type=\"number\" name=\"stok\" required><br><br>\n\n        <button type=\"submit\">Simpan Data</button>\n\n    </form>\n\n</body>\n</html>",
            ],
            [
                'id' => 'koneksi',
                'judul' => 'Koneksi Database',
                'icon' => 'fa-plug',
                'is_locked_for_guest' => false,
                'penjelasan' => 'Sebelum melakukan CRUD, aplikasi PHP harus dihubungkan ke MySQL menggunakan <code>mysqli_connect()</code>. Variabel koneksi ini wajib di-include di setiap halaman yang butuh database.',
                'kode' => "<?php\n// format: mysqli_connect(host, username, password, database);\n\$conn = mysqli_connect('localhost', 'root', '', 'nama_database');\n\nif (!\$conn) {\n    die('Koneksi Gagal: ' . mysqli_connect_error());\n}\necho 'Koneksi Berhasil!';\n?>",
            ],
            [
                'id' => 'read',
                'judul' => 'Read (SELECT)',
                'icon' => 'fa-table-list',
                'is_locked_for_guest' => false,
                'penjelasan' => 'Menampilkan data menggunakan perintah <code>SELECT</code>. Hasil query ditangkap dengan <code>mysqli_query()</code>, lalu dipecah menjadi array menggunakan perulangan <code>while</code> dan <code>mysqli_fetch_assoc()</code>.',
                'kode' => "<?php\n\$query = mysqli_query(\$conn, \"SELECT * FROM users ORDER BY id DESC\");\n\n// Looping data ke dalam tabel HTML\nwhile (\$row = mysqli_fetch_assoc(\$query)) {\n    echo \"<tr>\";\n    echo \"<td>\" . \$row['nama'] . \"</td>\";\n    echo \"<td>\" . \$row['email'] . \"</td>\";\n    echo \"</tr>\";\n}\n?>",
            ],
            [
                'id' => 'create',
                'judul' => 'Create (INSERT)',
                'icon' => 'fa-plus',
                'is_locked_for_guest' => true,
                'penjelasan' => 'Menyimpan data baru. Data biasanya dikirim dari form HTML via method POST, ditangkap PHP dengan <code>$_POST</code>, lalu dieksekusi dengan perintah <code>INSERT INTO</code>.',
                'kode' => "<?php\n// Tangkap data dari form input name=\"nama\"\n\$nama = \$_POST['nama'];\n\$email = \$_POST['email'];\n\n// Query Insert\n\$sql = \"INSERT INTO users (nama, email) VALUES ('\$nama', '\$email')\";\n\$eksekusi = mysqli_query(\$conn, \$sql);\n\nif (\$eksekusi) {\n    echo \"Data berhasil ditambahkan!\";\n}\n?>",
            ],
            [
                'id' => 'update',
                'judul' => 'Update (UPDATE)',
                'icon' => 'fa-pen',
                'is_locked_for_guest' => true,
                'penjelasan' => 'Mengubah data. <b>WAJIB MENGGUNAKAN WHERE</b> agar tidak semua data ikut terubah. Biasanya ID dikirim via form tersembunyi (hidden input).',
                'kode' => "<?php\n\$id = \$_POST['id'];\n\$nama_baru = \$_POST['nama'];\n\n// Query Update dengan WHERE\n\$sql = \"UPDATE users SET nama = '\$nama_baru' WHERE id = '\$id'\";\n\$eksekusi = mysqli_query(\$conn, \$sql);\n?>",
            ],
            [
                'id' => 'delete',
                'judul' => 'Delete (DELETE)',
                'icon' => 'fa-trash',
                'is_locked_for_guest' => true,
                'penjelasan' => 'Menghapus data berdasarkan ID. Biasanya ID dikirim melalui URL dan ditangkap dengan metode <code>$_GET</code>.',
                'kode' => "<?php\n// Tangkap ID dari URL (contoh: hapus.php?id=5)\n\$id = \$_GET['id'];\n\n// Query Delete\n\$sql = \"DELETE FROM users WHERE id = '\$id'\";\n\$eksekusi = mysqli_query(\$conn, \$sql);\n?>",
            ],
        ];
    }

    /**
     * Kumpulan Materi & Step Workspace (Interactive Live Coding)
     */
    private function getMateriPerpustakaan()
    {
        return [
            1 => [
                'judul' => 'STAGE 1: KONEKSI DATABASE',
                'teks' => '<p class="mb-3 text-gray-800">Langkah pertama dalam PHP Native adalah membangun "jembatan" ke database MySQL. Kita menggunakan fungsi bawaan <code>mysqli_connect()</code>.</p><div class="bg-black text-green-400 p-3 text-xs rounded border-2 border-gray-700 mb-3 shadow-inner font-mono"><span class="text-gray-500">// Struktur Dasar:</span><br>$koneksi = mysqli_connect("host", "user", "password", "nama_db");</div><p class="text-xs text-gray-600 italic">*Jika pakai XAMPP, host selalu "localhost", user "root", dan password dikosongkan ("").</p>',
                'instruksi' => 'Buat variabel $conn dan lakukan koneksi ke database "perpustakaan" dengan user "root" dan password "".',
                'kode_harapan' => '$conn = mysqli_connect("localhost", "root", "", "perpustakaan");',
                'hint' => '$conn = mysqli_connect("...", "...", "...", "...");',
            ],
            2 => [
                'judul' => 'STAGE 2: READ (MENAMPILKAN DATA)',
                'teks' => '<p class="mb-3 text-gray-800">Untuk mengambil data, kita mengirimkan perintah Raw SQL <code>SELECT</code> melalui fungsi <code>mysqli_query()</code>.</p><div class="bg-black text-blue-400 p-3 text-xs rounded border-2 border-gray-700 mb-3 shadow-inner font-mono"><span class="text-gray-500">// Contoh Raw SQL:</span><br>SELECT * FROM nama_tabel;</div><p class="text-xs text-gray-600">Nantinya data ini di-looping menggunakan <code>while($row = mysqli_fetch_assoc($query))</code> untuk dirender ke dalam tabel HTML.</p>',
                'instruksi' => 'Simpan hasil mysqli_query ke variabel $query dengan perintah SELECT seluruh data dari tabel "buku".',
                'kode_harapan' => '$query = mysqli_query($conn, "SELECT * FROM buku");',
                'hint' => '$query = mysqli_query($conn, "SELECT ... FROM ...");',
            ],
            3 => [
                'judul' => 'STAGE 3: CREATE (MENAMBAH DATA)',
                'teks' => '<p class="mb-3 text-gray-800">Data biasanya dikirim dari form HTML menggunakan method <code>POST</code>. Di file PHP, kita menangkapnya dengan variabel global <code>$_POST</code>.</p><div class="bg-black text-orange-400 p-3 text-xs rounded border-2 border-gray-700 mb-3 shadow-inner font-mono overflow-x-auto"><span class="text-gray-500">&lt;!-- 1. Form HTML --&gt;</span><br>&lt;form action="simpan.php" method="POST"&gt;<br>   &lt;input type="text" name="judul"&gt;<br>&lt;/form&gt;<br><br><span class="text-gray-500">// 2. Tangkap di PHP</span><br>$judul = $_POST[\'judul\'];</div>',
                'instruksi' => 'Tulis query eksekusi INSERT INTO ke tabel "buku" kolom (judul) dengan value variabel \'$judul\'. Gunakan mysqli_query.',
                'kode_harapan' => 'mysqli_query($conn, "INSERT INTO buku (judul) VALUES (\'$judul\')");',
                'hint' => 'mysqli_query($conn, "INSERT INTO ... (...) VALUES (\'...\')");',
            ],
            4 => [
                'judul' => 'STAGE 4: UPDATE (MENGUBAH DATA)',
                'teks' => '<div class="bg-red-100 border-l-4 border-red-500 p-2 mb-3"><p class="text-red-700 font-black text-sm uppercase animate-pulse"><i class="fa-solid fa-triangle-exclamation"></i> WARNING: JANGAN LUPA WHERE!</p><p class="text-xs text-red-600 mt-1">Jika kamu menjalankan UPDATE tanpa WHERE, <b>seluruh data di tabel akan berubah!</b></p></div><div class="bg-black text-purple-400 p-3 text-xs rounded border-2 border-gray-700 mb-3 shadow-inner font-mono"><span class="text-gray-500">// Raw SQL Update:</span><br>UPDATE tabel SET kolom = \'nilai\' WHERE id = \'1\';</div>',
                'instruksi' => 'Tulis mysqli_query untuk UPDATE tabel "buku", SET stok = \'$stok\', WHERE id = \'$id\'.',
                'kode_harapan' => 'mysqli_query($conn, "UPDATE buku SET stok = \'$stok\' WHERE id = \'$id\'");',
                'hint' => 'mysqli_query($conn, "UPDATE ... SET ... = \'...\' WHERE ... = \'...\'");',
            ],
            5 => [
                'judul' => 'STAGE 5: DELETE (MENGHAPUS DATA)',
                'teks' => '<p class="mb-3 text-gray-800">Menghapus data juga sangat bergantung pada klausa <code>WHERE</code>. Biasanya, ID dikirim melalui URL dan ditangkap dengan metode <code>GET</code>.</p><div class="bg-black text-pink-400 p-3 text-xs rounded border-2 border-gray-700 mb-3 shadow-inner font-mono"><span class="text-gray-500">&lt;!-- 1. Tombol Hapus HTML --&gt;</span><br>&lt;a href="hapus.php?id=5"&gt;Hapus Data&lt;/a&gt;<br><br><span class="text-gray-500">// 2. Tangkap URL & Delete (PHP)</span><br>$id = $_GET[\'id\'];<br>DELETE FROM tabel WHERE id = \'$id\';</div>',
                'instruksi' => 'Tulis mysqli_query dengan perintah DELETE FROM tabel "buku" WHERE id = \'$id\'.',
                'kode_harapan' => 'mysqli_query($conn, "DELETE FROM buku WHERE id = \'$id\'");',
                'hint' => 'mysqli_query($conn, "DELETE FROM ... WHERE ... = \'...\'");',
            ],
        ];
    }

    /**
     * Kumpulan Materi & Step Project Workspace (Multi-file)
     */
    private function getProjectPerpustakaan()
    {
        return [
            'koneksi.php' => [
                'order' => 1,
                'judul' => 'Koneksi Database',
                'teks' => 'Langkah pertama adalah membuat file konfigurasi. File <code>koneksi.php</code> ini nantinya akan di-include ke semua file lain agar terhubung ke database MySQL.',
                'instruksi' => 'Buat variabel $conn dan panggil fungsi mysqli_connect() ke database "perpustakaan" dengan user "root" dan password kosong ("").',
                'kode_harapan' => '<?php $conn = mysqli_connect("localhost", "root", "", "perpustakaan"); ?>',
                'hint' => "<?php\n\$conn = mysqli_connect(\"...\", \"...\", \"\", \"...\");\n?>",
            ],

            'index.php' => [
                'order' => 2,
                'judul' => 'Read: Menampilkan Data',
                'teks' => 'File utama untuk menampilkan daftar buku. Di file ini kita harus memanggil <code>koneksi.php</code> lalu mengeksekusi query <code>SELECT</code>.',
                'instruksi' => 'Tulis query untuk mengambil semua data dari tabel "buku" dan simpan ke variabel $query.',
                'kode_harapan' => '$query = mysqli_query($conn, "SELECT * FROM buku");',
                'hint' => "<!DOCTYPE html>\n<html>\n<head><title>Data Buku</title></head>\n<body>\n    <h2>Daftar Buku</h2>\n    <table border=\"1\">\n        <tr><th>Judul</th><th>Penulis</th></tr>\n        \n    </table>\n\n    <?php\n    include 'koneksi.php';\n    \$query = mysqli_query(\$conn, \"SELECT ... FROM ...\");\n    ?>\n</body>\n</html>",
            ],

            'tambah.php' => [
                'order' => 3,
                'judul' => 'Create: Menambah Data',
                'teks' => 'File ini bertugas menangkap data dari form (via <code>$_POST</code>) dan menyimpannya ke tabel buku.',
                'instruksi' => 'Eksekusi query INSERT INTO ke tabel "buku". Isi kolom judul, penulis, tahun_terbit, dan stok.',
                'kode_harapan' => 'mysqli_query($conn, "INSERT INTO buku (judul, penulis, tahun_terbit, stok) VALUES (\'$judul\', \'$penulis\', \'$tahun\', \'$stok\')");',
                'hint' => "<!DOCTYPE html>\n<html>\n<head><title>Tambah Buku</title></head>\n<body>\n    <h2>Tambah Buku</h2>\n    <form method=\"POST\">\n        <input type=\"text\" name=\"judul\" placeholder=\"Judul Buku\"><br>\n        <input type=\"text\" name=\"penulis\" placeholder=\"Penulis\"><br>\n        <button type=\"submit\">Simpan</button>\n    </form>\n\n    <?php\n    include 'koneksi.php';\n    // \$judul = \$_POST['judul']; ...\n    mysqli_query(\$conn, \"INSERT INTO ... (..., ...) VALUES ('...', '...')\");\n    ?>\n</body>\n</html>",
            ],

            'edit.php' => [
                'order' => 4,
                'judul' => 'Update: Mengubah Data',
                'teks' => 'Hati-hati saat mengedit data! Pastikan kamu menyertakan klausa <code>WHERE</code> berdasarkan <code>$id</code> agar tidak merusak seluruh isi tabel.',
                'instruksi' => 'Tulis query UPDATE untuk tabel "buku". Set stok = \'$stok_baru\' di mana id = \'$id\'.',
                'kode_harapan' => 'mysqli_query($conn, "UPDATE buku SET stok=\'$stok_baru\' WHERE id=\'$id\'");',
                'hint' => "<?php\ninclude 'koneksi.php';\nmysqli_query(\$conn, \"UPDATE ... SET ...='...' WHERE ...='...'\");\n?>",
            ],

            'hapus.php' => [
                'order' => 5,
                'judul' => 'Delete: Menghapus Data',
                'teks' => 'File ini mengeksekusi penghapusan data berdasarkan ID yang dikirim melalui URL (metode <code>$_GET</code>).',
                'instruksi' => 'Tulis query eksekusi DELETE FROM tabel "buku" berdasarkan parameter id = \'$id\'.',
                'kode_harapan' => 'mysqli_query($conn, "DELETE FROM buku WHERE id=\'$id\'");',
                'hint' => "<?php\ninclude 'koneksi.php';\nmysqli_query(\$conn, \"DELETE FROM ... WHERE ...='...'\");\n?>",
            ],
        ];
    }
}
