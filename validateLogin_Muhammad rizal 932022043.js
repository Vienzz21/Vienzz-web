document.addEventListener('DOMContentLoaded', function() {
    console.log("Document loaded and script executed."); // Debugging

    document.getElementById('createForm').addEventListener('submit', function(event) {
        event.preventDefault(); // Mencegah pengiriman formulir standar

        console.log("Form submitted."); // Debugging

        // Mengambil nilai dari input
        var email = document.getElementById('email').value;
        var password = document.getElementById('password').value;

        console.log("Email:", email); // Debugging
        console.log("Password:", password); // Debugging

        // Kirim permintaan ke server untuk memeriksa akun
        fetch('index_Muhammad rizal 932022043.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: new URLSearchParams({
                email: email,
                password: password
            }).toString(),
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Akun ditemukan, arahkan ke halaman homepage.html
                console.log("Login successful, redirecting to homepage."); // Debugging
                window.location.href = 'homepage_Kevien Abdul Winata 932022061.html';
            } else {
                // Akun tidak ditemukan, tampilkan notifikasi
                Swal.fire({
                    icon: 'error',
                    title: 'Akun Tidak Ditemukan',
                    text: data.message, // Tampilkan pesan dari server
                    confirmButtonText: 'OK'
                });
            }
        })
        .catch(error => {
            console.error('Error:', error); // Debugging
            // Menangani kesalahan saat melakukan permintaan ke server
            Swal.fire({
                icon: 'error',
                title: 'Terjadi Kesalahan',
                text: 'Silakan coba lagi nanti.',
                confirmButtonText: 'OK'
            });
        });
    });
});
