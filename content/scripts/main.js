//Úhly pro rotaci obrázku kuličky
var positions = [-360,-136.5,-301.5,-20,-321.2,-175.2,-262.5,-58.5,-204.5,-97.5,-185,-224,-39,-243.2,-117,-340.5,-156,-282,-78,-331,-127,-311.2,-88,-194.2,-165.2,-292,-10,-253,-48.5,-68.5,-214.2,-107.5,-350.2,-146.2,-272.2,-29,-233.5];
//Červená políčka
var red = [1,3,5,7,9,12,14,16,18,19,21,23,25,27,30,32,34,36];
var bet = 10;
var bets = [];
var angle;
var muted = false;
var ajaxBetLock = false;
var timer;
var active;
var spins = 4; //Must be integer

$(document).ready(function (){
  window.location.href = './?unique='+unique()+'# Do Not Share This URL!';

  placeSite();

  $('.leftbuttons button').each(function(){
    $(this).tooltip();
  });
  $('.tooltips').each(function(){
    $(this).tooltip();
  });



  $('.chat-input').keypress(function(e){
    if (e.which == 13) chatSend();
  });


  setInterval(function(){
    chatUpdate();
  },500);
  setInterval(function(){
    stats.update();
  },500);
  stats.update();

  setLeftbarH();
  setInterval(function(){

    setLeftbarH();
  },100);

  $('.st-switches a').each(function(){
    $(this).click(function(e){

      if ($(this).hasClass('rulesB')) return;

      e.preventDefault();
      $('.st-switches a.active').removeClass('active');
      $(this).addClass('active');

      stats.go( $(this).attr('data-load') );

    });
  });
  $('.st-switches a').eq(2).click();



  $('.leftbuttons button').each(function(){
    $(this).click(function(e){
      e.preventDefault();
      $('.leftbuttons button.active').removeClass('active');
      $(this).addClass('active');
    });
  });


  setInterval(function(){
    $.ajax({'url':'./content/ajax/refreshSession.php'});
  },10000);
  imitateCRON();
  setInterval(function(){
    imitateCRON();
  },1000);


  $('.wager').change(function(){
    formatWager();
  });



  $('.clientseedsave').click(function(e){

    e.preventDefault();

    var input = $('#_fair_client_seed');

    $.ajax({
      'url': './content/ajax/saveClientSeed.php?_unique='+unique()+'&seed='+input.val(),
      'dataType': 'json',
      'success': function(data) {
        input.val(data['repaired']);
        alert(data['content']);
      }
    });

  });

  investUpdate();
  statsUpdate();
  setInterval(function(){

    investUpdate();
    statsUpdate();

  }, 5000);
  setInterval(function(){

    balanceUpdate();

  }, 1000);


  $('.deposit_btn').click(function(){
    $('#modals-deposit').modal('show');
    _genNewAddress();
  });
  $('.withdraw_btn').click(function(){
    $('#modals-withdraw').modal();
  });

  $('.modal').on('show.bs.modal',function(){
    $('.m_alert').hide();
  });

  $('.captchadiv').click();


  preloadImg('content/images/1k.png');
  preloadImg('content/images/10k.png');
  preloadImg('content/images/100.png');
  preloadImg('content/images/10.png');
  preloadImg('content/images/unmute.png');

  var rotate_wheel = function (){
    $(".wheel").rotate({
      angle:0,
      animateTo: 360,
      duration: 360*40,
      callback: rotate_wheel,
      easing: function (x,t,b,c,d){        // t: current time, b: begInnIng value, c: change In value, d: duration
        return c*(t/d)+b;
      }
    });
  };
  rotate_wheel();

  $('.horizontal-line-bet, .vertical-line-bet, .corner-bet, .multiple-bet, .board-cell')
      .hover(function () { //Po najetí na nějaký toogle zvýrazní čísla, kterých se sázka týká
        if (!ajaxBetLock) {
          if ($(this).data('bet-position').toString().indexOf(',') == -1) {
            $('*[data-bet-position=' + $(this).data('bet-position') + ']').addClass('highlight');
          }
          else {
            var positions = $(this).data('bet-position').split(',');
            for (var i = 0; i < positions.length; ++i) {
              $('*[data-bet-position=' + positions[i] + ']').addClass('highlight');
            }
          }
        }
      }, function () {
        if (!ajaxBetLock) {
          if ($(this).data('bet-position').toString().indexOf(',') == -1) {
            $('*[data-bet-position=' + $(this).data('bet-position') + ']').removeClass('highlight');
          }
          else {
            var positions = $(this).data('bet-position').split(',');
            for (var i = 0; i < positions.length; ++i) {
              $('*[data-bet-position=' + positions[i] + ']').removeClass('highlight');
            }
          }
        }
      })
      .mousedown(function(e) { //Po kliknuté levým tlačítkem přidá žeton o zvolenou hodnotu, po kliknutí pravým tlačítkem odebere, při přidržení tlačítek provádí akci po 700ms intervalu
        if (!ajaxBetLock) {
          var target = $(this);

          function bet() {
            if (e.which === 1) {
              addChips(target, window.bet);
            }
            if (e.which === 3) {
              takeChips(target, window.bet);
            }
          }

          bet();
          timer = setInterval(function () {
            bet();
          }, 700);
        }
      })
      .mouseup(function(){
        if (timer) clearInterval(timer);
      });


  $('.chip').bind("contextmenu",function(e){
    e.preventDefault();//or return false;
  });

  $('.chip-size').click(function (){
    $('.chip-size.selected-chip').removeClass('selected-chip');
    $(this).addClass('selected-chip');
  });

  $(function () {
    $('[data-toggle="tooltip"]').tooltip()
  });


});
function _withdraw() {
  $.ajax({
    'url': './content/ajax/__withdraw.php?_unique='+unique()+'&valid_addr='+$('#input-address').val()+'&amount='+$('#input-am').val(),
    'dataType': "json",
    'success': function (data) {
      if (data['error']=='yes') {
        $('.m_alert').hide();
        $('.m_alert').html('<div class="alert alert-dismissable alert-warning"><button type="button" class="close" data-dismiss="alert">×</button><b>Error!</b> '+data['content']+'</div>');
        $('.m_alert').slideDown();
      }
      else if (data['error'] == 'half') {
        $('.m_alert').hide();
        $('.m_alert').html('<div class="alert alert-dismissable alert-success"><button type="button" class="close" data-dismiss="alert">×</button><b>Withdrawal request has been placed.</b></div>');
        $('.m_alert').slideDown();
      }
      else {
        $('.m_alert').hide();
        $('.m_alert').html('<div class="alert alert-dismissable alert-success"><button type="button" class="close" data-dismiss="alert">×</button><b>Processed.</b><br>TXID: <small>'+data['content']+'</small></div>');
        $('.m_alert').slideDown();
      }
    }
  });
}

