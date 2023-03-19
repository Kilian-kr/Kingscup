// Initialize an empty array to hold available cards
const avail_cards = [];

// Split the URL to get the game ID
const url = window.location.href.split("=");
const game_id = url[url.length - 1].split("#")[0];

// Set the initial z-index value for cards
var zIndex = 0;

// Set an interval to update the game state every second
let intervalId = setInterval(update, 100);

// Function to pause the interval
function pauseInterval() {
  clearInterval(intervalId);
}

// Function to start the interval again after pausing it
function startInterval() {
  intervalId = setInterval(update, 100);
}

// Function to update the game state
function update() {
  // Wait for the DOM to be ready before making the AJAX call
  $(document).ready(function () {
    // Make an AJAX call to get the game data
    $.ajax({
      url: "game_data.php",
      type: "GET",
      data: { get_update: game_id },
      success: function (response) {
        // Split the response into updates
        const updates = response.split("#");
        // Loop through each update
        for (let i = 1; i < updates.length; i++) {
          // Split the update into individual elements
          elem = updates[i].split(":");
          // Get the corresponding element in the DOM
          elem_ = document.getElementById(elem[0]);
          // If the element exists in the DOM, update its properties
          if (elem_ !== null) {
            elem_.src = elem[3];
            elem_.style.left = elem[2];
            elem_.style.top = elem[1];
          }
        }
      },
    });
  });
}

// Function to load images and add them to the game field
function loadimages() {
  // Wait for the DOM to be ready before making the AJAX call
  $(document).ready(function () {
    // Call the deleteImages function to remove any existing images from the game field
    deleteImages();
    // Make an AJAX call to get the game data
    $.ajax({
      url: "game_data.php",
      type: "GET",
      data: { load_game: game_id },
      success: function (response) {
        // Split the response into played cards
        const played_cards = response.split("#");
        // Loop through each played card
        for (let i = 0; i < played_cards.length - 1; i++) {
          // Split the card into individual elements
          const elem = played_cards[i].split(":");
          // Create a new image element
          const img = document.createElement("img");

          // Set the ID, source, and position properties of the image
          img.id = elem[0];
          img.src = elem[3];
          img.style.backgroundColor = "white";
          img.style.border = "thin solid Black";
          img.style.position = "absolute";
          img.style.left = elem[2];
          img.style.top = elem[1];
          img.style.cursor = "grab";

          // Set the z-index property of the image
          zIndex += 1;
          img.style.zIndex = zIndex;

          // Set the height and width of the image
          img.style.height = "100px";
          img.style.width = "60px";

          // Set the class attribute of the image
          const class_name = img.id;
          img.setAttribute("class", class_name);

          // Add the image to the available cards array and the game field
          avail_cards.push(img);
          document.getElementById("game-field").append(img);

          // Make the image draggable
          $(img).draggable();
        }
        // Call the addEventListenersToCards function to add event listeners to the cards
        addEventListenersToCards();
        // Start the interval again after loading the images
        startInterval();
      },
    });
  });
}

// This function adds event listeners to the available cards 
function addEventListenersToCards() {
  // Iterate through each card
  avail_cards.forEach((card) => {
    // Add a mousedown event listener to the card
    card.addEventListener("mousedown", () => {
      // Pause the interval that updates the game
      pauseInterval();
      // Set the cursor style to grabbing
      card.style.cursor = "grabbing";
      // Increment the zIndex and set it to the card's zIndex
      zIndex += 1;
      card.style.zIndex = zIndex;
    });

    // Add a mouseup event listener to the card
    card.addEventListener("mouseup", () => {
      // Set the cursor style back to grab
      card.style.cursor = "grab";
      // Get the name of the image file from the source path
      const checkCards = card.src.split("/");
      // If the image is a card_back, it means it was just flipped over
      if (checkCards[checkCards.length - 1] === "cards_back.png") {
        // Send a request to update the game with the new card
        $.ajax({
          url: "game_data.php",
          type: "GET",
          data: {
            game_id: game_id,
            new_card: card.id,
            left_val: card.style.left,
            top_val: card.style.top,
          },
          success: (response) => {
            // Change the source of the card to the new card's image
            card.src = response;
          },
        });
      } else {
        // If the image is not a card_back, it means the card was moved
        // Send a request to update the game with the card's new position
        $.ajax({
          url: "game_data.php",
          type: "GET",
          data: {
            game_id: game_id,
            card: card.id,
            left_val: card.style.left,
            top_val: card.style.top,
          },
          success: (response) => {
            // Do nothing on success
          },
        });
      }
      // Start the interval that updates the game again
      startInterval();
      // Increment the zIndex and set it to the card's zIndex
      zIndex += 1;
      card.style.zIndex = zIndex;
    });
  });
}


// Function to delete all images
function deleteImages() {
  avail_cards.forEach((card) => {
  card.remove();
  });
}
  
  // Function to copy URL to clipboard
function copyUrl() {
  // Get link input element
  const copyLink = document.getElementById("link-input");
  // Set focus to link input element, select all its content
  copyLink.focus();
  copyLink.select();
  copyLink.setSelectionRange(0, 99999);
  
  try {
  // Try to write the URL to the clipboard using navigator.clipboard API
    navigator.clipboard.writeText(copyLink.value);
  } catch (err) {
  // If navigator.clipboard API is not available, use execCommand to copy the URL to the clipboard
    document.execCommand("copy");
  }
}
  
  // Function to add event listener to copy link button
function addEventListenerToCopyBtn() {
  const copyBtn = document.getElementById("copy-link-btn");
  copyBtn.addEventListener("click", () => {
  copyUrl();
  });
}
  
  // Function to set the value of the link input element to the URL of the current page without the hash
function setUrlValue() {
  const copyLink = document.getElementById("link-input");
  copyLink.value = window.location.href.split("#")[0];
}
  
  // Document ready function
$(document).ready(() => {
  // Add event listener to copy link button
  addEventListenerToCopyBtn();
  // Set the value of the link input element
  setUrlValue();
  // Pause the interval for updating the game board
  pauseInterval();
});