<?php
// Memuat pustaka FPDF
require('vendor/fpdf.php');

// Fungsi untuk membagi alamat panjang menjadi beberapa baris
function wrapText($text, $length)
{
    $wrapped = wordwrap($text, $length, "\n", true);
    return explode("\n", $wrapped);
}

// Mendapatkan email dari parameter GET
$email = $_GET['email'] ?? null;

// Jika email tidak tersedia atau kosong, keluarkan pesan error
if (!$email) {
    die('Email tidak valid atau tidak ditemukan.');
}

// Koneksi ke database
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "web_sicaper";
$conn = new mysqli($servername, $username, $password, $dbname);

// Memeriksa koneksi
if ($conn->connect_error) {
    die("Koneksi ke database gagal: " . $conn->connect_error);
}

// Query untuk mengambil data siswa berdasarkan email
$stmt_siswa = $conn->prepare("SELECT * FROM data_siswa WHERE email = ?");
$stmt_siswa->bind_param("s", $email);
$stmt_siswa->execute();
$result_siswa = $stmt_siswa->get_result();
$row_siswa = $result_siswa->fetch_assoc();

// Query untuk mengambil data program studi berdasarkan email
$stmt_program_studi = $conn->prepare("SELECT * FROM program_studii WHERE email = ?");
$stmt_program_studi->bind_param("s", $email);
$stmt_program_studi->execute();
$result_program_studi = $stmt_program_studi->get_result();
$row_program_studi = $result_program_studi->fetch_assoc();

// Query untuk mengambil data berkas pendukung berdasarkan email
$stmt_berkas_pendukung = $conn->prepare("SELECT * FROM berkas_pendukung WHERE email = ?");
$stmt_berkas_pendukung->bind_param("s", $email);
$stmt_berkas_pendukung->execute();
$result_berkas_pendukung = $stmt_berkas_pendukung->get_result();
$row_berkas_pendukung = $result_berkas_pendukung->fetch_assoc();

// Memeriksa apakah data siswa, program studi, dan berkas pendukung ditemukan
if (!$row_siswa || !$row_program_studi || !$row_berkas_pendukung) {
    die("Data siswa, program studi, atau berkas pendukung tidak ditemukan.");
}

// Membuat kelas PDF yang diperluas
class PDF extends FPDF
{
    // Path ke gambar latar belakang
    protected $bgPath = '';

    function __construct($bgPath)
    {
        parent::__construct();
        $this->bgPath = $bgPath;
    }

    // Header
    function Header()
    {
        // Menambahkan latar belakang gambar yang sudah diatur opasitasnya
        $this->Image($this->bgPath, 20, 60, 170, 0, '', '', true);
        $this->SetFont('Arial', 'B', 16);
        $this->Cell(0, 10, 'Bukti Pendaftaran', 0, 1, 'C');
        $this->Ln(10);
    }

    // Footer
    function Footer()
    {
        $this->SetY(-15);
        $this->SetFont('Arial', 'I', 8);
        $this->Cell(0, 10, 'Halaman ' . $this->PageNo(), 0, 0, 'C');
    }
}

// Instansiasi PDF
$pdf = new PDF('images/sicaper_transparent.jpg.png');
$pdf->AliasNbPages();
$pdf->AddPage();

// Menampilkan data siswa
$pdf->SetFont('Arial', 'B', 14);
$pdf->Cell(0, 10, 'Informasi Siswa:', 0, 1);
$pdf->SetFont('Arial', '', 12);
$pdf->Cell(60, 10, 'Nama', 1);
$pdf->Cell(60, 10, 'NISN', 1);
$pdf->Cell(70, 10, 'Alamat', 1);
$pdf->Ln();
$pdf->Cell(60, 10, $row_siswa['nama'], 1);
$pdf->Cell(60, 10, $row_siswa['nisn'], 1);

$alamat_lines = wrapText($row_siswa['alamat'], 35);
foreach ($alamat_lines as $i => $line) {
    if ($i > 0) {
        $pdf->Ln();
        $pdf->Cell(120, 10, '', 0); // Empty cells for spacing
    }
    $pdf->Cell(70, 10, $line, 1);
}
$pdf->Ln();
$pdf->Cell(60, 10, 'No. Telpon', 1);
$pdf->Cell(60, 10, 'Asal Sekolah', 1);
$pdf->Cell(70, 10, 'Nilai Akhir', 1);
$pdf->Ln();
$pdf->Cell(60, 10, $row_siswa['no_telpon'], 1);
$pdf->Cell(60, 10, $row_siswa['asal_sekolah'], 1);
$pdf->Cell(70, 10, $row_siswa['nilai_akhir'], 1);

// Menampilkan data program studi
$pdf->Ln(10);
$pdf->SetFont('Arial', 'B', 14);
$pdf->Cell(0, 10, 'Informasi Program Studi:', 0, 1);
$pdf->SetFont('Arial', '', 12);
$pdf->Cell(0, 10, 'Jurusan: ' . $row_program_studi['jurusan'], 0, 1);
$pdf->Cell(0, 10, 'Program Studi: ' . $row_program_studi['program_studi'], 0, 1);

// Menampilkan data berkas pendukung
$pdf->Ln(10);
$pdf->SetFont('Arial', 'B', 14);
$pdf->Cell(0, 10, 'Informasi Berkas Pendukung:', 0, 1);
$pdf->SetFont('Arial', '', 12);

$imageWidth = 60;
$imageHeight = 50;

$pdf->Cell($imageWidth, $imageHeight, 'Ijazah', 1, 0, 'C');
$pdf->Cell($imageWidth, $imageHeight, 'Transkrip Nilai', 1, 0, 'C');
$pdf->Cell($imageWidth + 10, $imageHeight, 'Surat Sehat', 1, 1, 'C');

$pdf->Cell($imageWidth, $imageHeight, '', 1, 0, 'C');
$pdf->Cell($imageWidth, $imageHeight, '', 1, 0, 'C');
$pdf->Cell($imageWidth + 10, $imageHeight, '', 1, 1, 'C');

$pdf->SetY($pdf->GetY() - $imageHeight);

if (file_exists($row_berkas_pendukung['ijazah'])) {
    $pdf->Image($row_berkas_pendukung['ijazah'], $pdf->GetX(), $pdf->GetY(), $imageWidth, $imageHeight);
}
$pdf->SetX($pdf->GetX() + $imageWidth);
if (file_exists($row_berkas_pendukung['transkrip_nilai'])) {
    $pdf->Image($row_berkas_pendukung['transkrip_nilai'], $pdf->GetX(), $pdf->GetY(), $imageWidth, $imageHeight);
}
$pdf->SetX($pdf->GetX() + $imageWidth);
if (file_exists($row_berkas_pendukung['surat_sehat'])) {
    $pdf->Image($row_berkas_pendukung['surat_sehat'], $pdf->GetX(), $pdf->GetY(), $imageWidth + 10, $imageHeight);
}

// Menampilkan footer
$pdf->Ln($imageHeight + 10);
$pdf->SetFont('Arial', 'I', 10);
$pdf->Cell(0, 10, 'Terima kasih telah mendaftar.', 0, 1, 'C');
$pdf->Cell(0, 10, 'Halaman ' . $pdf->PageNo(), 0, 0, 'C');

// Keluarkan file PDF
$pdf->Output();
?>
