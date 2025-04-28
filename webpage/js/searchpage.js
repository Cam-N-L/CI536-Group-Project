window.addEventListener("load", function () {
    const submit = document.querySelector('#submission');
    submit.addEventListener("submit", showResult);

    function showResult(event) {
        event.preventDefault();
        var term = document.getElementById("searchTerm").value;

        var ele;
        var gameUsers = document.getElementsByName("games_user");
        for (var i = 0; i < gameUsers.length; i++) {
            if (gameUsers[i].checked) {
                ele = gameUsers[i].value;
                break;
            }
        }

        var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                document.getElementById("search").innerHTML = this.responseText;
                document.getElementById("search").style.border = "1px solid #A5ACB2";
            }
        };

        console.log(ele);
        if (ele === "user") {
            xmlhttp.open("GET", "../src/processSearchUsers.php?term=" + encodeURIComponent(term), true);
        } else {
            xmlhttp.open("GET", "../src/processSearchGames.php?term=" + encodeURIComponent(term), true);
        }
        
        // Send the request
        xmlhttp.send();
}
})
