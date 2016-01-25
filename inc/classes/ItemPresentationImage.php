<?php
/////////////////////////////////////////////////////////////////////////////
//
// ItemPresentationImage class - represents the item_presentation_image database table
// Created on: 2015-12-22
/////////////////////////////////////////////////////////////////////////////

require_once("BaseModelPdo.php");

class ItemPresentationImage extends BaseModelPdo
{
    public $Id;
    public $ItemPresentationId;
    public $ItemPresentationImageUrl;
    public $SortOrder;

    // Default constructor creates an empty object
    public function __construct(
        $this_id = null,
        $this_item_presentation_id = null,
        $this_item_presentation_image_url = null,
        $this_sort_order = null
    ) {
        // Run parent constructor
        parent::__construct();

        // Full constructor: use input vars to set up property values of this object
        if (isset($this_id)
            && isset($this_item_presentation_id)
            && isset($this_item_presentation_image_url)
            && isset($this_sort_order)
        ) {
            $this->Id = $this_id;
            $this->ItemPresentationId = $this_item_presentation_id;
            $this->ItemPresentationImageUrl = $this_item_presentation_image_url;
            $this->SortOrder = $this_sort_order;
        } elseif (isset($this_id)) {
            // ID constructor: get values from database and populate property values
            // Retrieve this row
            $q = "SELECT * FROM item_presentation_image WHERE id = ?";
            $id = intval($this_id);
            $r = parent::returnResultSet($q, $id);
            $hasRow = false;
            // Set property values to values in result set
            foreach ($r as $l) {
                $this->Id = $l->id;
                $this->ItemPresentationId = $l->item_presentation_id;
                $this->ItemPresentationImageUrl = $l->item_presentation_image_url;
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
            $this->ItemPresentationId = "";
            $this->ItemPresentationImageUrl = "";
            $this->SortOrder = "";
        }
    }

    // Get a list of all ItemPresentationImage items and return as array of objects
    public function getAllItemPresentationImage($orderby = "id")
    {
        // Retrieve all _item_presentation_image
        $q = "SELECT * FROM item_presentation_image ORDER BY " . addslashes($orderby);
        $r = parent::returnResultSet($q);
        // Array to hold ItemPresentationImage objects
        $arrItemPresentationImage = array();
        // Create a ItemPresentationImage object for each row and add to array
        foreach ($r as $l) {
            $thisObj = new ItemPresentationImage(
                $l->id,
                $l->item_presentation_id,
                $l->item_presentation_image_url,
                $l->sort_order
            );
            $arrItemPresentationImage[] = $thisObj;
        }
        // Return array of objects
        return $arrItemPresentationImage;
    }

    // Get a list of all ItemPresentationImage items by ItemPresentationId and return as array of objects
    public function getAllItemPresentationImageByItemPresentationId($item_presentation_id = null)
    {
        // Retrieve all ItemPresentationImage By ItemPresentationId
        $q = "SELECT * FROM item_presentation_image WHERE item_presentation_id = ?";
        $var = addslashes($item_presentation_id);
        $r = parent::returnResultSet($q, $var);
        // Array to hold ItemPresentationImage objects
        $arrItemPresentationImage = array();
        // Create a ItemPresentationImage object for each row and add to array
        foreach ($r as $l) {
            $thisObj = new ItemPresentationImage(
                $l->id,
                $l->item_presentation_id,
                $l->item_presentation_image_url,
                $l->sort_order
            );
            $arrItemPresentationImage[] = $thisObj;
        }
        // Return array of objects
        return $arrItemPresentationImage;
    }

    // Persist changes to the db
    public function update()
    {
        $q = "UPDATE item_presentation_image SET ";
        $q .= "item_presentation_id = '" . addslashes($this->ItemPresentationId) . "', ";
        $q .= "item_presentation_image_url = '" . addslashes($this->ItemPresentationImageUrl) . "', ";
        $q .= "sort_order = '" . addslashes($this->SortOrder) . "' ";
        $q .= "WHERE id = " . intval($this->Id);
        parent::updateQuery($q);
        $this->resetSortOrder($this->ItemPresentationId);
    }

    // Save to database as new object and assign ID
    public function create()
    {
        $q = "INSERT INTO item_presentation_image (";
        $q .= "item_presentation_id, ";
        $q .= "item_presentation_image_url, ";
        $q .= "sort_order";
        $q .= ") VALUES (";
        $q .= "'" . addslashes($this->ItemPresentationId) . "', ";
        $q .= "'" . addslashes($this->ItemPresentationImageUrl) . "', ";
        $q .= "'" . addslashes($this->SortOrder) . "')";
        $this->Id = parent::insertQuery($q);
        $this->resetSortOrder($this->ItemPresentationId);
    }

    // Delete function takes ID as parameter
    public function delete($deleteID)
    {
        $q = "DELETE FROM item_presentation_image WHERE id = " . intval($deleteID);
        parent::updateQuery($q);
        $this->resetSortOrder($item_presentation_id);
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

    private function resetSortOrder($item_presentation_id) {
    	$q = "SELECT id, sort_order FROM item_presentation_image WHERE item_presentation_id = " . $item_presentation_id . " ORDER BY sort_order ";
        $r = parent::returnResultSet($q);
        $i = 10;
        foreach ($r as $l) {
			$q = "UPDATE item_presentation_image SET sort_order = '" . $i . "' WHERE id = '" . $l->id . "' ";
        	parent::updateQuery($q);
			$i += 10;
		}
    }

    public function sortValues($id, $direction) {
        // Get current sort value
        $q = "SELECT item_presentation_id, sort_order FROM item_presentation_image WHERE id = " . $id;
        $r = parent::returnresultSet($q);
        $l = $r[0];
        $sortVal = $l->sort_order;
        $item_presentation_id= $l->item_presentation_id;

        if ($direction == 'U') {
            // Move item that is currently in the new position to this item's position
            $q = "UPDATE item_presentation_image SET sort_order = " . $sortVal . " WHERE item_presentation_id = " . $item_presentation_id . " AND sort_order = " . ($sortVal-10);
        	parent::updateQuery($q);
            // Put selected item in new position
            $q = "UPDATE item_presentation_image SET sort_order = " . ($sortVal-10) . " WHERE id = " . $id;
        	parent::updateQuery($q);
        }
        else {
            // Move item that is currently in the new position to this item's position
            $q = "UPDATE item_presentation_image SET sort_order = " . $sortVal . " WHERE item_presentation_id = " . $item_presentation_id . " AND sort_order = " . ($sortVal+10);
        	parent::updateQuery($q);
            // Put selected item in new position
            $q = "UPDATE item_presentation_image SET sort_order = " . ($sortVal+10) . " WHERE id = " . $id;
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

		if (!is_dir($appPath."/" . $this->getPath())) {
			mkdir($appPath."/" . $this->getPath());
		}
		$success = move_uploaded_file($uploaded_file,$appPath."/" . $this->getPath() . $_FILES['image_file']['name']);

		if ($success) {
		    $this->ItemPresentationImageUrl = $_FILES['image_file']['name'];
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
			if (!is_dir($appPath."/" . $this->getPath())) {
				mkdir($appPath."/" . $this->getPath());
			}
    		$success = rename($appPath."/files/drag_and_drop_tmp/" . $_REQUEST['hidden_tmp_' . $filename], $appPath."/" . $this->getPath() . $_REQUEST['hidden_' . $filename]);
    		if ($success) {
    		    $this->$fldname = $_REQUEST['hidden_' . $filename];
    		    $this->update();
        		$err = 'success';
        	}
		}

		return 'success';
	}
	
	public function getPath() {
	    return "files/itempresentationimages/" . $this->ItemPresentationId . "/";
	}

    public function GetItemPresentationImageCountByItemPresentationId($item_presentation_id)
    {
        // Retrieve all ItemImage By ItemId
        $q = "SELECT COUNT(*) As NumCount FROM item_presentation_image WHERE item_presentation_id = ?";
        $var = $item_presentation_id;
        $r = parent::returnResultSet($q, $var);
        return $r[0]->NumCount;
    }

}