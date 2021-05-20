<?php
error_reporting(E_ERROR);
?>
<?php
session_start();

// ***************************************** //
// **********	DECLARE VARIABLES  ********** //
// ***************************************** //

$username = 'pepe';
$password = 'Admin1234';

$random1 = 'secret_key1';
$random2 = 'secret_key2';

$hash = md5($random1.$pass.$random2); 

$self = $_SERVER['REQUEST_URI'];


// ************************************ //
// **********	USER LOGOUT  ********** //
// ************************************ //

if(isset($_GET['logout'])) {
	unset($_SESSION['login']);
}


// ******************************************* //
// **********	USER IS LOGGED IN	********** //
// ******************************************* //

if (isset($_SESSION['login']) && $_SESSION['login'] == $hash) {

	?>
		<style>
  * {
  outline:none;
	border:none;
	margin:0px;
	padding:0px;
	font-family:Courier, monospace;
}
body {
	background:#333 url(https://static.tumblr.com/maopbtg/a5emgtoju/inflicted.png) repeat;        
}
#paper {
	color:#FFF;
	font-size:20px;
}
#margin {
	margin-left:12px;
	margin-bottom:20px;
	-webkit-user-select: none;
	-moz-user-select: none;
	-ms-user-select: none;
	-o-user-select: none;
	user-select: none; 
}
#text {
	width:500px;
	overflow:hidden;
	background-color:#FFF;
	color:#222;
	font-family:Courier, monospace;
	font-weight:normal;
	font-size:24px;
	resize:none;
	line-height:40px;
	padding-left:100px;
	padding-right:100px;
	padding-top:45px;
	padding-bottom:34px;
	background-image:url(https://static.tumblr.com/maopbtg/E9Bmgtoht/lines.png), url(https://static.tumblr.com/maopbtg/nBUmgtogx/paper.png);
	background-repeat:repeat-y, repeat;
	-webkit-border-radius:12px;
	border-radius:12px;
	-webkit-box-shadow: 0px 2px 14px #000;
	box-shadow: 0px 2px 14px #000;
	border-top:1px solid #FFF;
	border-bottom:1px solid #FFF;
}
#title {
	background-color:transparent;
	border-bottom:3px solid #FFF;
	color:#FFF;
	font-size:20px;
	font-family:Courier, monospace;
	height:28px;
	font-weight:bold;
	width:220px;
}
#button {
	cursor:pointer;
	margin-top:20px;
	float:right;
	height:40px;
	padding-left:24px;
	padding-right:24px;
	font-family:Arial, Helvetica, sans-serif;
	font-weight:bold;
	font-size:20px;
	color:#FFF;
	text-shadow: 0px -1px 0px #000000;
	-webkit-border-radius:8px;
	border-radius:8px;
	border-top:1px solid #FFF;
	-webkit-box-shadow: 0px 2px 14px #000;
	box-shadow: 0px 2px 14px #000;
	background-color: #62add6;
	background-image:url(https://static.tumblr.com/maopbtg/ZHLmgtok7/button.png);
	background-repeat:repeat-x;
}
#button:active {
	zoom: 1;
	filter: alpha(opacity=80);
	opacity: 0.8;
}
#button:focus {
	zoom: 1;
	filter: alpha(opacity=80);
	opacity: 0.8;
}
#wrapper {
	width:700px;
	height:auto;
	margin-left:auto;
	margin-right:auto;
	margin-top:24px;
	margin-bottom:100px;
}
a:hover{
	font-weight: bold;
}
</style>
<script>
  $(document).ready(function(){
  $('#title').focus();
    $('#text').autosize();
});
</script>
<div id="wrapper">

	<form id="paper" method="get" action="">
		<p>Hello <?php echo $username; ?>!</br></p> 
		<p style=""><a style="float:right;color:white;text-decoration:none;" href="?logout=true">Logout?</a></p>
    <div id="margin">Title: <p id="title" type="text" name="title">Flag</p></div>
		<p placeholder="Enter something funny." id="text" name="text" rows="4" style="overflow: hidden; word-wrap: break-word; resize: none; height: 160px; ">flag{simple_website_fail}</p>  
		<br>
		<!--<input id="button" type="submit" value="Create">-->
		
	</form>

