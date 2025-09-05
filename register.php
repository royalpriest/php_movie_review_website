<?php
$pageTitle = "User Registration Page";
$pageDescription = "This page allows the user to register";

require './inc/header.php';
require './classes/database.php';
require './classes/user.php';
require './classes/validate_info.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $age = $_POST['age'];
    $profile_picture = '';

    // Image upload logic (unchanged)
    if (!empty($_FILES['profile_picture']['name'])) {
        $profile_picture = time() . '_' . basename($_FILES['profile_picture']['name']);
        move_uploaded_file($_FILES['profile_picture']['tmp_name'], "uploads/" . $profile_picture);
    }

    // Password match check (unchanged)
    if ($password !== $confirm_password) {
        $error = "Passwords do not match";
    } else {
        $db = (new Database())->connect();
        $user = new User($db);

        // Registration with only fields that exist in the table (unchanged logic flow)
        if ($user->register($name, $email, $password, $profile_picture, $age)) {
            header("Location: login.php");
            exit;
        } else {
            $error = "Registration Error - Username or email already exists";
        }
    }
}
?>

    <section class="form-container">
        <div class="form-card">
            <div class="form-header">
                <h2>Create Your CineCove Account</h2>
            </div>

            <?php if (isset($error)): ?>
                <div class="form-error"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>

            <form method="post" action="register.php" enctype="multipart/form-data">
                <!-- Name field (unchanged) -->
                <div class="form-info">
                    <label for="name">Name</label>
                    <input type="text" id="name" name="name" placeholder="Peter Quill" required>
                </div>

                <!-- Email field (unchanged) -->
                <div class="form-info">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" placeholder="peter@gmail.com" required>
                </div>

                <!-- Age field (unchanged) -->
                <div class="form-info">
                    <label for="age">Age</label>
                    <input type="number" id="age" name="age" min="13" max="120" required>
                </div>

                <!-- Password field (unchanged) -->
                <div class="form-info">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" required>
                </div>

                <!-- Confirm Password field (unchanged) -->
                <div class="form-info">
                    <label for="confirm_password">Confirm Password</label>
                    <input type="password" id="confirm_password" name="confirm_password" required>
                </div>

                <!-- Profile Picture Upload (unchanged) -->
                <div class="form-info">
                    <label for="profile_picture">Profile Picture</label>
                    <input type="file" id="profile_picture" name="profile_picture" accept="image/*">
                </div>

                <!-- Submit Button (unchanged) -->
                <div class="form-action">
                    <button type="submit" class="btn-submit">Register</button>
                </div>

                <!-- Login link (unchanged) -->
                <div class="form-footer">
                    <p>Already have an account? <a href="login.php">Login here</a></p>
                </div>
            </form>
        </div>
    </section>

<?php require './inc/footer.php'; ?>