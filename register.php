<?php
include "includes/db.php";
include "includes/header.php";

$msg = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $check = mysqli_query($conn, "SELECT * FROM users WHERE email='$email'");
    if (mysqli_num_rows($check) > 0) {
        $msg = "Email already registered.";
    } else {
        $sql = "INSERT INTO users (name, email, password) VALUES ('$name', '$email', '$password')";
        if (mysqli_query($conn, $sql)) {
            $msg = "Registration successful!";
        } else {
            $msg = "Error: " . mysqli_error($conn);
        }
    }
}
?>

<h2 style="text-align:center; margin:30px;">User Registration</h2>
<form method="post">
    <label>Name:</label>
    <input type="text" name="name" required>

    <label>Email:</label>
    <input type="email" name="email" required>

    <label>Password:</label>
    <input type="password" name="password" required>

    <button type="submit">Register</button>
    <p style="margin-top: 20px;">Already have an account? <a href="login.php">Login here</a></p>
</form>

<?php if ($msg): ?>
    <p class="message"><?php echo $msg; ?></p>
<?php endif; ?>