</div>
		
			
	<?php
}


// *********************************************** //
// **********	FORM HAS BEEN SUBMITTED	********** //
// *********************************************** //

else if (isset($_POST['submit'])) {

	if ($_POST['username'] == $username && $_POST['password'] == $password){
	
		//IF USERNAME AND PASSWORD ARE CORRECT SET THE LOG-IN SESSION
		$_SESSION["login"] = $hash;
		header("Location: $_SERVER[PHP_SELF]");
		
	} else {
		
		// DISPLAY FORM WITH ERROR
		display_login_form();
		echo '<p style="color:white;font-size:24px;"><font style="background-color:red;padding:5px;border:3px white solid;border-radius:10px;">Username or password is invalid</font></p>';
		
	}
}
	
	
// *********************************************** //
// **********	SHOW THE LOG-IN FORM	********** //
// *********************************************** //

else {
	display_login_form();
}


function display_login_form() { ?>
	<!--<form action="<?php echo $self; ?>" method='post'>
		<label for="username">username</label>
		<input type="text" name="username" id="username">
		<label for="password">password</label>
		<input type="password" name="password" id="password">
		<input type="submit" name="submit" value="submit">
	</form>	-->

	<div class="login" id="login" style="display: block;">
<form action="<?php echo $self; ?>" method='post'>
  <p><h1>login</h1></p>
    <div class="profile" id="profile"></div>
      <p>
  		  <select name="username" id="username">
        </select>	
        <br>
      </p>
      <p>
  			<input type="password" name="password" id="password" required="required" placeholder="No Password"><br>
  		</p>
  		<p>
	      <input type="submit" name="submit" value="Login" id="btnLogin" onclick="btnLogin_onclick('#login')">
      </p>
  </form>
</div><!-- end login -->


<style>
  html{width: 100%;  margin:0; padding: 0;}
body{
  margin:0; padding: 0;
  background-image: url('https://www.giannicomusic.net/wp-content/uploads/2017/01/dark-background.jpg');
  background-position: center center;
  background-repeat: no-repeat;
  background-origin: border-box;
  background-clip: bottom;
  background-attachment: fixed;
  min-height: 100% !important;
  font-family: Arial;
  overflow: hidden;
}

img{border: 0;}

*::selection{background-color: inherit;
  color:inherit;}
*::-moz-selection{
  background-color: inherit;
  color:inherit;
}

.login{
  width: 300px; height: 350px;
  margin: 200px auto;
  background-color: #FFFFFF;
  border-radius: 6px;
  border: 2px solid #999;
  box-shadow: 0 0 12px #333;
  display: block;
}
.login h1{
  text-align: center;
  text-transform: uppercase;
  color: #333;
}
.profile{
  width: 75px;
  height: 75px;
  border-radius: 50px;
  margin: 0 auto;
  background-image: url('https://static.thenounproject.com/png/55393-200.png');
  background-position: center center;
  background-size: cover;
  background-repeat: no-repeat;
  transition: all ease-in-out;
}
/*http://www.hdwallpapersinhd.net/wp-content/uploads/2013/10/Nature-Wallpaper-Widescreen-Nature-With-Extra-Moon-HD-Wallpapers.jpg*/
#username{width: 149px;}
p{text-align: center;}

#background{
  position: absolute;
  top: 0;
  left: 0;
  z-index: -1;
  overflow: hidden;
}
#login{display: block;}
#btnLogin{
  width: 150px;
  height: 40px;
  outline: none;
}

.desktop{display: none; margin: 0; padding: 0;}

.desktop a.icon{
  width: 100px !important; height: 90px;
  margin: 0; padding: 10px 0;
  position: absolute;
  top: 25px;
  left: 25px;
  text-decoration: none;
  font-weight: 100;
  color: #fff;
  text-align: center;
  display: block;
  background-color: rgba(0, 0, 0, 0.2);
  border-radius: 4px;
}
.desktop a.icon span{
  margin: 0; padding: 0;
  text-decoration: none;
  color: #fff;
}

