<?php
session_start();
$pageTitle = "CineCove Community";
$pageDescription = "Browse all community movie posts and reviews";
require './inc/header.php';
require './classes/database.php';
require './classes/content.php';

$db = (new Database())->connect();
$content = new Content($db);

$movies = $content->readAll();
?>

    <section class="welcome">
        <div class="welcome-content">
            <h1>Welcome to CineCove</h1>
            <p>At CineCove, we're building a vibrant community for film enthusiasts to share their favorite movies, reviews, and recommendations. Whether you're a casual viewer or a cinephile, this platform lets you discover new films and connect with fellow movie lovers.</p>
            <p>
                Share your thoughts on recent watches, create lists of your favorites, and discuss everything from classic cinema to the latest blockbusters. Join us and be part of a community that celebrates the magic of movies.
            </p>
        </div>
    </section>

<?php if (isset($_SESSION['message'])): ?>
    <div class="alert alert-success"><?= htmlspecialchars($_SESSION['message']) ?></div>
    <?php unset($_SESSION['message']); ?>
<?php endif; ?>

<?php if (isset($_SESSION['error'])): ?>
    <div class="alert alert-danger"><?= htmlspecialchars($_SESSION['error']) ?></div>
    <?php unset($_SESSION['error']); ?>
<?php endif; ?>

    <section class="content-page">
        <div class="content-header">
            <h1>Reel Reviews</h1>
            <a href="content_create.php" class="btn create-btn"> Add Movie</a>
        </div>

        <div class="movie-grid">
            <?php if (empty($movies)): ?>
                <p class="no-content">No movies posted yet. Be the first to share!</p>
            <?php else: ?>
                <?php foreach ($movies as $movie): ?>
                    <article class="movie-card">
                        <?php if ($movie['poster']): ?>
                            <div class="movie-poster">
                                <img src="uploads/movies/<?= htmlspecialchars($movie['poster']) ?>" alt="<?= htmlspecialchars($movie['title']) ?>">
                            </div>
                        <?php else: ?>
                            <div class="movie-poster placeholder">
                                <span>No Poster</span>
                            </div>
                        <?php endif; ?>

                        <div class="movie-body">
                            <h2><?= htmlspecialchars($movie['title']) ?> (<?= htmlspecialchars($movie['year']) ?>)</h2>
                            <div class="movie-meta">
                                <span class="director">Director: <?= htmlspecialchars($movie['director']) ?></span>
                                <span class="genre">Genre: <?= htmlspecialchars($movie['genre']) ?></span>
                            </div>
                            <p><?= nl2br(htmlspecialchars($movie['description'])) ?></p>
                            <div class="movie-footer">
                                <span class="post-date">Posted on <?= date('M j, Y', strtotime($movie['created_at'])) ?></span>
                            </div>
                        </div>

                        <?php if (isset($_SESSION['user_id'])): ?>
                            <div class="movie-actions">
                                <a href="content_update.php?id=<?= $movie['id'] ?>" class="edit-btn">Edit</a>
                                <a href="content_delete.php?id=<?= $movie['id'] ?>" class="delete-btn"
                                   onclick="return confirm('Delete this movie post permanently?')">Delete</a>
                            </div>
                        <?php endif; ?>
                    </article>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </section>

<?php require './inc/footer.php'; ?>