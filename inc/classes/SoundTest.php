<?php
/////////////////////////////////////////////////////////////////////////////
//
// SoundTest class - represents the sound_test database table
// Created on: 2016-01-14
/////////////////////////////////////////////////////////////////////////////

require_once("BaseModelPdo.php");

class SoundTest extends BaseModelPdo
{
    public $Id;
    public $SoundId;
    public $SoundTestDescription;
    public $SoundUrl1;
    public $SoundUrl2;
    public $SoundUrl3;
    public $ImageUrl1;
    public $ImageUrl2;
    public $ImageUrl3;
    public $Text1;
    public $Text2;
    public $Text3;
    public $SortOrder;

    // Default constructor creates an empty object
    public function __construct(
        $this_id = null,
        $this_sound_id = null,
        $this_sound_test_description = null,
        $this_sound_url1 = null,
        $this_sound_url2 = null,
        $this_sound_url3 = null,
        $this_image_url1 = null,
        $this_image_url2 = null,
        $this_image_url3 = null,
        $this_text1 = null,
        $this_text2 = null,
        $this_text3 = null,
        $this_sort_order = null
    ) {
        // Run parent constructor
        parent::__construct();

        // Full constructor: use input vars to set up property values of this object
        if (isset($this_id)
            && isset($this_sound_id)
            && isset($this_sound_test_description)
            && isset($this_sound_url1)
            && isset($this_sound_url2)
            && isset($this_sound_url3)
            && isset($this_image_url1)
            && isset($this_image_url2)
            && isset($this_image_url3)
            && isset($this_text1)
            && isset($this_text2)
            && isset($this_text3)
            && isset($this_sort_order)
        ) {
            $this->Id = $this_id;
            $this->SoundId = $this_sound_id;
            $this->SoundTestDescription = $this_sound_test_description;
            $this->SoundUrl1 = $this_sound_url1;
            $this->SoundUrl2 = $this_sound_url2;
            $this->SoundUrl3 = $this_sound_url3;
            $this->ImageUrl1 = $this_image_url1;
            $this->ImageUrl2 = $this_image_url2;
            $this->ImageUrl3 = $this_image_url3;
            $this->Text1 = $this_text1;
            $this->Text2 = $this_text2;
            $this->Text3 = $this_text3;
            $this->SortOrder = $this_sort_order;
        } elseif (isset($this_id)) {
            // ID constructor: get values from database and populate property values
            // Retrieve this row
            $q = "SELECT * FROM sound_test WHERE id = ?";
            $id = intval($this_id);
            $r = parent::returnResultSet($q, $id);
            $hasRow = false;
            // Set property values to values in result set
            foreach ($r as $l) {
                $this->Id = $l->id;
                $this->SoundId = $l->sound_id;
                $this->SoundTestDescription = $l->sound_test_description;
                $this->SoundUrl1 = $l->sound_url1;
                $this->SoundUrl2 = $l->sound_url2;
                $this->SoundUrl3 = $l->sound_url3;
                $this->ImageUrl1 = $l->image_url1;
                $this->ImageUrl2 = $l->image_url2;
                $this->ImageUrl3 = $l->image_url3;
                $this->Text1 = $l->text1;
                $this->Text2 = $l->text2;
                $this->Text3 = $l->text3;
                $this->SortOrder = $l->sort_order;
                $hasRow = true;
            }
            // Make sure there was a row returned; if not, set ID to -1 to indicate a bad object
            if (!$hasRow) {
                $this->Id = -1;
            }
        }

        // Empty constructor: set all properties to empty strings
        else {
            $this->Id = 0;
            $this->SoundId = "";
            $this->SoundTestDescription = "";
            $this->SoundUrl1 = "";
            $this->SoundUrl2 = "";
            $this->SoundUrl3 = "";
            $this->ImageUrl1 = "";
            $this->ImageUrl2 = "";
            $this->ImageUrl3 = "";
            $this->Text1 = "";
            $this->Text2 = "";
            $this->Text3 = "";
            $this->SortOrder = "";
        }
    }

