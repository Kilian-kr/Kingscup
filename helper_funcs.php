<?php

$card_set = ['10_of_clubs.png', '10_of_diamonds.png', '10_of_hearts.png' , '10_of_spades.png', '2_of_clubs.png', '2_of_diamonds.png', '2_of_hearts.png', '2_of_spades.png', '3_of_clubs.png', '3_of_diamonds.png', '3_of_hearts.png', '3_of_spades.png', '4_of_clubs.png', '4_of_diamonds.png', '4_of_hearts.png', '4_of_spades.png', '5_of_clubs.png', '5_of_diamonds.png', '5_of_hearts.png', '5_of_spades.png', '6_of_clubs.png', '6_of_diamonds.png', '6_of_hearts.png', '6_of_spades.png', '7_of_clubs.png', '7_of_diamonds.png', '7_of_hearts.png', '7_of_spades.png', '8_of_clubs.png', '8_of_diamonds.png', '8_of_hearts.png', '8_of_spades.png', '9_of_clubs.png', '9_of_diamonds.png', '9_of_hearts.png', '9_of_spades.png', 'ace_of_clubs.png', 'ace_of_diamonds.png', 'ace_of_hearts.png', 'ace_of_spades.png', 'jack_of_clubs.png', 'jack_of_diamonds.png', 'jack_of_hearts.png', 'jack_of_spades.png', 'king_of_clubs.png', 'king_of_diamonds.png', 'king_of_hearts.png', 'king_of_spades.png', 'queen_of_clubs.png', 'queen_of_diamonds.png', 'queen_of_hearts.png', 'queen_of_spades.png'];



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


// Function to sanitize input
function sanitize_input($input){
    $input = trim($input);
    $input = stripslashes($input);
    $input = htmlspecialchars($input);
    return $input;
}


// Function to generate the HTML body of the page
function generateBody(){
    // Load the HTML template from file
    $template = file_get_contents("index_frontend.html");
    // Replace the placeholder "{game_id}" with a randomly generated game ID
    $template = str_replace("{game_id}", generateRandomString(5), $template);
    // Return the HTML template with the replaced game ID
    return $template;
}
?>
