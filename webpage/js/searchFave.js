document.addEventListener('DOMContentLoaded', () => {
    const gamesList = document.getElementsByClassName("sortable-list")[0];
    const gameNames = document.getElementById("gameNames");

    const updateHiddenInput = () => {
        const items = gamesList.children;
        const names = [];

        for (let item of items) {
            names.push(item.textContent.trim());
        }

        gameNames.value = names.join(",");
    };

    const observer = new MutationObserver((mutationList, observer) => {
        for (const mutation of mutationList) {
            if (mutation.type === "childList") {
                updateHiddenInput();
            }
        }
    });

    const config = { childList: true };
    observer.observe(gamesList, config);

    // Optional: also update on DOM load or manual changes
    updateHiddenInput();

    for (let item of gamesList.children) {
        item.addEventListener("dblclick", (event) => {
            deleteItemFromList(item);
        });
    }
});

function showResult(str) {
    if (str.length === 0) {
        document.getElementById("livesearch").innerHTML = "";
        document.getElementById("livesearch").style.border = "0px";
        return;
    }
  
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            document.getElementById("livesearch").innerHTML = this.responseText;
            document.getElementById("livesearch").style.border = "1px solid #A5ACB2";
            document.getElementById("livesearch").style.backgroundColor = "#A5ACB2";
        }
    };
    
    str = encodeURIComponent(str);
    xmlhttp.open("GET", "../src/livesearch.php?q=" + str, true);
    xmlhttp.send();
  }
  
  
function titleClicked(str) {
    var list = document.getElementsByClassName("sortable-list")[0];
    if (list.children.length < 5){
        document.getElementById("livesearchinput").value = "";
        var entry = document.createElement('li');
        entry.appendChild(document.createTextNode(str));
        entry.setAttribute("class", "sortable-item");
        entry.setAttribute("draggable", "true");
        entry.addEventListener("dblclick", (event) => {
            deleteItemFromList(entry);
        });
        list.appendChild(entry);
        document.getElementById("hint").innerHTML = "search to add up to five games, drag to order or double click to remove!";
    } else {
        document.getElementById("hint").innerHTML = "please remove a game, as you already have 5";
    }
  }

function deleteItemFromList(item){
    item.remove();
}

document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('favs');
    const hiddenInput = document.getElementById('gameList');
    const list = document.querySelector('.sortable-list');

    form.addEventListener('submit', (e) => {
        const items = list.querySelectorAll('.sortable-item');
        const gameNames = [];

        items.forEach(item => {
            gameNames.push(item.textContent.trim());
        });

        // Join items into a comma-separated string or JSON
        hiddenInput.value = JSON.stringify(gameNames);
    });
});
