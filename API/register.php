<?php

$my_user = "root";
$my_password = "hackathon";
$my_db = "TradeBooks";

$first = htmlspecialchars($_POST["first"]);
$last = htmlspecialchars($_POST["last"]);
$email = htmlspecialchars($_POST["email"]);
$phonenum = htmlspecialchars($_POST["phonenum"]);
$propic = htmlspecialchars($_POST["propic"]);
$primaryschool = htmlspecialchars($_POST["primaryschool"]);
$dob = strtotime(htmlspecialchars($_POST["DOB"]));
$authtoken = htmlspecialchars($_POST["authtoken"]);
$maxsalerad = htmlspecialchars($_POST["maxsalerad"]);
$datejoined = date("Y-m-d");

$con=mysqli_connect("localhost",$my_user,$my_password,$my_db);
// Check connection
if (mysqli_connect_errno())
  {
  echo "Failed to connect to MySQL: " . mysqli_connect_error();
  }

//    First check the request type
$req_type = $_SERVER["REQUEST_METHOD"];

switch( $req_type ) {

    case 'POST':
        $json_raw = file_get_contents("php://input");

        if ($decoded_json = json_decode($json_raw, true)) {
            $first = $decoded_json["first"];
            $last = $decoded_json["last"];
            $email = $decoded_json["email"];
            $phonenum = $decoded_json["phonenum"];
            $propic = $decoded_json["propic"];
            $primaryschool = $decoded_json["primaryschool"];
            $dob = $decoded_json["DOB"];
            $authtoken = $decoded_json["authtoken"];
            $maxsalerad = $decoded_json["maxsalerad"];
        }
	break;

    default:
	break;
}

if(!empty($email) && !empty($first) && !empty($last)){
    // Make a new order in User Table. Inserts the posted contents into the db.
    $query = "INSERT INTO User (First, Last, Email, PhoneNum, ProfilePic, PrimarySchool, DOB, AuthToken, MaxSaleRadius, DateJoined) VALUES ('$first', '$last', '$email','$phonenum','$propic','$primaryschool','$dob','$authtoken','$maxsalerad','$datejoined')";
    mysqli_query($con,$query);
    echo "success";
}
else{
    echo "failure";
}

$conn->close();

mysqli_close($con);
?>
