

const card_set = ['10_of_clubs.png', '10_of_diamonds.png', '10_of_hearts.png' , '10_of_spades.png', '2_of_clubs.png', '2_of_diamonds.png', '2_of_hearts.png', '2_of_spades.png', '3_of_clubs.png', '3_of_diamonds.png', '3_of_hearts.png', '3_of_spades.png', '4_of_clubs.png', '4_of_diamonds.png', '4_of_hearts.png', '4_of_spades.png', '5_of_clubs.png', '5_of_diamonds.png', '5_of_hearts.png', '5_of_spades.png', '6_of_clubs.png', '6_of_diamonds.png', '6_of_hearts.png', '6_of_spades.png', '7_of_clubs.png', '7_of_diamonds.png', '7_of_hearts.png', '7_of_spades.png', '8_of_clubs.png', '8_of_diamonds.png', '8_of_hearts.png', '8_of_spades.png', '9_of_clubs.png', '9_of_diamonds.png', '9_of_hearts.png', '9_of_spades.png', 'ace_of_clubs.png', 'ace_of_diamonds.png', 'ace_of_hearts.png', 'ace_of_spades.png', 'jack_of_clubs.png', 'jack_of_diamonds.png', 'jack_of_hearts.png', 'jack_of_spades.png', 'king_of_clubs.png', 'king_of_diamonds.png', 'king_of_hearts.png', 'king_of_spades.png', 'queen_of_clubs.png', 'queen_of_diamonds.png', 'queen_of_hearts.png', 'queen_of_spades.png']
const avail_cards = []
const url = window.location.href.split("=")
var game_id = url[url.length -1]
const hidden_cards = {
    card: []
}
const open_cards = {
    card: []
}

setInterval(update, 1000);
function update(){
    $( document ).ready(function() {
        $.ajax({
            'url': 'game_data.php',
            'type': 'GET',
            'data': {'get_update' : game_id},
            'success': function(response) {
                console.log(response)
                const updates = response.split("#")
                console.log(updates)
              for (let i = 1; i < updates.length; i++) {
                elem = updates[i].split(":")
                elem_ = document.getElementById(elem[0])
                
                if (elem_ != null){
                    elem_.src = elem[3]
                    elem_ .style.left = elem[2]
                    elem_.style.top = elem[1] 
                }

              }

            }
            })
        })
}


var zIndex = 0

function loadimages(){
    
    $( document ).ready(function() {
        delete_imgs();
        $.ajax({
            'url': 'game_data.php',
            'type': 'GET',
            'data': {'load_game' : game_id},
            'success': function(response) {
              const played_cards = response.split("#")
              for (let i = 0; i < played_cards.length - 1; i++) {

                elem = played_cards[i].split(":")
                elem_ = document.getElementById(elem[0])

                var img = ""
                var class_name = ""
                yposition = 0
                xposition = 0
                img = document.createElement("img");

                img.id = elem[0];
                img.src = elem[3]
                img.style.backgroundColor = "white"
                img.style.border = "thin solid Black"
                img.style.position = "absolute"
                img.style.left = elem[2] 
                img.style.top = elem[1]
                img.style.cursor = "grab"

                zIndex += 1
                img.style.zIndex = zIndex

                img.style.height = "100px"
                img.style.width = "60px"
            
                class_name =  img.id;
                img.setAttribute("class", class_name);
                avail_cards.push(img)
                document.getElementById("game-field").append(img)
                $(img).draggable();
                

              }
              check();

            }
            })
        }
        )
        
}

function check(){
    for (let i = 0; i < avail_cards.length; i++) {
        avail_cards[i].addEventListener("mousedown", function(){
            avail_cards[i].style.cursor = "grabbing" 
            zIndex += 1
            avail_cards[i].style.zIndex = zIndex
        })
        avail_cards[i].addEventListener("mouseup",function(){
                avail_cards[i].style.cursor = "grab" 
                check_cards = avail_cards[i].src.split("/")
            if (check_cards[check_cards.length - 1] == "cards_back.png"){
                $( document ).ready(function() {
                    $.ajax({
                        'url': 'game_data.php',
                        'type': 'GET',
                        'data': {'game_id': game_id, 'new_card': avail_cards[i].id, 'left_val':avail_cards[i].style.left, 'top_val':avail_cards[i].style.top},
                        'success': function(response) {
                            avail_cards[i].src = response
                        }
                    })});

            }else{
                $( document ).ready(function() {
                    $.ajax({
                        'url': 'game_data.php',
                        'type': 'GET',
                        'data': {'game_id': game_id, 'card': avail_cards[i].id, 'left_val':avail_cards[i].style.left, 'top_val':avail_cards[i].style.top},
                        'success': function(response) {
                        }
                    })});

            }
            zIndex += 1
            avail_cards[i].style.zIndex = zIndex
            


            }) 

       

    }

}


function delete_imgs(){
    zIndex = 0
    for (let i = 0; i < avail_cards.length; i++) {
        avail_cards[i].remove()
    }
}


function shuffle(array) {
    let currentIndex = array.length,  randomIndex;
  
    // While there remain elements to shuffle.
    while (currentIndex != 0) {
  
      // Pick a remaining element.
      randomIndex = Math.floor(Math.random() * currentIndex);
      currentIndex--;
  
      // And swap it with the current element.
      [array[currentIndex], array[randomIndex]] = [
        array[randomIndex], array[currentIndex]];
    }
  
    return array;
  }


  function get_hidden_cards(){
    var jsonstr = JSON.stringify(hidden_cards.card);
    return jsonstr
  }

  function copy_url() {
    // Get the text field
  
    // Select the text field
    copy_link = document.getElementById("link-input")
    copy_link.focus();
    copy_link.select();
    copy_link.setSelectionRange(0, 99999); // For mobile devices
  
     // Copy the text inside the text field
     try {
        navigator.clipboard.writeText(copy_link.value);
      } catch (err) {
        document.execCommand("copy");
      }
  
  } 

  $( document ).ready(function() {
  const copy_link = document.getElementById("link-input")
  const copy_btn = document.getElementById("copy-link-btn")

  copy_btn.addEventListener("click",function(){
    copy_url()
  })
  copy_link.value = window.location.href.split("#")[0]
  })
