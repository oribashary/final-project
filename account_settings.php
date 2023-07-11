<!DOCTYPE html>
<html>

<head>
    <title>Account Settings</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</head>

<body>
<?php
session_start();

if ($_SESSION['loggedIn'] !== true) {
    header("Location: index.php");
    exit;
}
require 'connection.php';

$dogId = isset($_GET['dogId']) ? $_GET['dogId'] : '';

if (!empty($dogId)) {
    $sql = "SELECT * FROM tbl_231_dogs WHERE id = '$dogId'";
    $result = $conn->query($sql);

    if (!($result->num_rows > 0)) {
        echo 'No dog found with the provided dogId.';
        header("Location: dog_list.php");
        exit;
    }
} else {
    header("Location: dog_list.php");
    exit;
}

$emailExists = false;
if (isset($_POST['email'])) {
    $email = $_POST['email'];
    $checkEmailQuery = "SELECT * FROM tbl_231_users WHERE email = '$email' AND id <> '$dogId'";
    $checkEmailResult = $conn->query($checkEmailQuery);

    if ($checkEmailResult->num_rows > 0) {
        $emailExists = true;
    }
}

if (isset($_POST['email']) && !$emailExists) {
    $email = $_POST['email'];
    $username = $_POST['username'];
    $password = $_POST['password'];

    $updateQuery = "UPDATE tbl_231_users SET email = '$email', username = '$username', password = '$password' WHERE id = '$dogId'";
    $updateResult = $conn->query($updateQuery);

    if ($updateResult) {
        $successMessage = 'Account updated successfully.';
    } else {
        $errorMessage = 'Error updating account. Please try again.';
    }
}

if ($_SESSION['role'] === 'admin') {
    $currentUserId = $_SESSION['id'];
    $sql = "SELECT * FROM tbl_231_users WHERE id <> '$currentUserId'";
    $result = $conn->query($sql);
    $users = $result->fetch_all(MYSQLI_ASSOC);

    if (isset($_POST['deleteAccount']) && isset($_POST['accountId'])) {
        $accountId = $_POST['accountId'];

        $deleteQuery = "DELETE FROM tbl_231_users WHERE id = '$accountId'";
        $deleteResult = $conn->query($deleteQuery);

        if ($deleteResult) {
            $successMessage = 'Account deleted successfully.';
        } else {
            $errorMessage = 'Error deleting account. Please try again.';
        }
    }
}
?>
    <nav class="navbar navbar-expand-lg navbar-light" id="navbar">
        <a class="navbar-brand" href="home.php?dogId=<?php echo isset($dogId) ? $dogId : ''; ?>" id="navbar-brand"></a>
        <h3 class="d-inline-block mx-2 my-0">IDog</h3>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarLinks" aria-controls="navbarLinks" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarLinks">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a class="nav-link" href="home.php?dogId=<?php echo isset($dogId) ? $dogId : ''; ?>">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="gardens.php?dogId=<?php echo isset($dogId) ? $dogId : ''; ?>">Gardens</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="dog_list.php?dogId=<?php echo isset($dogId) ? $dogId : ''; ?>">My Dogs</a>
                </li>
                <li class="nav-item active">
                    <a class="nav-link" href="account_settings.php?dogId=<?php echo isset($dogId) ? $dogId : ''; ?>">Settings</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#" data-toggle="modal" data-target="#logoutModal">Logout</a>
                </li>
            </ul>
        </div>
    </nav>

    <div class="container mt-3">
        <h2>Account Settings</h2>
        <?php if (isset($successMessage)) { ?>
            <div class="alert alert-success" role="alert">
                <?php echo $successMessage; ?>
            </div>
        <?php } elseif (isset($errorMessage)) { ?>
            <div class="alert alert-danger" role="alert">
                <?php echo $errorMessage; ?>
            </div>
        <?php } ?>
        <form method="POST">
            <div class="form-group">
                <label for="email"><i class="bi bi-envelope"></i> Email:</label>
                <input type="email" class="form-control" id="email" name="email" placeholder="Enter email" value="<?php echo isset($_POST['email']) ? $_POST['email'] : ''; ?>" required>
                <?php if ($emailExists) { ?>
                    <small class="text-danger">This email is already registered.</small>
                <?php } ?>
            </div>
            <div class="form-group">
                <label for="username"><i class="bi bi-person"></i> Username:</label>
                <input type="text" class="form-control" id="username" name="username" placeholder="Enter username" value="<?php echo isset($_POST['username']) ? $_POST['username'] : ''; ?>" required>
            </div>
            <div class="form-group">
                <label for="password"><i class="bi bi-lock"></i> Password:</label>
                <input type="password" class="form-control" id="password" name="password" placeholder="Enter password" value="<?php echo isset($_POST['password']) ? $_POST['password'] : ''; ?>" required>
            </div>
            <button type="submit" class="btn btn-primary"><i class="bi bi-save"></i> Save Changes</button>
        </form>
    </div>

    <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="logoutModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="logoutModalLabel">Confirm Logout</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    Are you sure you want to log out?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <a class="btn btn-primary" href="logout.php">Logout</a>
                </div>
            </div>
        </div>
    </div>

    <div class="container mt-3">
    <?php if ($_SESSION['role'] === 'admin') { ?>
        <h2>Account Settings</h2>
        <table class="table">
            <thead>
                <tr>
                    <th>Email</th>
                    <th>Username</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $user) { ?>
                    <tr>
                        <td><?php echo $user['email']; ?></td>
                        <td><?php echo $user['username']; ?></td>
                        <td>
                            <form method="POST" onsubmit="return confirm('Are you sure you want to delete this account?');">
                                <input type="hidden" name="accountId" value="<?php echo $user['id']; ?>">
                                <button type="submit" name="deleteAccount" class="btn btn-danger btn-sm">Delete</button>
                            </form>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    <?php } ?>
    </div>
</body>

</html>