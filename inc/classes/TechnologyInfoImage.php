<?php
/////////////////////////////////////////////////////////////////////////////
//
// TechnologyInfoImage class - represents the technology_info_image database table
// Created on: 2016-01-11
/////////////////////////////////////////////////////////////////////////////

require_once("BaseModelPdo.php");

class TechnologyInfoImage extends BaseModelPdo
{
    public $Id;
    public $TechnologyInfoId;
    public $TechnologyInfoImageUrl;
    public $SortOrder;

    // Default constructor creates an empty object
    public function __construct(
        $this_id = null,
        $this_technology_info_id = null,
        $this_technology_info_image_url = null,
        $this_sort_order = null
    ) {
        // Run parent constructor
        parent::__construct();

        // Full constructor: use input vars to set up property values of this object
        if (isset($this_id)
            && isset($this_technology_info_id)
            && isset($this_technology_info_image_url)
            && isset($this_sort_order)
        ) {
            $this->Id = $this_id;
            $this->TechnologyInfoId = $this_technology_info_id;
            $this->TechnologyInfoImageUrl = $this_technology_info_image_url;
            $this->SortOrder = $this_sort_order;
        } elseif (isset($this_id)) {
            // ID constructor: get values from database and populate property values
            // Retrieve this row
            $q = "SELECT * FROM technology_info_image WHERE id = ?";
            $id = intval($this_id);
            $r = parent::returnResultSet($q, $id);
            $hasRow = false;
            // Set property values to values in result set
            foreach ($r as $l) {
                $this->Id = $l->id;
                $this->TechnologyInfoId = $l->technology_info_id;
                $this->TechnologyInfoImageUrl = $l->technology_info_image_url;
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
            $this->TechnologyInfoId = "";
            $this->TechnologyInfoImageUrl = "";
            $this->SortOrder = "";
        }
    }

    // Get a list of all TechnologyInfoImage items and return as array of objects
    public function getAllTechnologyInfoImage($orderby = "id")
    {
        // Retrieve all Technology_info_image
        $q = "SELECT * FROM technology_info_image ORDER BY " . addslashes($orderby);
        $r = parent::returnResultSet($q);
        // Array to hold TechnologyInfoImage objects
        $arrTechnologyInfoImage = array();
        // Create a TechnologyInfoImage object for each row and add to array
        foreach ($r as $l) {
            $thisObj = new TechnologyInfoImage(
                $l->id,
                $l->technology_info_id,
                $l->technology_info_image_url,
                $l->sort_order
            );
            $arrTechnologyInfoImage[] = $thisObj;
        }
        // Return array of objects
        return $arrTechnologyInfoImage;
    }

    // Get a list of all TechnologyInfoImage items by TechnologyInfoId and return as array of objects
    public function getAllTechnologyInfoImageByTechnologyInfoId($technology_info_id = null)
    {
        // Retrieve all TechnologyInfoImage By TechnologyInfoId
        $q = "SELECT * FROM technology_info_image WHERE technology_info_id = ? ORDER BY sort_order";
        $var = addslashes($technology_info_id);
        $r = parent::returnResultSet($q, $var);
        // Array to hold TechnologyInfoImage objects
        $arrTechnologyInfoImage = array();
        // Create a TechnologyInfoImage object for each row and add to array
        foreach ($r as $l) {
            $thisObj = new TechnologyInfoImage(
                $l->id,
                $l->technology_info_id,
                $l->technology_info_image_url,
                $l->sort_order
            );
            $arrTechnologyInfoImage[] = $thisObj;
        }
        // Return array of objects
        return $arrTechnologyInfoImage;
    }

    // Persist changes to the db
    public function update()
    {
        $q = "UPDATE technology_info_image SET ";
        $q .= "technology_info_id = '" . addslashes($this->TechnologyInfoId) . "', ";
        $q .= "technology_info_image_url = '" . addslashes($this->TechnologyInfoImageUrl) . "', ";
        $q .= "sort_order = '" . addslashes($this->SortOrder) . "' ";
        $q .= "WHERE id = " . intval($this->Id);
        parent::updateQuery($q);
    }

    // Save to database as new object and assign ID
    public function create()
    {
        $q = "INSERT INTO technology_info_image (";
        $q .= "technology_info_id, ";
        $q .= "technology_info_image_url, ";
        $q .= "sort_order";
        $q .= ") VALUES (";
        $q .= "'" . addslashes($this->TechnologyInfoId) . "', ";
        $q .= "'" . addslashes($this->TechnologyInfoImageUrl) . "', ";
        $q .= "'" . addslashes($this->SortOrder) . "')";
        $this->Id = parent::insertQuery($q);
    }

    // Delete function takes ID as parameter
    public function delete($deleteID)
    {
        $q = "DELETE FROM technology_info_image WHERE id = " . intval($deleteID);
        parent::updateQuery($q);
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

    public function getCountTechnologyInfoImageByTechnologyInfoId($technology_info_id = null)
    {
        $q = "SELECT COUNT(*) As NumCount FROM technology_info_image WHERE technology_info_id = ?";
        $var = addslashes($technology_info_id);
        $r = parent::returnResultSet($q, $var);
        return $r[0]->NumCount;
    }

    private function resetSortOrder($technology_info_id) {
    	$q = "SELECT id, sort_order FROM technology_info_image WHERE technology_info_id = " . $technology_info_id . " ORDER BY sort_order ";
        $r = parent::returnResultSet($q);
        $i = 10;
        foreach ($r as $l) {
			$q = "UPDATE technology_info_image SET sort_order = '" . $i . "' WHERE id = '" . $l->id . "' ";
        	parent::updateQuery($q);
			$i += 10;
		}
    }

    public function sortValues($id, $direction) {
        // Get current sort value
        $q = "SELECT technology_info_id, sort_order FROM technology_info_image WHERE id = " . $id;
        $r = parent::returnresultSet($q);
        $l = $r[0];
        $sortVal = $l->sort_order;
        $technology_info_id = $l->technology_info_id;

        if ($direction == 'U') {
            // Move item that is currently in the new position to this item's position
            $q = "UPDATE technology_info_image SET sort_order = " . $sortVal . " WHERE technology_info_id = " . $technology_info_id . " AND sort_order = " . ($sortVal-10);
        	parent::updateQuery($q);
            // Put selected item in new position
            $q = "UPDATE technology_info_image SET sort_order = " . ($sortVal-10) . " WHERE id = " . $id;
        	parent::updateQuery($q);
        }
        else {
            // Move item that is currently in the new position to this item's position
            $q = "UPDATE technology_info_image SET sort_order = " . $sortVal . " WHERE technology_info_id = " . $technology_info_id . " AND sort_order = " . ($sortVal+10);
        	parent::updateQuery($q);
            // Put selected item in new position
            $q = "UPDATE technology_info_image SET sort_order = " . ($sortVal+10) . " WHERE id = " . $id;
        	parent::updateQuery($q);
        }
    }

	// checks to see if file upload is needed
	public function handleFileUploads() {
		if ($_FILES['technology_info_image_url']['error'] <> 4) {
			return $this->uploadFile();
		}
		
		return 'success';
	}
	
	// upload function for saving photo
	public function uploadFile() {
	    
		$appPath = $_SERVER['DOCUMENT_ROOT'];
		
		// get the temp location and type
		$uploaded_file = $_FILES['technology_info_image_url']['tmp_name'];
		
		$err = '';
        //if ($err == '') {		
    	//	if (intval($info[0]) != 310 || intval($info[1]) != 215) {
    	//	    $err = 'Error: Image must be 310 pixels wide by 215 pixels high.';
    	//	}
    	//}

		$success = move_uploaded_file($uploaded_file,$appPath."/" . $this->GetPath() . $_FILES['technology_info_image_url']['name']);

		if ($success) {
		    $this->TechnologyInfoImageUrl = $_FILES['technology_info_image_url']['name'];
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
	    return "files/technologyinfoimage/";
	}

}
