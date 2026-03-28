<?php
include "includes/db.php";
include "includes/header.php";

$msg = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password'];

    $query = mysqli_query($conn, "SELECT * FROM users WHERE email='$email'");
    $user = mysqli_fetch_assoc($query);

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['name'];
        header("Location: index.php");
        exit();
    } else {
        $msg = "Invalid credentials.";
    }
}
?>

<h2 style="text-align:center; margin:30px;">Login</h2>
<form method="post">
    <label>Email:</label>
    <input type="email" name="email" required>

    <label>Password:</label>
    <input type="password" name="password" required>

    <button type="submit">Login</button>
    <p style="margin-top:20px;">Don't have an account? <a href="register.php">Register here</a></p>
</form>


<?php if ($msg): ?>
    <p class="message"><?php echo $msg; ?></p>
<?php endif; ?>
