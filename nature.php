<?php
include "includes/db.php";
include "includes/header.php";

// Initialize cart if not already set
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

if (isset($_POST['add_to_cart']) && isset($_SESSION['user_id'])) {
    $photoId = $_POST['photo_id'];
    $photoTitle = $_POST['photo_title'];
    $photoPrice = $_POST['photo_price'];
    $photoImage = $_POST['photo_image_path'];
    $userId = $_SESSION['user_id']; // Assuming user ID is stored in session
    $printSize = $_POST['print_size'];
    $printType = $_POST['print_type'];

    // Add to session cart
    $_SESSION['cart'][$photoId] = [
        'title' => $photoTitle,
        'price' => $photoPrice,
        'image_path' => $photoImage,
        'quantity' => 1,
        'print_size' => $printSize, // Uncomment if you want to store print size
        'print_type' => $printType  // Uncomment if you want to store print type
    ];

    // Add to database cart table or update if exists
    $query = "INSERT INTO cart (photo_id, title, price, image_path, quantity, user_id, print_size, print_type) 
              VALUES ($photoId, '$photoTitle', $photoPrice, '$photoImage', 1, $userId, '$printSize', '$printType')
              ON DUPLICATE KEY UPDATE quantity = quantity + 1";
    
    mysqli_query($conn, $query);
}

// Check if the buy now request is made
if (isset($_POST['buy_now']) && isset($_SESSION['user_id'])) {
    $photoId = (int)$_POST['photo_id'];
    $photoTitle = $_POST['photo_title'];
    $photoPrice = (int)$_POST['photo_price'];
    $photoImage = $_POST['photo_image_path'];
    $userId = (int)$_SESSION['user_id'];
    $printSize = $_POST['print_size'];
    $printType = $_POST['print_type'];

    // Add the item to the cart
    $_SESSION['cart'][$photoId] = [
        'title' => $photoTitle,
        'price' => $photoPrice,
        'image' => $photoImage,
        'quantity' => 1,
        'print_size' => $printSize, // Uncomment if you want to store print size
        'print_type' => $printType  // Uncomment if you want to store print type
    ];

    // Insert into the database
    $insertQuery = "INSERT INTO cart (photo_id, title, price, image_path, quantity, user_id, print_size, print_type)
                    VALUES ($photoId, '$photoTitle', $photoPrice, '$photoImage', 1, $userId, '$printSize', '$printType')
                    ON DUPLICATE KEY UPDATE quantity = quantity + 1";
    mysqli_query($conn, $insertQuery);

    // Redirect to the cart page
    header("Location: cart.php");
    exit(); // Make sure to exit after redirection
}

$category = "nature";

$result = mysqli_query($conn, "SELECT * FROM photos WHERE category = '$category' ORDER BY uploaded_at DESC");
?>

<h2 class="heading" style="text-align:left; margin: 5px 200px;">Nature Photography</h2>

<div class="gallery-container">
<?php
if (mysqli_num_rows($result) > 0):
    while ($row = mysqli_fetch_assoc($result)): ?>
        <div class="photo-card">
            <div class="photo-zoom">
                <img src="<?php echo $row['image_path']; ?>" alt="<?php echo $row['title']; ?>" class="zoom-img">
            </div>
            <h4><?php echo htmlspecialchars($row['title']); ?></h4>
            <p>Rs. <?php echo number_format($row['price'], 2); ?></p>
            <p class="stock <?php echo $row['stock'] == 0 ? 'out' : ''; ?>">
                Stock: <?php echo $row['stock']; ?>
            </p>
            <?php if ($row['stock'] > 0): ?>
                <form method="post" action="" class="inline-form" onsubmit="return handleFormSubmit(event)">
                    <input type="hidden" name="photo_id" value="<?php echo $row['id']; ?>">
                    <input type="hidden" name="photo_title" value="<?php echo htmlspecialchars($row['title']); ?>">
                    <input type="hidden" name="photo_price" value="<?php echo $row['price']; ?>">
                    <input type="hidden" name="photo_image_path" value="<?php echo $row['image_path']; ?>">

                     <div class="print-option">
                        <label for="print_size">Size:</label>
                        <select name="print_size" required>
                            <option value="">Select Size</option>
                            <option value="4x6">4x6</option>
                            <option value="5x7">5x7(+Rs.20)</option>
                            <option value="A4">A4(+Rs.50)</option>
                            <option value="A3">A3(+Rs.100)</option>
                        </select>
                    </div>

                    <div class="print-option">
                        <label for="print_type">Type:</label>
                        <select name="print_type" required>
                            <option value="">Select Type</option>
                            <option value="Glossy">Glossy</option>
                            <option value="Matte">Matte(+Rs.20)</option>
                            <option value="Canvas">Canvas(+Rs.50)</option>
                        </select>
                    </div>

                    <div class="button-row">
                        <button type="submit" class="btn" name="add_to_cart">Add To Cart</button>
                        <button type="submit" class="btn" name="buy_now">Buy Now</button>
                    </div>
                </form>
            <?php else: ?>
                <button class="sold-out-btn" disabled>Sold Out</button>
            <?php endif; ?>
        </div>
<?php endwhile;
else: ?>
    <p class="message">No nature photos found.</p>
<?php endif; ?>
</div>

<!-- Lightbox Overlay -->
<div id="lightbox" class="lightbox">
    <span class="close-lightbox">&times;</span>
    <img class="lightbox-img" src="" alt="Zoomed Image">
</div>

<?php include "includes/footer.php"; ?>

<script>
  function handleFormSubmit(event) {
    const isLoggedIn = <?php echo isset($_SESSION['user_id']) ? 'true' : 'false'; ?>;

    const clickedButton = document.activeElement.name;

    if (!isLoggedIn) {
      alert("Please log in");
      event.preventDefault();
      return false;
    }

    if (clickedButton === "add_to_cart") {
      alert("Product has been added");
    }

    // For "buy_now", do nothing — allow normal form submission
    return true;
  }
</script>


<!-- Lightbox Script -->
<script>
    const lightbox = document.getElementById("lightbox");
    const lightboxImg = document.querySelector(".lightbox-img");
    const closeBtn = document.querySelector(".close-lightbox");

    document.querySelectorAll(".zoom-img").forEach(img => {
        img.addEventListener("click", () => {
            lightbox.style.display = "flex";
            lightboxImg.src = img.src;
        });
    });

    closeBtn.addEventListener("click", () => {
        lightbox.style.display = "none";
    });

    lightbox.addEventListener("click", (e) => {
        if (e.target === lightbox) {
            lightbox.style.display = "none";
        }
    });
</script>

