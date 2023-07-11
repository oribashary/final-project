<!DOCTYPE html>
<html>

<head>
    <title>Signup</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>

<body>
<?php
require 'connection.php';

$message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = sanitize_input($_POST["name"]);
    $email = sanitize_input($_POST["email"]);
    $password = sanitize_input($_POST["password"]);
    $confirmPassword = sanitize_input($_POST["confirmPassword"]);
    $role = sanitize_input($_POST["role"]);
    $dogName = sanitize_input($_POST["dogName"]);
    $dogBreed = sanitize_input($_POST["dogBreed"]);
    $dogAge = sanitize_input($_POST["dogAge"]);
    $dogDescription = sanitize_input($_POST["dogDescription"]);

    if ($password !== $confirmPassword) {
        $message = "Password and Confirm Password do not match.";
    }

    $checkEmailQuery = "SELECT email FROM tbl_231_users WHERE email = '$email'";
    $checkEmailResult = $conn->query($checkEmailQuery);

    if ($checkEmailResult->num_rows > 0) {
        $message = "Email already exists in the database.";
    } else {
        $sql = "INSERT INTO tbl_231_users (email, username, password, role) VALUES ('$email', '$name', '$password', '$role')";
        if ($conn->query($sql) !== TRUE) {
            $message = "Error inserting user: " . $conn->error;
        } else {
            $userId = $conn->insert_id;

            $sql = "INSERT INTO tbl_231_dogs (user_id, dogName, breed, age, description) VALUES ('$userId', '$dogName', '$dogBreed', '$dogAge', '$dogDescription')";
            if ($conn->query($sql) !== TRUE) {
                $message = "Error inserting dog: " . $conn->error;
            } else {
                header("Location: success.php");
                exit();
            }
        }
    }
}
function sanitize_input($data)
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

$conn->close();
?>
    <div class="container">
        <h1>Signup Form</h1>
        <?php if (!empty($message)) : ?>
            <div class="alert alert-danger"><?php echo $message; ?></div>
        <?php endif; ?>
        <form id="signupForm" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
            <div class="form-group">
                <label for="name">Name</label>
                <input type="text" class="form-control" id="name" name="name" required>
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" class="form-control" id="email" name="email" required>
                <small id="emailError" class="form-text text-danger"></small>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <div class="form-group">
                <label for="confirmPassword">Confirm Password</label>
                <input type="password" class="form-control" id="confirmPassword" name="confirmPassword" required>
                <small id="passwordError" class="form-text text-danger"></small>
            </div>
            <div class="form-group">
                <label for="role">Role</label>
                <select class="form-control" id="role" name="role" required>
                    <option value="user">User</option>
                    <option value="admin">Admin</option>
                </select>
            </div>
            <h3>Create Dog</h3>
            <div class="form-group">
                <label for="dogName">Dog Name</label>
                <input type="text" class="form-control" id="dogName" name="dogName" required>
            </div>
            <div class="form-group">
                <label for="dogBreed" class="form-label">Breed</label>
                <select class="form-control" id="dogBreed" name="dogBreed">
                    <?php
                    $breedsJson = file_get_contents('dog_breeds.json');
                    $breeds = json_decode($breedsJson, true);

                    foreach ($breeds as $breed) {
                        echo '<option value="' . $breed['name'] . '">' . $breed['name'] . '</option>';
                    }
                    ?>
                </select>
            </div>
            <div class="form-group">
                <label for="dogAge">Dog Age</label>
                <input type="number" class="form-control" id="dogAge" name="dogAge" required>
            </div>
            <div class="form-group">
                <label for="dogDescription">Dog Description</label>
                <textarea class="form-control" id="dogDescription" name="dogDescription" required></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Sign Up</button>
            <a href="index.php" class="btn btn-secondary">Cancel</a>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="js/signup.js"></script>
</body>

</html>