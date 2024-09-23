<footer class="text-center text-lg-start bg-body-tertiary text-muted">
    <!-- Section: Social media -->
    <section class="d-flex justify-content-center justify-content-lg-between p-4 border-bottom">
        <!-- Left -->
        <div class="me-5 d-none d-lg-block">
        <span>Get connected with us on social networks:</span>
        </div>
        <!-- Left -->

        <!-- Right -->
        <div>
        <a href="" class="me-4 text-reset">
            <i class="fab fa-facebook-f"></i>
        </a>
        <a href="" class="me-4 text-reset">
            <i class="fab fa-linkedin"></i>
        </a>
        
        </div>
        <!-- Right -->
    </section>
    <!-- Section: Social media -->

    <!-- Section: Links  -->
    <section class="">
        <div class="container text-center text-md-start mt-5">
        <!-- Grid row -->
        <div class="row mt-3">
            <!-- Grid column -->
            <div class="col-md-3 col-lg-4 col-xl-3 mx-auto mb-4">
            <!-- Content -->
            <h6 class="text-uppercase fw-bold mb-3">
                <i class="fas fa-tooth me-3"></i>Roselle Santander Dental Clinic
            </h6>
            <p>
                Here you can use rows and columns to organize your footer content. Lorem ipsum
                dolor sit amet, consectetur adipisicing elit.
            </p>
            </div>
            <!-- Grid column -->

            <!-- Grid column -->
            <div class="col-md-2 col-lg-2 col-xl-2 mx-auto mb-4">
            <!-- Links -->
            <h6 class="text-uppercase fw-bold mb-4">
                Our Sevices
            </h6>
        
            <p>
                <a href="#!" class="text-reset">Braces</a>
            </p>
            <p>
                <a href="#!" class="text-reset">Denture</a>
            </p>
            <p>
                <a href="#!" class="text-reset">Others</a>
            </p>
            </div>
            <!-- Grid column -->

            <!-- Grid column -->
            <div class="col-md-3 col-lg-2 col-xl-2 mx-auto mb-4">
            <!-- Links -->
            <h6 class="text-uppercase fw-bold mb-4">
                Useful links
            </h6>
            <p>
                <a href="#!" class="text-reset">Home</a>
            </p>
            <p>
                <a href="#!" class="text-reset">Book an Appoinment now</a>
            </p>
            </div>
            <!-- Grid column -->

            <!-- Grid column -->
            <div class="col-md-4 col-lg-3 col-xl-3 mx-auto mb-md-0 mb-4">
            <!-- Links -->
            <h6 class="text-uppercase fw-bold mb-4">Contact</h6>
            <p><i class="fas fa-home me-3"></i> Visit Us! 2nd Flr. EDP bldg, San Juan 1 Gen. Trias City, Cavite, PH</p>
            <p>
                <i class="fas fa-envelope me-3"></i>
                info@example.com
            </p>
            <p><i class="fas fa-phone me-3"></i> 0995-499-3703</p>
            <p><i class="fas fa-facebook me-3"></i> <a href="https://www.facebook.com/login/"></a></p>
            </div>
            <!-- Grid column -->
        </div>
        <!-- Grid row -->
        </div>
    </section>
    <!-- Section: Links  -->

    <!-- Copyright -->
    <div class="text-center p-4" style="background-color: rgba(0, 0, 0, 0.05);">
        © 2024 Copyright: for Capstone purpose
    </div>
    <!-- Copyright -->
</footer>

</div>
    <script src="js/bootstrap.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/5.1.3/js/bootstrap.bundle.min.js"></script>
    <script src="js/login.js"></script>
    <script src="js/register.js"></script>
    <script type="text/javascript">
            document.getElementById("login_button").onclick = function () {
                location.href = "login.php";
            };

            document.addEventListener('DOMContentLoaded', function() {
                const slides = document.querySelectorAll('.carousel-slide');
                const nextBtn = document.getElementById('next-slide');
                const prevBtn = document.getElementById('prev-slide');
                let currentIndex = 0;

                function showSlide(index) {
                    if (index >= slides.length) {
                        currentIndex = 0;
                    } else if (index < 0) {
                        currentIndex = slides.length - 1;
                    } else {
                        currentIndex = index;
                    }
                    const offset = -currentIndex * 100;
                    document.querySelector('.carousel-container').style.transform = `translateX(${offset}%)`;
                }

                nextBtn.addEventListener('click', function() {
                    showSlide(currentIndex + 1);
                });

                prevBtn.addEventListener('click', function() {
                    showSlide(currentIndex - 1);
                });

                // Optional: Auto-slide functionality
                setInterval(function() {
                    showSlide(currentIndex + 1);
                }, 5000); // Change slide every 5 seconds
            });
        </script>
</body>
</html>