<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    logout();

}
    if ($user) {
        ?>
        <!DOCTYPE html>
        <html>
        <head>
            <title><?= $user['LastName'] ?> <?= $user['FirstName'] ?> - About Me</title>
            <link rel="icon" type="image/png" href="<?= $user['ProfilePicture'] ?>">
            <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
            <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
            <link href="css/styles.css" rel="stylesheet">
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
            <div class="container mt-4 mb-3">
                <h1>About <?= $user['FirstName'] ?> <?= $user['LastName'] ?></h1>
                <img src="<?= $user['ProfilePicture'] ?>" alt="User Image" class="img-fluid mb-3 profile-image">
                <div class="mb-3">
                    <label class="form-label">First Name:</label>
                    <span class="fw-bold"><?= $user['FirstName'] ?></span>
                </div>
                <div class="mb-3">
                    <label class="form-label">Last Name:</label>
                    <span class="fw-bold"><?= $user['LastName'] ?></span>
                </div>
                <div class="mb-3">
                    <label class="form-label">Description:</label>
                    <span class="fw-bold"><?= $user['Description'] ?></span>
                </div>
                <h2>Skills</h2>
                <ul class="list-group">
                    <?php foreach ($user['Skills'] as $skill): ?>
                        <li class="list-group-item skill-item">
                            <div class="skill-logo small-logo">
                                <img src="<?= $skill['Logo'] ?>" class="small-logo" alt="Skill Logo">
                            </div>
                            <div><?= $skill['Name'] ?></div>
                            <div class="progress progress-bar-small">
                                <div class="progress-bar" style="width: <?= $skill['Progress'] ?>%;"></div>
                            </div>
                        </li>
                    <?php endforeach ?>
                </ul>
            </div>
        </body>
        </html>
        <?php
    } else {
        echo "User not found.";
    }  
?>