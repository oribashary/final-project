<!DOCTYPE html>
<html>

<head>
	<title>Add Garden</title>
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

	if ($_SERVER['REQUEST_METHOD'] === 'POST') {
		$dogId = $_POST['dogId'];
		$name = $_POST['name'];
		$description = $_POST['description'];
		$sql = "INSERT INTO tbl_231_gardens (name, description) VALUES ('$name', '$description')";
		$result = mysqli_query($conn, $sql);

		if ($result) {
			header("Location: gardens.php?dogId=$dogId");
			exit;
		} else {
			echo "Error: " . mysqli_error($conn);
		}
	}
	?>
	<nav class="navbar navbar-expand-lg navbar-light" id="navbar">
		<a class="navbar-brand" href="home.php?dogId=<?php echo isset($_GET['dogId']) ? $_GET['dogId'] : ''; ?>" id="navbar-brand"></a>
		<h3 class="d-inline-block mx-2 my-0">IDog</h3>
		<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarLinks" aria-controls="navbarLinks" aria-expanded="false" aria-label="Toggle navigation">
			<span class="navbar-toggler-icon"></span>
		</button>
		<div class="collapse navbar-collapse" id="navbarLinks">
			<ul class="navbar-nav ml-auto">
				<li class="nav-item">
					<a class="nav-link" href="home.php?dogId=<?php echo isset($_GET['dogId']) ? $_GET['dogId'] : ''; ?>">Home</a>
				</li>
				<li class="nav-item active">
					<a class="nav-link" href="gardens.php?dogId=<?php echo isset($_GET['dogId']) ? $_GET['dogId'] : ''; ?>">Gardens</a>
				</li>
				<li class="nav-item">
					<a class="nav-link" href="dog_list.php?dogId=<?php echo isset($_GET['dogId']) ? $_GET['dogId'] : ''; ?>">My Dogs</a>
				</li>
				<li class="nav-item">
					<a class="nav-link" href="account_settings.php?dogId=<?php echo isset($_GET['dogId']) ? $_GET['dogId'] : ''; ?>">Settings</a>
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
				<h2>Add Garden</h2>
				<form method="POST" action="garden_add.php">
					<input type="hidden" name="dogId" value="<?php echo isset($_GET['dogId']) ? $_GET['dogId'] : ''; ?>">
					<div class="form-group">
						<label for="name">Name:</label>
						<input type="text" class="form-control" id="name" name="name" required>
					</div>
					<div class="form-group">
						<label for="description">Description:</label>
						<textarea class="form-control" id="description" name="description" required></textarea>
					</div>
					<button type="submit" class="btn btn-primary">Add</button>
				</form>
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
