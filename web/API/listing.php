<?php
	
	$my_user = "root";
	$db_pass = "hackathon";
	$my_db = "TradeBooks";
	$db_host = "localhost";
	
	
	$db_conn = new mysqli($db_host, $db_user, $db_pass, $db_db);
	
	if ($db_conn->error_code) {
		
		set_error_response( 400 , "I couldn't connect to the database -> " . $db_conn->connect_error);
		die("The connection to the database failed: " . $db_conn->connect_error);
	}
	
	
	$req_type = $_SERVER["REQUEST_METHOD"];
	
	$listing_table = "Listing";
	
	switch( $req_type ) {
		
		
		case 'GET':
		
			//	Pull down and send back all listings
		
			
			$get_all_listings_sql = 	"SELECT * FROM " . $listing_table . " "
										. "ORDER BY PostDate DESC";
										
			
			
		break;
		
		case 'POST':
		
			//	Pull out the post data
			$json_raw = file_get_contents("php://input");
			
			if ($decoded_json = json_decode($json_raw, true)) {
				
				$isbn 			= $decoded_json["isbn"];
				$title 			= $decoded_json["title"];
				$author 		= $decoded_json["author"];
				$date_published = $decoded_json["date_published"];
				$category 		= $decoded_json["category"];
				$summary 		= $decoded_json["summary"];
				$seller_id		= $decoded_json["seller_id"];
				$price			= $decoded_json["price"];
				
				
				//	First we should do a check to make sure this book doesn't already
				//	exist in our database
				$check_isbn_sql = 	"SELECT * FROM Book WHERE ISBN = ? LIMIT 1";
				
				if(!($check_isbn_stmt = $db_conn->prepare($check_isbn_sql))) {
					set_generic_error_response( "There was an error preparing the statement ... " . $db_conn->error);
				}
				
				if(!($check_isbn_stmt->bind_param("s", $isbn))) {
					set_generic_error_response( "There was an error binding the params ... " . $db_conn->error);
				}
				
				if(!($check_isbn_stmt->execute())) {
					set_generic_error_response( "I couldn't execute the statement -> " . $check_isbn_stmt );
				}
				
				if(!($result = $check_isbn_stmt->get_result())) {
					
				}
				
				if($result->num_rows == 0) {
					//	This means that the book is new to the database. We should insert it.
					
					$insert_book_sql = "INSERT INTO Book (ISBN, Summary, Author, Title, PubDate, Category) VALUES ( ? , ? , ? , ? , ? , ? )";
					
					if(!($insert_book_stmt = $db_conn->prepare($insert_book_sql))) {
						
					}
					
					if(!($insert_book_stmt->bind_param("ssssss", $isbn, $summary, $author, $title, $date_published, $category))) {
						
					}
					
					
					if(!($insert_book_stmt->execute())) {
						
					}
				}
				
				//	Now that the book is inserted into the database we can add the listing
				$curr_date = get_sql_current_date();
				
				$add_listing_sql =	"INSERT INTO Listing ( Price, BookID, PosterID, PostDate ) VALUES ( ? , ? , ? , ?)";
				
				if(!($add_listing_stmt = $db_conn->prepare($add_listing_sql))) {
					break;
				}
				
				if(!($add_listing_stmt->bind_param("dsis", $price, $isbn, $seller_id, $curr_date))) {
					break;
				}
				
				if(!($add_listing_stmt->execute())) {
					break;
				}
				else {
					$listing_insert_id = $add_listing_stmt->insert_id;
				}
				
				
				
				http_response_code(200);
				
				$json_response_array = [
										"status" => "success",
										"listing_id" => $listing_insert_id
				];
				
				echo json_encode($json_response_array);
			}
			else {
				//	Echo an error message to indicate the the posted data could
				//	not be converted
				
				
			}
			
			
			
			
			
			
			
			
		default:
		break;
		
	}
	
	function set_error_response( $error_code , $error_message ) {
		
		
		$http_response_code = 500;
		
		$response_array = array(
			"error_code" => $error_code,
			"error_message" => $error_message
		);
				echo json_encode($response_array);
		http_response_code($error_code);
		
	}
	
	function set_generic_error_response( $error_message ) {
		set_error_response( 500, $error_message);
	}
	
	function get_sql_current_date() {
		
		$format_string = "Y-m-d H:i:s";
		
		return date($format_string);
	}
	
	