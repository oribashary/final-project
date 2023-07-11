<!DOCTYPE html>
<html>

<head>
	<title>Garden Information</title>
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

	<div class="container">
		<div class="row justify-content-center">
			<div class="col-md-6">
				<?php
				$gardenId = isset($_GET['gardenId']) ? $_GET['gardenId'] : '';

				if (!empty($gardenId)) {
					$sql = "SELECT * FROM tbl_231_gardens WHERE id = '$gardenId'";
					$result = $conn->query($sql);

					if ($result->num_rows > 0) {
						$row = $result->fetch_assoc();
						$gardenName = $row['name'];
						$gardenDescription = $row['description'];

						echo "<h2>Garden Information</h2>";

						$isAdmin = ($_SESSION['role'] === 'admin');

						if ($isAdmin) {
							echo '<form method="POST" action="">
                    <input type="hidden" name="gardenId" value="' . $gardenId . '">
                    <label for="gardenName">Garden Name:</label>
                    <input type="text" name="gardenName" value="' . $gardenName . '"><br/>
                    <label for="gardenDescription">Description:</label>
                    <textarea name="gardenDescription">' . $gardenDescription . '</textarea>
                    <button type="submit" name="save" class="btn btn-primary">Save</button>
                </form>';

							if (isset($_POST['save'])) {
								$newGardenName = $_POST['gardenName'];
								$newGardenDescription = $_POST['gardenDescription'];

								$updateSql = "UPDATE tbl_231_gardens SET name = '$newGardenName', description = '$newGardenDescription' WHERE id = '$gardenId'";
								if ($conn->query($updateSql) === TRUE) {
									echo "Garden information saved successfully.";
									$gardenName = $newGardenName;
									$gardenDescription = $newGardenDescription;
								} else {
									echo "Error updating garden information: " . $conn->error;
								}
							}
						} else {
							echo "<p>Garden Name: $gardenName</p>";
							echo "<p>Description: $gardenDescription</p>";
						}

						$dogSql = "SELECT dogName FROM tbl_231_dogs WHERE place = '$gardenId'";
						$dogResult = $conn->query($dogSql);

						if ($dogResult->num_rows > 0) {
							echo "<p>Dogs in the Garden:</p>";
							echo "<ul>";
							while ($dogRow = $dogResult->fetch_assoc()) {
								$dogName = $dogRow['dogName'];
								echo "<li>$dogName</li>";
							}
							echo "</ul>";
						} else {
							echo "No dogs found in the garden.";
						}

						$dogInGardenSql = "SELECT * FROM tbl_231_dogs WHERE id = '$dogId' AND place = '$gardenId'";
						$dogInGardenResult = $conn->query($dogInGardenSql);
						$dogInGarden = $dogInGardenResult->num_rows > 0;

						if ($dogInGarden) {
							echo '<form method="POST" action="">
                    <input type="hidden" name="gardenId" value="' . $gardenId . '">
                    <input type="hidden" name="dogId" value="' . $dogId . '">
                    <button type="submit" name="leave" class="btn btn-primary" id="leaveBtn">Leave</button>
                </form>';

							if (isset($_POST['leave'])) {
								$dogId = $_POST['dogId'];
								$sql = "UPDATE tbl_231_dogs SET place = '0' WHERE id = '$dogId'";
								if ($conn->query($sql) === TRUE) {
									echo "You left the garden.";
									echo "<script>document.getElementById('leaveBtn').disabled = true;</script>";
								} else {
									echo "Error updating place: " . $conn->error;
								}
							}
						} else {
							echo '<form method="POST" action="">
                    <input type="hidden" name="gardenId" value="' . $gardenId . '">
                    <input type="hidden" name="dogId" value="' . $dogId . '">
                    <button type="submit" name="join" class="btn btn-primary" id="imHereBtn">I\'m Here</button>
                </form>';

							if (isset($_POST['join'])) {
								$dogId = $_POST['dogId'];
								$sql = "UPDATE tbl_231_dogs SET place = '$gardenId' WHERE id = '$dogId'";
								if ($conn->query($sql) === TRUE) {
									echo "Welcome!";
									echo "<script>document.getElementById('imHereBtn').disabled = true;</script>";
								} else {
									echo "Error updating place: " . $conn->error;
								}
							}
						}
					} else {
						echo 'No garden found with the provided gardenId.';
						exit;
					}
				} else {
					echo 'No gardenId provided.';
					exit;
				}

				$conn->close();
				?>

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
</body>

</html>