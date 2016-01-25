<?php
/////////////////////////////////////////////////////////////////////////////
// 
// Helpers class - contains certain static helper functions
// 
/////////////////////////////////////////////////////////////////////////////

class Helpers {
	// takes a mysql date format (yyyy-mm-dd) and returns as mm/dd/yyyy
	public static function DisplayDate($mySqlDate,$format = null) {
		if ($mySqlDate != "") {
			$returnString = "";
			if (!isset($format)) {
				$returnString = date("n/j/Y" ,strtotime($mySqlDate));
			} else {
				$returnString = date($format,strtotime($mySqlDate));
			}
			return $returnString;
			}
		else {
			return "";
		}
	}
	
	// takes a date formatted as mm/dd/yyyy and converts to yyyy-mm-dd for mysql
	public static function InputDate($dateString) {
		$aDate = explode("/",$dateString);
		return $aDate[2]."-".$aDate[0]."-".$aDate[1];
	}
	
	// takes as input params the location of the images folder, the name of the image to be resized, the thumb directory
	// and the new width of the thumbnail.  Resizes the image and saves to the thumb directory as "tn_"+imageName
	public static function CreateJpgThumbnail($imageDirectory, $imageName, $thumbDirectory, $thumbWidth, $thumbName) {
		$srcImg = imagecreatefromjpeg($imageDirectory.$imageName);
		$origWidth = imagesx($srcImg);
		$origHeight = imagesy($srcImg);
		
		$ratio = $thumbWidth / $origWidth;
		$thumbHeight = $origHeight * $ratio;
		
		$thumbImg = imagecreatetruecolor($thumbWidth, $thumbHeight);
		imagecopyresampled($thumbImg, $srcImg, 0, 0, 0, 0, $thumbWidth, $thumbHeight,
			imagesx($srcImg), imagesy($srcImg));
		
		imagejpeg($thumbImg, $thumbDirectory.$thumbName);
	}
	
	// takes as input params the location of the images folder, the name of the image to be resized, the thumb directory
	// and the new width of the thumbnail.  Resizes the image and saves to the thumb directory as "tn_"+imageName
	public static function CreateGifThumbnail($imageDirectory, $imageName, $thumbDirectory, $thumbWidth) {
		$srcImg = ImageCreateFromGif($imageDirectory.$imageName);
		$origWidth = imagesx($srcImg);
		$origHeight = imagesy($srcImg);
		
		$ratio = $thumbWidth / $origWidth;
		$thumbHeight = $origHeight * $ratio;
		
		$thumbImg = imagecreatetruecolor($thumbWidth, $thumbHeight);
		imagecopyresampled($thumbImg, $srcImg, 0, 0, 0, 0, $thumbWidth, $thumbHeight,
			imagesx($srcImg), imagesy($srcImg));
		
		imageGif($thumbImg, $thumbDirectory."tn_".$imageName);
	}
	
	// takes as input params the location of the images folder, the name of the image to be resized, the thumb directory
	// and the new width of the thumbnail.  Resizes the image and saves to the thumb directory as "tn_"+imageName
	public static function CreatePngThumbnail($imageDirectory, $imageName, $thumbDirectory, $thumbWidth, $imageNewName) {
		$srcImg = ImageCreateFromPng($imageDirectory.$imageName);
		$origWidth = imagesx($srcImg);
		$origHeight = imagesy($srcImg);
		
		$ratio = $thumbWidth / $origWidth;
		$thumbHeight = $origHeight * $ratio;
		
		$thumbImg = imagecreatetruecolor($thumbWidth, $thumbHeight);
		imagecopyresampled($thumbImg, $srcImg, 0, 0, 0, 0, $thumbWidth, $thumbHeight,
			imagesx($srcImg), imagesy($srcImg));
		
		imagePng($thumbImg, $thumbDirectory.$imageNewName);
	}
	
	// takes as input 0 or 1 and displays it as boolean no or yes
	public static function DisplayBoolean($booleanInteger) {
		if ($booleanInteger != "") {
			$returnString = "";
			switch ($booleanInteger) {
				case 0:
					$returnString = "No";
					break;
				case 1:
					$returnString = "Yes";
					break;
				default:
					$returnString = "NA";
					break;
			}
			return $returnString;
		}
	}
	
	// takes as input address, city, state, zipcode and puts it into one string
	public static function DisplayAddress($address, $city, $state, $zipcode) {
		$returnString = "";
		if (($address != "") && ($city != "") && ($state != "")) {
			$returnString .= $address."<br/>".$city.", ".$state;
		}
		if ($zipcode != "") {
			$returnString .= ", ".$zipcode;
		}
		if ($returnString != "") {
			$returnString .= "<br/>";
		} else {
			$returnString .= "&nbsp;";
		}
		return $returnString;
	}
	
	// takes a url and formats it into a hyperlink
	public static function DisplayWebsite($website, $css_class = null) {
		$returnString = "";
		
		if ($website != "") {
			if (isset($css_class)) {
				$returnString = "<a href=\"http://".$website."\" class=\"".$css_class."\">".$website."</a>";
			} else {
				$returnString = "<a href=\"http://".$website."\">".$website."</a>";
			}
		} else {
			$returnString = "&nbsp;";
		}
		return $returnString;
	}
	
	public static function DisplaySelected($option, $value) {
		$returnString = "";
		if ($option == $value) {
			$returnString = "selected";
		}
		return $returnString;
	}
	
