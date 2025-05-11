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
          document.getElementById("livesearch").style.border = "1px solid #e1697e";
          document.getElementById("livesearch").style.backgroundColor = "#e1697e";
      }
  };
  
  document.getElementById("livesearchinputHidden").value = str;
  str = encodeURIComponent(str);
  xmlhttp.open("GET", "../src/livesearch.php?q=" + str, true);
  xmlhttp.send();
}


function titleClicked(str) {
    document.getElementById("livesearchinput").value = str;
    document.getElementById("livesearchinputHidden").value = str;
    showResult(str);
}