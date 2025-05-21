    </div>

    <footer class="footer-absolute">
        <div class="footer-bottom">
            <div class="container">
                <div class="row">
                    <div class="col-md-6">
                        <p class="mb-0">&copy; <?= date('Y'); ?> ITR RSUD H. Abdul Aziz Marabahan. All Rights Reserved.</p>
                    </div>
                    <div class="col-md-6 text-md-right">
                        <p class="mb-0">Halaman diperbarui setiap 30 detik | <span class="last-updated"></span></p>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        // Menampilkan waktu terakhir diperbarui
        document.addEventListener('DOMContentLoaded', function() {
            // Update waktu
            function updateTime() {
                var now = new Date();
                var timeString = now.getHours().toString().padStart(2, '0') + ':' +
                                now.getMinutes().toString().padStart(2, '0') + ':' +
                                now.getSeconds().toString().padStart(2, '0');

                var elements = document.getElementsByClassName('last-updated');
                for (var i = 0; i < elements.length; i++) {
                    elements[i].textContent = timeString;
                }
            }

            // Update waktu setiap detik
            updateTime();
            setInterval(updateTime, 1000);

            // Tambahkan kelas active ke link navigasi yang aktif
            var currentLocation = window.location.pathname;
            var navLinks = document.querySelectorAll('.navbar-nav .nav-link');

            navLinks.forEach(function(link) {
                var linkPath = link.getAttribute('href').split('?')[0];
                if (currentLocation.includes(linkPath) && linkPath !== '/') {
                    link.classList.add('active');
                }
            });

            // Tambahkan efek pulse ke badge status
            var statusBadges = document.querySelectorAll('.status-badge');
            statusBadges.forEach(function(badge) {
                if (badge.classList.contains('badge-primary')) {
                    badge.classList.add('pulse');
                }
            });
        });
    </script>

    <style>
        /* Footer Styles */
        html {
            position: relative;
            min-height: 100%;
        }

        body {
            margin-bottom: 60px; /* Height of the footer */
        }

        .footer-absolute {
            position: absolute;
            bottom: 0;
            width: 100%;
            height: 60px; /* Set the fixed height of the footer here */
            left: 0;
        }

        .footer-bottom {
            background-color: var(--teal-dark);
            color: white;
            padding: 15px 0;
            width: 100%;
        }

        .footer-bottom p {
            margin: 0;
        }

        /* Animations */
        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.05); }
            100% { transform: scale(1); }
        }

        .pulse {
            animation: pulse 2s infinite;
        }
    </style>
</body>
</html>
