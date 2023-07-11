<!DOCTYPE html>
<html>

<head>
    <title>My Dogs</title>
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

    $user_id = $_SESSION['id'];
    ?>
    <nav class="navbar navbar-expand-lg navbar-light" id="navbar">
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

    <?php
    $sql = "SELECT id, dogName, breed, age, description FROM tbl_231_dogs WHERE user_id = '$user_id'";
    $result = $conn->query($sql);

    echo '
    <div class="container mt-3">
        <div class="row">
            <div class="col-md-8">
                <ul class="list-group">
                    <li class="list-group-item d-flex justify-content-between align-items-center border-0">
                        <h2 class="me-auto">My Dogs</h2>
                        <a href="dog_add.php?dogId=' . (isset($dogId) ? $dogId : '') . '" class="btn btn-primary ms-auto">Add Dog</a>
                    </li>';

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $id = $row['id'];
            $dogName = $row['dogName'];
            $breed = $row['breed'];
            $age = $row['age'];
            $description = $row['description'];

            $dogName = htmlspecialchars($dogName);
            $breed = htmlspecialchars($breed);
            $age = htmlspecialchars($age);
            $description = htmlspecialchars($description);

            $currentDog = ($id == $dogId);

            echo '
                            <li class="list-group-item d-flex justify-content-between align-items-center border-0">
                                <div class="d-flex align-items-center">
                                    <div>
                                        <div class="d-flex align-items-center justify-content-between">
                                            <h4 class="me-3"><a href="home.php?dogId=' . $id . '">' . $dogName . '</a></h4>
                                            <div>';

            if (!$currentDog) {
                echo '
                                                <form onsubmit="deleteDog(event, ' . $id . ')">
                                                    <input type="hidden" name="dogId" value="' . $id . '">
                                                    <button type="submit" class="btn btn-sm btn-danger me-2">Delete</button>
                                                    <a href="dog_edit.php?dogId=' . $id . '" class="btn btn-sm btn-primary ms-auto">Edit</a>
                                                    </form>';
            }

            echo '
                                            </div>
                                        </div>
                                        <p>Age: ' . $age . '<br>Breed: ' . $breed . '<br>Description: ' . $description . '</p>
                                    </div>
                                </div>
                            </li>';
        }
    } else {
        echo "<li class='list-group-item'>No dogs found.</li>";
    }

    echo '
                </ul>
            </div>
        </div>
    </div>';
    $conn->close();
    ?>
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
	<script src="js/doglist.js"></script>
</body>

</html>