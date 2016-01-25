<?php
/////////////////////////////////////////////////////////////////////////////
//
// HomepageImage class - represents the homepage_image database table
// Created on: 2015-12-02
/////////////////////////////////////////////////////////////////////////////

require_once("BaseModelPdo.php");

class HomepageImage extends BaseModelPdo
{
    public $Id;
    public $HomepageImageUrl;
    public $IsActive;
    public $SortOrder;

    // Default constructor creates an empty object
    public function __construct(
        $this_id = null,
        $this_homepage_image_url = null,
        $this_is_active = null,
        $this_sort_order = null
    ) {
        // Run parent constructor
        parent::__construct();

        // Full constructor: use input vars to set up property values of this object
        if (isset($this_id)
            && isset($this_homepage_image_url)
            && isset($this_is_active)
            && isset($this_sort_order)
        ) {
            $this->Id = $this_id;
            $this->HomepageImageUrl = $this_homepage_image_url;
            $this->IsActive = $this_is_active;
            $this->SortOrder = $this_sort_order;
        } elseif (isset($this_id)) {
            // ID constructor: get values from database and populate property values
            // Retrieve this row
            $q = "SELECT * FROM homepage_image WHERE id = ?";
            $id = intval($this_id);
            $r = parent::returnResultSet($q, $id);
            $hasRow = false;
            // Set property values to values in result set
            foreach ($r as $l) {
                $this->Id = $l->id;
                $this->HomepageImageUrl = $l->homepage_image_url;
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
            $this->HomepageImageUrl = "";
            $this->IsActive = "";
            $this->SortOrder = "";
        }
    }

    // Get a list of all HomepageImage items and return as array of objects
    public function getAllHomepageImage($orderby = "id")
    {
        // Retrieve all Homepage_image
        $q = "SELECT * FROM homepage_image ORDER BY " . addslashes($orderby);
        $r = parent::returnResultSet($q);
        // Array to hold HomepageImage objects
        $arrHomepageImage = array();
        // Create a HomepageImage object for each row and add to array
        foreach ($r as $l) {
            $thisObj = new HomepageImage(
                $l->id,
                $l->homepage_image_url,
                $l->is_active,
                $l->sort_order
            );
            $arrHomepageImage[] = $thisObj;
        }
        // Return array of objects
        return $arrHomepageImage;
    }

    // Persist changes to the db
    public function update()
    {
        $q = "UPDATE homepage_image SET ";
        $q .= "homepage_image_url = '" . addslashes($this->HomepageImageUrl) . "', ";
        $q .= "is_active = '" . addslashes($this->IsActive) . "', ";
        $q .= "sort_order = '" . addslashes($this->SortOrder) . "' ";
        $q .= "WHERE id = " . intval($this->Id);
        parent::updateQuery($q);
        $this->resetSortOrder();
    }

    // Save to database as new object and assign ID
    public function create()
    {
        $q = "INSERT INTO homepage_image (";
        $q .= "homepage_image_url, ";
        $q .= "is_active, ";
        $q .= "sort_order";
        $q .= ") VALUES (";
        $q .= "'" . addslashes($this->HomepageImageUrl) . "', ";
        $q .= "'" . addslashes($this->IsActive) . "', ";
        $q .= "'9990')";
        $this->Id = parent::insertQuery($q);
        $this->resetSortOrder();
    }

    // Delete function takes ID as parameter
    public function delete($deleteID)
    {
        $q = "DELETE FROM homepage_image WHERE id = " . intval($deleteID);
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

    // Get a list of all HomepageImage items and return as array of objects
    public function getAllHomepageImageByIsActive($orderby = "sort_order")
    {
        // Retrieve all Homepage_image
        $q = "SELECT * FROM homepage_image WHERE is_active ORDER BY " . addslashes($orderby);
        $r = parent::returnResultSet($q);
        // Array to hold HomepageImage objects
        $arrHomepageImage = array();
        // Create a HomepageImage object for each row and add to array
        foreach ($r as $l) {
            $thisObj = new HomepageImage(
                $l->id,
                $l->homepage_image_url,
                $l->is_active,
                $l->sort_order
            );
            $arrHomepageImage[] = $thisObj;
        }
        // Return array of objects
        return $arrHomepageImage;
    }

    private function resetSortOrder() {
    	$q = "SELECT id, sort_order FROM homepage_image ORDER BY sort_order ";
        $r = parent::returnResultSet($q);
        $i = 10;
        foreach ($r as $l) {
			$q = "UPDATE homepage_image SET sort_order = '" . $i . "' WHERE id = '" . $l->id . "' ";
        	parent::updateQuery($q);
			$i += 10;
		}
    }

    public function sortValues($id, $direction) {
        // Get current sort value
        $q = "SELECT sort_order FROM homepage_image WHERE id = " . $id;
        $r = parent::returnResultSet($q);
        $l = $r[0];
        $sortVal = $l->sort_order;

        if ($direction == 'U') {
            // Move item that is currently in the new position to this item's position
            $q = "UPDATE homepage_image SET sort_order = " . $sortVal . " WHERE sort_order = " . ($sortVal-10);
        	parent::updateQuery($q);
            // Put selected item in new position
            $q = "UPDATE homepage_image SET sort_order = " . ($sortVal-10) . " WHERE id = " . $id;
        	parent::updateQuery($q);
        }
        else {
            // Move item that is currently in the new position to this item's position
            $q = "UPDATE homepage_image SET sort_order = " . $sortVal . " WHERE sort_order = " . ($sortVal+10);
        	parent::updateQuery($q);
            // Put selected item in new position
            $q = "UPDATE homepage_image SET sort_order = " . ($sortVal+10) . " WHERE id = " . $id;
        	parent::updateQuery($q);
        }
    }

	// checks to see if file upload is needed
	public function handleFileUploads() {
		if ($_FILES['image_file']['error'] <> 4) {
			return $this->UploadFile();
		}
		
		return 'success';
	}
	
	// upload function for saving photo
	public function uploadFile() {
	    
		$appPath = $_SERVER['DOCUMENT_ROOT'];
		
		// get the temp location and type
		$uploaded_file = $_FILES['homepage_image_url']['tmp_name'];
		
		$err = '';
        //if ($err == '') {		
    	//	if (intval($info[0]) != 310 || intval($info[1]) != 215) {
    	//	    $err = 'Error: Image must be 310 pixels wide by 215 pixels high.';
    	//	}
    	//}

		$success = move_uploaded_file($uploaded_file,$appPath."/" . $this->GetPath() . $_FILES['homepage_image_url']['name']);

		if ($success) {
		    $this->HomepageImageUrl = $_FILES['homepage_image_url']['name'];
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
	    return "files/homepageimages/";
	}

}