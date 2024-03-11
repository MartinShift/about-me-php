<?php
require_once __DIR__ . '/../../../public/common.php';
use php\about\Models\User;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $login = $_POST['Login'];
    $password = $_POST['Password'];
    $confirmPassword = $_POST['ConfirmPassword'];
    if (empty($login) || empty($password) || empty($confirmPassword) || $password !== $confirmPassword) {
        $response = [
            'message' => 'Validation Error',
            'errors' => ['Invalid input data'],
        ];
        http_response_code(400);
        exit;
    }
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    if (isset($usersData[$login])) {
        $response = [
            'message' => 'Error',
            'errors' => ['User already exists'],
        ];
        http_response_code(400);
    } else {
            User::createUser($login,$hashedPassword);
        header("Location: index.php?c=Account&a=login");
    }
} else {
}
?>
<!DOCTYPE html>
<html>

<head>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>

    </style>
</head>

<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-4">
                <h2 class="mt-5 mb-3">Sign Up</h2>
                <form method="post">
                    <div class="mb-3">
                        <label for="Login" class="form-label">Login</label>
                        <input type="text" id="Login" name="Login" class="form-control" required autofocus>
                    </div>
                    <div class="mb-3">
                        <label for="Password" class="form-label">Password</label>
                        <input type="password" id="Password" name="Password" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="ConfirmPassword" class="form-label">Confirm Password</label>
                        <input type="password" id="ConfirmPassword" name="ConfirmPassword" class="form-control"
                            required>
                    </div>
                    <div class="mb-3">
                        <button type="submit" class="btn btn-primary">Sign Up</button>
                    </div>
                </form>
                <div class="mb-3">
                    <div class="row">

                        <div class="row">

                            <a
                                href="http://accounts.google.com/o/oauth2/auth?client_id=733461572156-qdmuq5en3kbagv89gej4dp8153k79cs9.apps.googleusercontent.com&redirect_uri=http://localhost:3000/callback.php&response_type=code&scope=email profile">
                                <button class="btn btn-outline-dark" style="text-transform: none;">
                                    <img width="20px" style="margin-bottom: 3px; margin-right: 5px;"
                                        alt="Google sign-in"
                                        src="https://upload.wikimedia.org/wikipedia/commons/thumb/5/53/Google_%22G%22_Logo.svg/512px-Google_%22G%22_Logo.svg.png" />
                                    Register With Google
                                </button>
                            </a>

                        </div>

                    </div>
                </div>
                <p class="mb-0">Already have an account? <a class="btn btn-link" href="index.php?c=Account&a=login">Sign In</a></p>
            </div>
        </div>
    </div>
</body>

</html>