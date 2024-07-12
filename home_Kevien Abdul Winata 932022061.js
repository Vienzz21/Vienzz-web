// Mendapatkan data nama mahasiswa dari server
function getNamaMahasiswa() {
    var xhr = new XMLHttpRequest();
    xhr.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            var response = JSON.parse(this.responseText);
            if (response.success) {
                // Jika permintaan berhasil, tampilkan nama mahasiswa di halaman HTML
                var namaMahasiswa = response.firstname + " " + response.lastname;
                document.getElementById("namaMahasiswa").innerText = namaMahasiswa;
            } else {
                console.error("Gagal mengambil data nama mahasiswa:", response.message);
            }
        }
    };
    xhr.open("GET", "homepage_Kevien Abdul Winata 932022061.php", true);
    xhr.send();
}

// Panggil fungsi untuk mendapatkan data nama mahasiswa saat halaman dimuat
window.onload = function() {
    getNamaMahasiswa();
};
