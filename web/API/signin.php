<?php
      
$my_user = "root";
$my_password = "root";
$my_db = "TradeBooks";
      
$first = htmlspecialchars($_POST["first"]);
$last = htmlspecialchars($_POST["last"]);
$email = htmlspecialchars($_POST["email"]);
$phonenum = htmlspecialchars($_POST["phonenum"]);
$propic = htmlspecialchars($_POST["propic"]);
$primaryschool = htmlspecialchars($_POST["primaryschool"]);
$dob = htmlspecialchars($_POST["DOB"]);
$authtoken = htmlspecialchars($_POST["authtoken"]);
$maxsalerad = htmlspecialchars($_POST["maxsalerad"]);
$datejoined = htmlspecialchars($_POST["datejoined"]);
      
$con=mysqli_connect("localhost",$my_user,$my_password,$my_db);
// Check connection
if (mysqli_connect_errno())
  {
  echo "Failed to connect to MySQL: " . mysqli_connect_error();
  } 

//find index of db
$maxresult = mysqli_query($con,"Select MAX(UserID) from User");
$max = mysqli_fetch_row($maxresult);
$userid = $max[0] + 1;

if(!empty($email) && !empty($first) && !empty($last)){
    // Make a new order in User Table. Inserts the posted contents into the db.
    $query = "INSERT INTO User (UserID, First, Last, Email, PhoneNum, ProfilePic, PrimarySchool, DOB, AuthToken, MaxSaleRadius, DateJoined) VALUES ('$userid','$first', '$last', '$email','$phonenum','$propic','$primaryschool','$dob','$authtoken','$maxsalerad','$datejoined')";
    mysqli_query($con,$query);
    echo "success";
}
else{
    echo "failure";
}

$conn->close();

mysqli_close($con);
?>