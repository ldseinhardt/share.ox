document.addEventListener('DOMContentLoaded', function() {
  $('#facebook-login').click(function() {
    FB.login(function(response) {
      facebook(response, function() {
        if (/^\/login(\/)?$/.test(window.location.pathname)) {
          window.location.pathname = '/';
        }
      });
    }, {
      scope: 'public_profile, email',
      auth_type: 'rerequest'
    });
  });
  
  $('#facebook-logout').click(function() {
    FB.logout(function(response) {
      window.location.pathname = '/login/';
    });
  });
});
    
function facebook(response, callback) {
  if (response.status === 'connected') {
    FB.api('/me', {fields: 'first_name, last_name, picture, email'}, function(response) {
      $.post('/register/', {
        firstname: response.first_name,
        lastname: response.last_name,
        picture: response.picture.data.url,
        email: response.email
      }, callback);
    });
  } else if (!/^\/login(\/)?$/.test(window.location.pathname)) {
    window.location.pathname = '/login/';
  }
}
    
window.fbAsyncInit = function() {
  FB.init({
    appId   : '1725608467696499',
    cookie  : true,
    xfbml   : true,
    version : 'v2.6'
  });
            
  FB.getLoginStatus(function(response) {
    facebook(response);
  });
};

(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) {return;}
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/pt_BR/sdk.js";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));

$(window).resize(function() {
  $('.navbar input').width($('.navbar .container').width() - $('.navbar .navbar-header').width() - $('.navbar .navbar-nav').width() - 80);
  if ($(window).width() < 750) {
    $('.navbar input').width($(window).width() - 90);
  }
});
        
$(function() {
  $(window).trigger('resize');
});
