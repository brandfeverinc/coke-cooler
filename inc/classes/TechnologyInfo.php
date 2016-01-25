<?php
/////////////////////////////////////////////////////////////////////////////
//
// TechnologyInfo class - represents the technology_info database table
// Created on: 2016-01-10
/////////////////////////////////////////////////////////////////////////////

require_once("BaseModelPdo.php");

class TechnologyInfo extends BaseModelPdo
{
    public $Id;
    public $TechnologyId;
    public $TechnologyInfoName;
    public $TechnologyInfoDescription;
    public $TechnologyInfoButtonImageUrl;
    public $TechnologyInfoTemplate;
    public $SortOrder;

    // Default constructor creates an empty object
    public function __construct(
        $this_id = null,
        $this_technology_id = null,
        $this_technology_info_name = null,
        $this_technology_info_description = null,
        $this_technology_info_button_image_url = null,
        $this_technology_info_template = null,
        $this_sort_order = null
    ) {
        // Run parent constructor
        parent::__construct();

        // Full constructor: use input vars to set up property values of this object
        if (isset($this_id)
            && isset($this_technology_id)
            && isset($this_technology_info_name)
            && isset($this_technology_info_description)
            && isset($this_technology_info_button_image_url)
            && isset($this_technology_info_template)
            && isset($this_sort_order)
        ) {
            $this->Id = $this_id;
            $this->TechnologyId = $this_technology_id;
            $this->TechnologyInfoName = $this_technology_info_name;
            $this->TechnologyInfoDescription = $this_technology_info_description;
            $this->TechnologyInfoButtonImageUrl = $this_technology_info_button_image_url;
            $this->TechnologyInfoTemplate = $this_technology_info_template;
            $this->SortOrder = $this_sort_order;
        } elseif (isset($this_id)) {
            // ID constructor: get values from database and populate property values
            // Retrieve this row
            $q = "SELECT * FROM technology_info WHERE id = ?";
            $id = intval($this_id);
            $r = parent::returnResultSet($q, $id);
            $hasRow = false;
            // Set property values to values in result set
            foreach ($r as $l) {
                $this->Id = $l->id;
                $this->TechnologyId = $l->technology_id;
                $this->TechnologyInfoName = $l->technology_info_name;
                $this->TechnologyInfoDescription = $l->technology_info_description;
                $this->TechnologyInfoButtonImageUrl = $l->technology_info_button_image_url;
                $this->TechnologyInfoTemplate = $l->technology_info_template;
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
            $this->TechnologyId = "";
            $this->TechnologyInfoName = "";
            $this->TechnologyInfoDescription = "";
            $this->TechnologyInfoButtonImageUrl = "";
            $this->TechnologyInfoTemplate = "";
            $this->SortOrder = "";
        }
    }

    // Get a list of all TechnologyInfo items and return as array of objects
    public function getAllTechnologyInfo($orderby = "id")
    {
        // Retrieve all Technology_info
        $q = "SELECT * FROM technology_info ORDER BY " . addslashes($orderby);
        $r = parent::returnResultSet($q);
        // Array to hold TechnologyInfo objects
        $arrTechnologyInfo = array();
        // Create a TechnologyInfo object for each row and add to array
        foreach ($r as $l) {
            $thisObj = new TechnologyInfo(
                $l->id,
                $l->technology_id,
                $l->technology_info_name,
                $l->technology_info_description,
                $l->technology_info_button_image_url,
                $l->technology_info_template,
                $l->sort_order
            );
            $arrTechnologyInfo[] = $thisObj;
        }
        // Return array of objects
        return $arrTechnologyInfo;
    }

    // Get a list of all TechnologyInfo items by TechnologyId and return as array of objects
    public function getAllTechnologyInfoByTechnologyId($technology_id = null)
    {
        // Retrieve all TechnologyInfo By TechnologyId
        $q = "SELECT * FROM technology_info WHERE technology_id = ? ORDER BY sort_order";
        $var = addslashes($technology_id);
        $r = parent::returnResultSet($q, $var);
        // Array to hold TechnologyInfo objects
        $arrTechnologyInfo = array();
        // Create a TechnologyInfo object for each row and add to array
        foreach ($r as $l) {
            $thisObj = new TechnologyInfo(
                $l->id,
                $l->technology_id,
                $l->technology_info_name,
                $l->technology_info_description,
                $l->technology_info_button_image_url,
                $l->technology_info_template,
                $l->sort_order
            );
            $arrTechnologyInfo[] = $thisObj;
        }
        // Return array of objects
        return $arrTechnologyInfo;
    }

    // Persist changes to the db
    public function update()
    {
        $q = "UPDATE technology_info SET ";
        $q .= "technology_id = '" . addslashes($this->TechnologyId) . "', ";
        $q .= "technology_info_name = '" . addslashes($this->TechnologyInfoName) . "', ";
        $q .= "technology_info_description = '" . addslashes($this->TechnologyInfoDescription) . "', ";
        $q .= "technology_info_button_image_url = '" . addslashes($this->TechnologyInfoButtonImageUrl) . "', ";
        $q .= "technology_info_template = '" . addslashes($this->TechnologyInfoTemplate) . "', ";
        $q .= "sort_order = '" . addslashes($this->SortOrder) . "' ";
        $q .= "WHERE id = " . intval($this->Id);
        parent::updateQuery($q);
        $this->resetSortOrder($this->TechnologyId);
    }

    // Save to database as new object and assign ID
    public function create()
    {
        $q = "INSERT INTO technology_info (";
        $q .= "technology_id, ";
        $q .= "technology_info_name, ";
        $q .= "technology_info_description, ";
        $q .= "technology_info_button_image_url, ";
        $q .= "technology_info_template, ";
        $q .= "sort_order";
        $q .= ") VALUES (";
        $q .= "'" . addslashes($this->TechnologyId) . "', ";
        $q .= "'" . addslashes($this->TechnologyInfoName) . "', ";
        $q .= "'" . addslashes($this->TechnologyInfoDescription) . "', ";
        $q .= "'" . addslashes($this->TechnologyInfoButtonImageUrl) . "', ";
        $q .= "'" . addslashes($this->TechnologyInfoTemplate) . "', ";
        $q .= "'" . addslashes($this->SortOrder) . "')";
        $this->Id = parent::insertQuery($q);
        $this->resetSortOrder($this->TechnologyId);
    }

    // Delete function takes ID as parameter
    public function delete($deleteID)
    {
        $q = "SELECT * FROM technology_info WHERE id = ?";
        $r = parent::ReturnResultSet($q, $deleteID);
        $technology_id = $r[0]->technology_id;
        
        $q = "DELETE FROM technology_info WHERE id = " . intval($deleteID);
        parent::updateQuery($q);
        $this->resetSortOrder($technology_id);
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

    public function getCountTechnologyInfoByTechnologyId($technology_id = null)
    {
        // Retrieve all TechnologyInfo By TechnologyId
        $q = "SELECT COUNT(*) As NumCount FROM technology_info WHERE technology_id = ?";
        $var = addslashes($technology_id);
        $r = parent::returnResultSet($q, $var);
        return $r[0]->NumCount;
    }

    private function resetSortOrder($technology_id) {
    	$q = "SELECT id, sort_order FROM technology_info WHERE technology_id = " . $technology_id . " ORDER BY sort_order ";
        $r = parent::returnResultSet($q);
        $i = 10;
        foreach ($r as $l) {
			$q = "UPDATE technology_info SET sort_order = '" . $i . "' WHERE id = '" . $l->id . "' ";
        	parent::updateQuery($q);
			$i += 10;
		}
    }

    public function sortValues($id, $direction) {
        // Get current sort value
        $q = "SELECT technology_id, sort_order FROM technology_info WHERE id = " . $id;
        $r = parent::returnresultSet($q);
        $l = $r[0];
        $sortVal = $l->sort_order;
        $technology_id = $l->technology_id;

        if ($direction == 'U') {
            // Move item that is currently in the new position to this item's position
            $q = "UPDATE technology_info SET sort_order = " . $sortVal . " WHERE technology_id = " . $technology_id . " AND sort_order = " . ($sortVal-10);
        	parent::updateQuery($q);
            // Put selected item in new position
            $q = "UPDATE technology_info SET sort_order = " . ($sortVal-10) . " WHERE id = " . $id;
        	parent::updateQuery($q);
        }
        else {
            // Move item that is currently in the new position to this item's position
            $q = "UPDATE technology_info SET sort_order = " . $sortVal . " WHERE technology_id = " . $technology_id . " AND sort_order = " . ($sortVal+10);
        	parent::updateQuery($q);
            // Put selected item in new position
            $q = "UPDATE technology_info SET sort_order = " . ($sortVal+10) . " WHERE id = " . $id;
        	parent::updateQuery($q);
        }
    }

	// checks to see if file upload is needed
	public function handleFileUploads() {
		if ($_FILES['technology_info_button_image_url']['error'] <> 4) {
			return $this->uploadFile();
		}
		
		return 'success';
	}
	
	// upload function for saving photo
	public function uploadFile() {
	    
		$appPath = $_SERVER['DOCUMENT_ROOT'];
		
		// get the temp location and type
		$uploaded_file = $_FILES['technology_info_button_image_url']['tmp_name'];
		
		$err = '';
        //if ($err == '') {		
    	//	if (intval($info[0]) != 310 || intval($info[1]) != 215) {
    	//	    $err = 'Error: Image must be 310 pixels wide by 215 pixels high.';
    	//	}
    	//}

		$success = move_uploaded_file($uploaded_file,$appPath."/" . $this->GetPath() . $_FILES['technology_info_button_image_url']['name']);

		if ($success) {
		    $this->TechnologyInfoButtonImageUrl = $_FILES['technology_info_button_image_url']['name'];
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
	    return "files/technologyinfo/";
	}

}