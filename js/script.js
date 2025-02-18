
function setconfirmed(str) {
  if (str == "") {
    document.getElementById("confirmation"+str).innerHTML = "";
    return;
  }
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
      if (this.readyState == 4 && this.status == 200) {
        document.getElementById("confirmation"+str).innerHTML = '<p class="order-status" style="color:greenyellow;" id="confirmation"><i class="bi bi-check"></i> Confirmed</p>';
        statusbtns = document.getElementById('statusbtns'+str);
        confbtns = document.getElementById('confirmationbtns'+str);
        confbtns.style.display = 'none';
        statusbtns.style.display = 'block';
        statusbtns.getElementsByTagName('button')[0].style.display = 'inline-block'; //Printed Btn
        statusbtns.getElementsByTagName('button')[1].style.display = 'none'; //Fulfilled Btn
        statusbtns.getElementsByTagName('button')[2].style.display = 'none'; //Cash Btn
        statusbtns.getElementsByTagName('button')[3].style.display = 'none'; //Visa Btn
        statusbtns.getElementsByTagName('button')[4].style.display = 'inline-block'; //Cancel Btn
      }
    };
    xmlhttp.open("GET","./database/updateconfirmation.php?q="+str,true);
    xmlhttp.send();
}


function setcancelled(str) {
  if (confirm("Are You Sure to Cancel This Order ?") == true) {
  if (str == "") {
    document.getElementById("confirmation"+str).innerHTML = "";
    return;
  }
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
      if (this.readyState == 4 && this.status == 200) {
        document.getElementById("confirmation"+str).innerHTML = '<p class="order-status" style="color:red;" id="confirmation"><i class="bi bi-x-circle-fill"> Cancelled</p>';
        statusbtns = document.getElementById('statusbtns'+str);
        confbtns = document.getElementById('confirmationbtns'+str);
        confbtns.style.display = 'none';
        statusbtns.style.display = 'none';
      }
    };
    xmlhttp.open("GET","./database/setcancelled.php?q="+str,true);
    xmlhttp.send();
}
  
}


function setnoanswer(str) {
  if (str == "") {
    document.getElementById("confirmation"+str).innerHTML = "";
    return;
  }
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
      if (this.readyState == 4 && this.status == 200) {
        document.getElementById("confirmation"+str).innerHTML = '<p class="order-status" style="color:yellow;" id="confirmation"><i class="bi bi-telephone-x-fill"></i> No Answer</p>';
        statusbtns = document.getElementById('statusbtns'+str);
        confbtns = document.getElementById('confirmationbtns'+str);
        confbtns.style.display = 'block';
        statusbtns.style.display = 'none';
      }
    };
    xmlhttp.open("GET","./database/setnoanswer.php?q="+str,true);
    xmlhttp.send();
}


function setonhold(str) {
  if (str == "") {
    document.getElementById("confirmation"+str).innerHTML = "";
    return;
  }
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
      if (this.readyState == 4 && this.status == 200) {
        document.getElementById("confirmation"+str).innerHTML = '<p class="order-status" style="color:orange;" id="confirmation"><i class="bi bi-phone-vibrate-fill"></i> On Hold</p>';
        statusbtns = document.getElementById('statusbtns'+str);
        confbtns = document.getElementById('confirmationbtns'+str);
        confbtns.style.display = 'block';
        statusbtns.style.display = 'none';
      }
    };
    xmlhttp.open("GET","./database/setonhold.php?q="+str,true);
    xmlhttp.send();
}

function changecomment(str) {
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
      if (this.readyState == 4 && this.status == 200) {
        let $com = document.getElementById("thecomment"+str).value;
      }
    };
      var $com = document.getElementById("thecomment"+str).value;
    xmlhttp.open("GET","./database/changecomment.php?q="+str+"&comm="+$com,true);
    xmlhttp.send();
}
function changebranch(str) {
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
      if (this.readyState == 4 && this.status == 200) {
        $("#analysiscontainer").load(location.href + " #analysiscontainer");
      }
    };
      var $branch = document.getElementById("thebranch"+str).value;
    xmlhttp.open("GET","./database/changebranch.php?q="+str+"&branch="+$branch,true);
    xmlhttp.send();
}

