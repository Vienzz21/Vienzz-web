document.getElementById('nextButton').addEventListener('click', function() {
    // Tangkap data yang diinput
    var nama = document.getElementById('nama').value;
    var nisn = document.getElementById('nisn').value;
    var alamat = document.getElementById('alamat').value;
    var no_telepon = document.getElementById('no_telepon').value;
    var asal_sekolah = document.getElementById('asal_sekolah').value;
    var nilai_akhir = document.getElementById('nilai_akhir').value;

    // Tampilkan data dalam sebuah pesan konfirmasi dengan SweetAlert
    Swal.fire({
        title: 'Data yang Anda Masukkan',
        html: `
            <p><strong>Nama:</strong> ${nama}</p>
            <p><strong>NISN:</strong> ${nisn}</p>
            <p><strong>Alamat:</strong> ${alamat}</p>
            <p><strong>No Telepon:</strong> ${no_telepon}</p>
            <p><strong>Asal Sekolah:</strong> ${asal_sekolah}</p>
            <p><strong>Nilai Akhir:</strong> ${nilai_akhir}</p>
        `,
        showCancelButton: true,
        confirmButtonText: 'Lanjut ke Program Studi',
        cancelButtonText: 'Periksa Kembali',
        reverseButtons: true,
        showClass: {
            popup: 'animate__animated animate__zoomIn'
        },
        hideClass: {
            popup: 'animate__animated animate__zoomOut'
        }
    }).then((result) => {
        if (result.isConfirmed) {
            // Kirim data ke database atau navigasi ke halaman berikutnya
            var xhr = new XMLHttpRequest();
            xhr.open("POST", "formdatadiri_Kevien Abdul Winata 932022061.php", true);
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            xhr.onreadystatechange = function() {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    try {
                        var response = JSON.parse(xhr.responseText);
                        if (response.exists) {
                            Swal.fire({
                                icon: 'error',
                                title: 'NISN sudah terdaftar',
                                text: 'Harap masukkan NISN yang berbeda.'
                            });
                        } else if (response.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil',
                                text: 'Data berhasil disimpan, akan diarahkan ke halaman Program Studi.',
                                showConfirmButton: false,
                                timer: 1000
                            }).then(() => {
                                window.location.href = 'programstudi.html';
                            });
                        } else if (response.error) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Kesalahan',
                                text: response.error
                            });
                        }
                    } catch (e) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Kesalahan',
                            text: 'Terjadi kesalahan pada server.'
                        });
                    }
                }
            };
            var data = "nama=" + encodeURIComponent(nama) + 
                       "&nisn=" + encodeURIComponent(nisn) + 
                       "&alamat=" + encodeURIComponent(alamat) + 
                       "&no_telepon=" + encodeURIComponent(no_telepon) + 
                       "&asal_sekolah=" + encodeURIComponent(asal_sekolah) + 
                       "&nilai_akhir=" + encodeURIComponent(nilai_akhir);
            xhr.send(data);
        }
    });
});

// Function to check NISN availability
function checkNISN() {
    var nisnInput = document.getElementById('nisn');
    var nisnValue = nisnInput.value;

    // Send AJAX request to check NISN
    var xhr = new XMLHttpRequest();
    xhr.open("POST", "formdatadiri_Kevien Abdul Winata 932022061.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.onreadystatechange = function() {
        if (xhr.readyState === 4 && xhr.status === 200) {
            try {
                var response = JSON.parse(xhr.responseText);
                if (response.exists) {
                    nisnInput.style.borderColor = 'red'; // Change border color to red
                    Swal.fire({
                        icon: 'error',
                        title: 'NISN sudah terdaftar',
                        text: 'Harap masukkan NISN yang berbeda.'
                    });
                } else {
                    nisnInput.style.borderColor = ''; // Reset border color
                }
            } catch (e) {
                Swal.fire({
                    icon: 'error',
                    title: 'Kesalahan',
                    text: 'Terjadi kesalahan pada server.'
                });
            }
        }
    };
    var data = "nisn=" + encodeURIComponent(nisnValue);
    xhr.send(data);
}

// Event listener for NISN input
document.getElementById('nisn').addEventListener('input', checkNISN);