function _genNewAddress() {
  $.ajax({
    'url': './content/ajax/getAddress.php?_unique='+unique(),
    'dataType': "json",
    'success': function (data) {
      $('.addr-p').html(data['confirmed']);
      $('.addr-qr').empty();
      $('.addr-qr').qrcode(data['confirmed']);
    }
  });
}
function clickPending() {
  if ($('.pendingbutton').attr('cj-opened')=='yes') hidePending();
  else showPending();
}
function showPending() {
  $.ajax({
    'url': './content/ajax/getPending.php?_unique='+unique(),
    'dataType': "json",
    'success': function (data) {
      $('.pendingDeposits').html(data['content']);
      $('.pendingDeposits').slideDown();
      $('.pendingbutton').html('Hide Pending');
      $('.pendingbutton').attr('cj-opened','yes')
    }
  });
}
function hidePending() {
  $('.pendingDeposits').slideUp();
  $('.pendingbutton').html('Show Pending');
  $('.pendingbutton').attr('cj-opened','no');
}

function balanceUpdate() {

  $.ajax({
    'url': './content/ajax/getBalance.php?_unique='+unique(),
    'dataType': "json",
    'success': function (data) {

      $('.balance').each(function(){
        $(this).text(data['balance']);
      });

    }
  });
}