    // Get a list of all SoundTest items and return as array of objects
    public function getAllSoundTest($orderby = "id")
    {
        // Retrieve all Sound_test
        $q = "SELECT * FROM sound_test ORDER BY " . addslashes($orderby);
        $r = parent::returnResultSet($q);
        // Array to hold SoundTest objects
        $arrSoundTest = array();
        // Create a SoundTest object for each row and add to array
        foreach ($r as $l) {
            $thisObj = new SoundTest(
                $l->id,
                $l->sound_id,
                $l->sound_test_description,
                $l->sound_url1,
                $l->sound_url2,
                $l->sound_url3,
                $l->image_url1,
                $l->image_url2,
                $l->image_url3,
                $l->text1,
                $l->text2,
                $l->text3,
                $l->sort_order
            );
            $arrSoundTest[] = $thisObj;
        }
        // Return array of objects
        return $arrSoundTest;
    }

    // Get a list of all SoundTest items by SoundId and return as array of objects
    public function getAllSoundTestBySoundId($sound_id = null)
    {
        // Retrieve all SoundTest By SoundId
        $q = "SELECT * FROM sound_test WHERE sound_id = ? ORDER BY sort_order";
        $var = addslashes($sound_id);
        $r = parent::returnResultSet($q, $var);
        // Array to hold SoundTest objects
        $arrSoundTest = array();
        // Create a SoundTest object for each row and add to array
        foreach ($r as $l) {
            $thisObj = new SoundTest(
                $l->id,
                $l->sound_id,
                $l->sound_test_description,
                $l->sound_url1,
                $l->sound_url2,
                $l->sound_url3,
                $l->image_url1,
                $l->image_url2,
                $l->image_url3,
                $l->text1,
                $l->text2,
                $l->text3,
                $l->sort_order
            );
            $arrSoundTest[] = $thisObj;
        }
        // Return array of objects
        return $arrSoundTest;
    }

    // Persist changes to the db
    public function update()
    {
        $q = "UPDATE sound_test SET ";
        $q .= "sound_id = '" . addslashes($this->SoundId) . "', ";
        $q .= "sound_test_description = '" . addslashes($this->SoundTestDescription) . "', ";
        $q .= "sound_url1 = '" . addslashes($this->SoundUrl1) . "', ";
        $q .= "sound_url2 = '" . addslashes($this->SoundUrl2) . "', ";
        $q .= "sound_url3 = '" . addslashes($this->SoundUrl3) . "', ";
        $q .= "image_url1 = '" . addslashes($this->ImageUrl1) . "', ";
        $q .= "image_url2 = '" . addslashes($this->ImageUrl2) . "', ";
        $q .= "image_url3 = '" . addslashes($this->ImageUrl3) . "', ";
        $q .= "text1 = '" . addslashes($this->Text1) . "', ";
        $q .= "text2 = '" . addslashes($this->Text2) . "', ";
        $q .= "text3 = '" . addslashes($this->Text3) . "', ";
        $q .= "sort_order = '" . addslashes($this->SortOrder) . "' ";
        $q .= "WHERE id = " . intval($this->Id);
        parent::updateQuery($q);
        $this->resetSortOrder($this->SoundId);
    }

