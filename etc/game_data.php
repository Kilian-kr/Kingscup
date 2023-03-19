<?php
// Require the necessary files
require 'db.php';
require 'helper_funcs.php';
// Check if the request method is GET
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    
    // Check if the required GET parameters are set
    if (isset($_GET['new_card']) && isset($_GET['game_id']) && isset($_GET['left_val']) && isset($_GET['top_val'])) {
        
        // Sanitize the input
        $game_id = sanitize_input($_GET['game_id']);
        $card_url = explode("/",$_GET['new_card']);
        $card_name = sanitize_input(end($card_url));
        $left_val = sanitize_input($_GET['left_val']);
        $top_val = sanitize_input($_GET['top_val']);
        
        // Connect to the database
        $con = connect_to_sql();
        
        // Loop until a unique card is found
        do {
            $new_card_url = $card_set[rand(0, 51)];
            $sql = "SELECT card_name FROM active_cards WHERE game_id=? AND card_src=?";
            $stmt = mysqli_prepare($con, $sql);
            mysqli_stmt_bind_param($stmt, "ss", $game_id, $new_card_url);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_store_result($stmt);
        } while(mysqli_stmt_num_rows($stmt) > 0);
        
        // Insert the new card into the active_cards table and delete it from the hidden_cards table
        $query_ins = "INSERT INTO active_cards (card_src, left_val, top_val, game_id, card_name) VALUES (?, ?, ?, ?, ?)";
        $query_del = "DELETE FROM hidden_cards WHERE game_id=? AND card_name=?";
        $stmt_ins = mysqli_prepare($con, $query_ins);
        mysqli_stmt_bind_param($stmt_ins, "sssss", $new_card_url, $left_val, $top_val, $game_id, $card_name);
        mysqli_stmt_execute($stmt_ins);
        $stmt_del = mysqli_prepare($con, $query_del);
        mysqli_stmt_bind_param($stmt_del, "ss", $game_id, $card_name);
        mysqli_stmt_execute($stmt_del);
        
        // Return the URL of the new card
        echo "cards/$new_card_url";
    }

// Check if all necessary parameters are set in the URL
    if (isset($_GET['card']) && isset($_GET['game_id']) && isset($_GET['left_val']) && isset($_GET['top_val'])) {
        // Sanitize user input for game ID, card name, left value, and top value
        $game_id = sanitize_input($_GET['game_id']);
        $card_url = explode("/",$_GET['card']);
        $card_name = sanitize_input(end($card_url));
        $left_val = sanitize_input($_GET['left_val']);
        $top_val = sanitize_input($_GET['top_val']);
    
        // Connect to the SQL database
        $con = connect_to_sql();

        // Update the active_cards table with the new left and top values for the specified card and game
        $query = "UPDATE active_cards SET left_val=?, top_val=? WHERE card_name=? AND game_id=?";
        $stmt = mysqli_prepare($con, $query);
        mysqli_stmt_bind_param($stmt, 'ssss', $left_val, $top_val, $card_name, $game_id);
        mysqli_stmt_execute($stmt);

        // Print a success message with the updated values
        echo "Card: $card_url, Left Val: $left_val, Top Val: $top_val, Game ID: $game_id";
    }    
    // This block of code retrieves the card updates for a specific game ID
    if (isset($_GET['get_update'])) {
        $game_id = sanitize_input($_GET['get_update']);
    
        // Connect to the database
        $con = connect_to_sql();

        // Prepare the SQL statement to retrieve active card data for the game
        $stmt = $con->prepare("SELECT card_name, top_val, left_val, card_src FROM active_cards WHERE game_id=?");
        $stmt->bind_param("s", $game_id);
        $stmt->execute();

        // Get the results of the SQL query
        $results = $stmt->get_result();
    
        // Loop through the results and create a formatted string with the card data
        foreach($results as $row) {
            $result = "#{$row['card_name']}:{$row['top_val']}:{$row['left_val']}:cards/{$row['card_src']}";
            echo $result;
        }
    }
    // This block of code retrieves the initial card layout for a specific game ID
    if (isset($_GET['load_game'])) {
        $game_id = sanitize_input($_GET['load_game']);
    
        // Connect to the database
        $con = connect_to_sql();

        // Prepare the SQL statement to retrieve hidden card data for the game
        $stmt_hidden = $con->prepare("SELECT card_name, top_val, left_val FROM hidden_cards WHERE game_id=?");
        $stmt_hidden->bind_param("s", $game_id);
        $stmt_hidden->execute();

        // Get the results of the SQL query
        $results_hidden = $stmt_hidden->get_result();

        // Loop through the results and create a formatted string with the hidden card data
        foreach($results_hidden as $row) {
            $result = "{$row['card_name']}:{$row['top_val']}:{$row['left_val']}:cards/cards_back.png#";
            echo $result;
        }

        // Prepare the SQL statement to retrieve active card data for the game
        $stmt_active = $con->prepare("SELECT card_src, card_name, top_val, left_val FROM active_cards WHERE game_id=?");
        $stmt_active->bind_param("s", $game_id);
        $stmt_active->execute();

        // Get the results of the SQL query
        $results_active = $stmt_active->get_result();

        // Loop through the results and create a formatted string with the active card data
        foreach($results_active as $row) {
            $result = "{$row['card_name']}:{$row['top_val']}:{$row['left_val']}:cards/{$row['card_src']}#";
            echo $result;
        }
    } 
}
?>
