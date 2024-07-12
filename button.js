var logoutButton = document.getElementById("logoutButton");

// Tambahkan event listener untuk menangani klik pada tombol Logout
logoutButton.addEventListener("click", function(event) {
    // Mencegah perilaku default dari tag <a> (navigasi ke halaman Logout)
    event.preventDefault();

    // Tampilkan pesan konfirmasi menggunakan SweetAlert2
    Swal.fire({
        title: 'Apakah Anda ingin keluar akun?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Ya, keluar',
        cancelButtonText: 'Tidak'
    }).then((result) => {
        // Jika pengguna menekan tombol "Ya, keluar"
        if (result.isConfirmed) {
            // Arahkan pengguna ke halaman index
            window.location.href = 'index_Muhammad rizal 932022043.html';
        }
    });
});