document.addEventListener('DOMContentLoaded', function () {
    let isFileSent = false;

    // Fungsi untuk memeriksa status pengiriman file dari server
    function checkFileSentStatus() {
        return fetch('path_to_your_php_file.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: 'action=check_file_sent'
        })
        .then(response => response.json())
        .then(data => {
            isFileSent = data.fileSent;
        })
        .catch(error => {
            console.error('Error:', error);
        });
    }

    // Dapatkan semua input file
    const fileInputs = document.querySelectorAll('input[type="file"]');

    // Loop melalui setiap input file
    fileInputs.forEach(input => {
        input.addEventListener('change', function() {
            const file = this.files[0];
            if (!file) {
                const form = this.parentNode;
                const previousImage = form.querySelector('img');
                if (previousImage) {
                    previousImage.remove();
                }
                return;
            }

            const fileURL = URL.createObjectURL(file);
            const form = this.parentNode;
            const previousImage = form.querySelector('img');
            if (previousImage) {
                previousImage.remove();
            }

            const img = document.createElement('img');
            img.src = fileURL;
            img.style.position = 'absolute';
            img.style.left = '-10px';
            img.style.top = '-10px';
            img.style.width = '708px';
            img.style.height = '130px';
            img.style.padding = '10px';
            form.appendChild(img);
        });
    });

    const berkasForm = document.getElementById('berkasForm');
    berkasForm.addEventListener('submit', function(event) {
        event.preventDefault();

        const fileInput = berkasForm.querySelector('input[type="file"]');
        if (!fileInput || !fileInput.files || fileInput.files.length === 0) {
            Swal.fire({
                icon: 'error',
                title: 'ERROR',
                text: 'Berkas wajib diisi!'
            });
            return;
        }

        if (isFileSent) {
            Swal.fire({
                icon: 'error',
                title: 'WARNING',
                text: 'Akun anda sudah pernah mengirim file sebelumnya!',
                footer: '<a href="https://example.com/hubungi-kami" style="color: blue;">Hubungi kami</a> jika anda mengalami masalah'
            });
            return;
        }

        kirimFormulir();
    });

    function kirimFormulir() {
        const formData = new FormData(berkasForm);
        fetch(berkasForm.action, {
            method: 'POST',
            body: formData
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Terjadi kesalahan saat memeriksa berkas!');
            }
            return response.text();
        })
        .then(data => {
            console.log(data);
            Swal.fire({
                icon: 'success',
                title: 'Berhasil',
                text: 'Berkas berhasil diunggah dan disimpan!',
            }).then(() => {
                // Redirect to buktipendaftaran.php
                window.location.href = 'buktipendaftaran.php';
            });
            isFileSent = true;
            sessionStorage.setItem('isFileSent', 'true');
        })
        .catch(error => {
            console.error('Error:', error.message);
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: error.message,
                footer: '<a href="https://example.com/hubungi-kami" style="color: blue;">Hubungi kami</a> jika anda mengalami masalah'
            });
        });
    }

    // Periksa status pengiriman file dari server saat halaman dimuat
    checkFileSentStatus().then(() => {
        if (isFileSent) {
            Swal.fire({
                icon: 'error',
                title: 'WARNING',
                text: 'Akun anda sudah pernah mengirim file sebelumnya!',
                footer: '<a href="https://example.com/hubungi-kami" style="color: blue;">Hubungi kami</a> jika anda mengalami masalah'
            });
        }
    });
});
