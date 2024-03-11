<?
use php\about\Models\User;
use php\about\Models\Skill;
/**
 *  @var Skill $skill;
 * @var User $aboutMe;
 */
require_once __DIR__ . '/../../../public/common.php';
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    logout();

}
?>
<!DOCTYPE html>
<html>

<head>
    <title>
        <?= $aboutMe->lastName ?>
        <?= $aboutMe->firstName ?> - About Me
    </title>
    <link rel="icon" type="image/png" href="<?= $aboutMe->profilePicture ?>">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <link href="../public/css/styles.css" rel="stylesheet">
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

    <div class="container mt-4">
        <h1>About Me</h1>
        <img src="<?= $aboutMe->profilePicture ?>" alt="Your Image" class="img-fluid mb-3 profile-image">

        <h2>Personal Information</h2>
        <div class="mb-3">
            <label class="form-label">First Name:</label>
            <span class="fw-bold">
                <?= $aboutMe->firstName ?>
            </span>
        </div>
        <div class="mb-3">
            <label class="form-label">Last Name:</label>
            <span class="fw-bold">
                <?= $aboutMe->lastName ?>
            </span>
        </div>
        <div class="mb-3">
            <label class="form-label">Description:</label>
            <span class="fw-bold">
                <?= $aboutMe->description ?>
            </span>
        </div>

        <h2>Skills</h2>
        <ul class="list-group">
            <?php foreach ($aboutMe->skills as $skill): ?>

                <li class="list-group-item skill-item">
                    <div class="skill-logo small-logo">
                        <img src="<?= $skill->logo ?>" class="small-logo" alt="Skill Logo">
                    </div>
                    <div>
                        <?= $skill->name ?>
                    </div>
                    <div class="progress progress-bar-small">
                        <div class="progress-bar" style="width: <?= $skill->progress ?>%;"></div>
                    </div>
                </li>
            <?php endforeach ?>
        </ul>
        <a class="btn btn-success mt-3 mb-3" href="index.php?c=Home&a=edit">Edit</a>
    </div>
</body>

</html>