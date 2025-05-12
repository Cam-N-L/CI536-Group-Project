function showResult() {
    var xmlhttp=new XMLHttpRequest();
    xmlhttp.onreadystatechange=function() {
      if (this.readyState==4 && this.status==200) {
        document.getElementById("activity-section").innerHTML=this.responseText;
        document.getElementById("activity-section").style.border="1px solid #A5ACB2";
      }
    }
    xmlhttp.open("GET","../src/gatherGameActivity.php?",true);
    xmlhttp.send();
  }