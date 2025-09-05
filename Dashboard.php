<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$pageTitle = "User Management";
$pageDescription = "CineCove Members Dashboard";

require './inc/header.php';
require_once './classes/database.php';
require_once './classes/user.php';

$db = (new Database())->connect();
$user = new User($db);

$users = $user->getAll();
?>
    <section class="dashboard-header">
        <div class="dashboard-content">
            <h1> CineCove Members</h1>
            <div class="dashboard-actions">
                <a href="user_create.php" class="action-btn create">Create User</a>
            </div>
        </div>
    </section>

    <!-- Alert Messages -->
    <section class="dashboard-container">
        <div class="dashboard-card">
            <table class="dashboard-table">
                <thead>
                <tr>
                    <th>ID</th>
                    <th>Profile</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Age</th>
                    <th>Member Since</th>
                    <th>Actions</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($users as $user): ?>
                    <tr>
                        <td><?= $user['id'] ?></td>
                        <td class="profile-picture">
                            <?php if (!empty($user['profile_picture'])): ?>
                                <img src="uploads/<?= htmlspecialchars($user['profile_picture']) ?>" alt="Profile Picture" width="50">
                            <?php else: ?>
                                <div class="default-avatar">
                                    <?= strtoupper(substr($user['username'], 0, 1)) ?>
                                </div>
                            <?php endif; ?>
                        </td>
                        <td><?= htmlspecialchars($user['username']) ?></td>
                        <td><?= htmlspecialchars($user['email']) ?></td>
                        <td><?= htmlspecialchars($user['age'] ?? 'N/A') ?></td>
                        <td><?= date('M j, Y', strtotime($user['created_at'])) ?></td>
                        <td class="actions">
                            <a href="user_update.php?id=<?= $user['id'] ?>" class="action-btn edit">Edit</a>
                            <a href="user_delete.php?id=<?= $user['id'] ?>" class="action-btn delete" onclick="return confirm('Are you sure you want to delete this user?')">Delete</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </section>

<?php require './inc/footer.php'; ?>