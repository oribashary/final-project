<!DOCTYPE html>
<html>

<head>
    <title>Dogs</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
</head>

<body>
    <?php
    session_start();

    if ($_SESSION['loggedIn'] !== true) {
        header("Location: index.php");
        exit;
    }

    require 'connection.php';

    $sql = "SELECT id, dogName, description FROM tbl_231_dogs WHERE user_id = " . $_SESSION['id'];
    $result = $conn->query($sql);
    $dogs = [];

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $dogs[] = $row;
        }
    }

    $conn->close();

    if (isset($_POST['dogId'])) {
        $selectedDogId = $_POST['dogId'];
        header("Location: home.php?dogId=$selectedDogId");
        exit;
    }
    ?>

    <nav class="navbar navbar-expand-lg navbar-light" id="navbar">
        <h3 class="d-inline-block mx-2 my-0">IDog</h3>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarLinks" aria-controls="navbarLinks" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarLinks">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item active">
                    <a class="nav-link" href="#">Dogs</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#" data-toggle="modal" data-target="#logoutModal">Logout</a>
                </li>
            </ul>
        </div>
    </nav>
    <div class="container mt-3">
        <div class="row">
            <div class="col-md-8">
                <h2>Select a dog</h2>
                <ul class="list-group border-0" id="sortable-list">
                    <div class="col-4 text-right">
                        <?php
                        foreach ($dogs as $dog) {
                            echo '<p>' . $dog['dogName'] . ': ' . $dog['description'] . '</p>';
                            echo '<a href="home.php?dogId=' . $dog['id'] . '" class="btn btn-primary">Choose me!</a>';
                        }
                        ?>
                    </div>
                </ul>
            </div>
        </div>
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
    <footer class="page-footer footer">
    </footer>
</body>

</html>