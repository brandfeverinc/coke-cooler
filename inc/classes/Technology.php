<?php
/////////////////////////////////////////////////////////////////////////////
//
// Technology class - represents the technology database table
// Created on: 2016-01-15
/////////////////////////////////////////////////////////////////////////////

require_once("BaseModelPdo.php");

class Technology extends BaseModelPdo
{
    public $Id;
    public $CategoryId;
    public $TechnologyName;
    public $TechnologyButtonImageUrl;
    public $TechnologyButtonActiveImageUrl;
    public $LinkUrl;
    public $TechnologyHeadline;
    public $IsActive;
    public $SortOrder;

    // Default constructor creates an empty object
    public function __construct(
        $this_id = null,
        $this_category_id = null,
        $this_technology_name = null,
        $this_technology_button_image_url = null,
        $this_technology_button_active_image_url = null,
        $this_link_url = null,
        $this_technology_headline = null,
        $this_is_active = null,
        $this_sort_order = null
    ) {
        // Run parent constructor
        parent::__construct();

        // Full constructor: use input vars to set up property values of this object
        if (isset($this_id)
            && isset($this_category_id)
            && isset($this_technology_name)
            && isset($this_technology_button_image_url)
            && isset($this_technology_button_active_image_url)
            && isset($this_link_url)
            && isset($this_technology_headline)
            && isset($this_is_active)
            && isset($this_sort_order)
        ) {
            $this->Id = $this_id;
            $this->CategoryId = $this_category_id;
            $this->TechnologyName = $this_technology_name;
            $this->TechnologyButtonImageUrl = $this_technology_button_image_url;
            $this->TechnologyButtonActiveImageUrl = $this_technology_button_active_image_url;
            $this->LinkUrl = $this_link_url;
            $this->TechnologyHeadline = $this_technology_headline;
            $this->IsActive = $this_is_active;
            $this->SortOrder = $this_sort_order;
        } elseif (isset($this_id)) {
            // ID constructor: get values from database and populate property values
            // Retrieve this row
            $q = "SELECT * FROM technology WHERE id = ?";
            $id = intval($this_id);
            $r = parent::returnResultSet($q, $id);
            $hasRow = false;
            // Set property values to values in result set
            foreach ($r as $l) {
                $this->Id = $l->id;
                $this->CategoryId = $l->category_id;
                $this->TechnologyName = $l->technology_name;
                $this->TechnologyButtonImageUrl = $l->technology_button_image_url;
                $this->TechnologyButtonActiveImageUrl = $l->technology_button_active_image_url;
                $this->LinkUrl = $l->link_url;
                $this->TechnologyHeadline = $l->technology_headline;
                $this->IsActive = $l->is_active;
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
            $this->CategoryId = "";
            $this->TechnologyName = "";
            $this->TechnologyButtonImageUrl = "";
            $this->TechnologyButtonActiveImageUrl = "";
            $this->LinkUrl = "";
            $this->TechnologyHeadline = "";
            $this->IsActive = "";
            $this->SortOrder = "";
        }
    }

    // Get a list of all Technology items and return as array of objects
    public function getAllTechnology($orderby = "id")
    {
        // Retrieve all Technology
        $q = "SELECT * FROM technology ORDER BY " . addslashes($orderby);
        $r = parent::returnResultSet($q);
        // Array to hold Technology objects
        $arrTechnology = array();
        // Create a Technology object for each row and add to array
        foreach ($r as $l) {
            $thisObj = new Technology(
                $l->id,
                $l->category_id,
                $l->technology_name,
                $l->technology_button_image_url,
                $l->technology_button_active_image_url,
                $l->link_url,
                $l->technology_headline,
                $l->is_active,
                $l->sort_order
            );
            $arrTechnology[] = $thisObj;
        }
        // Return array of objects
        return $arrTechnology;
    }

    // Get a list of all Technology items by CategoryId and return as array of objects
    public function getAllTechnologyByCategoryId($category_id = null)
    {
        // Retrieve all Technology By CategoryId
        $q = "SELECT * FROM technology WHERE category_id = ? ORDER BY sort_order";
        $var = addslashes($category_id);
        $r = parent::returnResultSet($q, $var);
        // Array to hold Technology objects
        $arrTechnology = array();
        // Create a Technology object for each row and add to array
        foreach ($r as $l) {
            $thisObj = new Technology(
                $l->id,
                $l->category_id,
                $l->technology_name,
                $l->technology_button_image_url,
                $l->technology_button_active_image_url,
                $l->link_url,
                $l->technology_headline,
                $l->is_active,
                $l->sort_order
            );
            $arrTechnology[] = $thisObj;
        }
        // Return array of objects
        return $arrTechnology;
    }

    // Persist changes to the db
    public function update()
    {
        $q = "UPDATE technology SET ";
        $q .= "category_id = '" . addslashes($this->CategoryId) . "', ";
        $q .= "technology_name = '" . addslashes($this->TechnologyName) . "', ";
        $q .= "technology_button_image_url = '" . addslashes($this->TechnologyButtonImageUrl) . "', ";
        $q .= "technology_button_active_image_url = '" . addslashes($this->TechnologyButtonActiveImageUrl) . "', ";
        $q .= "link_url = '" . addslashes($this->LinkUrl) . "', ";
        $q .= "technology_headline = '" . addslashes($this->TechnologyHeadline) . "', ";
        $q .= "is_active = '" . addslashes($this->IsActive) . "', ";
        $q .= "sort_order = '" . addslashes($this->SortOrder) . "' ";
        $q .= "WHERE id = " . intval($this->Id);
        parent::updateQuery($q);
        $this->resetSortOrder();
    }

    // Save to database as new object and assign ID
    public function create()
    {
        $q = "INSERT INTO technology (";
        $q .= "category_id, ";
        $q .= "technology_name, ";
        $q .= "technology_button_image_url, ";
        $q .= "technology_button_active_image_url, ";
        $q .= "link_url, ";
        $q .= "technology_headline, ";
        $q .= "is_active, ";
        $q .= "sort_order";
        $q .= ") VALUES (";
        $q .= "'" . addslashes($this->CategoryId) . "', ";
        $q .= "'" . addslashes($this->TechnologyName) . "', ";
        $q .= "'" . addslashes($this->TechnologyButtonImageUrl) . "', ";
        $q .= "'" . addslashes($this->TechnologyButtonActiveImageUrl) . "', ";
        $q .= "'" . addslashes($this->LinkUrl) . "', ";
        $q .= "'" . addslashes($this->TechnologyHeadline) . "', ";
        $q .= "'" . addslashes($this->IsActive) . "', ";
        $q .= "'" . addslashes($this->SortOrder) . "')";
        $this->Id = parent::insertQuery($q);
        $this->resetSortOrder();
    }

    // Delete function takes ID as parameter
    public function delete($deleteID)
    {
        $q = "DELETE FROM technology WHERE id = " . intval($deleteID);
        parent::updateQuery($q);
        $this->resetSortOrder();
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

    private function resetSortOrder() {
    	$q = "SELECT id, sort_order FROM technology ORDER BY sort_order ";
        $r = parent::returnResultSet($q);
        $i = 10 + ($this->ItemId * 1000); // separate into groups by 1000s
        foreach ($r as $l) {
			$q = "UPDATE technology SET sort_order = '" . $i . "' WHERE id = '" . $l->id . "' ";
        	parent::updateQuery($q);
			$i += 10;
		}
    }

    public function sortValues($id, $direction) {
        // Get current sort value
        $q = "SELECT sort_order FROM technology WHERE id = " . $id;
        $r = parent::returnresultSet($q);
        $l = $r[0];
        $sortVal = $l->sort_order;

        if ($direction == 'U') {
            // Move item that is currently in the new position to this item's position
            $q = "UPDATE technology SET sort_order = " . $sortVal . " WHERE sort_order = " . ($sortVal-10);
        	parent::updateQuery($q);
            // Put selected item in new position
            $q = "UPDATE technology SET sort_order = " . ($sortVal-10) . " WHERE id = " . $id;
        	parent::updateQuery($q);
        }
        else {
            // Move item that is currently in the new position to this item's position
            $q = "UPDATE technology SET sort_order = " . $sortVal . " WHERE sort_order = " . ($sortVal+10);
        	parent::updateQuery($q);
            // Put selected item in new position
            $q = "UPDATE technology SET sort_order = " . ($sortVal+10) . " WHERE id = " . $id;
        	parent::updateQuery($q);
        }
    }

	// checks to see if file upload is needed
	public function handleFileUploads() {
		if ($_FILES['technology_button_image_url']['error'] <> 4) {
			return $this->uploadFile();
		}
		if ($_FILES['technology_button_active_image_url']['error'] <> 4) {
			return $this->uploadActiveFile();
		}
		
		return 'success';
	}
	
	// upload function for saving photo
	public function uploadFile() {
	    
		$appPath = $_SERVER['DOCUMENT_ROOT'];
		
		// get the temp location and type
		$uploaded_file = $_FILES['technology_button_image_url']['tmp_name'];
		
		$err = '';
        //if ($err == '') {		
    	//	if (intval($info[0]) != 310 || intval($info[1]) != 215) {
    	//	    $err = 'Error: Image must be 310 pixels wide by 215 pixels high.';
    	//	}
    	//}

		$success = move_uploaded_file($uploaded_file,$appPath."/" . $this->GetPath() . $_FILES['technology_button_image_url']['name']);

		if ($success) {
		    $this->TechnologyButtonImageUrl = $_FILES['technology_button_image_url']['name'];
		    $this->update();
    		
    		$err = 'success';
		    
    	}
    	
    	return $err;
	}

	// upload function for saving photo
	public function uploadActiveFile() {
	    
		$appPath = $_SERVER['DOCUMENT_ROOT'];
		
		// get the temp location and type
		$uploaded_file = $_FILES['technology_button_active_image_url']['tmp_name'];
		
		$err = '';
        //if ($err == '') {		
    	//	if (intval($info[0]) != 310 || intval($info[1]) != 215) {
    	//	    $err = 'Error: Image must be 310 pixels wide by 215 pixels high.';
    	//	}
    	//}

		$success = move_uploaded_file($uploaded_file,$appPath."/" . $this->GetPath() . $_FILES['technology_button_active_image_url']['name']);

		if ($success) {
		    $this->TechnologyButtonActiveImageUrl = $_FILES['technology_button_active_image_url']['name'];
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
	    return "files/technologyimages/";
	}

    public function getAllTechnologyByCategoryIdLinkUrl($category_id, $link_url)
    {
        // Retrieve all Technology By CategoryId
        $q = "SELECT * FROM technology WHERE category_id = ? AND link_url = ?";
        $var = array(addslashes($category_id), addslashes($link_url));
        $r = parent::returnResultSet($q, $var);
        // Array to hold Technology objects
        $arrTechnology = array();
        // Create a Technology object for each row and add to array
        foreach ($r as $l) {
            $thisObj = new Technology(
                $l->id,
                $l->category_id,
                $l->technology_name,
                $l->technology_button_image_url,
                $l->technology_button_active_image_url,
                $l->link_url,
                $l->technology_headline,
                $l->is_active,
                $l->sort_order
            );
            $arrTechnology[] = $thisObj;
        }
        // Return array of objects
        return $arrTechnology;
    }

    public function getAllTechnologyByCategoryIdLinkUrlIsActive($category_id, $link_url)
    {
        // Retrieve all Technology By CategoryId
        $q = "SELECT * FROM technology WHERE category_id = ? AND link_url = ?";
        $var = array(addslashes($category_id), addslashes($link_url));
        $r = parent::returnResultSet($q, $var);
        // Array to hold Technology objects
        $arrTechnology = array();
        // Create a Technology object for each row and add to array
        
        if ($r[0]->is_active == 1) {
            return true;
        }
        else {
            return false;
        }
    }

}
?>