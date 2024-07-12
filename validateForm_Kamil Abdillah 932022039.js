document.getElementById('createForm').addEventListener('submit', function(event) {
    event.preventDefault(); // Mencegah pengiriman formulir standar

    // Validasi formulir sebelum pengiriman
    if (validateForm()) {
        // Kirim data ke server menggunakan fetch API
        fetch('buatakun_Kamil Abdillah 932022039.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: new URLSearchParams(new FormData(this)).toString(),
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'Akun berhasil dibuat',
                    text: 'Anda akan diarahkan ke halaman utama.',
                    confirmButtonText: 'OK'
                }).then(() => {
                    window.location.href = 'index_Muhammad rizal 932022043.html';
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal membuat akun',
                    text: data.message || 'Terjadi kesalahan saat membuat akun.',
                    confirmButtonText: 'OK'
                });
            }
        })
        .catch(error => {
            Swal.fire({
                icon: 'error',
                title: 'Terjadi kesalahan',
                text: 'Silakan coba lagi nanti.',
                confirmButtonText: 'OK'
            });
        });
    }
});

function validateForm() {
    // Mengambil nilai dari input
    var firstName = document.getElementById('firstName').value;
    var lastName = document.getElementById('lastName').value;
    var email = document.getElementById('email').value;
    var password = document.getElementById('password').value;

    // Validasi apakah semua input terisi
    if (firstName === '' || lastName === '' || email === '' || password === '') {
        Swal.fire({
            icon: 'error',
            title: 'Gagal membuat akun',
            text: 'Semua kolom harus diisi.',
            confirmButtonText: 'OK'
        });
        return false; // Menghentikan pengiriman formulir
    }

    // Validasi email yang sudah terdaftar
    // (Di sini Anda perlu mengganti logika validasi dengan logika sesuai dengan backend Anda)
    // Untuk demonstrasi, kita anggap bahwa email "test@example.com" sudah terdaftar
    if (email === 'test@example.com') {
        Swal.fire({
            icon: 'error',
            title: 'Gagal membuat akun',
            text: 'Email sudah terdaftar.',
            confirmButtonText: 'OK'
        });
        return false; // Menghentikan pengiriman formulir
    }

    // Jika semua validasi berhasil, kembalikan true untuk mengizinkan pengiriman formulir
    return true;
}
