<?php

?>
<link href="register.css" rel='stylesheet' type='text/css'>

<div class="testbox">
  <h1>Registration</h1>

  <form action="<?php $_SERVER['HTTP_HOST'];?>">
      <hr>
    <!-- <div class="accounttype">
      <input type="radio" value="None" id="radioOne" name="account" checked/>
      <label for="radioOne" class="radio" chec>Personal</label>
      <input type="radio" value="None" id="radioTwo" name="account" />
      <label for="radioTwo" class="radio">Company</label>
    </div>-->
  <hr>
  <label id="icon" for="emailAddr"><i class="icon-envelope "></i></label>
  <input type="text" name="emailAddr" id="emailAddr" placeholder="Email" required/>
  <label id="icon" for="FName"><i class="icon-user"></i></label>
  <input type="text" name="FName" id="FName" placeholder="First Name" required/>
  <label id="icon" for="Lname"><i class="icon-user"></i></label>
  <input type="text" name="LName" id="LName" placeholder="Last Name" required/>
  <label id="icon" for="name"><i class="icon-user"></i></label>
  <input type="text" name="DOB" id="DOB"  placeholder="Date of Birth" required/>
  <label id="icon" for="userID"><i class="icon-user"></i></label>
  <input type="text" name="userID" id="userID" placeholder="userID" required/>
  <label id="icon" for="password"><i class="icon-shield"></i></label>
  <input type="password" name="password" id="password" placeholder="Password" required/>
  <div class="gender">
    <input type="radio" value="None" id="male" name="gender" />
    <label for="male" class="radio" >Male</label>
    <input type="radio" value="None" id="female" name="gender" />
    <label for="female" class="radio">Female</label>
   </div> 
  <!-- <p>By clicking Register, you agree on our <a href="#">terms and condition</a>.</p>-->
   <a href="#" class="button">Register</a>
  </form>
</div>