	public static function IsEmailValid($email) {
		//$regexp = "^([_a-z0-9-]+)(\.[_a-z0-9-]+)*@([a-z0-9-]+)(\.[a-z0-9-]+)*(\.[a-z]{2,4})$";
		$isValid = 0;
		if (filter_var($email, FILTER_VALIDATE_EMAIL)) { 
			$isValid = 1; 
		} else { 
			$isValid = 0; 
		}
		return $isValid;
	}

	public static function CreateBreadCrumbs($aLabels, $aLinks) {
		$returnString = "";
        $returnString .= '<ul class="breadcrumb">' . PHP_EOL;
        $i = 0;
        foreach($aLabels as $label) {
            if (count($aLabels) > ($i+1)) {
                $returnString .= '    <li><a href="' . $aLinks[$i] . '">' . $label . '</a> <span class="divider">/</span></li>' . PHP_EOL;
            }
            else {
                $returnString .= '    <li class="active">' . $label . '</li>' . PHP_EOL;
            }
            $i++;
        }
        $returnString .= '</ul>' . PHP_EOL;
		return $returnString;
	}
	
	public function  mailchimpSubscribe($email) {
        require_once("inc/mailChimp/MCAPI.class.php");

        // Parameters to set:
        $mailchimp_list_id = '7a7ec75cd0';
        $apikey = '54e3b51ee3487256b688501e0d0358ff-us1'; // MailChimp API key for Sugo
        $optin = false;      // true = send optin emails
        $up_exist = true;    // true = update currently subscribed users
        $replace_int = true; // false = add interest, don't replace
        $mail_type = 'html'; // html or text
        
        $api = new MCAPI($apikey);
        //$merge_vars = array('FNAME'=>$first_name, 'LNAME'=>$last_name, 'DATECREATE' => $date_created, 'BIRTHDAY'=>$birthday, 'ANNIVERSRY'=>$anniversary);
          
        $retval = $api->listSubscribe($mailchimp_list_id, $email, '');
    
        // MailChimp response:
        //     if ($api->errorCode){
        //         echo "Unable to load listSubscribe()!\n";
        //         echo "\tCode=".$api->errorCode."\n";
        //         echo "\tMsg=".$api->errorMessage."\n";
        //     } else {
        //         echo "Subscribed!\n";
        //     }
        //     exit;

    }
	
   public function datediff($interval, $datefrom, $dateto, $using_timestamps = false) {
     /*
       $interval can be:
       yyyy - Number of full years
       q - Number of full quarters
       m - Number of full months
       y - Difference between day numbers
         (eg 1st Jan 2004 is "1", the first day. 2nd Feb 2003 is "33". The datediff is "-32".)
       d - Number of full days
       w - Number of full weekdays
       ww - Number of full weeks
       h - Number of full hours
       n - Number of full minutes
       s - Number of full seconds (default)
     */
     
     if (!$using_timestamps) {
       $datefrom = strtotime($datefrom, 0);
       $dateto = strtotime($dateto, 0);
     }
     $difference = $dateto - $datefrom; // Difference in seconds
   
     switch($interval) {
   
       case 'yyyy': // Number of full years

         $years_difference = floor($difference / 31536000);
         if (mktime(date("H", $datefrom), date("i", $datefrom), date("s", $datefrom), date("n", $datefrom), date("j", $datefrom), date("Y", $datefrom)+$years_difference) > $dateto) {
           $years_difference--;
         }
         if (mktime(date("H", $dateto), date("i", $dateto), date("s", $dateto), date("n", $dateto), date("j", $dateto), date("Y", $dateto)-($years_difference+1)) > $datefrom) {
           $years_difference++;
         }
         $datediff = $years_difference;
         break;

       case "q": // Number of full quarters

         $quarters_difference = floor($difference / 8035200);
         while (mktime(date("H", $datefrom), date("i", $datefrom), date("s", $datefrom), date("n", $datefrom)+($quarters_difference*3), date("j", $dateto), date("Y", $datefrom)) < $dateto) {
           $months_difference++;
         }
         $quarters_difference--;
         $datediff = $quarters_difference;
         break;

       case "m": // Number of full months

         $months_difference = floor($difference / 2678400);
         while (mktime(date("H", $datefrom), date("i", $datefrom), date("s", $datefrom), date("n", $datefrom)+($months_difference), date("j", $dateto), date("Y", $datefrom)) < $dateto) {
           $months_difference++;
         }
         $months_difference--;
         $datediff = $months_difference;
         break;

       case 'y': // Difference between day numbers

         $datediff = date("z", $dateto) - date("z", $datefrom);
         break;

       case "d": // Number of full days

         $datediff = floor($difference / 86400);
         break;

       case "w": // Number of full weekdays

         $days_difference = floor($difference / 86400);
         $weeks_difference = floor($days_difference / 7); // Complete weeks
         $first_day = date("w", $datefrom);
         $days_remainder = floor($days_difference % 7);
         $odd_days = $first_day + $days_remainder; // Do we have a Saturday or Sunday in the remainder?
         if ($odd_days > 7) { // Sunday
           $days_remainder--;
         }
         if ($odd_days > 6) { // Saturday
           $days_remainder--;
         }
         $datediff = ($weeks_difference * 5) + $days_remainder;
         break;

       case "ww": // Number of full weeks

         $datediff = floor($difference / 604800);
         break;

       case "h": // Number of full hours

         $datediff = floor($difference / 3600);
         break;

       case "n": // Number of full minutes

         $datediff = floor($difference / 60);
         break;

       default: // Number of full seconds (default)

         $datediff = $difference;
         break;
     }    

     return $datediff;

   }
   
    public function SpaceAroundSlash($item) {
        return (str_replace('/', ' / ', $item));
    }

}
?>