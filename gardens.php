<!DOCTYPE html>
<html>

<head>
	<title>Gardens</title>
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
				<li class="nav-item active">
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
	<div class="container mt-3">
		<div class="row">
			<div class="col-md-8">
				<?php
				if ($_SESSION['role'] === 'admin') {
					echo '<h2>Gardens <a class="btn btn-success" href="garden_add.php?dogId=' . $dogId . '">Add Garden</a></h2>';
				} else {
					echo '<h2>Gardens</h2>';
				}
				?>

				<ul class="list-group border-0" id="sortable-list">
					<?php
					$sql = "SELECT * FROM tbl_231_gardens";
					$result = $conn->query($sql);

					if ($result->num_rows > 0) {
						while ($row = $result->fetch_assoc()) {
							$gardenId = $row['id'];
							$gardenName = $row['name'];
							$gardenDescription = $row['description'];
							echo '
<li class="list-group-item d-flex justify-content-between align-items-center border-0">
	<div class="col-8">
		<span class="font-weight-bold">' . $gardenName . '</span>
		<p>' . $gardenDescription . '</p>
	</div>
	<div class="col-4 text-right">';

							if ($_SESSION['role'] === 'admin') {
								echo '<form onsubmit="deleteGarden(event, ' . $gardenId . ')">
				<input type="hidden" name="gardenId" value="' . $gardenId . '">
				<button type="submit" class="btn btn-danger">Delete</button>
			</form>';
							}

							echo '
		<a class="btn btn-primary" href="garden.php?dogId=' . $dogId . '&gardenId=' . $gardenId . '">View</a>
	</div>
</li>';
						}
					} else {
						echo '<li class="list-group-item">No gardens found.</li>';
					}
					?>



				</ul>
			</div>

		</div>
	</div>
	<!-- Logout Modal -->
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
	<script src="js/gardens.js"></script>
</body>

</html>