function changeddate(str) {
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
      if (this.readyState == 4 && this.status == 200) {
        let $ddate = document.getElementById("theddate"+str).value;
        $("#analysiscontainer").load(location.href + " #analysiscontainer");
      }
    };

      var $ddate = document.getElementById("theddate"+str).value;
    xmlhttp.open("GET","./database/changeddate.php?q="+str+"&date="+$ddate,true);
    xmlhttp.send();
}

function setprinted(str) {
  if (str == "") {
    document.getElementById("ostatus").innerHTML = "";
    return;
  }
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
      if (this.readyState == 4 && this.status == 200) {
        document.getElementById('ostatus'+str).innerHTML = '<p class="order-status" style="color:yellow;" id="ostatus"><i class="bi bi-printer-fill"></i> Printed</p>';
        statusbtns = document.getElementById('statusbtns'+str);
        confbtns = document.getElementById('confirmationbtns'+str);
        confbtns.style.display = 'none';
        statusbtns.style.display = 'block';
        statusbtns.getElementsByTagName('button')[0].style.display = 'none'; //Printed Btn
        statusbtns.getElementsByTagName('button')[1].style.display = 'inline-block'; //Fulfilled Btn
        statusbtns.getElementsByTagName('button')[2].style.display = 'none'; //Cash Btn
        statusbtns.getElementsByTagName('button')[3].style.display = 'none'; //Visa Btn
        statusbtns.getElementsByTagName('button')[4].style.display = 'none'; //Cancel Btn
        $("#analysiscontainer").load(location.href + " #analysiscontainer");
      }
    };
    xmlhttp.open("GET","./database/setprinted.php?q="+str,true);
    xmlhttp.send();
}

function setfulfilled(str) {
  if (str == "") {
    document.getElementById("ostatus").innerHTML = "";
    return;
  }
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
      if (this.readyState == 4 && this.status == 200) {
        document.getElementById('ostatus'+str).innerHTML = '<p class="order-status" style="color:yellow;" id="ostatus"><i class="bi bi-truck"></i> Fulfilled</p>';
        statusbtns = document.getElementById('statusbtns'+str);
        confbtns = document.getElementById('confirmationbtns'+str);
        confbtns.style.display = 'none';
        statusbtns.style.display = 'block';
        statusbtns.getElementsByTagName('button')[0].style.display = 'none'; //Printed Btn
        statusbtns.getElementsByTagName('button')[1].style.display = 'none'; //Fulfilled Btn
        statusbtns.getElementsByTagName('button')[2].style.display = 'inline-block'; //Cash Btn
        statusbtns.getElementsByTagName('button')[3].style.display = 'inline-block'; //Visa Btn
        statusbtns.getElementsByTagName('button')[4].style.display = 'inline-block'; //Cancel Btn
        document.getElementById('thebranch'+str).style.display = 'inline-block';
        $("#analysiscontainer").load(location.href + " #analysiscontainer");
      }
    };
    xmlhttp.open("GET","./database/setfulfilled.php?q="+str,true);
    xmlhttp.send();
}


function setcash(str) {
  if (str == "") {
    document.getElementById("ostatus").innerHTML = "";
    return;
  }
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
      if (this.readyState == 4 && this.status == 200) {
        document.getElementById('ostatus'+str).innerHTML = '<p class="order-status" style="color:greenyellow;" id="ostatus"><i class="bi bi-cash-coin"></i> Cash</p>';
        statusbtns = document.getElementById('statusbtns'+str);
        confbtns = document.getElementById('confirmationbtns'+str);
        confbtns.style.display = 'none';
        statusbtns.style.display = 'none';
        document.getElementById('thebranch'+str).style.display = 'none';   
        $("#analysiscontainer").load(location.href + " #analysiscontainer");   
      }
    };
    xmlhttp.open("GET","./database/setcash.php?q="+str,true);
    xmlhttp.send();
}

