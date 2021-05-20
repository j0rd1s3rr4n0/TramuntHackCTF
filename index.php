<html>
<head>
		  <link type="text/css" rel="stylesheet" href="stylesheet.css"/>
      <style>@import url(https://fonts.googleapis.com/css?family=Roboto|Quicksand); 

body{ 
    margin: auto;
    background-color: #eeeeee;
}
a:active{
  text-decoration:none;
  color:white;
}
a:link{
  text-decoration:none;
  color:white;

}
a:hover{
  background-color:black;
  padding:2px;
}
#header {
    background-color: #3D4C53;
    color: #eeeeee;
    line-height: 1px;
    padding-top:5px;
    padding-bottom:5px;
    text-align: center;
    font-family: Quicksand;
    font-size: 20px;
    height:60px;
    font-effect:shadow-multiple;
}   

#nav {
    background-color: #333333;
    height:40px;
    text-align: center;
    color: #eeeeee;
    font-family: Roboto;
    font-size: 16px;
    padding: 1px 0px 10px 0px;
}

a{
  text-decoration: none;
}
a:visited { text-decoration: none; color:#eeeeee; }
a:hover { text-decoration: none; color:#eeeeee; }
a:focus { text-decoration: none; color:#eeeeee; }
a:hover, a:active { text-decoration: none; color:#eeeeee }

span {
    color: #26A69A;
}

#body {
    background-color:white;
    margin: 30px auto;
    width: 500px;
    padding: 5px;
    text-align: center;
    font-family: Roboto;
    box-shadow: 0px 3px 10px rgba(0,0,0,0.2);
    transition: 1s;
    border-radius: 1px;
}

iframe{
  float: center;
  margin: 20px auto;
  box-shadow: 0px 3px 10px rgba(0,0,0,0.2);
  alignment: center;
  display: block;
  corner-radius:1px;
}

#holder{
  background: white;
  padding: 10px;
  width: 250px;
  margin: 30px auto;
  box-shadow: 0px 3px 10px rgba(0,0,0,0.2);
  border-radius:1px;
}

.button {
  background: #3D4C53;
  margin : 20px auto;
  width : 200px;
  height : 50px;
  overflow: hidden;
  text-align : center;
  transition : .2s;
  cursor : pointer;
  box-shadow: 0px 1px 2px rgba(0,0,0,.2);
  border-radius:1px;
}
.btnTwo {
  position : relative;
  width : 200px;
  height : 100px;
  margin-top: -100px;
  padding-top: 2px;
  background : #26A69A;
  left : -250px;
  transition : .3s;
  border-ra
}
.btnText {
  font-family: Roboto;
  color : white;
  transition : .3s;
}
.btnTwoText {
  font-family: Roboto;
  margin-top : 63px;
  margin-right : -130px;
  color : #FFF;
}
.button:hover .btnTwo{ /*When hovering over .button change .btnTwo*/
  left: -130px;
}
.button:hover .btnText{ /*When hovering over .button change .btnText*/
  margin-left : 65px;
}
.button:active { /*Clicked and hold*/
  box-shadow: 0px 5px 5px rgba(0,0,0,0.4);
}
}

  </style>
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons"
      rel="stylesheet">
  
		    <title>Index | MFW</title>
	</head>
  
	<body>
		  
  <div id="header">
    		<h1>My First Website<h1>
  		</div>
		    
  		<div class="navBtn">
 
  		<div id="nav"> <!--find better solution, also scroll sections--> <p><a href= "upload.php">UPLOAD</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="#">HOME</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="/uploads">FOLDER</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="login.php">LOGIN</a></p>
  		</div>
              
		  <div id="body">
    		<h2>Welcome.</h2>
        <p>This is my First Website. Enjoy uploading interesting files, photos... </br>I love Music!</p><br>
		</div>
        
<iframe width="510" height="200" scrolling="no" frameborder="no" src="https://w.soundcloud.com/player/?url=https%3A//api.soundcloud.com/tracks/177226422&amp;auto_play=false&amp;hide_related=false&amp;show_comments=false&amp;show_user=true&amp;show_reposts=false&amp;visual=false"></iframe>  
        
		
  <div id="holder">
		
		  <div class="button">
		    <p class="btnText">Upload</p>
		    <div class="btnTwo">
          <a href="upload.php"><p class="btnTwoText">or not :/<p></a>
		    </div>
  		</div>
	</body>
        
</html>
