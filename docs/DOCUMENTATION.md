# Table of Contents

- [Documentation for index.php](#documentation-for-indexphp)
  - [Required files](#required-files)
  - [HTTPS Redirect](#https-redirect)
  - [HTML Body Generation](#html-body-generation)
  - [Game ID Query String](#game-id-query-string)
  - [Circle Layout](#circle-layout)
  - [Database Connection](#database-connection)
  - [Active Games Table](#active-games-table)
  - [Hidden Cards Table](#hidden-cards-table)
  - [JavaScript Card Images](#javascript-card-images)
- [Documentation for index.js](#documentation-for-indexjs)
  - [Global Variables](#global-variables)
  - [Functions](#functions)
- [Documentation for game_data.php](#documentation-for-game_dataphp)
  - [Required files](#required-files-1)
  - [Request Methods](#request-methods)
    - [Adding a new card](#adding-a-new-card)
    - [Updating a card](#updating-a-card)
    - [Getting update](#getting-update)
    - [Loading Gameboard](#loading-gameboard)  
  - [Parameters](#parameters)
- [Documentation for helper_funcs.php](#documentation-for-helper_funcsphp)
  - [Variables](#variables)
  - [Functions](#functions-1)
   






## Documentation for index.php
###### [Back to Top](#table-of-contents)

The ```index.php``` file is the main entry point for the web application. It is responsible for handling requests and rendering the HTML content for the user's web browser.
### Required files

The following files are required for index.php to function correctly:

- ```etc/db.php```: contains the database connection function
- ```etc/helper_funcs.php```: contains helper functions used throughout the application

These files should be included at the top of the ```index.php```: file.

```php
require 'etc/db.php';
require 'etc/helper_funcs.php';
```
### HTTPS Redirect

To ensure that the application is accessed over HTTPS, the code checks if the current protocol is HTTPS. If it is not, it redirects to the HTTPS version of the page.


```php
if ($_SERVER['HTTPS'] != 'on') {
    header('Location: https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']);
    exit();
}
```

### HTML Body Generation

The ```generateBody()``` function is called to output the HTML body. This function is defined in ```etc/helper_funcs.php``` and returns a string containing the HTML markup for the body of the page.

```php

echo generateBody();
```

### Game ID Query String

If a game ID is provided in the URL query string, the code retrieves the value and stores it in the ```$game_id``` variable.

```php

if(isset($_GET['game_id'])){
    $game_id = sanitize_input($_GET['game_id']);
}
```

### Circle Layout

The hidden cards are arranged in a circular layout. The following variables are used to define the center point and radius of the circle:

```php
$centerX = 325;
$centerY = 500;
$radius = 200;
```

The angle between each card is calculated using the following formula:

```php
$angle = 2 * M_PI / 52;
```

The loop that generates the layout of the hidden cards uses trigonometry to calculate the X and Y positions of each card around the circle.

```php
for($i = 0; $i < 52; ++$i) {
    $cardAngle = $angle * $i;
    $top_val = $centerY - $radius * cos($cardAngle);
    $top_val_str = $top_val.'px';
    $left_val = $centerX + $radius * sin($cardAngle);
    $left_val_str = $left_val.'px';
    $card_name = "hidden-card-".$i;
}
```

### Database Connection

The code connects to the database using the ```connect_to_sql()``` function defined in ```etc/db.php```.

```php
$db_access = connect_to_sql();
```

### Active Games Table

The active_games table stores information about active games. If the game ID provided in the query string does not exist in the table, it is inserted using the following code:

```php
$stmt = mysqli_prepare($db_access, "INSERT INTO active_games (game_id) VALUES (?)");
mysqli_stmt_bind_param($stmt, "s", $game_id);
mysqli_stmt_execute($stmt);
```

### Hidden Cards Table

The ```hidden_cards``` table stores information about the position of the hidden cards. For each card, a new row is inserted into the table using the following code:

```php
$stmt = mysqli_prepare($db_access, "INSERT INTO hidden_cards (card_name,game_id, left_val, top_val) VALUES (?, ?, ?, ?)");
mysqli_stmt_bind_param($stmt, "ssss", $card_name, $game_id, $left_val_str, $top_val_str);
mysqli_stmt_execute($stmt);
```


### JavaScript Card Images

The ```index.php``` file outputs a JavaScript function call to ```loadimages()``` after checking if a game ID was provided in the URL query string. The ```loadimages()``` function is defined in the ```etc/index.js``` file. The ```loadimages()``` function dynamically loads the 52 card images into the HTML DOM, making them visible to the user.

 ```php
echo "<script>loadimages();</script>";
```

Note that the ```loadimages()``` function is called only if a game ID was provided in the URL query string. This ensures that the card images are loaded only when needed, saving bandwidth and speeding up page load times.

It's also worth noting that the card images are not preloaded before the page is loaded. This means that the user may see the card images loading one by one as they become visible on the screen, causing a momentary delay. Preloading the card images would require additional JavaScript code or HTML markup, which would increase the complexity of the code and the page load time.




## Documentation for index.js
###### [Back to Top](#table-of-contents)
This file is a client-side script written in JavaScript that handles the functionality for the game application. It consists of functions that update the game's state, load images of cards and add event listeners to the available cards.
### Global Variables
```javascript
const avail_cards
// An array that holds the available cards as image elements. 
// It is initialized as an empty array and is populated when the loadimages() function is called.
```


```javascript
const url
// A list that holds the URL of the current web page. 
//It is obtained by splitting the window location URL.
```
  
```javascript
const game_id
// A string that holds the ID of the current game. 
// It is obtained by splitting the url.
```

  
```javascript
var zIndex
// A number that holds the initial value of the z-index property for cards. 
// It is initialized as 0 and is incremented for each new card added to the game field.

```
  
```javascript
let intervalId
// A number that holds the ID of the interval set by setInterval() function.
// It is used to update the game state every second and can be paused or started again
// by calling the pauseInterval() or startInterval() functions.
```


### Functions
```javascript
pauseInterval() 
// This function pauses the interval set by setInterval() function by clearing it using clearInterval() function.
```


```javascript
startInterval() 
// This function starts the interval again after pausing it by setting the intervalId variable to the ID returned by setInterval() function.
```



```javascript
update() 
// This function updates the game state by making an AJAX call to the server to get the game data. 
// It waits for the DOM to be ready before making the call and updates the properties of the game elements based on the response received.
```



```javascript
loadimages()
// This function loads the images of the cards by making an AJAX call to the server to get the game data. 
// It waits for the DOM to be ready before making the call and creates image elements for each card received in the response. 
// It sets the properties of the image elements and adds them to the game field. 
// It also adds event listeners to the cards and starts the interval again after loading the images.
```


```javascript
addEventListenersToCards() 
// This function adds event listeners to the available cards.
// It iterates through each card in the avail_cards array and adds a mousedown event listener
// to set the cursor style to grabbing and pause the interval. It also adds a mouseup event listener
// to send a request to the server to update the game state with the new position of the card 
// and change the source of the card to the new card's image if the card was flipped over.
```


## Documentation for game_data.php
###### [Back to Top](#table-of-contents)

```game_data.php``` is a PHP script that interacts with a SQL database to manage the card game. It receives GET requests with specific parameters to create, update, and retrieve card data for a specific game ID.

#### Required files
The following files are required for get_data.php to function correctly:

- ```etc/db.php```: contains the database connection function
- ```etc/helper_funcs.php```: contains helper functions used throughout the application


### **Request Methods**

```game_data.php``` responds to ```GET``` requests with specific parameters.

#### Adding a new card

To add a new card to the game board, the ```new_card``` parameter must be included in the URL along with the necessary additional parameters. The script will check if all the required parameters are set and sanitize them. It will then connect to the database and loop until a unique card is found. It will then insert the new card into the ```active_cards``` table and delete it from the ```hidden_cards``` table. Finally, it will return the URL of the new card.

#### Updating a card

To update the position of an existing card on the game board, the ```card``` parameter must be included in the URL along with the necessary additional parameters. The script will check if all the required parameters are set and sanitize them. It will then connect to the database and update the ```active_cards``` table with the new position of the specified ```card```. Finally, it will print a success message with the updated values.

#### Getting update

To retrieve the current state of the game, the ```get_update``` parameter must be included in the URL along with the ```game_id``` parameter. The script will check if the required parameters are set and sanitize them. It will then connect to the database and retrieve the data for the specified game. It will then loop through the results and create a formatted string with the card data. Finally, it will return a json with all the values.

#### Loading Gameboard
To retrieve the initial state of the game, the ```load_game``` parameter must be included in the URL along with the ```game_id parameter```. The script will check if the required parameters are set and sanitize them. It will then connect to the database and retrieve the data for the specified game. It will then loop through the results and create a formatted string with the card data. Finally, it will return a json array with all the values.


### Parameters

#### ```new_card ```
This parameter is used to add a new card to the game board. It requires the following additional parameters:

```php
game_id // The ID of the game that the card is being added to.
new_card // The URL of the new card.
left_val // The left value (horizontal position) of the new card.
top_val  // The top value (vertical position) of the new card.
```
#### ```card``` 
This parameter is used to update the position of an existing card on the game board. It requires the following additional parameters:

```php
game_id // The ID of the game that the card is being updated in.
card // The URL of the card that is being updated.
left_val // The new left value (horizontal position) of the card.
top_val // The new top value (vertical position) of the card.
```

#### ```get_update```
This parameter is used to retrieve the current state of the game. It requires the following additional parameter:

```php
game_id // The ID of the game that the data is being retrieved for.
```

#### ```load_game ```
This parameter is used to retrieve the initial state of the game. It requires the following additional parameter:

```php
game_id // The ID of the game that the data is being retrieved for.
```




## Documentation for helper_funcs.php
###### [Back to Top](#table-of-contents)

The helper_funcs.php file contains several helper functions.

### Variables
```php
$card_set // This variable is an array containing the names of 52 card images in the png format.
```
### Functions
```php
function generateRandomString() // This function generates a random string of specified length.
//Parameters
    $length //The length of the random string to be generated. The default value is 10.
//Return Value
    //The generated random string.
```


```php
function sanitize_input() //This function sanitizes input by removing 
//leading and trailing white spaces, backslashes, and HTML special characters.

//Parameters
  $input //The input to be sanitized.

//Return Value
  //The sanitized input.
```

```php
function generateBody()
// This function generates the HTML body of the page by loading an HTML template from a file,
//replacing a placeholder with a randomly generated game ID, and returning the resulting HTML.

//Parameters
    None

//Return Value
    //The generated HTML.
``` 
