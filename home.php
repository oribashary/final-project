<!DOCTYPE html>
<html>

<head>
    <title>Home</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <link href="https://fonts.googleapis.com/css?family=Montserrat&display=swap" rel="stylesheet">
</head>

<body id="body-index">
    <?php
    session_start();

    if ($_SESSION['loggedIn'] !== true) {
        header("Location: index.php");
        exit;
    }
    require 'connection.php';
    ?>
    <nav class="navbar navbar-expand-lg navbar-light" id="navbar">
        <a class="navbar-brand" href="#" id="navbar-brand"></a>
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
                <li class="nav-item active">
                    <a class="nav-link" href="home.php?dogId=<?php echo isset($dogId) ? $dogId : ''; ?>">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="gardens.php?dogId=<?php echo isset($dogId) ? $dogId : ''; ?>">Gardens</a>
                </li>
                <li class="nav-item">
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
    if (isset($_GET['dogId'])) {
        $dogId = $_GET['dogId'];

        $sql = "SELECT * FROM tbl_231_dogs WHERE id = '$dogId'";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $dogName = $row['dogName'];
            $breed = $row['breed'];
            $age = $row['age'];
            $description = $row['description'];
            $place = $row['place'];
            $hugs = $row['hugs'];
            $dogName = htmlspecialchars($dogName);
            $breed = htmlspecialchars($breed);
            $age = htmlspecialchars($age);
            $description = htmlspecialchars($description);

            echo '
        <div class="row align-items-center my-4 mx-4 justify-content-center">
            <div class="row mt-6">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Dog Details</h5>
                            <p class="card-text"><i class="fa fa-male"></i> Name: ' . $dogName . '</p>
                            <p class="card-text"><i class="fa fa-male"></i> Breed: ' . $breed . '</p>
                            <p class="card-text"><button id="hugButton" class="btn btn-primary">Hug</button></p>
                            <p class="card-text">Hugs: <span id="hugCounter">'. $hugs .' </span></p>
                    
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">More details</h5>
                            <p class="card-text"><i class="fa fa-calendar"></i> Age: ' . $age . '</p>
                            <p class="card-text"><i class="fa fa-info-circle"></i> ' . $description . '</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>';

            $sql = "SELECT name FROM tbl_231_gardens WHERE id = '$place'";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                $gardenName = $row['name'];
    
            }else{
                $gardenName = 'Unknown';
            }

                echo '
        <div class="row align-items-center my-4 mx-4 justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Current Location</h5>
                        <p class="card-text"><i class="fa fa-map-marker"></i> Garden Name: ' . $gardenName . '</p>
                    </div>
                </div>
            </div>
        </div>';
            }
        } else {
            echo 'No dog found with the provided dogId.';
            header("Location: dog_list.php");
            exit;
        }
    ?>

    <div class="gif-container">
        <?php
        $gifFiles = glob("images/home/*.gif");
        $randomGif = $gifFiles[array_rand($gifFiles)];
        ?>
        <img src="<?php echo $randomGif; ?>" alt="Random GIF">
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
    <script> var dogId = <?php echo json_encode($dogId); ?>; </script>
	<script src="js/home.js"></script>
</body>

</html>