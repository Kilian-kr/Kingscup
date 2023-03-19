<?php
// Import the database connection file
require 'etc/db.php';
require 'etc/helper_funcs.php';

if ($_SERVER['HTTPS'] != 'on') {
    header('Location: https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']);
    exit();
}




// Output the HTML body
echo generateBody();

// Check if a game ID was provided in the URL query string
if(isset($_GET['game_id'])){
    $game_id = sanitize_input($_GET['game_id']);
    // Define the center point and radius of the circle layout
    $centerX = 325;
    $centerY = 500;
    $radius = 200;
    // Calculate the angle between each card
    $angle = 2 * M_PI / 52;
    // Connect to the database
    $db_access = connect_to_sql();

    // Check if the game ID already exists in the database
    $stmt = mysqli_prepare($db_access, "SELECT * FROM active_games WHERE game_id = ?");
    mysqli_stmt_bind_param($stmt, "s", $game_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    // If the game ID does not exist, insert it into the active_games table and generate the layout of hidden cards
    if(mysqli_num_rows($result) == 0){
        $stmt = mysqli_prepare($db_access, "INSERT INTO active_games (game_id) VALUES (?)");
        mysqli_stmt_bind_param($stmt, "s", $game_id);
        mysqli_stmt_execute($stmt);

        $left_val = 0;
        $top_val = 0;
        // Loop through all 52 cards and calculate their position in the circle layout
        for($i = 0; $i < 52; ++$i) {
            $cardAngle = $angle * $i;
            $top_val = $centerY - $radius * cos($cardAngle);
            $top_val_str = $top_val.'px';
            $left_val = $centerX + $radius * sin($cardAngle);
            $left_val_str = $left_val.'px';
            $card_name = "hidden-card-".$i;


            // Insert the card name, game ID, and position into the hidden_cards table
            $stmt = mysqli_prepare($db_access, "INSERT INTO hidden_cards (card_name,game_id, left_val, top_val) VALUES (?, ?, ?, ?)");
            mysqli_stmt_bind_param($stmt, "ssss", $card_name, $game_id, $left_val_str, $top_val_str);
            mysqli_stmt_execute($stmt);
        }
    }

    // Output JavaScript code to load the card images
    echo "<script>loadimages();</script>";
}
?>