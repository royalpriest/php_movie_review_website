<?php
session_start();
$pageTitle = "Login Page";
$pageDescription = "This page will allow the users to login into our application.";

require './inc/header.php';
require './classes/database.php';
require './classes/user.php';
require './classes/validate_info.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $db = (new Database())->connect();
    $user = new User($db);

    $loginUser = $user->login($_POST['email'], $_POST['password']);
    if ($loginUser) {
        $_SESSION['user_id'] = $loginUser['id'];
        $_SESSION['username'] = $loginUser['username'];
        $_SESSION['email'] = $loginUser['email'];
        $_SESSION['is_admin'] = $loginUser['is_admin']; // Added admin flag to session
        header("Location: Dashboard.php");
        exit;
    } else {
        $error = "Invalid Credentials";
    }
}
?>

    <section class="form-container">
        <div class="form-card">
            <div class="form-header">
                <h2>Welcome Back</h2>
            </div>

            <?php if (isset($error)): ?>
                <div class="form-error"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>

            <form method="post" action="login.php">
                <!-- Email field -->
                <div class="form-info">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" placeholder="john@example.com" required>
                </div>

                <!-- Password field -->
                <div class="form-info">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" required>
                </div>

                <div class="form-action">
                    <button type="submit" class="btn-submit">Login</button>
                </div>


                <!-- Register link -->
                <div class="form-footer">
                    <p>Don't have an account? <a href="register.php">Register here</a></p>
                </div>
            </form>
        </div>
    </section>

<?php require './inc/footer.php'; ?>