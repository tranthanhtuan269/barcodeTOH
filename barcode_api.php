<?php
header('Content-Type: application/json;charset=utf-8;');
error_reporting(0);
//ini_set('display_errors', 1);ini_set('display_startup_errors', 1);error_reporting(E_ALL);

// Constants
$HOST = "192.168.192.179";	// public IP: 45.56.85.153
$USERNAME='tohapi_db';
$PASSWORD='TOHapidb@123';
$DBNAME='tohsystem';

// input barcode is invalid or not
$IS_INVALID_BARCODE = 0;

$barcode = $_GET['barcode'];
if (!is_null($barcode)) {
	// firstly find on database
	$result = find_on_local_database($barcode);
	
	if (is_null($result)) {	//try from EANdata
		$result = find_on_eandata($barcode);	// https://eandata.com/feed/?v=3&keycode=8F5CF49059BA332E&mode=json&find=4902778913772 
		if (is_null($result)) {
			build_response("", "", "", "", "", "", "", "", "");
			if ($IS_INVALID_BARCODE == 0) {
				update_statistics(0, 0, 0, 0, 0, $barcode);
			} else {
				update_statistics(0, 0, 0, 0, 0, "1");	//reset all invalid barcode to 1	
			}
		}
		/* => not use at this time
		if (is_null($result)) {	//try from UPCitemDB
			$result = find_on_upcitemdb($barcode);	// https://devs.upcitemdb.com/docs 
			if (is_null($result)) {	//try from BarcodeLookup
				$result = find_on_barcodelookup($barcode);	// https://www.barcodelookup.com/api 
			}
		}*/
	}
} else {
	echo 'Null barcode received.';
}

function find_on_local_database($barcode) {
	global $USERNAME;
    global $PASSWORD;
    global $DBNAME;
	global $HOST;
	
	// Create connection
	$return_val = NULL;
	$conn = mysqli_connect($HOST, $USERNAME, $PASSWORD, $DBNAME);
	$sql = "SELECT * FROM barcode_dict WHERE barcode = '".$barcode."'";
	$result = $conn->query($sql);
	
	if ($result->num_rows > 0) {	//found
		while ($row = $result->fetch_assoc()) {
			build_response($row["barcode"], $row["name"], $row["model"], $row["manufacturer"], 
				$row["image"], $row["avg_price"], $row["spec"], $row["feature"], $row["description"]);
			$return_val = "Found";
			break;
	    }	    
	}
	if (!is_null($return_val)) {	//found
		update_statistics(1, 1, 0, 0, 0, "");
	}
	$conn->close();
	return $return_val;
}

function save_to_local_database($barcode, $name, $model, $manufacturer, $image, $avg_price, $spec, $feature, $description) {
	global $USERNAME;
    global $PASSWORD;
    global $DBNAME;
	global $HOST;
	
	// Create connection
	$conn = mysqli_connect($HOST, $USERNAME, $PASSWORD, $DBNAME);
	$sql = "INSERT INTO barcode_dict VALUES('".$barcode."','".$name."','".$model."','".$manufacturer."','"
			.$image."','".$avg_price."','".$spec."','".$feature."','".$description."',".time(true).")";
	$result = $conn->query($sql);
	$conn->close();
}

function find_on_eandata($barcode) {
	global $IS_INVALID_BARCODE;
	
	$keycode = "8F5CF49059BA332E"; // nhan key: "E103EF3E59A7BD56";
	$raw = file_get_contents("http://eandata.com/feed/?v=3&mode=json&keycode=".urlencode($keycode)."&find=".$barcode);
	$reply = json_decode($raw);	
	
	if($reply->status->code != 200) {
		//echo "Message: ".$reply->status->message."\n";
		// 400: invalid barcode, 404: valid barcode, but product not found
		if ($reply->status->code == 400) {
			$IS_INVALID_BARCODE = 1;	
		}
		return NULL;
	} else {
		$name = $model = $manufacturer = $image = $avg_price = $spec = $feature = $description = "";
		if(is_valid($reply->product->attributes->product)) {
			$name = $reply->product->attributes->product;
		}
		if(is_valid($reply->product->attributes->model)) {
			$model = $reply->product->attributes->model;
		}
		if(is_valid($reply->company->name)) {
			$manufacturer = $reply->company->name;
		}
		if(is_valid($reply->product->image)) {
			$image = $reply->product->image;
		}
		if(is_valid($reply->product->attributes->price_new) && is_valid($reply->product->attributes->price_new_extra)) {
			$avg_price = $reply->product->attributes->price_new.' '.$reply->product->attributes->price_new_extra;
		}
		// spec
		if(is_valid($reply->product->attributes->length)) {
			$spec = 'Length: '.$reply->product->attributes->length.' '.$reply->product->attributes->length_extra.';';
		}
		if(is_valid($reply->product->attributes->width)) {
			$spec = $spec.' Width: '.$reply->product->attributes->width.' '.$reply->product->attributes->width_extra.';';
		}
		if(is_valid($reply->product->attributes->height)) {
			$spec = $spec.' Height: '.$reply->product->attributes->height.' '.$reply->product->attributes->height_extra.';';
		}
		if(is_valid($reply->product->attributes->weight)) {
			$spec = $spec.' Weight: '.$reply->product->attributes->weight.' '.$reply->product->attributes->weight_extra;
		}
		// end spec
		
		if(is_valid($reply->product->attributes->features)) {
			$feature = $reply->product->attributes->features;
		}
		if(is_valid($reply->product->attributes->long_desc)) {
			$description = $reply->product->attributes->long_desc;
		}		
		
		build_response($barcode, $name, $model, $manufacturer, $image, $avg_price, $spec, $feature, $description);
		save_to_local_database($barcode, $name, $model, $manufacturer, $image, $avg_price, $spec, $feature, $description);
		update_statistics(1, 0, 1, 0, 0, "");
		return "Found";
	}
}