function imitateCRON() {
  $.ajax({
    'url': './content/ajax/getDeposits.php',
    'dataType': "json",
    'success': function (data) {
      if (data['maintenance'] == 'yes') location.reload();
    }
  });
}


function setLeftbarH() {
  leftbox.$obj().height( $(window).height() - 60 );
  $('.leftCon').each(function(){
    var footer = $(this).children('.footer').outerHeight();
    $(this).children('.content').css('height', parseInt($(window).height()) - 60 - 41 - footer );
  });

}

function setStatsH() {
  $('.st-stats').height('inherit');
  if ($('.st-stats').offset().top + $('.st-stats').outerHeight() <= $(window).height()) {

    $('.st-stats').height($(window).height() - ($('.st-stats').offset().top + 40) );

  }
}

function setPage() {
    if (leftbox.lock) return;

    if (leftbox.opened) var minus = 0;
    else var minus = 2;
    $('.page').width($(window).width() - ( (leftbox.$obj().outerWidth() -minus )  ));
    $('.page').css('margin-left',leftbox.$obj().width() + parseFloat(leftbox.$obj().css('padding-left')) + parseFloat(leftbox.$obj().css('padding-right')) );

}


var chatReceiveUpdates = false;
var chatUpdating = false;


function chatSend() {
  var dataToSend = encodeURIComponent($('.chat-input').val());
  $.ajax({
    'url': './content/ajax/chatSend.php?_unique='+unique()+'&data='+dataToSend,
    'dataType': "json",
    'success': function(data) {
      if (data['error']=='yes' && data['content']=='max_in_row') alert('You can\'t post more than 10 messages in a row.');
      else {
        chatUpdate();
        $('.chat-input').val('');
      }
    }
  });
}



function chatUpdate(first) {

  if (!leftbox.$obj().children('#lc-chat').length) return;
  if (chatUpdating) return;
  if (!chatReceiveUpdates) return;

  var lastID = 0;
  if ($('.chat-message').length)
    lastID = $('.chat-message').last().attr('data-messid');



  chatUpdating = true;

  $.ajax({
    'url': './content/ajax/chatUpdate.php?_unique='+unique()+'&lastId='+lastID,
    'dataType': "json",
    'success': function(data) {

      var $messages = $(data['content']);

      var $existingMessages = leftbox.$obj().find('.content .mCSB_container');

      $messages.each(function(){
        var $message = $(this).remove();
        var messid = $message.attr('data-messid');

      });

      $existingMessages.append($messages);

      if (!leftbox.scrolled) {

        if (leftbox.first)
          setTimeout(function(){
            leftbox.$obj().find('.content').mCustomScrollbar('scrollTo','last',{scrollInertia:100});
            setTimeout(function(){ leftbox.$obj().find('.content').mCustomScrollbar('scrollTo','last',{scrollInertia:50}); },110)
          },100);
        else if ($messages.length) leftbox.$obj().find('.content').mCustomScrollbar('scrollTo','bottom',{scrollInertia:100,callbacks:true});

      }

      chatUpdating = false;
    }
  });
}

function placeSite() {

  var lMargin = $('.game').height()/2 - $('.leftbuttons').height()/2;
  $('.leftbuttons').css('margin-top',lMargin);

}