    // Save to database as new object and assign ID
    public function create()
    {
        $q = "INSERT INTO sound_test (";
        $q .= "sound_id, ";
        $q .= "sound_test_description, ";
        $q .= "sound_url1, ";
        $q .= "sound_url2, ";
        $q .= "sound_url3, ";
        $q .= "image_url1, ";
        $q .= "image_url2, ";
        $q .= "image_url3, ";
        $q .= "text1, ";
        $q .= "text2, ";
        $q .= "text3, ";
        $q .= "sort_order";
        $q .= ") VALUES (";
        $q .= "'" . addslashes($this->SoundId) . "', ";
        $q .= "'" . addslashes($this->SoundTestDescription) . "', ";
        $q .= "'" . addslashes($this->SoundUrl1) . "', ";
        $q .= "'" . addslashes($this->SoundUrl2) . "', ";
        $q .= "'" . addslashes($this->SoundUrl3) . "', ";
        $q .= "'" . addslashes($this->ImageUrl1) . "', ";
        $q .= "'" . addslashes($this->ImageUrl2) . "', ";
        $q .= "'" . addslashes($this->ImageUrl3) . "', ";
        $q .= "'" . addslashes($this->Text1) . "', ";
        $q .= "'" . addslashes($this->Text2) . "', ";
        $q .= "'" . addslashes($this->Text3) . "', ";
        $q .= "'" . addslashes($this->SortOrder) . "')";
        $this->Id = parent::insertQuery($q);
        $this->resetSortOrder($this->SoundId);
    }

    // Delete function takes ID as parameter
    public function delete($deleteID)
    {
        $q = "SELECT * FROM sound_test WHERE id = ?";
        $r = parent::ReturnResultSet($q, $deleteID);
        $sound_id = $r[0]->sound_id;
        
        $q = "DELETE FROM sound_test WHERE id = " . intval($deleteID);
        parent::updateQuery($q);
        $this->resetSortOrder($sound_id);
    }

    // Generic save function: determines if this is an existing or new entry and calls appropriate function
    public function save()
    {
        if ($this->Id == 0) {
            // Empty classes are set with ID = 0
            $this->create();
        } elseif ($this->Id == -1) {
            // Unretrievable rows are set up as ID = -1
            // Do nothing
        } else {
            // Otherwise, it's an existing ID
            $this->update();
        }
    } // end save()

/////////////////////////////////////////////////////////////////////////////
//                                                                         //
//       Place any functions not auto generated below this area.           //
//                                                                         //
/////////////////////////////////////////////////////////////////////////////

    private function resetSortOrder($sound_id) {
    	$q = "SELECT id, sort_order FROM sound_test WHERE sound_id = " . $sound_id . " ORDER BY sort_order ";
        $r = parent::returnResultSet($q);
        $i = 10;
        foreach ($r as $l) {
			$q = "UPDATE sound_test SET sort_order = '" . $i . "' WHERE id = '" . $l->id . "' ";
        	parent::updateQuery($q);
			$i += 10;
		}
    }

    public function sortValues($id, $direction) {
        // Get current sort value
        $q = "SELECT sound_id, sort_order FROM sound_test WHERE id = " . $id;
        $r = parent::returnresultSet($q);
        $l = $r[0];
        $sortVal = $l->sort_order;
        $sound_id= $l->sound_id;

        if ($direction == 'U') {
            // Move item that is currently in the new position to this item's position
            $q = "UPDATE sound_test SET sort_order = " . $sortVal . " WHERE sound_id = " . $sound_id . " AND sort_order = " . ($sortVal-10);
        	parent::updateQuery($q);
            // Put selected item in new position
            $q = "UPDATE sound_test SET sort_order = " . ($sortVal-10) . " WHERE id = " . $id;
        	parent::updateQuery($q);
        }
        else {
            // Move item that is currently in the new position to this item's position
            $q = "UPDATE sound_test SET sort_order = " . $sortVal . " WHERE sound_id = " . $sound_id . " AND sort_order = " . ($sortVal+10);
        	parent::updateQuery($q);
            // Put selected item in new position
            $q = "UPDATE sound_test SET sort_order = " . ($sortVal+10) . " WHERE id = " . $id;
        	parent::updateQuery($q);
        }
    }