function find_on_upcitemdb($barcode) {
	$keycode = "<waiting>";
	$raw = file_get_contents("https://api.upcitemdb.com/prod/trial/lookup?upc=".$barcode);
	$reply = json_decode($raw);	
	
	if($reply->code != "OK") {
		return NULL;
	} else {
		$name = $model = $manufacturer = $image = $avg_price = $spec = $feature = $description = "";
		
		if(is_valid($reply->items[0]->title)) {
			$name = $reply->items[0]->title;
		}
		if(is_valid($reply->items[0]->model)) {
			$model = $reply->items[0]->model;
		}
		if(is_valid($reply->items[0]->brand)) {
			$manufacturer = $reply->items[0]->brand;
		}
		if(is_valid($reply->items[0]->images[0])) {
			$image = $reply->items[0]->images[0];
		}
		if(is_valid($reply->items[0]->offers[0]->price)) {
			$avg_price = $reply->items[0]->offers[0]->price.' '.$reply->items[0]->offers[0]->currency;
		}
		// spec
		if(is_valid($reply->items[0]->dimension)) {
			$spec = 'Dimension: '.$reply->items[0]->dimension.';';
		}
		if(is_valid($reply->items[0]->weight)) {
			$spec = $spec.' Weight: '.$reply->items[0]->weight;
		}
		// end spec
		if(is_valid($reply->items[0]->color)) {
			$feature = $reply->items[0]->color;
		}
		if(is_valid($reply->items[0]->description)) {
			$description = $reply->items[0]->description;
		}		
		
		build_response($barcode, $name, $model, $manufacturer, $image, $avg_price, $spec, $feature, $description);
		save_to_local_database($barcode, $name, $model, $manufacturer, $image, $avg_price, $spec, $feature, $description);
		update_statistics(1, 0, 0, 1, 0, "");
		return "Found";
	}
}

function find_on_barcodelookup($barcode) {
	
}

// build repsonse to client as JSON
function build_response($barcode, $name, $model, $manufacturer, $image, $avg_price, $spec, $feature, $description) {	
	$result = array('barcode' => $barcode, 'name' => $name, 'model' => $model, 'manufacturer' => $manufacturer, 
					'image' => $image, 'avg_price' => $avg_price, 'spec' => $spec, 'feature' => $feature, 'description' => $description);
	echo json_encode($result);
}

// check 
function is_valid($variable) {
	$valid = 0;
	if (isset($variable) && !is_null($variable) && $variable != "") {
		$valid = 1;	
	}
	return $valid;
}

function update_statistics($is_found, $found_on_db, $found_on_ean, $found_on_upc, $found_on_bclu, $not_foud_barcode) {
	global $USERNAME;
    global $PASSWORD;
    global $DBNAME;
	global $HOST;
	
	// Create connection
	$conn = mysqli_connect($HOST, $USERNAME, $PASSWORD, $DBNAME);
	$sql = "";	
	if ($is_found == 1) {
		$sql = "UPDATE request_statistics SET total_request = total_request + 1, total_found = total_found + 1, "
			."found_on_db = found_on_db + ".$found_on_db.", found_on_ean = found_on_ean + ".$found_on_ean
			.", found_on_upc = found_on_upc + ".$found_on_upc.", found_on_bclu = found_on_bclu + ".$found_on_bclu;
	} else {
		if ($not_foud_barcode != "1") {	//valid barcode but not found
			$sql = "UPDATE request_statistics SET total_request = total_request + 1, total_not_found = total_not_found + 1, "
				."not_found_barcode = CONCAT(not_found_barcode, '".$not_foud_barcode."',';')";
		} else {	//invalid barcode
			$sql = "UPDATE request_statistics SET total_request = total_request + 1, total_not_found = total_not_found + 1, "
				."invalid_barcode_count = invalid_barcode_count + 1";	
		}
	}
	$conn->query($sql);
	$conn->close();
}

/*
Product ID: 
- 4902778913772 (uni-ball pen)
- 8804819086055 (point stick)
- 6935364090555 <= product not found
*/

	
?>
