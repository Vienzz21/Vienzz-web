function updateProgramStudi() {
    var jurusan = document.getElementById("jurusan").value;
    var programStudiSelect = document.getElementById("program_studi");
    programStudiSelect.innerHTML = ""; // Kosongkan opsi sebelumnya

    var programStudiOptions = {
        "Rekayasa Elektro": ["Elektronika", "Teknik Kendali", "Teknik Telekomunikasi"],
        "Teknik Mesin": ["Konversi Energi", "Teknik Material", "Teknik Produksi"],
        "Akuntasi": ["Akuntansi Keuangan", "Akuntansi Manajemen", "Sistem Informasi Akuntansi"],
        "Teknik Sipil": ["Teknik Konstruksi Bangunan", "Teknik Transportasi", "Teknik Sumber Daya Air"],
        "Perhotelan": ["Manajemen Perhotelan", "Kuliner", "Manajemen Resepsionis"]
    };

    if (programStudiOptions[jurusan]) {
        programStudiOptions[jurusan].forEach(function(programStudi) {
            var option = document.createElement("option");
            option.value = programStudi.toLowerCase().replace(/\s+/g, ' ');
            option.text = programStudi;
            programStudiSelect.appendChild(option);
        });
    }
}

document.addEventListener('DOMContentLoaded', () => {
    document.getElementById('form').addEventListener('submit', function(event) {
        event.preventDefault(); // Mencegah pengiriman form

        var jurusan = document.getElementById("jurusan").value;
        var programStudi = document.getElementById("program_studi").value;

        if (jurusan === "" || programStudi === "") {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Data harus di-isi!'
            });
        } else {
            // Kirim data ke server untuk memeriksa keberadaan data sebelum dikirim ke database
            fetch('programstudi.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: new URLSearchParams({ jurusan: jurusan, program_studi: programStudi })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success === false) {
                    if (data.message === 'Sesi telah berakhir') {
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: data.message
                        }).then(() => {
                            window.location.href = data.redirect;
                        });
                    } else if (data.message === 'Anda sudah mengirimkan data untuk jurusan dan program studi ini sebelumnya.') {
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: 'Akun anda sudah terdaftar'
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: data.message
                        });
                    }
                } else {
                    // Pengiriman berhasil, tampilkan pemberitahuan
                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: 'Data berhasil disimpan.'
                    }).then(() => {
                        // Arahkan ke halaman berkaspendukung.html
                        window.location.href = 'berkaspendukung.html';
                    });
                }
            })
            .catch(error => {
                console.error(error);
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Terjadi kesalahan. Silakan coba lagi.',
                });
            });
        }
    });
});

// Jalankan updateProgramStudi() saat halaman dimuat untuk mengisi opsi awal
window.onload = updateProgramStudi;