	// checks to see if file upload is needed
	public function handleFileUploads() {
		if ($_FILES['image_url1']['error'] <> 4) {
			return $this->uploadUrl1File();
		}
		if ($_FILES['image_url2']['error'] <> 4) {
			return $this->uploadUrl2File();
		}
		if ($_FILES['image_url3']['error'] <> 4) {
			return $this->uploadUrl3File();
		}
		
		return 'success';
	}
	
	// upload function for saving photo
	public function uploadUrl1File() {
	    
		$appPath = $_SERVER['DOCUMENT_ROOT'];
		
		// get the temp location and type
		$uploaded_file = $_FILES['image_url1']['tmp_name'];
		
		$err = '';
        //if ($err == '') {		
    	//	if (intval($info[0]) != 310 || intval($info[1]) != 215) {
    	//	    $err = 'Error: Image must be 310 pixels wide by 215 pixels high.';
    	//	}
    	//}

		$success = move_uploaded_file($uploaded_file,$appPath."/" . $this->GetPath() . $_FILES['image_url1']['name']);

		if ($success) {
		    $this->ImageUrl1 = $_FILES['image_url1']['name'];
		    $this->update();
    		
    		$err = 'success';
		    
    	}
    	
    	return $err;
	}

	// upload function for saving photo
	public function uploadUrl2File() {
	    
		$appPath = $_SERVER['DOCUMENT_ROOT'];
		
		// get the temp location and type
		$uploaded_file = $_FILES['image_url2']['tmp_name'];
		
		$err = '';
        //if ($err == '') {		
    	//	if (intval($info[0]) != 310 || intval($info[1]) != 215) {
    	//	    $err = 'Error: Image must be 310 pixels wide by 215 pixels high.';
    	//	}
    	//}

		$success = move_uploaded_file($uploaded_file,$appPath."/" . $this->GetPath() . $_FILES['image_url2']['name']);

		if ($success) {
		    $this->ImageUrl2 = $_FILES['image_url2']['name'];
		    $this->update();
    		
    		$err = 'success';
		    
    	}
    	
    	return $err;
	}

	// upload function for saving photo
	public function uploadUrl3File() {
	    
		$appPath = $_SERVER['DOCUMENT_ROOT'];
		
		// get the temp location and type
		$uploaded_file = $_FILES['image_url3']['tmp_name'];
		
		$err = '';
        //if ($err == '') {		
    	//	if (intval($info[0]) != 310 || intval($info[1]) != 215) {
    	//	    $err = 'Error: Image must be 310 pixels wide by 215 pixels high.';
    	//	}
    	//}

		$success = move_uploaded_file($uploaded_file,$appPath."/" . $this->GetPath() . $_FILES['image_url3']['name']);

		if ($success) {
		    $this->ImageUrl3 = $_FILES['image_url3']['name'];
		    $this->update();
    		
    		$err = 'success';
		    
    	}
    	
    	return $err;
	}

	public function handleDropFileUploads($filename, $fldname) {
	    $err = "";
	    
		// Handle Drag and Drop files
		if ($_REQUEST['hidden_' . $filename] != '') {
    		$appPath = $_SERVER['DOCUMENT_ROOT'];
    		$success = rename($appPath."/files/drag_and_drop_tmp/" . $_REQUEST['hidden_tmp_' . $filename], $appPath."/" . $this->GetPath() . $_REQUEST['hidden_' . $filename]);
    		if ($success) {
    		    $this->$fldname = $_REQUEST['hidden_' . $filename];
    		    $this->update();
        		$err = 'success';
        	}
		}

		return 'success';
	}
	
	public function getPath() {
	    return "files/soundtests/";
	}

    public function getCountSoundTestBySoundId($sound_id)
    {
        // Retrieve all ItemImage By ItemId
        $q = "SELECT COUNT(*) As NumCount FROM sound_test WHERE sound_id = ?";
        $var = $sound_id;
        $r = parent::returnResultSet($q, $var);
        return $r[0]->NumCount;
    }

}
