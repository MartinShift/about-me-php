<?php
require_once __DIR__ . '/../../../public/common.php';


// Function to sort skills by progress in descending order
function sortByProgress($a, $b) {
    return $b['Progress'] - $a['Progress'];
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>User List</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container">
            <a class="nav-link" href="index.php">Main Page</a>
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a href="index.php?c=User&a=userList" class="nav-link">Users</a>
                </li>
                <li class="nav-item">
                        <a  href="index.php?c=Account&a=log_out" type="submit" class="nav-link">Logout</a>
                </li>
            </ul>
        </div>
    </nav>
    <div class="container">
        <h1>User List</h1>
        <?php foreach ($usersData as $userId => $userData): ?>
            <a href="index.php?c=User&a=viewUser&userId=<?= $userId ?>" class="mb-4 p-3 border d-block text-decoration-none">
                <div class="d-flex align-items-center">
                    <img src="<?= $userData['ProfilePicture'] ?>" class="rounded-circle" width="50" height="50" alt="User Image">
                    <div class="ms-3">
                        <h5 class="card-title"><?= $userData['FirstName'] ?> <?= $userData['LastName'] ?></h5>
                        <?php if (!empty($userData['Skills'])): ?>
                            <?php
                            // Sort skills by progress in descending order
                            usort($userData['Skills'], "sortByProgress");
                            ?>
                            <div class="d-flex">
                                <?php foreach ($userData['Skills'] as $skill): ?>
                                    <div class="me-2">
                                        <img src="<?= $skill['Logo'] ?>" alt="Skill Logo" width="20" height="20">
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php else: ?>
                            <p class="card-text">No skills</p>
                        <?php endif; ?>
                    </div>
                </div>
            </a>
        <?php endforeach; ?>
    </div>
</body>
</html>
