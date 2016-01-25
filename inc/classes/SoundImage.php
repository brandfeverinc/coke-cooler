<?php
/////////////////////////////////////////////////////////////////////////////
//
// SoundImage class - represents the sound_image database table
// Created on: 2016-01-13
/////////////////////////////////////////////////////////////////////////////

require_once("BaseModelPdo.php");

class SoundImage extends BaseModelPdo
{
    public $Id;
    public $SoundId;
    public $ImageFile;
    public $SortOrder;

    // Default constructor creates an empty object
    public function __construct(
        $this_id = null,
        $this_sound_id = null,
        $this_image_file = null,
        $this_sort_order = null
    ) {
        // Run parent constructor
        parent::__construct();

        // Full constructor: use input vars to set up property values of this object
        if (isset($this_id)
            && isset($this_sound_id)
            && isset($this_image_file)
            && isset($this_sort_order)
        ) {
            $this->Id = $this_id;
            $this->SoundId = $this_sound_id;
            $this->ImageFile = $this_image_file;
            $this->SortOrder = $this_sort_order;
        } elseif (isset($this_id)) {
            // ID constructor: get values from database and populate property values
            // Retrieve this row
            $q = "SELECT * FROM sound_image WHERE id = ?";
            $id = intval($this_id);
            $r = parent::returnResultSet($q, $id);
            $hasRow = false;
            // Set property values to values in result set
            foreach ($r as $l) {
                $this->Id = $l->id;
                $this->SoundId = $l->sound_id;
                $this->ImageFile = $l->image_file;
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
            $this->ImageFile = "";
            $this->SortOrder = "";
        }
    }

    // Get a list of all SoundImage items and return as array of objects
    public function getAllSoundImage($orderby = "id")
    {
        // Retrieve all Sound_image
        $q = "SELECT * FROM sound_image ORDER BY " . addslashes($orderby);
        $r = parent::returnResultSet($q);
        // Array to hold SoundImage objects
        $arrSoundImage = array();
        // Create a SoundImage object for each row and add to array
        foreach ($r as $l) {
            $thisObj = new SoundImage(
                $l->id,
                $l->sound_id,
                $l->image_file,
                $l->sort_order
            );
            $arrSoundImage[] = $thisObj;
        }
        // Return array of objects
        return $arrSoundImage;
    }

    // Get a list of all SoundImage items by SoundId and return as array of objects
    public function getAllSoundImageBySoundId($sound_id = null)
    {
        // Retrieve all SoundImage By SoundId
        $q = "SELECT * FROM sound_image WHERE sound_id = ? ORDER BY sort_order";
        $var = addslashes($sound_id);
        $r = parent::returnResultSet($q, $var);
        // Array to hold SoundImage objects
        $arrSoundImage = array();
        // Create a SoundImage object for each row and add to array
        foreach ($r as $l) {
            $thisObj = new SoundImage(
                $l->id,
                $l->sound_id,
                $l->image_file,
                $l->sort_order
            );
            $arrSoundImage[] = $thisObj;
        }
        // Return array of objects
        return $arrSoundImage;
    }

    // Persist changes to the db
    public function update()
    {
        $q = "UPDATE sound_image SET ";
        $q .= "sound_id = '" . addslashes($this->SoundId) . "', ";
        $q .= "image_file = '" . addslashes($this->ImageFile) . "', ";
        $q .= "sort_order = '" . addslashes($this->SortOrder) . "' ";
        $q .= "WHERE id = " . intval($this->Id);
        parent::updateQuery($q);
        $this->resetSortOrder($this->SoundId);
    }

    // Save to database as new object and assign ID
    public function create()
    {
        $q = "INSERT INTO sound_image (";
        $q .= "sound_id, ";
        $q .= "image_file, ";
        $q .= "sort_order";
        $q .= ") VALUES (";
        $q .= "'" . addslashes($this->SoundId) . "', ";
        $q .= "'" . addslashes($this->ImageFile) . "', ";
        $q .= "'" . addslashes($this->SortOrder) . "')";
        $this->Id = parent::insertQuery($q);
        $this->resetSortOrder($this->SoundId);
    }

    // Delete function takes ID as parameter
    public function delete($deleteID)
    {
        $q = "SELECT * FROM sound_image WHERE id = ?";
        $r = parent::ReturnResultSet($q, $deleteID);
        $sound_id = $r[0]->sound_id;
        
        $q = "DELETE FROM sound_image WHERE id = " . intval($deleteID);
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
    	$q = "SELECT id, sort_order FROM sound_image WHERE sound_id = " . $sound_id . " ORDER BY sort_order ";
        $r = parent::returnResultSet($q);
        $i = 10;
        foreach ($r as $l) {
			$q = "UPDATE sound_image SET sort_order = '" . $i . "' WHERE id = '" . $l->id . "' ";
        	parent::updateQuery($q);
			$i += 10;
		}
    }

    public function sortValues($id, $direction) {
        // Get current sort value
        $q = "SELECT sound_id, sort_order FROM sound_image WHERE id = " . $id;
        $r = parent::returnresultSet($q);
        $l = $r[0];
        $sortVal = $l->sort_order;
        $sound_id= $l->sound_id;

        if ($direction == 'U') {
            // Move item that is currently in the new position to this item's position
            $q = "UPDATE sound_image SET sort_order = " . $sortVal . " WHERE sound_id = " . $sound_id . " AND sort_order = " . ($sortVal-10);
        	parent::updateQuery($q);
            // Put selected item in new position
            $q = "UPDATE sound_image SET sort_order = " . ($sortVal-10) . " WHERE id = " . $id;
        	parent::updateQuery($q);
        }
        else {
            // Move item that is currently in the new position to this item's position
            $q = "UPDATE sound_image SET sort_order = " . $sortVal . " WHERE sound_id = " . $sound_id . " AND sort_order = " . ($sortVal+10);
        	parent::updateQuery($q);
            // Put selected item in new position
            $q = "UPDATE sound_image SET sort_order = " . ($sortVal+10) . " WHERE id = " . $id;
        	parent::updateQuery($q);
        }
    }

	// checks to see if file upload is needed
	public function handleFileUploads() {
		if ($_FILES['image_file']['error'] <> 4) {
			return $this->uploadFile();
		}
		
		return 'success';
	}
	
	// upload function for saving photo
	public function uploadFile() {
	    
		$appPath = $_SERVER['DOCUMENT_ROOT'];
		
		// get the temp location and type
		$uploaded_file = $_FILES['image_file']['tmp_name'];
		
		$err = '';
        //if ($err == '') {		
    	//	if (intval($info[0]) != 310 || intval($info[1]) != 215) {
    	//	    $err = 'Error: Image must be 310 pixels wide by 215 pixels high.';
    	//	}
    	//}

		$success = move_uploaded_file($uploaded_file,$appPath."/" . $this->GetPath() . $_FILES['image_file']['name']);

		if ($success) {
		    $this->ImageFile = $_FILES['image_file']['name'];
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
	    return "files/soundimages/";
	}

    public function getCountSoundImageBySoundId($sound_id)
    {
        // Retrieve all ItemImage By ItemId
        $q = "SELECT COUNT(*) As NumCount FROM sound_image WHERE sound_id = ?";
        $var = $sound_id;
        $r = parent::returnResultSet($q, $var);
        return $r[0]->NumCount;
    }

}