<?php
require_once '../repo/db_connect.php';
require_once '../repo/auth.php';

$username = $_SESSION['username'];
$user_id = $_SESSION['user_id'];
$passwordmessage = '';
$uploadmessage = '';


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['currentPassword'])) {
    $currentPassword = $_POST['currentPassword'];
    $newPassword     = $_POST['newPassword'];
    $confirmPassword = $_POST['confirmPassword'];

    if (strlen($newPassword) < 6 || strlen($newPassword) > 20) {
        $passwordmessage = "New password must be between 6 and 20 characters.";
    } elseif ($newPassword !== $confirmPassword) {
        $passwordmessage = "New passwords do not match.";
    } else {
        $stmt = $pdo->prepare("SELECT password FROM users WHERE username = :username");
        $stmt->execute(['username' => $username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$user || !password_verify($currentPassword, $user['password'])) {
            $passwordmessage = "Current password is incorrect.";
        } else {
            $hashedNewPassword = password_hash($newPassword, PASSWORD_DEFAULT);
            $updateStmt = $pdo->prepare("UPDATE users SET password = :password WHERE username = :username");
            $updateStmt->execute(['password' => $hashedNewPassword, 'username' => $username]);
            $passwordmessage = "Password updated successfully!";
        }
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['profilePic'])) {
    $uploadDir = __DIR__ . "/uploads/profiles/";
    $ext = strtolower(pathinfo($_FILES["profilePic"]["name"], PATHINFO_EXTENSION));

    if (in_array($ext, ['jpg', 'jpeg', 'png'])) {
        $newFilename = $username . '_' . time() . '.' . $ext;
        $targetFile = $uploadDir . $newFilename;

        if (move_uploaded_file($_FILES["profilePic"]["tmp_name"], $targetFile)) {
            $stmt = $pdo->prepare("UPDATE users SET profile_picture = :pic WHERE username = :username");
            $stmt->execute(['pic' => $newFilename, 'username' => $username]);
            $_SESSION['profile_picture'] = $newFilename;
            $uploadmessage = "Profile picture updated!";
        } else {
            $uploadmessage = "Error uploading file.";
        }
    } else {
        $uploadmessage = "Only JPG, JPEG, and PNG files are allowed.";
    }
}

$stmt = $pdo->prepare("SELECT profile_picture FROM users WHERE username = :username");
$stmt->execute(['username' => $username]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);
$pic = (!empty($user['profile_picture']) && file_exists(__DIR__ . "/uploads/profiles/" . $user['profile_picture']))
    ? $user['profile_picture']
    : 'default.jpg';
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>My Account - Project Manager</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .nav-pills .nav-link {
            color: white;
            background-color: transparent;
            border-bottom: 1px solid rgba(255, 255, 255, 0.3);
            border-radius: 0;
            padding: 12px 20px;
            text-align: left;
        }

        .nav-pills .nav-link:hover {
            background-color: rgba(0, 0, 0, 0.3);
        }

        .nav-pills .nav-link.active {
            background-color: rgba(0, 0, 0, 0.5);
            font-weight: bold;
            color: white;
        }

        .center-wrapper {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 80vh;
        }
    </style>
</head>
<body class="bg-primary bg-gradient text-white">

<nav class="navbar navbar-expand-lg bg-dark navbar-dark">
    <div class="container-fluid">
        <a href="#" class="navbar-brand px-5">Project Task Manager</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navmenu">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navmenu">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item"><a class="nav-link" href="index.php">Back to Boards</a></li>
            </ul>
        </div>
    </div>
</nav>

<div class="container-fluid">
    <div class="row min-vh-100">
        <aside class="col-12 col-md-auto p-5 bg-primary bg-gradient text-white" style="width:14rem;">
            <div class="d-flex align-items-start">
                <div class="nav flex-column nav-pills me-3" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                    <button class="nav-link active" id="v-pills-profile-tab" data-bs-toggle="pill"
                        data-bs-target="#v-pills-profile" type="button" role="tab" aria-controls="v-pills-profile"
                        aria-selected="true">Profile</button>
                    <button class="nav-link" id="v-pills-security-tab" data-bs-toggle="pill"
                        data-bs-target="#v-pills-security" type="button" role="tab" aria-controls="v-pills-security"
                        aria-selected="false">Security</button>
                </div>
            </div>
        </aside>

        <main class="col py-3 bg-primary bg-gradient">
            <div class="tab-content" id="v-pills-tabContent">
                <div class="tab-pane fade show active" id="v-pills-profile" role="tabpanel"
                    aria-labelledby="v-pills-profile-tab">
                    <div class="center-wrapper">
                        <div class="card bg-white text-dark shadow" style="width: 100%; max-width: 600px;">
                            <div class="card-body">
                                <h3 class="card-title text-center mb-4">Profile</h3>
                                <p class="text-center">Logged in as <strong><?php echo htmlspecialchars($username); ?></strong></p>
                                <div class="text-center mb-3">
                                    <img src="uploads/profiles/<?php echo htmlspecialchars($pic); ?>" class="rounded-circle" width="100">                                </div>
                                <form method="post" enctype="multipart/form-data">
                                    <div class="mb-3">
                                        <label for="profilePic" class="form-label">Upload Profile Picture</label>
                                        <input type="file" class="form-control" name="profilePic" accept="image/*" required>
                                    </div>
                                    <button type="submit" class="btn btn-dark w-100">Upload</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="tab-pane fade" id="v-pills-security" role="tabpanel" aria-labelledby="v-pills-security-tab">
                    <div class="center-wrapper">
                        <div class="card bg-white text-dark shadow" style="width: 100%; max-width: 600px;">
                            <div class="card-body">
                                <h3 class="card-title text-center mb-4">Change Password</h3>
                                    <p class="text-center">Logged in as <strong><?php echo htmlspecialchars($username); ?></strong></p>

                                    <form method="post" action="myAccount.php#v-pills-security">                                    
                                        <div class="mb-3">
                                        <label for="currentPassword" class="form-label">Current Password</label>
                                        <input type="password" class="form-control" name="currentPassword" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="newPassword" class="form-label">New Password</label>
                                        <input type="password" class="form-control" name="newPassword" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="confirmPassword" class="form-label">Confirm New Password</label>
                                        <input type="password" class="form-control" name="confirmPassword" required>
                                    </div>
                                    <?php if ($passwordmessage): ?>
                                        <div class="alert alert-info text-center mb-3"><?php echo htmlspecialchars($passwordmessage); ?></div>
                                    <?php endif; ?>
                                    <button type="submit" class="btn btn-dark w-100">Update Password</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
<script>
document.addEventListener("DOMContentLoaded", function () { // used to keep you on the Security tab after failing an password change.
    const hash = window.location.hash;
    if (hash) {
        const keep = document.querySelector(`button[data-bs-target="${hash}"]`);
        if (keep) {
            const tab = new bootstrap.Tab(keep);
            tab.show();
        }
    }
});
</script>

</body>
</html>