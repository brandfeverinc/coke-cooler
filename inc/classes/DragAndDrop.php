<?php
/////////////////////////////////////////////////////////////////////////////
// 
// DragAndDrop class - contains functions for dragging and dropping files
// 
/////////////////////////////////////////////////////////////////////////////

class DragAndDrop {

	public static function Control($aFldArray, $aFldType) {
	    $r = '';
        $r .= '<script type="text/javascript">' . PHP_EOL;
        $r .= '    // initialize drag-n-drop file upload if supported by browser' . PHP_EOL;
        $r .= '    if (window.File && window.FileList && window.FileReader) {' . PHP_EOL;
        $r .= '        var xhr_test = new XMLHttpRequest();' . PHP_EOL;
        $r .= '        if (xhr_test.upload) {' . PHP_EOL;
        $r .= '            // file upload is supported' . PHP_EOL;
        $r .= '            initDndUpload();' . PHP_EOL;
        $r .= '        }' . PHP_EOL;
        $r .= '    }' . PHP_EOL;
        $r .= '    ' . PHP_EOL;
        $r .= '    function initDndUpload() {' . PHP_EOL;
        
        foreach($aFldArray as $fld_array) {
            $r .= '        var ' . $fld_array . ' = document.getElementById("' . $fld_array . '");' . PHP_EOL;
            $r .= '        ' . $fld_array . '.addEventListener("dragover", stopEvents, false);' . PHP_EOL;
            $r .= '        ' . $fld_array . '.addEventListener("dragleave", stopEvents, false);' . PHP_EOL;
            $r .= '        ' . $fld_array . '.addEventListener("drop", handleFileUpload_' . $fld_array . ', false);' . PHP_EOL;
            $r .= '        ' . $fld_array . '.style.display = "block"; // ok to show drag & drop upload area' . PHP_EOL;
            $r .= '' . PHP_EOL;
        }
        
        $r .= '    }' . PHP_EOL;
        $r .= '    ' . PHP_EOL;
        $r .= '    function stopEvents(e) {' . PHP_EOL;
        $r .= '        e.stopPropagation();' . PHP_EOL;
        $r .= '        e.preventDefault();' . PHP_EOL;
        $r .= '    }' . PHP_EOL;
        $r .= '    ' . PHP_EOL;
        
        $nCnt = 0;
        foreach($aFldArray as $fld_array) {
            $r .= '    function handleFileUpload_' . $fld_array . '(e) {' . PHP_EOL;
            $r .= '        // handles image type file upload' . PHP_EOL;
            $r .= '        // note: don\'t try a js alert before setting files var!' . PHP_EOL;
            $r .= '        var ' . $fld_array . ' = document.getElementById("' . $fld_array . '");' . PHP_EOL;
            $r .= '        ' . $fld_array . '.classList.add("fdhover");' . PHP_EOL;
            $r .= '        stopEvents(e);' . PHP_EOL;
            $r .= '        var files = e.dataTransfer.files;' . PHP_EOL;
            $r .= '        // file item has: lastModifiedDate, name, size, type ' . PHP_EOL;
            $r .= '        var up_file_' . $fld_array . ' = files[0];' . PHP_EOL;
            $r .= '        ' . PHP_EOL;
            
            if ($aFldType[$nCnt] == 'image') {
                $r .= '        if (up_file_' . $fld_array . '.type.substring(0, 5) != "image") {' . PHP_EOL;
                $r .= '            alert("Wrong file type. Not an image.");' . PHP_EOL;
                $r .= '            return;' . PHP_EOL;
                $r .= '        }' . PHP_EOL;
                $r .= '' . PHP_EOL;
                $r .= '        // display image:' . PHP_EOL;
                $r .= '        var fr = new FileReader();' . PHP_EOL;
                $r .= '        fr.onload = imageHandler_' . $fld_array . ';' . PHP_EOL;
                $r .= '        fr.readAsDataURL(up_file_' . $fld_array . ');' . PHP_EOL;
                $r .= '' . PHP_EOL;
                $r .= '        // prepare to upload file' . PHP_EOL;
                $r .= '        var xhr_' . $fld_array . ' = new XMLHttpRequest();' . PHP_EOL;
                $r .= '        ' . PHP_EOL;
                $r .= '        // function to handle upload success' . PHP_EOL;
                $r .= '        xhr_' . $fld_array . '.onreadystatechange = function() {' . PHP_EOL;
                $r .= '            if (xhr_' . $fld_array . '.readyState == 4) {' . PHP_EOL;
                $r .= '                // upload is complete, get saved record id' . PHP_EOL;
                $r .= '                var response_parts = xhr_' . $fld_array . '.responseText.split("|");' . PHP_EOL;
                $r .= '                if (response_parts[0] == "success") {' . PHP_EOL;
                $r .= '                    var msg = document.getElementById("file_message_' . $fld_array . '");' . PHP_EOL;
                $r .= '                    msg.innerHTML = "Uploaded successfully."' . PHP_EOL;
                $r .= '                    ' . $fld_array . '.classList.remove("fdhover");' . PHP_EOL;
                $r .= '                    $("#file_' . $fld_array . '_display").hide();        // (jQuery)' . PHP_EOL;
                $r .= '                    $("#file_message_' . $fld_array . '").fadeOut(5000); // (jQuery)' . PHP_EOL;
                $r .= '                    $("#hidden_' . $fld_array . '").val(response_parts[1]);        // (jQuery)' . PHP_EOL;
                $r .= '                    $("#hidden_tmp_' . $fld_array . '").val(response_parts[2]);        // (jQuery)' . PHP_EOL;
                $r .= '                } else {' . PHP_EOL;
                $r .= '                    alert(response_parts[0]);' . PHP_EOL;
                $r .= '                }' . PHP_EOL;
                $r .= '            }' . PHP_EOL;
                $r .= '        }' . PHP_EOL;
                $r .= '' . PHP_EOL;
            }
            if ($aFldType[$nCnt] == 'pdf') {
                $r .= '        if (up_file_' . $fld_array . '.type != "application/pdf") {' . PHP_EOL;
                $r .= '            alert("Wrong file type. Not a PDF.");' . PHP_EOL;
                $r .= '            ' . $fld_array . '.classList.remove("fdhover");' . PHP_EOL;
                $r .= '            return;' . PHP_EOL;
                $r .= '        }' . PHP_EOL;
                $r .= '        ' . PHP_EOL;
                $r .= '        // display file name:' . PHP_EOL;
                $r .= '        ' . $fld_array . '.innerHTML = up_file_' . $fld_array . '.name + \' <span id="file_message_' . $fld_array . '"></span>\';' . PHP_EOL;
                $r .= '' . PHP_EOL;
                $r .= '        // prepare to upload file' . PHP_EOL;
                $r .= '        var xhr_' . $fld_array . ' = new XMLHttpRequest();' . PHP_EOL;
                $r .= '        ' . PHP_EOL;
                $r .= '        // function to handle upload success' . PHP_EOL;
                $r .= '        xhr_' . $fld_array . '.onreadystatechange = function() {' . PHP_EOL;
                $r .= '            if (xhr_' . $fld_array . '.readyState == 4) {' . PHP_EOL;
                $r .= '                // upload is complete, get saved record id' . PHP_EOL;
                $r .= '                var response_parts = xhr_' . $fld_array . '.responseText.split("|");' . PHP_EOL;
                $r .= '                if (response_parts[0] == "success") {' . PHP_EOL;
                $r .= '                    var msg = document.getElementById("file_message_' . $fld_array . '");' . PHP_EOL;
                $r .= '                    msg.innerHTML = "Uploaded successfully."' . PHP_EOL;
                $r .= '                    ' . $fld_array . '.classList.remove("fdhover");' . PHP_EOL;
                $r .= '                    $("#file_' . $fld_array . '_display").hide();        // (jQuery)' . PHP_EOL;
                $r .= '                    $("#file_message_' . $fld_array . '").fadeOut(5000); // (jQuery)' . PHP_EOL;
                $r .= '                    $("#hidden_' . $fld_array . '").val(response_parts[1]);        // (jQuery)' . PHP_EOL;
                $r .= '                    $("#hidden_tmp_' . $fld_array . '").val(response_parts[2]);        // (jQuery)' . PHP_EOL;
                $r .= '                } else {' . PHP_EOL;
                $r .= '                    alert(response_parts[0]);' . PHP_EOL;
                $r .= '                }' . PHP_EOL;
                $r .= '            }' . PHP_EOL;
                $r .= '        }' . PHP_EOL;
                $r .= '' . PHP_EOL;
            }
            if ($aFldType[$nCnt] == 'video') {
                $r .= '        if (up_file_' . $fld_array . '.type != "video/mp4" && up_file_' . $fld_array . '.type != "video/x-ms-wmv" && up_file_' . $fld_array . '.type != "video/quicktime") {' . PHP_EOL;
                $r .= '            alert("Wrong file type. Not an MP4, WMV or MOV video.");' . PHP_EOL;
                $r .= '            ' . $fld_array . '.classList.remove("fdhover");' . PHP_EOL;
                $r .= '            return;' . PHP_EOL;
                $r .= '        }' . PHP_EOL;
                $r .= '        ' . PHP_EOL;
                $r .= '        // display file name:' . PHP_EOL;
                $r .= '        ' . $fld_array . '.innerHTML = up_file_' . $fld_array . '.name + \' <span id="file_message_' . $fld_array . '"></span>\';' . PHP_EOL;
                $r .= '' . PHP_EOL;
                $r .= '        // prepare to upload file' . PHP_EOL;
                $r .= '        var xhr_' . $fld_array . ' = new XMLHttpRequest();' . PHP_EOL;
                $r .= '        ' . PHP_EOL;
                $r .= '        // function to handle upload success' . PHP_EOL;
                $r .= '        xhr_' . $fld_array . '.onreadystatechange = function() {' . PHP_EOL;
                $r .= '            if (xhr_' . $fld_array . '.readyState == 4) {' . PHP_EOL;
                $r .= '                // upload is complete, get saved record id' . PHP_EOL;
                $r .= '                var response_parts = xhr_' . $fld_array . '.responseText.split("|");' . PHP_EOL;
                $r .= '                if (response_parts[0] == "success") {' . PHP_EOL;
                $r .= '                    var msg = document.getElementById("file_message_' . $fld_array . '");' . PHP_EOL;
                $r .= '                    msg.innerHTML = "Uploaded successfully."' . PHP_EOL;
                $r .= '                    ' . $fld_array . '.classList.remove("fdhover");' . PHP_EOL;
                $r .= '                    $("#file_' . $fld_array . '_display").hide();        // (jQuery)' . PHP_EOL;
                $r .= '                    $("#file_message_' . $fld_array . '").fadeOut(5000); // (jQuery)' . PHP_EOL;
                $r .= '                    $("#hidden_' . $fld_array . '").val(response_parts[1]);        // (jQuery)' . PHP_EOL;
                $r .= '                    $("#hidden_tmp_' . $fld_array . '").val(response_parts[2]);        // (jQuery)' . PHP_EOL;
                $r .= '                } else {' . PHP_EOL;
                $r .= '                    alert(response_parts[0]);' . PHP_EOL;
                $r .= '                }' . PHP_EOL;
                $r .= '            }' . PHP_EOL;
                $r .= '        }' . PHP_EOL;
                $r .= '' . PHP_EOL;
            }
            else {
            	// no file validation for other file types
                $r .= '        // display file name:' . PHP_EOL;
                $r .= '        ' . $fld_array . '.innerHTML = up_file_' . $fld_array . '.name + \' <span id="file_message_' . $fld_array . '"></span>\';' . PHP_EOL;
                $r .= '' . PHP_EOL;
                $r .= '        // prepare to upload file' . PHP_EOL;
                $r .= '        var xhr_' . $fld_array . ' = new XMLHttpRequest();' . PHP_EOL;
                $r .= '        ' . PHP_EOL;
                $r .= '        // function to handle upload success' . PHP_EOL;
                $r .= '        xhr_' . $fld_array . '.onreadystatechange = function() {' . PHP_EOL;
                $r .= '            if (xhr_' . $fld_array . '.readyState == 4) {' . PHP_EOL;
                $r .= '                // upload is complete, get saved record id' . PHP_EOL;
                $r .= '                var response_parts = xhr_' . $fld_array . '.responseText.split("|");' . PHP_EOL;
                $r .= '                if (response_parts[0] == "success") {' . PHP_EOL;
                $r .= '                    var msg = document.getElementById("file_message_' . $fld_array . '");' . PHP_EOL;
                $r .= '                    msg.innerHTML = "Uploaded successfully."' . PHP_EOL;
                $r .= '                    ' . $fld_array . '.classList.remove("fdhover");' . PHP_EOL;
                $r .= '                    $("#file_' . $fld_array . '_display").hide();        // (jQuery)' . PHP_EOL;
                $r .= '                    $("#file_message_' . $fld_array . '").fadeOut(5000); // (jQuery)' . PHP_EOL;
                $r .= '                    $("#hidden_' . $fld_array . '").val(response_parts[1]);        // (jQuery)' . PHP_EOL;
                $r .= '                    $("#hidden_tmp_' . $fld_array . '").val(response_parts[2]);        // (jQuery)' . PHP_EOL;
                $r .= '                } else {' . PHP_EOL;
                $r .= '                    alert(response_parts[0]);' . PHP_EOL;
                $r .= '                }' . PHP_EOL;
                $r .= '            }' . PHP_EOL;
                $r .= '        }' . PHP_EOL;
                $r .= '' . PHP_EOL;
            
            }
            $r .= '        // upload file' . PHP_EOL;
            $r .= '        var f = document.getElementById("admin-form");' . PHP_EOL;
            //$r .= '        var id = document.getElementById("id").value;' . PHP_EOL;
            $r .= '        var url = "/inc/classes/DragAndDrop_ajax.php";' . PHP_EOL;
            $r .= '        xhr_' . $fld_array . '.open("POST", url, true);' . PHP_EOL;
            $r .= '        xhr_' . $fld_array . '.setRequestHeader("UPLOAD_FILENAME", up_file_' . $fld_array . '.name);' . PHP_EOL;
            $r .= '        xhr_' . $fld_array . '.send(up_file_' . $fld_array . ');   ' . PHP_EOL;
            $r .= '        ' . PHP_EOL;
            $r .= '    } // end handleFileUpload' . $fld_array . '' . PHP_EOL;
            $r .= '' . PHP_EOL;

            $r .= '    ' . PHP_EOL;
            if ($aFldType[$nCnt] == 'image') {
                $r .= '    function imageHandler_' . $fld_array . '(e) { ' . PHP_EOL;
                $r .= '        var ' . $fld_array . ' = document.getElementById("' . $fld_array . '");' . PHP_EOL;
                $r .= '        ' . $fld_array . '.innerHTML = \'<img src="\' + e.target.result + \'" style="height:100px; margin:5px 0;"> <span id="file_message_' . $fld_array . '"></span>\';' . PHP_EOL;
                $r .= '    }' . PHP_EOL;
                $r .= '    ' . PHP_EOL;
            }

            $nCnt++;
        }
        $r .= '</script>' . PHP_EOL;
        
        return $r;
	}

	public static function CreateArea($sField) {
	    $r = '';
	    $r .= '<div style="clear:both;"></div>' . PHP_EOL;
	    $r .= '<div id="' . $sField . '" class="filedrag">Or drop file here.</div>' . PHP_EOL;
	    $r .= '<input type="hidden" name="hidden_' . $sField . '" id="hidden_' . $sField . '" value="" />' . PHP_EOL;
	    $r .= '<input type="hidden" name="hidden_tmp_' . $sField . '" id="hidden_tmp_' . $sField . '" value="" />' . PHP_EOL;
        
        return $r;
	}

	public static function SetStyles() {
	    $r = '';
        $r .= '<style>
            .filedrag {
                padding: 10px;
                border: 2px dotted #576580;
                display: none;
                margin: 10px 0;
            }
            .filedrag.fdhover {
                border-color:#08C;
            }
        </style>';
        
        return $r;
	}

}
?>