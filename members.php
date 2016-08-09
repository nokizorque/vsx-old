<?php
    require "core.php";
    _header();
	global $db;

	// Move this to a template?
	?>
		<body>
			<div class="container">
				<div class="row centered col-lg-4 col-lg-offset-4">
						<form class="form-inline" action="members.php" method="get">
                            <div class="form-group">
                                <div class="input-group" style="width: 100%;">
                        			<label class="sr-only">Search</label>
                                    <input type="text" name="q" class="form-control" placeholder="Search for...">
                                    <span class="input-group-btn">
                                        <button class="btn btn-default">
                                            <span class="glyphicon glyphicon-search" aria-hidden="true"></span>
                                        </button>
                                    </span>
                        			<!--
                                    <span class="input-group-btn">
                        				<button class="btn btn-default" type="submit">Search</button>
                				    </span>
                                    -->
                                </div>
                            </div>
						</form>
				</div>
			</div>
			<br>
			<br>
		</body>
	<?php

	$fields = array("usr", "DATE_FORMAT(registered_on, '%d-%m-%Y') AS registered_on", "DATE_FORMAT(last_visited, '%d-%m-%Y') AS last_visited", "name", "website", "COUNT(*) AS row_count");
	$default["query_string"] = "SELECT " . implode(", ", $fields) . " FROM users LIMIT 100";

	$queryString = $default["query_string"];

	if ($_GET && $_GET["q"] && $_GET["q"]) {
		$i = $_GET["q"];
		$i = str_clean($i);
		$queryString = "SELECT " . implode(", ", $fields) . " FROM users WHERE usr LIKE '%" . $i . "%' OR email LIKE '%" . $i . "%'";
	}

	$query = $db->query($queryString);

	if (!$query || $query->num_rows == 0) {
		echo "No users found from search parameters";
        _footer();
		exit;
	}

	if ($queryString === $default["query_string"]) {
		?>
			<title>Members - VSX</title>
		<?php
	}
	else {
		?>
			<title>Search Results - VSX</title>
		<?php
	}

    //echo "Showing " . $query->fetch_assoc()["row_count"] . " of " . $query->num_rows;

	?>
		<html>
			<body>
				<div class="container">
                    <div class="row">
    					<table class="table table-hover" id="results-table">
    						<tr>
    							<th>Username</th>
    							<th>Name</th>
    							<th>Website</th>
    							<th>Last visited</th>
    							<th>Registered on</th>
    						</tr>
    						<?php
    						while ($row = $query->fetch_assoc()) {
    							$website = "<a href='" . $row["website"] . "' target='_blank'>" . $row["website"] . "</a>";
    							if (!$row["website"] || !isset($row["website"])) {
    								$website = "";
    							}
    							echo "<tr>
    								<td><a href='profile.php?u=" . $row["usr"] ."'>" . $row["usr"] . "</a></td>
    								<td>" . $row["name"] . "</td>
    								<td>" . $website . "</td>
    								<td>" . $row["last_visited"] . "</td>
    								<td>" . $row["registered_on"] . "</td>
    							</tr>";
    						}
    						?>
    					</table>
                    </div>
				</div>
			</body>
		</html>
	<?php

    _footer();
?>
