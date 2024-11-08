<?php
    include_once('inc/header.php');
?>

<section class="home-slider owl-carousel">
  <div class="slider-item bread-item" style="background-image: url('img/bg_1.jpg');" data-stellar-background-ratio="0.5">
    <div class="overlay"></div>
    <div class="container" data-scrollax-parent="true">
      <div class="row slider-text align-items-end">
        <div class="col-md-7 col-sm-12 ftco-animate mb-5">
          <p class="breadcrumbs" data-scrollax=" properties: { translateY: '70%', opacity: 1.6}"><span class="mr-2"><a href="index.php">Home</a></span> <span>Contact Us</span></p>
          <h1 class="mb-3" data-scrollax=" properties: { translateY: '70%', opacity: .9}">Contact Us</h1>
        </div>
      </div>
    </div>
  </div>
</section>
		
<section class="ftco-section contact-section ftco-degree-bg">
  <div class="container">
    <div class="row d-flex mb-5 contact-info">
      <div class="col-md-12 mb-4">
        <h2 class="h4">Contact Information</h2>
      </div>
      <div class="w-100"></div>
      <div class="col-md-3">
        <p><span>Address:</span> 2nd flr. EDP Bldg. San Juan I Gen. Trias, Cavite</p>
      </div>
      <div class="col-md-3">
        <p><span>Phone:</span> <a href="tel://1234567920"> 09954993703</a></p>
      </div>
      <div class="col-md-4">
        <p><span>Email:</span> <a href="mailto:info@yoursite.com"> rossellesantanderdentalclinic@gmail.com</a></p>
      </div>
    </div>
    
    <div class="row block-9">
      <div class="col-md-6">
        <form action="#">
          <div class="form-group">
            <input type="text" class="form-control" placeholder="Your Name">
          </div>
          <div class="form-group">
            <input type="text" class="form-control" placeholder="Your Email">
          </div>
          <div class="form-group">
            <input type="text" class="form-control" placeholder="Subject">
          </div>
          <div class="form-group">
            <textarea name="" id="" cols="30" rows="7" class="form-control" placeholder="Message"></textarea>
          </div>
          <div class="form-group">
            <input type="submit" value="Send Message" class="btn btn-primary py-3 px-5">
          </div>
        </form>
      </div>
      <div class="col-md-6">
        <h2>Find Us</h2>
        <p>We are conveniently located in the heart of Smile City. Visit us for exceptional dental care.</p>
        <div class="map-container">
          <iframe
            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3153.2794415107775!2d-122.419418!3d37.774929!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x8085808c2b4e4b57%3A0x1d6d8b43a3c5d80!2sYour%20Clinic%20Name!5e0!3m2!1sen!2sus!4v1619266462340!5m2!1sen!2sus"
            width="100%" height="450" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
        </div>
      </div>
    </div>
  </div>
</section>

<?php
  include_once('inc/footer.php');
?>
