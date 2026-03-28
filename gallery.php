<?php
include "includes/db.php";
include "includes/header.php";

// If a category is passed, show photos
if (isset($_GET['category'])) {
    $selected_category = mysqli_real_escape_string($conn, $_GET['category']);
    $result = mysqli_query($conn, "SELECT * FROM photos WHERE category = '$selected_category' ORDER BY uploaded_at DESC");
    echo "<h2 style='margin-bottom: 20px;'>Category: " . ucfirst(htmlspecialchars($selected_category)) . "</h2>";
} else {
    // If no category selected, show category cards first
    ?>
    <section class="categories" id="gallery-categories">
        <h2 class="heading">Browse by Category</h2>
        <div class="category-wrapper">
            <a href="animal.php" class="category-card" style="background-image: url('uploads/animal1.jpg');">
                <span class="category-name">Animal</span>
            </a>
            <a href="people.php" class="category-card" style="background-image: url('uploads/people1.jpg');">
                <span class="category-name">People</span>
            </a>
            <a href="nature.php" class="category-card" style="background-image: url('uploads/nature1.jpg');">
                <span class="category-name">Nature</span>
            </a>
            <a href="heritage.php" class="category-card" style="background-image: url('uploads/heritage1.jpeg');">
                <span class="category-name">Heritage</span>
            </a>
            <a href="street.php" class="category-card" style="background-image: url('uploads/street1.png');">
                <span class="category-name">Street</span>
            </a>
        </div>
    </section>
    <?php
    include "includes/footer.php";
    exit();
}
