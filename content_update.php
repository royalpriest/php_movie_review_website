<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}
$pageTitle = "Update Movies reviews or logs";
$pageDescription = "This page allows users to update movies reviews or logs";

require './inc/header.php';
require_once './classes/database.php';
require_once './classes/content.php';

$db = (new Database())->connect();
$content = new Content($db);


// this is to get the current movie information that is to be edited
$movie = $content->getById($_GET['id'] ?? 0);
if (!$movie) {
    header("Location: index.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $year = $_POST['year'];
    $director = $_POST['director'];
    $genre = $_POST['genre'];
    $poster = $movie['poster'];

    // Handle image upload
    if (!empty($_FILES['poster']['name'])) {
        $poster = time() . '_' . basename($_FILES['poster']['name']);
        $target_dir = "uploads/movies/";
        $target_file = $target_dir . $poster;

        // Check if file is an actual image
        $check = getimagesize($_FILES['poster']['tmp_name']);
        if($check === false) {
            $error = "File is not an image.";
        } elseif (!move_uploaded_file($_FILES['poster']['tmp_name'], $target_file)) {
            $error = "Sorry, there was an error uploading your file.";
        }
    }

    if (!isset($error)) {
        if ($content->update($movie['id'], $title, $description, $poster, $year, $director, $genre)) {
            $_SESSION['message'] = "Movie updated successfully!";
            header("Location: index.php");
            exit;
        } else {
            $error = "Failed to update movie.";
        }
    }
}
?>

    <section class="form-container">
        <div class="form-card">
            <div class="form-header">
                <h2>Edit Movie</h2>
            </div>

            <?php if (isset($error)): ?>
                <div class="form-error"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>

            <form method="POST" enctype="multipart/form-data" class="movie-form">
                <div class="form-info">
                    <label for="title">Movie Title*</label>
                    <input type="text" id="title" name="title" value="<?= htmlspecialchars($movie['title']) ?>" required>
                </div>

                <div class="form-info">
                    <label for="year">Release Year*</label>
                    <input type="number" id="year" name="year" min="1888" max="<?= date('Y') + 5 ?>"
                           value="<?= htmlspecialchars($movie['year']) ?>" required>
                </div>

                <div class="form-info">
                    <label for="director">Director*</label>
                    <input type="text" id="director" name="director" value="<?= htmlspecialchars($movie['director']) ?>" required>
                </div>

                <div class="form-info">
                    <label for="description">Description*</label>
                    <textarea id="description" name="description" rows="5" required><?= htmlspecialchars($movie['description']) ?></textarea>
                </div>

                <div class="form-info">
                    <label for="genre">Genre*</label>
                    <select id="genre" name="genre" required>
                        <option value="">Select a genre</option>
                        <option value="Action" <?= ($movie['genre'] === 'Action') ? 'selected' : '' ?>>Action</option>
                        <option value="Adventure" <?= ($movie['genre'] === 'Adventure') ? 'selected' : '' ?>>Adventure</option>
                        <option value="Animation" <?= ($movie['genre'] === 'Animation') ? 'selected' : '' ?>>Animation</option>
                        <option value="Comedy" <?= ($movie['genre'] === 'Comedy') ? 'selected' : '' ?>>Comedy</option>
                        <option value="Crime" <?= ($movie['genre'] === 'Crime') ? 'selected' : '' ?>>Crime</option>
                        <option value="Documentary" <?= ($movie['genre'] === 'Documentary') ? 'selected' : '' ?>>Documentary</option>
                        <option value="Drama" <?= ($movie['genre'] === 'Drama') ? 'selected' : '' ?>>Drama</option>
                        <option value="Fantasy" <?= ($movie['genre'] === 'Fantasy') ? 'selected' : '' ?>>Fantasy</option>
                        <option value="Horror" <?= ($movie['genre'] === 'Horror') ? 'selected' : '' ?>>Horror</option>
                        <option value="Mystery" <?= ($movie['genre'] === 'Mystery') ? 'selected' : '' ?>>Mystery</option>
                        <option value="Romance" <?= ($movie['genre'] === 'Romance') ? 'selected' : '' ?>>Romance</option>
                        <option value="Sci-Fi" <?= ($movie['genre'] === 'Sci-Fi') ? 'selected' : '' ?>>Sci-Fi</option>
                        <option value="Thriller" <?= ($movie['genre'] === 'Thriller') ? 'selected' : '' ?>>Thriller</option>
                        <option value="Western" <?= ($movie['genre'] === 'Western') ? 'selected' : '' ?>>Western</option>
                        <option value="Other" <?= ($movie['genre'] === 'Other') ? 'selected' : '' ?>>Other</option>
                    </select>
                </div>

                <div class="form-info">
                    <label>Current Poster:</label>
                    <?php if ($movie['poster']): ?>
                        <img src="uploads/movies/<?= htmlspecialchars($movie['poster']) ?>" class="current-poster" width="200">
                        <small>Leave blank to keep current poster</small>
                    <?php endif; ?>

                    <label for="poster">New Poster (optional):</label>
                    <input type="file" id="poster" name="poster" accept="image/*">
                </div>

                <div class="form-action">
                    <button type="submit" class="btn-submit">Update Movie</button>
                    <a href="index.php" class="btn-cancel">Cancel</a>
                </div>
            </form>
        </div>
    </section>

<?php require './inc/footer.php'; ?>