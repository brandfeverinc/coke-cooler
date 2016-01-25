<?php
//if (isset($_SERVER['HTTP_UPLOAD_FILENAME_IMAGE']) || isset($_SERVER['HTTP_UPLOAD_FILENAME_PDF'])) {
if (isset($_SERVER['HTTP_UPLOAD_FILENAME'])) {
    // ajax-style upload of files using drag & drop:
    // just handle the request and echo "success|<id>" then exit
    $datetime = date('YmdHis');
    $image_filename = "";
    $pdf_filename = "";
//    $objItemTest = new ItemTest();
    
    // first save the file
    if (isset($_SERVER['HTTP_UPLOAD_FILENAME'])) {
        $filename = $_SERVER['HTTP_UPLOAD_FILENAME'];
        $tmpfilename = RandomAlphaString(8);
        $ret = file_put_contents($_SERVER['DOCUMENT_ROOT'] . "/files/drag_and_drop_tmp/" . $tmpfilename, file_get_contents('php://input'));
        if ($ret === false) {
            echo "Could not save file.";
            exit; // abort
        }
    }
    
//    // then save file name to db
//    $objItemTest = new ItemTest($_REQUEST['id']);
//    if ($image_filename != "") {
//        $objItemTest->ImageFile = $image_filename;
//    }
//    if ($pdf_filename != "") {
//        $objItemTest->PdfFile = $pdf_filename;
//    }
//    $objItemTest->Update();
    
    // finally echo result for use by javascript:
    echo "success|" . $filename . "|" . $tmpfilename;
    
    exit;
    
}

function RandomAlphaString($length) {
    $characters = 'abcdefghijklmnopqrstuvwxyz0123456789';

    $returnString = '';
    for ($i = 0; $i < $length; $i++) {
        $returnString .= $characters[rand(0, strlen($characters) - 1)];
    }
	return $returnString;
}
?>