<?php
include 'config.php'; 
$q = $_GET["q"];

// Initialize the response
$response = "no results";

// Lookup all links from the database if the length of q > 0
$hint = "";
if (strlen(trim($q)) > 0) {
    $hint = "";

    // Prepare the SQL query to search titles and URLs that match the query
    $sql = "SELECT Title FROM GamesInfo WHERE Title LIKE ?";
    $stmt = $conn->prepare($sql);
    $searchTerm = "%" . $q . "%"; // Use wildcards to match the query
    $stmt->bind_param("s", $searchTerm); // Bind the parameter to the SQL query

    // Execute the query
    $stmt->execute();
    
    // Bind result variables
    $stmt->bind_result($title);

    // Fetch results and build the response
    $i = 0;
    while ($stmt->fetch() && $i < 5) {
        if (!str_contains($hint, $title)){
            if ($hint == "") {
                $hint = "<p onclick=\"titleClicked('" . htmlspecialchars($title, ENT_QUOTES) . "')\">" . htmlspecialchars($title, ENT_QUOTES) . "</p>";
            } else {
                $hint = $hint . "<p onclick=\"titleClicked('" . htmlspecialchars($title, ENT_QUOTES) . "')\">" . htmlspecialchars($title, ENT_QUOTES) . "</p>";
            }
        }
        $i++;
    }

    // Close the statement
    $stmt->close();
}

// Output the response
echo $hint ? $hint : $response;


// Close the database connection
$conn->close();
?>