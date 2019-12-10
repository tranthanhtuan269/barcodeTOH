<?php // Code within app\Helpers\Helper.php

namespace App\Helpers;
use DateTime;
use App\Barcode;

class BatvHelper{

public static function insertBarcode($barcode){
//dd($barcode);
// Create connection
$HOST = "45.56.85.153";
$USERNAME='tohapi_db';
$PASSWORD='TOHapidb@123';
$DBNAME='tohsystem';
    $conn = mysqli_connect($HOST, $USERNAME, $PASSWORD, $DBNAME);
    $sql = "INSERT INTO barcode_dict VALUES('".$barcode->barcode."','".$barcode->name."','".$barcode->model."','".$barcode->manufacturer."','".$barcode->image."','".$barcode->avg_price."','".$barcode->spec."','".$barcode->feature."','".$barcode->description."',".time(true).")";
    $result = $conn->query($sql);
    $conn->close();	
}

public static function insertBarcode2($barcode){
//dd($barcode);
// Create connection
$HOST = "45.56.85.153";
$USERNAME='tohapi_db';
$PASSWORD='TOHapidb@123';
$DBNAME='tohsystem';
    $conn = mysqli_connect($HOST, $USERNAME, $PASSWORD, $DBNAME);
    $sql = "INSERT INTO barcode_dict VALUES('".$barcode['barcode']."','".$barcode['name']."','".$barcode['model']."','".$barcode['manufacturer']."','".$barcode['image']."','".$barcode['avg_price']."','".$barcode['spec']."','".$barcode['feature']."','".$barcode['description']."',".time(true).")";
    $result = $conn->query($sql);
    $conn->close();
}


public static function insertMultiBarcode($arr){
	foreach($arr as $item){
		BatvHelper::insertBarcode2($item);
	}
}

public static function updateBarcode($barcode){
$HOST = "45.56.85.153";
$USERNAME='tohapi_db';
$PASSWORD='TOHapidb@123';
$DBNAME='tohsystem';
$conn = mysqli_connect($HOST, $USERNAME, $PASSWORD, $DBNAME);
    $sql = "UPDATE barcode_dict SET name = '".$barcode->name."', model = '".$barcode->model."', manufacturer = '".$barcode->manufacturer."', image = '".$barcode->image."', avg_price = '".$barcode->avg_price."', spec = '".$barcode->spec."', feature = '".$barcode->feature."', description = '".$barcode->description."' WHERE barcode = '".$barcode->barcode . "'";
    $result = $conn->query($sql);
    $conn->close();
}

    public static function parse_number($number, $dec_point=null) {
        $result = $number;
        if( (int) $number > 0  ){
            if (empty($dec_point)) {
                $locale = localeconv();
                $dec_point = $locale['decimal_point'];
            }
            $result = floatval(str_replace($dec_point, '.', preg_replace('/[^\d'.preg_quote($dec_point).']/', '', $number)));
        }
        return $result;
    }

    // Hàm so sánh các khoảng thời gian
    public static function handlingTime( $date ){
        $dt = new DateTime($date);
        return $dt->format('U');
    }

    public static function formatDate($date, $orgDateFormat = "d/m/Y", $formatDate="d-m-Y",$timeFormat="H:i:s",$time=true){
        if($time)
        {
            $formatDateTime=$formatDate.' '.$timeFormat;
        }else{
            $formatDateTime=$formatDate;
        }
        $valid_date = date_format(date_create_from_format($orgDateFormat, $date), $formatDateTime);    
        return $valid_date;
    }

    public static function formatPrice($price)
    {
        if ((int) $price == $price) {
            return  number_format($price);  
        }else{
            return number_format((float)$price, 2, '.', ',');
        }
    }

    public static function formatDateStandard($format_time,$time,$format){
        return (!empty($time)) ? \Carbon\Carbon::createFromFormat($format_time,$time)->format($format) : '';
    }   

    public static function CreateKey(){
        return  substr(md5(microtime()),rand(0,26),10); 
    }
    public static function getGender($check) {
        
        switch ($check) {
            case "0" :
                $result = "Female";
                break;
            case "1" :
                $result = "Male";
                break;
            default :
                $result = "-";
        }
        return $result;
    }

    public static function getResponseDescription($responseCode) {
        
        switch ($responseCode) {
            case "0" :
                $result = "Giao dịch thành công - Approved";
                break;
            case "1" :
                $result = "Ngân hàng từ chối giao dịch - Bank Declined";
                break;
            case "3" :
                $result = "Mã đơn vị không tồn tại - Merchant not exist";
                break;
            case "4" :
                $result = "Không đúng access code - Invalid access code";
                break;
            case "5" :
                $result = "Số tiền không hợp lệ - Invalid amount";
                break;
            case "6" :
                $result = "Mã tiền tệ không tồn tại - Invalid currency code";
                break;
            case "7" :
                $result = "Lỗi không xác định - Unspecified Failure ";
                break;
            case "8" :
                $result = "Số thẻ không đúng - Invalid card Number";
                break;
            case "9" :
                $result = "Tên chủ thẻ không đúng - Invalid card name";
                break;
            case "10" :
                $result = "Thẻ hết hạn/Thẻ bị khóa - Expired Card";
                break;
            case "11" :
                $result = "Thẻ chưa đăng ký sử dụng dịch vụ - Card Not Registed Service(internet banking)";
                break;
            case "12" :
                $result = "Ngày phát hành/Hết hạn không đúng - Invalid card date";
                break;
            case "13" :
                $result = "Vượt quá hạn mức thanh toán - Exist Amount";
                break;
            case "21" :
                $result = "Số tiền không đủ để thanh toán - Insufficient fund";
                break;
            case "99" :
                $result = "Người sủ dụng hủy giao dịch - User cancel";
                break;
            default :
                $result = "Giao dịch thất bại - Failured";
        }
        return $result;
    }


    public static function null2unknown($data) {
        if ($data == "") {
            return "No Value Returned";
        } else {
            return $data;
        }
    }

    public static function sendEmail($template, $title, $email, $content_mail){
        // Gửi mail
        \Mail::send($template, $content_mail, function($message) use ($email, $title) {
            $message->from('nhansu@tohsoft.com', 'TOH');
            $message->to($email)->subject($title);
        });
    }
}