.desktop a.icon:hover{background-color: rgba(0, 0, 0, 0.3);}

.desktop .window{
  width: 80%; height: 700px;
  margin: 0 auto;
  padding: 0 auto;
  display: none; /* none */
  background-color: #FFFFFF;
  position: relative;
  top: 20px;
  margin-top: 30px;
  padding-top:0;
  color: #FFF;
  bottom: 28px;
  z-index: 1;
  border-radius: 6px 6px 4px 4px;
  box-shadow: 0px 0px 15px #000000;
  resize: both;
}

.desktop .window .notepad{
  display: block;
  width: 80%; height: 80%;
  margin:0 auto;
}

.desktop .window .window-top-bar{
  width: 100%;
  height: 30px;
  display: block;
  background-color: #292929;
  border-radius: 4px 4px 0px 0px;
}
.desktop .window .title{
  text-align: center;
  height: 32px;
  width: 80%;
  line-height: 32px;
  float: left;
  padding-left:10%;
  font-weight: 700;
}
.window .controls{
  width: 5;
  height: 32px;
  float: right;
  font-size: 8px;
  margin-right: 0;
}
.window .controls span{
  height: 20px;
  color: #000;
  line-height: 20px;
  width: 20px;
  display: block;
  float: left;
  text-align: center;
  margin: 5px 3px;
  background-color: #444;
  cursor: pointer;
  border-radius: 12px;
  transition: background-color 0.2s ease;
}
.window .controls span#close{background-color: #e67e22;}
.window. controls span:hover{background-color: #666;}
.window .controls span#close{background-color: #e67e22;}
.window-navigation{
  width: 100%;
  box-sizing: border-box;
  height: 40px; line-height: 38px;
  background-color: #CCC;
  text-align: left;
  padding-left: 5px;
}
.window-navigation input{
  border: none;
  height: 30px;
  width: 30px;
  color: #333;
}
.window-navigation input[type="submit"]{
  cursor: pointer;
  outline: none;
}
.window-navigation #frameURL{
  width: 92.3%;
  padding-left: 5px;
  height: 28px;
}


.window-content{
  color: #000;
  width: 99.6%;
  height: 70%;
}

.window-content iframe{
  max-width: 100%;
  max-height: 100%;
  border: none;
}
.window-content textarea{
  width: 100%; height: 100%;
}



.desktop .bottom-bar{
  color: #FFF;
  width: 100%;
  height: 28px;
  line-height:28px;
  position: absolute; bottom: 0;
  display: none;
  background-color: rgba(0, 0,0, 0.7);
  z-index: 10;
  font-family: arial;
  font-size: 12px;
  text-align: center;
}

