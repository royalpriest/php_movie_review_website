<?php
session_start();

$pageTitle = "Create Movies reviews or logs";
$pageDescription = "This page allows users to create movies reviews or logs";



require './inc/header.php';
require './classes/database.php';
require './classes/content.php';

$db = (new Database())->connect();
$content = new Content($db);

// this is for the handling of submission through the forma]s
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $year = $_POST['year'];
    $director = $_POST['director'];
    $genre = $_POST['genre'];
    $poster = '';


    $user_id = null;
    if (isset($_SESSION['user_id'])) {
        // To check if  the user exists in database
        $stmt = $db->prepare("SELECT id FROM users WHERE id = ?");
        $stmt->execute([$_SESSION['user_id']]);
        $user_id = $stmt->fetch() ? $_SESSION['user_id'] : null;
    }

    //this is for the upload of images
    if (!empty($_FILES['poster']['name'])) {
        $poster = time() . '_' . basename($_FILES['poster']['name']);
        $target_dir = "uploads/movies/";
        $target_file = $target_dir . $poster;

        $check = getimagesize($_FILES['poster']['tmp_name']);
        if($check === false) {
            $error = "File is not an image.";
        } elseif (!move_uploaded_file($_FILES['poster']['tmp_name'], $target_file)) {
            $error = "Sorry, there was an error uploading your file.";
        }
    }

//    checking for errors before updating the movie
    if (!isset($error)) {
        if ($content->create($user_id, $title, $description, $poster, $year, $director, $genre)) {
            $_SESSION['message'] = "Movie added successfully!";
            header("Location: index.php");
            exit;
        } else {
            $error = "Failed to create movie post.";
        }
    }
}
?>

    <section class="form-container">
        <div class="form-card">
            <div class="form-header">
                <h2>Add New Movie</h2>
                <?php if (!isset($_SESSION['user_id'])): ?>
                <?php endif; ?>
            </div>

<!--            This is to display a message for any error that occurs-->
            <?php if (isset($error)): ?>
                <div class="form-error"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>


<!--            Movie Creation Form-->
            <form method="POST" enctype="multipart/form-data" class="movie-form">
                <div class="form-info">
                    <label for="title">Movie Title*</label>
                    <input type="text" id="title" name="title" required>
                </div>

                <div class="form-info">
                    <label for="year">Release Year*</label>
                    <input type="number" id="year" name="year" min="1888" max="<?= date('Y') + 5 ?>" required>
                </div>

                <div class="form-info">
                    <label for="director">Director*</label>
                    <input type="text" id="director" name="director" required>
                </div>

                <div class="form-info">
                    <label for="description">Description*</label>
                    <textarea id="description" name="description" rows="5" required></textarea>
                </div>
<!-- for user to choose from different options-->
                <div class="form-info">
                    <label for="genre">Genre*</label>
                    <select id="genre" name="genre" required>
                        <option value="">Select a genre</option>
                        <option value="Action">Action</option>
                        <option value="Adventure">Adventure</option>
                        <option value="Animation">Animation</option>
                        <option value="Comedy">Comedy</option>
                        <option value="Crime">Crime</option>
                        <option value="Documentary">Documentary</option>
                        <option value="Drama">Drama</option>
                        <option value="Fantasy">Fantasy</option>
                        <option value="Horror">Horror</option>
                        <option value="Mystery">Mystery</option>
                        <option value="Romance">Romance</option>
                        <option value="Sci-Fi">Sci-Fi</option>
                        <option value="Thriller">Thriller</option>
                        <option value="Western">Western</option>
                        <option value="Other">Other</option>
                    </select>
                </div>

                <div class="form-info">
                    <label for="poster">Movie Poster (optional)</label>
                    <input type="file" id="poster" name="poster" accept="image/*">
                </div>

                <div class="form-action">
                    <button type="submit" class="btn-submit">Add Movie</button>
                    <a href="index.php" class="btn-cancel">Cancel</a>
                </div>
            </form>
        </div>
    </section>

<?php require './inc/footer.php'; ?>