function setvisa(str) {
  if (str == "") {
    document.getElementById("ostatus").innerHTML = "";
    return;
  }
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
      if (this.readyState == 4 && this.status == 200) {
        document.getElementById('ostatus'+str).innerHTML = '<p class="order-status" style="color:greenyellow;" id="ostatus"><i class="bi bi-credit-card-2-front-fill"></i> Visa</p>';
        statusbtns = document.getElementById('statusbtns'+str);
        confbtns = document.getElementById('confirmationbtns'+str);
        confbtns.style.display = 'none';
        statusbtns.style.display = 'none';
        document.getElementById('thebranch'+str).style.display = 'none';  
        $("#analysiscontainer").load(location.href + " #analysiscontainer");   
      }
    };
    xmlhttp.open("GET","./database/setvisa.php?q="+str,true);
    xmlhttp.send();
}

function setocancelled(str) {
  if (confirm("Are You Sure to Cancel This Order ?") == true) {
    if (str == "") {
      document.getElementById("ostatus").innerHTML = "";
      return;
    }
      var xmlhttp = new XMLHttpRequest();
      xmlhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
          document.getElementById('ostatus'+str).innerHTML = '<p class="order-status" style="color:red;" id="ostatus"><i class="bi bi-x-circle-fill"></i> Cancelled</p>';
          statusbtns = document.getElementById('statusbtns'+str);
          confbtns = document.getElementById('confirmationbtns'+str);
          confbtns.style.display = 'none';
          statusbtns.style.display = 'none';
          document.getElementById('thebranch'+str).style.display = 'none';   
          $("#analysiscontainer").load(location.href + " #analysiscontainer");  
        }
      };
      xmlhttp.open("GET","./database/setocancelled.php?q="+str,true);
      xmlhttp.send();
  }
}
function cancelitem(str,clo) {
  if (confirm("Are You Sure to Cancel This Order ?") == true) {
      var xmlhttp = new XMLHttpRequest();
      xmlhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
          var y = "myPopup"+clo ;
          var popup = document.getElementById(y);
          popup.style.visibility = "hidden";
        }
      };
      xmlhttp.open("GET","./database/cancelitem.php?id="+str,true);
      xmlhttp.send();
  }
}

setInterval(function(){ 
  var ifConnected = window.navigator.onLine;
    if (ifConnected) {
      document.getElementById("onlinecheck").style.display = 'none';
    } else {
      document.getElementById("onlinecheck").style.display = 'block';
    }
 },);

    //$("#closebtn").click(function(){
    //  $("#addneworder").fadeOut();
   // });


    function addnewinput(){
      var x = document.getElementById("inputgroupss").lastElementChild.name;
      
      newx = x .replace(/(\d+)+/g, function(match, number) {return parseInt(number)+1;});


      document.getElementById("legend").innerHTML = parseInt(x);
      var zz = '&nbsp;<div class="input-group-prepend"><span class="input-group-text" id="inputGroup-sizing-default">'+newx.match(/(\d+)/)[0]+'</span></div><input id="'+newx+'" name="'+newx+'" type="text" class="form-control" aria-label="Default" aria-describedby="inputGroup-sizing-default" required></div>';
      var zz = '<input type="text" name="'+newx+'" class="form-control hey99" id="hey99" placeholder="'+newx.match(/(\d+)/)[0]+'" required>'
    document.getElementById("inputgroupss").innerHTML = document.getElementById("inputgroupss").innerHTML+zz;
    document.getElementById("noofitems").value = document.getElementById("noofitems").value.replace(/(\d+)+/g, function(match, number) {return parseInt(number)+1;});
}


function removeinput() {
  var x = document.getElementById("inputgroupss").lastElementChild.name;
  if (x !== 'item1') {
     document.getElementById("inputgroupss").lastElementChild.remove();
     document.getElementById("noofitems").value = document.getElementById("noofitems").value.replace(/(\d+)+/g, function(match, number) {return parseInt(number)-1;});
  }

}
function openbtn(){
  document.getElementById("addneworder").style.display = 'block';
}
function closebtn(){
  document.getElementById("addneworder").style.display = 'none';
}
    