<?php
    include_once('inc/header.php');
?>
    <main>
        <section class="carousel">
            <div class="carousel-container">
                <div class="carousel-slide">
                    <img src="img/slider1.jpg" alt="Dental Check-Up">
                    <div class="carousel-caption">
                        <h2>Comprehensive Check-Ups</h2>
                        <p>Regular check-ups ensure your dental health is on track. Schedule your appointment today!</p>
                    </div>
                </div>
                <div class="carousel-slide">
                    <img src="img/slider2.jpg" alt="Teeth Whitening">
                    <div class="carousel-caption">
                        <h2>Brighten Your Smile</h2>
                        <p>Our teeth whitening services will give you a brighter, whiter smile in no time.</p>
                    </div>
                </div>
                <div class="carousel-slide">
                    <img src="img/slider3.jpg" alt="Family Dentistry">
                    <div class="carousel-caption">
                        <h2>Family Dentistry</h2>
                        <p>We cater to patients of all ages. Bring the whole family for top-notch dental care.</p>
                    </div>
                </div>
                <!-- Add more slides as needed -->
            </div>
            <button class="carousel-control prev" id="prev-slide">&#10094;</button>
            <button class="carousel-control next" id="next-slide">&#10095;</button>
        </section>
        <section id="services" class="services">
            <h2>Our Services</h2>
            <div class="services-container">
                <div class="service-card">
                    <div class="service-icon">
                        <i class="fa fa-stethoscope fa-3x" aria-hidden="true"></i>
                    </div>
                    <h3>Dental Check-Up</h3>
                    <p>Comprehensive check-ups to keep your smile healthy. Schedule your appointment today!</p>
                    <a href="#" class="learn-more">Learn More</a>
                </div>
                <div class="service-card">
                    <div class="service-icon">
                        <i class="fa fa-teeth fa-3x" aria-hidden="true"></i>
                    </div>
                    <h3>Teeth Whitening</h3>
                    <p>Brighten your smile with our advanced whitening treatments. Achieve a dazzling smile in no time.</p>
                    <a href="#" class="learn-more">Learn More</a>
                </div>
                <div class="service-card">
                    <div class="service-icon">
                        <i class="fa fa-align-left fa-3x" aria-hidden="true"></i>
                    </div>
                    <h3>Orthodontics</h3>
                    <p>Straighten your teeth with personalized orthodontic care. Explore our braces and aligners options.</p>
                    <a href="#" class="learn-more">Learn More</a>
                </div>
                <!-- Add more service cards as needed -->
            </div>
        </section>
        <section class="banner">
            <div class="banner-content">
                <h1>Transform Your Smile with Roselle Santander's Dental Clinic</h1>
                <p>Experience top-notch dental care with our expert team. Book your appointment today!</p>
                <a href="login.php" class="btn-primary">Schedule an Appointment</a>
            </div>
        </section>
        <section class="why-choose-us">
            <h2>Why Choose Us?</h2>
            <ul>
                <li>Experienced Team: Our skilled dentists and hygienists are dedicated to providing top-notch care.</li>
                <li>State-of-the-Art Technology: We use the latest technology and techniques for accurate diagnoses.</li>
                <li>Comfortable Environment: Our clinic is designed for your comfort.</li>
                <li>Flexible Financing Options: Various payment options, including insurance and financing plans.</li>
            </ul>
        </section>

        <section id="map" class="map">
            <h2>Find Us</h2>
            <p>We are conveniently located in the heart of Smile City. Visit us for exceptional dental care.</p>
            <div class="map-container">
                <!-- Replace the src with the iframe embed code from Google Maps -->
                <iframe
                    src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3153.2794415107775!2d-122.419418!3d37.774929!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x8085808c2b4e4b57%3A0x1d6d8b43a3c5d80!2sYour%20Clinic%20Name!5e0!3m2!1sen!2sus!4v1619266462340!5m2!1sen!2sus"
                    width="100%" height="450" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
            </div>
        </section>

    </main>
    <!-- Footer -->
    <?php
        include_once('inc/footer.php');
    ?>
    </body>
</html>