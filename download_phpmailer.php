<?php
/**
 * Script untuk mengunduh PHPMailer tanpa Composer & Terminal
 */
$dir = __DIR__ . '/lib/PHPMailer/src';

echo "<h3>Memulai Unduhan PHPMailer...</h3>";

if (!is_dir($dir)) {
    if (mkdir($dir, 0777, true)) {
        echo "<p style='color:green;'>✓ Folder lib/PHPMailer/src berhasil dibuat.</p>";
    } else {
        echo "<p style='color:red;'>✗ Gagal membuat folder. Pastikan folder project memiliki izin menulis (write permission).</p>";
        exit;
    }
}

$files = [
    'Exception.php' => 'https://raw.githubusercontent.com/PHPMailer/PHPMailer/master/src/Exception.php',
    'PHPMailer.php' => 'https://raw.githubusercontent.com/PHPMailer/PHPMailer/master/src/PHPMailer.php',
    'SMTP.php'      => 'https://raw.githubusercontent.com/PHPMailer/PHPMailer/master/src/SMTP.php'
];

function downloadWithCurl(string $url): string|false {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // Bypass SSL verification untuk server lokal
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);
    $result = curl_exec($ch);
    curl_close($ch);
    return $result;
}

$successCount = 0;
foreach ($files as $name => $url) {
    echo "Mengunduh $name... ";
    $content = downloadWithCurl($url);
    if ($content !== false && $content !== '' && !str_contains($content, '404: Not Found')) {
        file_put_contents($dir . '/' . $name, $content);
        echo "<span style='color:green;'>Berhasil!</span><br>";
        $successCount++;
    } else {
        echo "<span style='color:red;'>Gagal mengunduh (404 atau Koneksi Terputus).</span><br>";
    }
}

if ($successCount === 3) {
    echo "<h4><span style='color:green;'>✓ PHPMailer berhasil diunduh sepenuhnya!</span></h4>";
    echo "<p>Anda sekarang dapat menghapus file <code>download_phpmailer.php</code> ini.</p>";
} else {
    echo "<h4><span style='color:red;'>✗ Pengunduhan belum lengkap. Silakan muat ulang halaman ini.</span></h4>";
}