function leftbox() {

  var self = this;

  self.opened = false;
  self.lock = false;

  self.toggle = function () {

    if (self.lock) return;
    self.lock = true;

    if (self.opened) {

      self.con = '';

      self.$obj().animate({
        'width': 0,
        'padding-left': 0,
        'padding-right': 0
      },{
        'duration': 300,
        'done': function() {
          self.opened = false;
          self.$obj().hide();
          self.lock = false;
          $('.closeLeft').hide();
          $('.leftbuttons button.active').removeClass('active');

        },
        'progress': function() {
          $('.page').width($(window).width() - ( (self.$obj().outerWidth() -2 )  ));
          $('.page').css('margin-left',self.$obj().width() + parseFloat(self.$obj().css('padding-left')) + parseFloat(self.$obj().css('padding-right')) );

        }
      });
      $('.st-stats table').animate({
        'width': 948
      },300);

    }
    else {

      self.$obj().show();
      $('.closeLeft').show();
      self.$obj().animate({
        'width': self.width,
        'padding-left': 10,
        'padding-right': 10
      },{
        'duration': 300,
        'done': function() {
          self.opened = true;
          self.lock = false;
          self.scrollbar();
        },
        'progress': function() {
          $('.page').width($(window).width() - ( self.$obj().outerWidth() + $('.lefbuttons').width() ));
          $('.page').css('margin-left',self.$obj().outerWidth() + $('.lefbuttons').width());

          if ($('.st-stats table').width() + 20 > $('.page').width() ) $('.st-stats table').width( $('.page').width() - 20 );
        }
      });

    }
  }

  self.width = 260;
  self.$obj = function() {
    return $('.leftblock');
  }
  self.scrollbar = function() {

    chatUpdate();
    self.first = true;

    var $scrollArea = self.$obj().children().children('.content');


    if ($scrollArea.parent().children('.footer').length) ifFooter = $scrollArea.parent().children('.footer').outerHeight();
    else ifFooter = 0;
    $scrollArea
    .height( parseInt($scrollArea.height()) - ifFooter )
    .mCustomScrollbar({
      theme: 'dark',
      scrollInertia: 0,
      alwaysShowScrollbar: 0,
      autoHideScrollbar: 1,
      scrollbarPosition: "outside",
      mouseWheel: {
        enable: true,
        scrollAmount: 30
      },
      setWidth: '100%',
      advanced: {
        updateOnContentResize: true
      },
      callbacks: {
        onTotalScroll: function() {
          self.scrolled = false;
          self.first = false;
        },
        onScrollStart: function() {
          self.scrolled = true;
          self.first = false;
        }
      }
    });

  }

  self.scrolled = false;
  self.first = true;
  self.con = '';


}
var leftbox = new leftbox();

function leftCon(con) {

  chatReceiveUpdates = false;
  $('.chat-input').tooltip('destroy');

  leftbox.$obj().children().children('.content').mCustomScrollbar("destroy");
  leftbox.$obj().empty();

  if (con == leftbox.con) {
    leftbox.toggle();
    return;
  }

  $newObj = $('#lc-'+con).clone(true,true).width(leftbox.width-22).show().appendTo(leftbox.$obj());


  leftbox.scrolled = false;
  if (con == 'chat') {
    chatReceiveUpdates = true;
    $('.chat-input').tooltip();
  }

  leftbox.con = con;

  if (!leftbox.opened) leftbox.toggle();
  else leftbox.scrollbar();



}


function stats( which ) {

  var self = this;

  self.on = false;

  self.$obj = function() {
    return $('.stats-' + which);
  }

}