.bottom-bar div.panel{
  width: 300px;
  position: absolute;
  bottom: 30px;
  left: 0px;
  margin: 0; padding: 0;
  background-color: rgba(0,0,0, 0.6);
  display: none;
  text-align: left;
  font-size: 14px;
}
.bottom-bar div.panel ul{list-style: none;}
.bottom-bar div.panel ul li a{color: #FFF; text-decoration: none;}
.bottom-bar div.panel ul li a:hover{color: #FFF;}

.bottom-bar a.app{
  color: #fff;
  display:none;
  width: 100px;
  line-height: 28px;
  background-color: rgba(255,255,255, 0.1);
  float: left;
  text-decoration: none;
}
a.app span{padding-left: 8px; position:relative; top:-6px}
.bottom-bar a#menu{
  color: #FFF;
  text-decoration: none;
  padding: 0px 13px 7px 13px;
  line-height: 28px;
  float: left;
  width: 30px;
  background-color: rgba(255,255,255, 0.2);
}


.bottom-bar .panel .panel-icons ul{
  margin: 0;  padding: 0;
  width: 65px;
  height: 100%;
  float: left;
}
.bottom-bar .panel .panel-icon img{
  width: 30px; height: 30px;
  margin: 0; padding: 0;
}
.bottom-bar a.chrome{
  color: #fff;
  display: none;
  box-sixing: border-box;
  background-color: rgba(255,255,255, 0.1);
  float: left;
  text-decoration: none;
  text-align: left;
  padding: 0 10px
}
.bottom-bar a:chrome img{float:left; clear: left;}


.left{float:left;}
.clear{clear: both;}
</style>
<script>
  var users = [
   {
    name: 'Select User',
    avatar: 'https://static.thenounproject.com/png/55393-200.png',
    background: 'https://biddown.com/wp-content/uploads/2020/02/Light-blue-tech-background-vector-05-1.png'
  },
   {
    name: 'Admin',
    avatar: 'https://i.pinimg.com/736x/d9/56/9b/d9569bbed4393e2ceb1af7ba64fdf86a.jpg',
    background: 'https://biddown.com/wp-content/uploads/2020/02/Light-blue-tech-background-vector-05-1.png'
  },
   {
    name: 'pepe',
    avatar: 'https://www.collinsdictionary.com/images/thumb/dog_230497594_250.jpg',
    background: 'https://htmlcolorcodes.com/assets/images/html-color-codes-color-tutorials-hero.jpg'
  },
  
  
];

(function(d) {
  // elements
  var select = d.getElementById('username'),
      profile = d.getElementById('profile'),
      body = d.body;
  
  // create options and preload images
  for (var i = 0; i < users.length; i++) {
    var option = d.createElement('option');
    
    option.value = users[i].name;
    option.innerHTML = users[i].name;
    
    select.appendChild(option);
    
    // preload
    var image = new Image();
    image.src = users[i].avatar;
    image = new Image();
    image.src = users[i].background;
  }
  
  // event handler
  select.addEventListener('change', function() {
    var user = getUser(this.value);
    
    if (!user) return;
    
    profile.style.backgroundImage = 'url("' + user.avatar + '")';
    body.style.backgroundImage = 'url("' + user.background + '")';
  });
  
  // get user by name
  function getUser(name) {
    for (var i = 0; i < users.length; i++) {
      if (users[i].name == name)
        return users[i];
    }
      
    return false;
  }
})(document);


/*
function btnLogin(id) {
  var e = document.getElementById(id);
  if(e.style.display == 'block')
    e.style.display = 'none';
  else
    e.style.display = 'block';
}
*/


$(function(){
  $("#btnLogin").click(function(){
    $(".login").css("display","none");
    $(".desktop").delay(1000).show("slow");
    $(".bottom-bar").slideDown("slow");
    $(".icons").delay(3000).show("fast");
  });
   $("#logout").click(function(){
    $(".login").delay(1000).show("slow");
    $(".desktop").hide("slow");
    $(".icons").hide("slow");
     $(".panel").css("display","none");
  });
  
     $("#shutdown").click(function(){
       $(".desktop").hide();
       $(".icons").hide();
       $(".panel").css("display","none");
       $('body').css("background-image","none");
       $('html').css("background-color","black");

  });

  $("#dskIcnChrome").click(function(){
    $(".panel").slideUp("fast");

  });
  $("#dskIcnChrome").dblclick(function(){
    $(".window").fadeIn("slow").css("display","block");
    $(".bottom-bar .chrome").css("display","block");
    $(".panel").slideUp("fast");

  });
  $(".bottom-bar #mnuIcnChrome").click(function(){
    $(".panel").slideUp("fast");
    $(".window").fadeIn("slow").css("display","block");
    $(".bottom-bar .chrome").css("display","block"); 
  });

  $(".bottom-bar .chrome").click(function(){
     $(".window").fadeToggle();
  });

  $(".window #minimize").click(function(){
    $(".window").fadeOut();
  }); 
  
  $(".window #close").click(function(){
    $(".window").fadeOut("slow");
    $(".bottom-bar .chrome").fadeOut("slow").css("display","none"); 
  });
  

  $(".bottom-bar a#menu").click(function(){
    $(this).css("background-color", "rgba(255,255,255, 0.2)");
    $(".panel").slideToggle("fast");    
  });
});
</script>


<?php } ?>
