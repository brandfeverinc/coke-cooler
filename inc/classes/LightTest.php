<?php
/////////////////////////////////////////////////////////////////////////////
//
// LightTest class - represents the light_test database table
// Created on: 2016-01-13
/////////////////////////////////////////////////////////////////////////////

require_once("BaseModelPdo.php");

class LightTest extends BaseModelPdo
{
    public $Id;
    public $LightId;
    public $DarkText;
    public $LightText;
    public $ImageFileDark;
    public $ImageFileLight;
    public $BackgroundImageFile;
    public $RgbaValue;
    public $RgbaValueRight;
    public $SortOrder;

    // Default constructor creates an empty object
    public function __construct(
        $this_id = null,
        $this_light_id = null,
        $this_dark_text = null,
        $this_light_text = null,
        $this_image_file_dark = null,
        $this_image_file_light = null,
        $this_background_image_file = null,
        $this_rgba_value = null,
        $this_rgba_value_right = null,
        $this_sort_order = null
    ) {
        // Run parent constructor
        parent::__construct();

        // Full constructor: use input vars to set up property values of this object
        if (isset($this_id)
            && isset($this_light_id)
            && isset($this_dark_text)
            && isset($this_light_text)
            && isset($this_image_file_dark)
            && isset($this_image_file_light)
            && isset($this_background_image_file)
            && isset($this_rgba_value)
            && isset($this_rgba_value_right)
            && isset($this_sort_order)
        ) {
            $this->Id = $this_id;
            $this->LightId = $this_light_id;
            $this->DarkText = $this_dark_text;
            $this->LightText = $this_light_text;
            $this->ImageFileDark = $this_image_file_dark;
            $this->ImageFileLight = $this_image_file_light;
            $this->BackgroundImageFile = $this_background_image_file;
            $this->RgbaValue = $this_rgba_value;
            $this->RgbaValueRight = $this_rgba_value_right;
            $this->SortOrder = $this_sort_order;
        } elseif (isset($this_id)) {
            // ID constructor: get values from database and populate property values
            // Retrieve this row
            $q = "SELECT * FROM light_test WHERE id = ?";
            $id = intval($this_id);
            $r = parent::returnResultSet($q, $id);
            $hasRow = false;
            // Set property values to values in result set
            foreach ($r as $l) {
                $this->Id = $l->id;
                $this->LightId = $l->light_id;
                $this->DarkText = $l->dark_text;
                $this->LightText = $l->light_text;
                $this->ImageFileDark = $l->image_file_dark;
                $this->ImageFileLight = $l->image_file_light;
                $this->BackgroundImageFile = $l->background_image_file;
                $this->RgbaValue = $l->rgba_value;
                $this->RgbaValueRight = $l->rgba_value_right;
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
            $this->LightId = "";
            $this->DarkText = "";
            $this->LightText = "";
            $this->ImageFileDark = "";
            $this->ImageFileLight = "";
            $this->BackgroundImageFile = "";
            $this->RgbaValue = "";
            $this->RgbaValueRight = "";
            $this->SortOrder = "";
        }
    }

    // Get a list of all LightTest items and return as array of objects
    public function getAllLightTest($orderby = "id")
    {
        // Retrieve all Light_test
        $q = "SELECT * FROM light_test ORDER BY " . addslashes($orderby);
        $r = parent::returnResultSet($q);
        // Array to hold LightTest objects
        $arrLightTest = array();
        // Create a LightTest object for each row and add to array
        foreach ($r as $l) {
            $thisObj = new LightTest(
                $l->id,
                $l->light_id,
                $l->dark_text,
                $l->light_text,
                $l->image_file_dark,
                $l->image_file_light,
                $l->background_image_file,
                $l->rgba_value,
                $l->rgba_value_right,
                $l->sort_order
            );
            $arrLightTest[] = $thisObj;
        }
        // Return array of objects
        return $arrLightTest;
    }

    // Get a list of all LightTest items by LightId and return as array of objects
    public function getAllLightTestByLightId($light_id = null)
    {
        // Retrieve all LightTest By LightId
        $q = "SELECT * FROM light_test WHERE light_id = ? ORDER BY sort_order";
        $var = addslashes($light_id);
        $r = parent::returnResultSet($q, $var);
        // Array to hold LightTest objects
        $arrLightTest = array();
        // Create a LightTest object for each row and add to array
        foreach ($r as $l) {
            $thisObj = new LightTest(
                $l->id,
                $l->light_id,
                $l->dark_text,
                $l->light_text,
                $l->image_file_dark,
                $l->image_file_light,
                $l->background_image_file,
                $l->rgba_value,
                $l->rgba_value_right,
                $l->sort_order
            );
            $arrLightTest[] = $thisObj;
        }
        // Return array of objects
        return $arrLightTest;
    }

    // Persist changes to the db
    public function update()
    {
        $q = "UPDATE light_test SET ";
        $q .= "light_id = '" . addslashes($this->LightId) . "', ";
        $q .= "dark_text = '" . addslashes($this->DarkText) . "', ";
        $q .= "light_text = '" . addslashes($this->LightText) . "', ";
        $q .= "image_file_dark = '" . addslashes($this->ImageFileDark) . "', ";
        $q .= "image_file_light = '" . addslashes($this->ImageFileLight) . "', ";
        $q .= "background_image_file = '" . addslashes($this->BackgroundImageFile) . "', ";
        $q .= "rgba_value = '" . addslashes($this->RgbaValue) . "', ";
        $q .= "rgba_value_right = '" . addslashes($this->RgbaValueRight) . "', ";
        $q .= "sort_order = '" . addslashes($this->SortOrder) . "' ";
        $q .= "WHERE id = " . intval($this->Id);
        parent::updateQuery($q);
        $this->resetSortOrder($this->LightId);
    }

    // Save to database as new object and assign ID
    public function create()
    {
        $q = "INSERT INTO light_test (";
        $q .= "light_id, ";
        $q .= "dark_text, ";
        $q .= "light_text, ";
        $q .= "image_file_dark, ";
        $q .= "image_file_light, ";
        $q .= "background_image_file, ";
        $q .= "rgba_value, ";
        $q .= "rgba_value_right, ";
        $q .= "sort_order";
        $q .= ") VALUES (";
        $q .= "'" . addslashes($this->LightId) . "', ";
        $q .= "'" . addslashes($this->DarkText) . "', ";
        $q .= "'" . addslashes($this->LightText) . "', ";
        $q .= "'" . addslashes($this->ImageFileDark) . "', ";
        $q .= "'" . addslashes($this->ImageFileLight) . "', ";
        $q .= "'" . addslashes($this->BackgroundImageFile) . "', ";
        $q .= "'" . addslashes($this->RgbaValue) . "', ";
        $q .= "'" . addslashes($this->RgbaValueRight) . "', ";
        $q .= "'" . addslashes($this->SortOrder) . "')";
        $this->Id = parent::insertQuery($q);
        $this->resetSortOrder($this->LightId);
    }

    // Delete function takes ID as parameter
    public function delete($deleteID)
    {
        $q = "SELECT * FROM sound_test WHERE id = ?";
        $r = parent::ReturnResultSet($q, $deleteID);
        $light_id = $r[0]->light_id;
        
        $q = "DELETE FROM light_test WHERE id = " . intval($deleteID);
        parent::updateQuery($q);
        $this->resetSortOrder($light_id);
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

    private function resetSortOrder($light_id) {
    	$q = "SELECT id, sort_order FROM light_test WHERE light_id = " . $light_id . " ORDER BY sort_order ";
        $r = parent::returnResultSet($q);
        $i = 10;
        foreach ($r as $l) {
			$q = "UPDATE light_test SET sort_order = '" . $i . "' WHERE id = '" . $l->id . "' ";
        	parent::updateQuery($q);
			$i += 10;
		}
    }

    public function sortValues($id, $direction) {
        // Get current sort value
        $q = "SELECT light_id, sort_order FROM light_test WHERE id = " . $id;
        $r = parent::returnresultSet($q);
        $l = $r[0];
        $sortVal = $l->sort_order;
        $light_id= $l->light_id;

        if ($direction == 'U') {
            // Move item that is currently in the new position to this item's position
            $q = "UPDATE light_test SET sort_order = " . $sortVal . " WHERE light_id = " . $light_id . " AND sort_order = " . ($sortVal-10);
        	parent::updateQuery($q);
            // Put selected item in new position
            $q = "UPDATE light_test SET sort_order = " . ($sortVal-10) . " WHERE id = " . $id;
        	parent::updateQuery($q);
        }
        else {
            // Move item that is currently in the new position to this item's position
            $q = "UPDATE light_test SET sort_order = " . $sortVal . " WHERE light_id = " . $light_id . " AND sort_order = " . ($sortVal+10);
        	parent::updateQuery($q);
            // Put selected item in new position
            $q = "UPDATE light_test SET sort_order = " . ($sortVal+10) . " WHERE id = " . $id;
        	parent::updateQuery($q);
        }
    }

	// checks to see if file upload is needed
	public function handleFileUploads() {
		if ($_FILES['image_file_dark']['error'] <> 4) {
			return $this->uploadDarkFile();
		}
		if ($_FILES['image_file_light']['error'] <> 4) {
			return $this->uploadLightFile();
		}
		if ($_FILES['background_image_file']['error'] <> 4) {
			return $this->uploadBackgroundFile();
		}
		
		return 'success';
	}
	
	// upload function for saving photo
	public function uploadDarkFile() {
	    
		$appPath = $_SERVER['DOCUMENT_ROOT'];
		
		// get the temp location and type
		$uploaded_file = $_FILES['image_file_dark']['tmp_name'];
		
		$err = '';
        //if ($err == '') {		
    	//	if (intval($info[0]) != 310 || intval($info[1]) != 215) {
    	//	    $err = 'Error: Image must be 310 pixels wide by 215 pixels high.';
    	//	}
    	//}

		$success = move_uploaded_file($uploaded_file,$appPath."/" . $this->GetPath() . $_FILES['image_file_dark']['name']);

		if ($success) {
		    $this->ImageFileDark = $_FILES['image_file_dark']['name'];
		    $this->update();
    		
    		$err = 'success';
		    
    	}
    	
    	return $err;
	}

	// upload function for saving photo
	public function uploadLightFile() {
	    
		$appPath = $_SERVER['DOCUMENT_ROOT'];
		
		// get the temp location and type
		$uploaded_file = $_FILES['image_file_light']['tmp_name'];
		
		$err = '';
        //if ($err == '') {		
    	//	if (intval($info[0]) != 310 || intval($info[1]) != 215) {
    	//	    $err = 'Error: Image must be 310 pixels wide by 215 pixels high.';
    	//	}
    	//}

		$success = move_uploaded_file($uploaded_file,$appPath."/" . $this->GetPath() . $_FILES['image_file_light']['name']);

		if ($success) {
		    $this->ImageFileLight = $_FILES['image_file_light']['name'];
		    $this->update();
    		
    		$err = 'success';
		    
    	}
    	
    	return $err;
	}

	// upload function for saving photo
	public function uploadBackgroundFile() {
	    
		$appPath = $_SERVER['DOCUMENT_ROOT'];
		
		// get the temp location and type
		$uploaded_file = $_FILES['background_image_file']['tmp_name'];
		
		$err = '';
        //if ($err == '') {		
    	//	if (intval($info[0]) != 310 || intval($info[1]) != 215) {
    	//	    $err = 'Error: Image must be 310 pixels wide by 215 pixels high.';
    	//	}
    	//}

		$success = move_uploaded_file($uploaded_file,$appPath."/" . $this->GetPath() . $_FILES['background_image_file']['name']);

		if ($success) {
		    $this->BackgroundImageFile = $_FILES['background_image_file']['name'];
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
	    return "files/lighttests/";
	}

    public function getCountLightTestByLightId($light_id)
    {
        // Retrieve all ItemImage By ItemId
        $q = "SELECT COUNT(*) As NumCount FROM light_test WHERE light_id = ?";
        $var = $light_id;
        $r = parent::returnResultSet($q, $var);
        return $r[0]->NumCount;
    }

}
