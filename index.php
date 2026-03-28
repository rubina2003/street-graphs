<?php
include "includes/db.php";
include "includes/header.php";
?>

<section class="hero">
    <div class="hero-text">
        <h2>From Street to Frame, Print the Moments That Matter</h2>
        <p>
            Explore a curated gallery of street, nature, and culture photography, now available in high-quality prints delivered to your door.
        </p>

        <div class="hero-buttons">
            <a href="gallery.php" class="btn">Browse Gallery</a>

            <?php if (!isset($_SESSION['user_id'])): ?>
                <a href="register.php" class="btn">Register</a>
            <?php endif; ?>
        </div>
    </div>

     <div class="hero-right">
    <div class="slider-wrapper">
      <input type="radio" name="slider" id="slide1" checked>
      <input type="radio" name="slider" id="slide2">
      <input type="radio" name="slider" id="slide3">
      <input type="radio" name="slider" id="slide4">

      <div class="slides">
        <div class="slide" id="s1">
          <img src="uploads/nature4.jpg" alt="Image 1">
          <label for="slide4" class="prev">&#10094;</label>
          <label for="slide2" class="next">&#10095;</label>
        </div>
        <div class="slide" id="s2">
          <img src="uploads/animal1.jpg" alt="Image 2">
          <label for="slide1" class="prev">&#10094;</label>
          <label for="slide3" class="next">&#10095;</label>
        </div>
        <div class="slide" id="s3">
          <img src="uploads/street8.png" alt="Image 3">
          <label for="slide2" class="prev">&#10094;</label>
          <label for="slide4" class="next">&#10095;</label>
        </div>
        <div class="slide" id="s4">
          <img src="uploads/heritage4.png" alt="Image 4">
          <label for="slide3" class="prev">&#10094;</label>
          <label for="slide1" class="next">&#10095;</label>
        </div>
      </div>
    </div>
  </div>
</section>

    <!-- <div class="hero-image">
        <img src="uploads/street4.jpg" alt="Street Photo">
    </div> -->
</section>


<!-- Categories Section -->
<section class="categories" id="categories">
    <h2 class="heading">Explore By Category</h2>

    <div class="category-wrapper">
        <a href="animal.php" class="category-card" style="background-image: url('uploads/animal1.jpg');">
            <span class="category-name">Animal</span>
        </a>
        <!-- <a href="people.php" class="category-card" style="background-image: url('uploads/people1.jpg');">
            <span class="category-name">People</span>
        </a> -->
        <a href="nature.php" class="category-card" style="background-image: url('uploads/nature1.jpg');">
            <span class="category-name">Nature</span>
        </a>
        <a href="heritage.php" class="category-card" style="background-image: url('uploads/heritage1.jpeg');">
            <span class="category-name">Heritage</span>
        </a>
        <!-- <a href="street.php" class="category-card" style="background-image: url('uploads/street1.png');">
            <span class="category-name">Street</span>
        </a> -->
    </div>

    <a href="gallery.php" class="btn">Explore All</a>
</section>


<!-- About Us Section -->
<section class="about-us">
    <div class="about-container">
        <div class="about-image">
            <img src="uploads/heritage3.png" alt="About StreetGraphs">
        </div>
        <div class="about-text">
            <h2>About StreetGraphs</h2>
            <p>
                StreetGraphs is more than just a photography platform, it's a living gallery of life unfolding on the streets. Our goal is to celebrate the beauty in everyday moments by curating and showcasing high-quality street photography from talented artists across the globe.
            </p>
            <p>
                Whether it's a fleeting glance, a bustling market scene, a quiet alleyway, or a bold cultural festival — each photograph tells a unique story. We believe in the raw, unfiltered power of street photography to capture real life with honesty, emotion, and depth.
            </p>
            <p>
                Join us in exploring the world through a new lens, one that sees magic in the mundane and artistry in the everyday.
            </p>

        </div>
    </div>
</section>

<!-- CTA section -->
<section class="cta-section" style="background-image: url('uploads/nature7.jpg');">
    <div class="cta-overlay">
        <div class="cta-content">
            <h2>Start Collecting Powerful Visual Stories</h2>
            <a href="gallery.php" class="btn">Browse Gallery</a>
        </div>
    </div>
</section>

<?php include "includes/footer.php"; ?>

