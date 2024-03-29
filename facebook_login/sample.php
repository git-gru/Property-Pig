<html>
<script src="//code.jquery.com/jquery-1.11.0.min.js"></script>
<script>
  window.fbAsyncInit = function() {
    FB.init({
      appId      : "1653046914952007",
      xfbml      : true,
      version    : 'v2.0'
    });
  };

  (function(d, s, id){
     var js, fjs = d.getElementsByTagName(s)[0];
     if (d.getElementById(id)) {return;}
     js = d.createElement(s); js.id = id;
     js.src = "//connect.facebook.net/en_US/sdk.js";
     fjs.parentNode.insertBefore(js, fjs);
   }(document, 'script', 'facebook-jssdk'));


function basicAPIRequest() {
    FB.api('/me', 
        {fields: "id,about,age_range,picture,bio,birthday,context,email,first_name,gender,hometown,link,location,middle_name,name,timezone,website,work"}, 
        function(response) {
          console.log('API response', response);
          $("#fb-profile-picture").append('<img src="' + response.picture.data.url + '"> ');
          $("#name").append(response.name);
          $("#user-id").append(response.id);
          $("#work").append(response.gender);
          $("#email").append(response.email);
          $("#birthday").append(response.birthday);
          $("#education").append(response.hometown);
        }
    );
  }
jQuery(document).ready(function(){
  jQuery("#load").click(function(e){
    if(typeof(FB) == "undefined") {
        alert("Facebook SDK not yet loaded please wait.")
      }
    FB.getLoginStatus(function(response) {
      if (response.status === 'connected') {
        console.log('Logged in.');
        basicAPIRequest();

      }
      else {
        FB.login();
      }
    });      
  });

});
</script>
fb-profile-picture: <div id="fb-profile-picture"></div>
name: <div id="name"></div>
user-id: <div id="user-id"></div>
work: <div id="work"></div>
birthday: <div id="birthday"></div>
email: <div id="email"></div>
education: <div id="education"></div>

<p>1) Click login</p>
<div class="fb-login-button" data-scope="email,user_birthday,user_hometown,user_location,user_website,user_work_history,user_about_me
" data-max-rows="1" data-size="medium" data-show-faces="false" data-auto-logout-link="false"></div>
<p>2) Click load data</p>
<button id='load'>Load data</button>
</html>