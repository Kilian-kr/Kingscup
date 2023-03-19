<?php
// Import the database connection file
require 'db.php';

// Function to generate a random string of specified length
function generateRandomString($length = 10) {
    // Define the character set
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    // Calculate the length of the character set
    $charactersLength = strlen($characters);
    // Initialize an empty string to store the random string
    $randomString = '';
    // Loop through the specified length and append a random character from the character set
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[random_int(0, $charactersLength - 1)];
    }
    // Return the generated random string
    return $randomString;
}

// Function to generate the HTML body of the page
function body(){
    // Load the HTML template from file
    $template = file_get_contents("index_frontend.html");
    // Replace the placeholder "{game_id}" with a randomly generated game ID
    $template = str_replace("{game_id}", generateRandomString(5), $template);
    // Return the HTML template with the replaced game ID
    return $template;
}

// Output the HTML body
echo body();

// Check if a game ID was provided in the URL query string
if(isset($_GET['game_id'])){
    // Define the center point and radius of the circle layout
    $centerX = 325;
    $centerY = 500;
    $radius = 200;
    // Calculate the angle between each card
    $angle = 2 * M_PI / 52;
    // Connect to the database
    $db_access = connect_to_sql();
    // Check if the game ID already exists in the database
    $query = mysqli_query($db_access,"SELECT * FROM active_games WHERE game_id ='".$_GET['game_id']."' ");
    // If the game ID does not exist, insert it into the active_games table and generate the layout of hidden cards
    if(mysqli_num_rows($query) == 0){
        $query = mysqli_query($db_access,"INSERT INTO active_games (game_id) VALUES ('".$_GET['game_id']."')");

        $left_val = 0;
        $top_val = 0;
        // Loop through all 52 cards and calculate their position in the circle layout
        for($i = 0; $i < 52; ++$i) {
            $cardAngle = $angle * $i;
            $top_val = $centerY - $radius * cos($cardAngle);
            $left_val = $centerX + $radius * sin($cardAngle);

            // Insert the card name, game ID, and position into the hidden_cards table
            $query = mysqli_query($db_access,"INSERT INTO hidden_cards (card_name,game_id, left_val, top_val) VALUES ('hidden-card-".$i."', '".$_GET['game_id']."', '".$left_val."px','".$top_val."px')");
        };
    }
    // Output JavaScript code to load the card images
    echo "<script>";
    echo "loadimages();";
    echo "</script>";
}
?>