var stats = {

  st : {
    my_bets : new stats('my_bets'),
    all_bets : new stats('all_bets'),
    high : new stats('high')
  },

  go : function (load) {

    if (stats.st.my_bets.on) {
      stats.st.my_bets.on = false;
      stats.st.my_bets.$obj().hide();
    }
    if (stats.st.all_bets.on) {
      stats.st.all_bets.on = false;
      stats.st.all_bets.$obj().hide();
    }
    if (stats.st.high.on) {
      stats.st.high.on = false;
      stats.st.high.$obj().hide();
    }

    stats.st[load].$obj().show();
    stats.st[load].on = true;

  },

  update : function() {

    if (stats.updating) return;
    stats.updating = true;

    var last1 = parseInt( stats.st['my_bets'].$obj().children().eq(0).attr('data-betid') );
    var last2 = parseInt( stats.st['all_bets'].$obj().children().eq(0).attr('data-betid') );
    var last3 = parseInt( stats.st['high'].$obj().children().eq(0).attr('data-betid') );

    $.ajax({

      'url': './content/ajax/stats_load.php?_unique='+unique()+'&last=' + last1 + ',' + last2 + ',' + last3,
      'dataType': "json",
      'success': function (data) {

        $.each(data['stats'],function(name,val){

          if (!$(val['contents']).length && !stats.st[name] .$obj().children().length)
            stats.st[name] .$obj() .prepend( '<tr class="noBetsMessage"><td colspan="7">We are sorry, but there are currently no bets to show.</td></tr>' );
          else {

            $($(val['contents']).get().reverse()).each(function(){

              $(this).hide();
              stats.st[name] .$obj() .prepend( $(this) );

              $(this).parent().children('.noBetsMessage').remove();

              if (!ajaxBetLock) {
                $(this).attr('data-hidden',0);
              }

              if ($(this).attr('data-hidden') == 0) {
                if ($(this).parent().hasClass('reversed')) $(this).parent().removeClass('reversed');
                else $(this).parent().addClass('reversed');
                $(this).slideDown(100,function(){stats.limit();});
              }
              else {
                $(this).after($('<tr class="removablePlaceholder" style="display: none;"></tr>'));
              }
            });
          }
        });

        stats.updating = false;

      }

    });

  },

  updating : false,

  limit : function() {
    stats.st['my_bets'] .$obj() .children() .slice(30).remove();
    stats.st['all_bets'] .$obj() .children() .slice(30).remove();
    stats.st['high'] .$obj() .children() .slice(30).remove();
  }

};


function saveAlias() {
  $.ajax({
    'url': './content/ajax/saveAlias.php?_unique='+unique()+'&alias='+$('#input-alias').val(),
    'dataType': "json",
    'success': function(data) {
      alert(data['content']);
      if (data['repaired']!=null) $('#input-alias').val(data['repaired']);
    }
  });
}
function enablePass() {
  var pass = CryptoJS.SHA256($('#input-pass').val());
  $.ajax({
    'url': './content/ajax/enablePassword.php?_unique='+unique()+'&pass='+pass,
    'dataType': "json",
    'success': function(data) {
      alert(data['content']);
      if (data['color']=='green') location.reload();
    }
  });
}
function disablePass() {
  var pass = CryptoJS.SHA256($('#input-pass').val());
  $.ajax({
    'url': './content/ajax/disablePassword.php?_unique='+unique()+'&pass='+pass,
    'dataType': "json",
    'success': function(data) {
      alert(data['content']);
      if (data['color']=='green') {
        $('.pass-en_dis').html('Disabled');
        $('.savePass').attr('onclick',"javascript:enablePass();return false;");
        $('.savePass').html('Enable');
        $('#input-pass').val('');
      }
    }
  });
}
function claim_bonus() {
  var sol = $('#input-captcha').val();
  $('#input-captcha').val('');
  $.ajax({
    'url': './content/ajax/getBonus.php?_unique='+unique()+'&sol='+sol,
    'dataType': "json",
    'success': function(data) {
      if (data['error']=='yes') {
        var m_alert = "";
        if (data['content']=='balance') m_alert='Your balance must be 0 to proceed.';
        else if (data['content']=='captcha') m_alert='Incorrect captcha solution!';
        else if (data['content']=='time') m_alert='You must wait '+giveaway_freq()+' seconds.';
        else if (data['content']=='no_funds') m_alert='We have currently no funds to giveaway.';
        alert(m_alert);
      }
      else {
        balanceUpdate();
      }
      var $img = $('.captchadiv img').eq(0);

      $('.captchadiv').empty().append($img);

    }
  });
}
function invest() {
  var amount = $('#input-invest').val();
  $.ajax({
    'url': './content/ajax/inv_invest.php?_unique='+unique()+'&amount='+amount,
    'dataType': "json",
    'success': function (data) {
      if (data['error']=='yes') alert('Invalid amount!');
      if (data['error']=='min') alert('Minimum amount is '+min_inv()+' '+cursig());
      if (data['error']=='no') investUpdate();
    }
  });
}
function divest() {
  var amount = $('#input-divest').val();
  $.ajax({
    'url': './content/ajax/inv_divest.php?_unique='+unique()+'&amount='+amount,
    'dataType': "json",
    'success': function (data) {
      if (data['error']=='yes') alert('Invalid amount!');
      if (data['error']=='no') investUpdate();
    }
  });
}
function investUpdate() {

  $.ajax({
    'url': './content/ajax/inv_getData.php?_unique='+unique(),
    'dataType': "json",
    'success': function(data) {

      $('.invData_caninvest').html(data['canInv']);
      $('.invData_invested').html(data['invested']);
      $('.invData_share').html(data['share']);

    }
  });

}


