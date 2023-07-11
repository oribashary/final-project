<!DOCTYPE html>
<html>

<head>
    <title>Add Dog</title>
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
require 'connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userId = $_SESSION['id'];
    $dogName = $_POST['dogName'];
    $breed = $_POST['breed'];
    $age = $_POST['age'];
    $description = $_POST['description'];

    $insertQuery = "INSERT INTO tbl_231_dogs (user_id, dogName, breed, age, description)
                    VALUES ('$userId', '$dogName', '$breed', '$age', '$description')";
    $result = $conn->query($insertQuery);

    if ($result) {
        $successMessage = 'Dog added successfully.';
    } else {
        $errorMessage = 'Error adding dog. Please try again.';
    }
}

if (isset($_POST['cancel'])) {
    header("Location: dog_list.php");
    exit();
}
?>
<nav class="navbar navbar-expand-lg navbar-light" id="navbar">
        <a class="navbar-brand" href="home.php?dogId=<?php echo isset($dogId) ? $dogId : ''; ?>" id="navbar-brand"></a>
        <h3 class="d-inline-block mx-2 my-0">IDog</h3>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarLinks" aria-controls="navbarLinks" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <?php
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
        ?>
        <div class="collapse navbar-collapse" id="navbarLinks">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a class="nav-link" href="home.php?dogId=<?php echo isset($dogId) ? $dogId : ''; ?>">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="gardens.php?dogId=<?php echo isset($dogId) ? $dogId : ''; ?>">Gardens</a>
                </li>
                <li class="nav-item active">
                    <a class="nav-link" href="dog_list.php?dogId=<?php echo isset($dogId) ? $dogId : ''; ?>">My Dogs</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="account_settings.php?dogId=<?php echo isset($dogId) ? $dogId : ''; ?>">Settings</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#" data-toggle="modal" data-target="#logoutModal">Logout</a>
                </li>
            </ul>
        </div>
    </nav>
    <div class="container">
        <h1>Add Dog</h1>
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
            <div class="mb-3">
                <label for="dogName" class="form-label">Dog Name</label>
                <input type="text" class="form-control" id="dogName" name="dogName" required>
            </div>
            <div class="mb-3">
                <label for="breed" class="form-label">Breed</label>
                <select class="form-control" id="breed" name="breed">
                    <?php
                    $breedsJson = file_get_contents('dog_breeds.json');
                    $breeds = json_decode($breedsJson, true);

                    foreach ($breeds as $breed) {
                        echo '<option value="' . $breed['name'] . '">' . $breed['name'] . '</option>';
                    }
                    ?>
                </select>
            </div>
            <div class="mb-3">
                <label for="age" class="form-label">Age</label>
                <input type="number" class="form-control" id="age" name="age" required>
            </div>
            <div class="mb-3">
                <label for="description" class="form-label">Description</label>
                <input type="text" class="form-control" id="description" name="description" required>
            </div>
            <div>
                <button type="submit" class="btn btn-primary">Add Dog</button>
            </div>
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
</body>

</html>
