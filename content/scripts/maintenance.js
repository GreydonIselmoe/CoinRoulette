$(document).ready(function(){
  _ready();
});
function _ready() {


  var cookieTheme = readCookie('theme');
  if (cookieTheme != null) {
    setTheme(cookieTheme);
  }

  var defTheme = default_theme();
  if (defTheme != '1' && cookieTheme == null) {
    setTheme(defTheme);
  }

  setInterval(function(){
    imitateCRON();
  },1000);

}



function imitateCRON() {
  $.ajax({
    'url': './content/ajax/getDeposits.php',
    'dataType': "json",
    'success': function (data) {
      if (data['maintenance'] == 'no') location.reload();
    }  
  });
}

function setTheme(id) {
    $('.themeLinker').attr('href','./styles/themes/'+id+'/style.css');
    $('body').css('background-image',"url('./styles/themes/"+id+"/bg.jpg')");
    
}


function createCookie(name,value,days) {
    if (days) {
        var date = new Date();
        date.setTime(date.getTime()+(days*24*60*60*1000));
        var expires = "; expires="+date.toGMTString();
    }
    else var expires = "";
    document.cookie = name+"="+value+expires+"; path=/";
}

function readCookie(name) {
    var nameEQ = name + "=";
    var ca = document.cookie.split(';');
    for(var i=0;i < ca.length;i++) {
        var c = ca[i];
        while (c.charAt(0)==' ') c = c.substring(1,c.length);
        if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length,c.length);
    }
    return null;
}