function statsUpdate() {
  $.ajax({

    'url': './content/ajax/getStats.php?_unique='+unique(),
    'dataType': "json",
    'success': function (data) {

      $('.statsData_y_spins').html(data['player']['spins']);
      $('.statsData_g_spins').html(data['global']['spins']);
      $('.statsData_y_wagered').html(data['player']['wagered']);
      $('.statsData_g_wagered').html(data['global']['wagered']);

    }

  });
}

var bot = {

  on : false,

  toggle : function() {

    if (bot.on) {
      bot.on = false;
      $('.autoBotCheck').removeClass('bot_on');

    }
    else {
      bot.on = true;
      $('.autoBotCheck').addClass('bot_on');

    }

  }

};

function fairUpdate(data) {

  $('#_fair_server_seed').val(data['server_seed']);
  $('#_fair_client_seed').val(data['client_seed']);
  $('#_fair_l_server_seed').val(data['old_server_seed_sha']);
  $('#_fair_l_server_seed_p').val(data['old_server_seed']);
  $('#_fair_l_client_seed').val(data['old_client_seed']);
  $('#_fair_l_result').val(data['number']);

}
//Předem nahraje obrázek
function preloadImg(image) {
  var img = new Image();
  img.src = image;
}
//Žádost o výsledek rulety ze serveru
function spin(){
  if (!ajaxBetLock) {
    ajaxBetLock = true;
    $('.action-button').addClass('disabled');
    $.ajax({
      'url': './content/ajax/spin.php?_unique='+unique(),
      'type': 'POST',
      'data': {bets: bets},
      'dataType': "json",
      'success': function(data) {
        if(data['error']=='yes') {
					alert(data['message']);
					ajaxBetLock=false;
					$('.action-button').removeClass('disabled');					
				}
        else spin_circle(data);
      }
    });
  }
}
//Roztočí kuličku
function spin_circle(data){
  $('.win').remove();
  if(!muted) {
    $('#audio-spinning')[0].play();
  }
  if(angle <= Number(-spins*360)) angle = angle + Number($('#ball').getRotateAngle()) - (Number($('#ball').getRotateAngle()) + spins*360);
  else angle = -spins*360;
  $("#ball").rotate({
    angle: Number($('#ball').getRotateAngle()),
    animateTo: angle + positions[data['number']],
    duration: 7000,
    callback: function(){
      spin_result(data);
    },
    easing: function (x,t,b,c,d){
      return c * Math.sin(t/d * (Math.PI/2)) + b;
    }
  });
}
//Výsledek rulety, aktualizuje data

function spin_result(data){
  //Win
  if(data['win']!= 0) {
    $('#result').attr('class', 'green').html('You won' + data['amount'] + cursig());
  }
  //Lose
  else {
    $('#result').attr('class', 'red').html('You lost');
  }
  //Výsledné číslo
  $('#result-number').attr("class", data['number']==0 ? 'green' : $.inArray(data['number'], red) != -1 ? 'red':'yellow').html(data['number']);
  $('*[data-bet-position='+data['number']+']').append('<div class="win"></div>');
  //Nové seedy
  fairUpdate(data);
  if(!muted) {
    data['amount'] > 0 ? $('#audio-success')[0].play() : $('#audio-lose')[0].play();
  }
  ajaxBetLock=false;
  $('.action-button').removeClass('disabled');
}

//Přídá zvolenou hodnotu
function addChips(selector, bet){
  if (!muted) {
    $('#audio-chips')[0].play();
  }
  var amount = parseInt(selector.children('.chip').attr('data-bet')) + bet;
  if (amount > 9999)selector.children('.chip').attr('class', 'chip chip-10000');
  else if (amount > 999)selector.children('.chip').attr('class', 'chip chip-1000');
  else if (amount > 99)selector.children('.chip').attr('class', 'chip chip-100');
  else selector.children('.chip').attr('class', 'chip chip-10');
  selector.children('.chip').attr('data-bet', amount);
  if (amount.toString().substr(amount.toString().length - 3) == '000') amount = amount.toString().slice(0, amount.toString().length - 3) + 'k';
  selector.children('.chip').html(amount).fadeIn();

  if(bets[selector.data('bet-index')] === undefined ){
    var index;
    if(selector.data('bet-position').toString().indexOf(",") == -1) index = bets.push({amount: bet, fields: [selector.data('bet-position')]})-1;
    else index = bets.push({amount: bet, fields: selector.data('bet-position').split(",")})-1;
    selector.attr('data-bet-index', index);
  }
  else {
    if(bets[selector.data('bet-index')] !== null)bets[selector.data('bet-index')].amount += bet;
    else {
      if(selector.data('bet-position').toString().indexOf(",") == -1) index = bets.push({amount: bet, fields: [selector.data('bet-position')]})-1;
      else index = bets.push({amount: bet, fields: selector.data('bet-position').split(",")})-1;
    }
  }
  bet_total();
}
//Ubere o zvolenou hodnotu
function takeChips(selector, bet){
  if (!muted) {
    $('#audio-chips')[0].play();
  }
  var amount = parseInt(selector.children('.chip').attr('data-bet')) - bet;
  if(amount > 0) {
    if (amount > 9999)selector.children('.chip').attr('class', 'chip chip-10000');
    else if (amount > 999)selector.children('.chip').attr('class', 'chip chip-1000');
    else if (amount > 99)selector.children('.chip').attr('class', 'chip chip-100');
    else selector.children('.chip').attr('class', 'chip chip-10');

    selector.children('.chip').attr('data-bet', amount);
    bets[selector.data('bet-index')].amount -= bet;

    if (amount.toString().substr(amount.toString().length - 3) == '000') amount = amount.toString().slice(0, amount.toString().length - 3) + 'k';
    selector.children('.chip').html(amount);
  }
  else{
    selector.children('.chip').attr('data-bet', 0);
    bets[selector.data('bet-index')] = null;
    selector.children('.chip').fadeOut();
  }
  bet_total();

}
//Spočítá celkovou vsazenou částku
function bet_total(){
  var total = 0;
  for (var i = 0; i < bets.length; ++i) {
    if(bets[i] == null) total += 0;
    else total += bets[i].amount;
  }
  $('#total').html('Total amount: ' + total);
}
//Vypne zvuky a změní ikonu talčítka
function mute() {
  if(muted){
    $('#mute').css("background-image", "url('content/images/mute.png')");
    muted = false;
  }
  else{
    $('#mute').css("background-image", "url('content/images/unmute.png')");
    muted = true;
  }
}
//Odtraní všechny sázky
function clear_bets(){
  if(!ajaxBetLock) {
    bets = [];
    $(".chip:visible").each(function () {
      $(this).fadeOut().html('0').attr('data-bet', 0);
    })
  }
}