<?php
/////////////////////////////////////////////////////////////////////////////
//
// ItemImage class - represents the item_image database table
// Created on: 2015-12-04
/////////////////////////////////////////////////////////////////////////////

require_once("BaseModelPdo.php");

class ItemImage extends BaseModelPdo
{
    public $Id;
    public $ItemId;
    public $ItemImageSide;
    public $ItemImageUrl;
    public $SortOrder;

    // Default constructor creates an empty object
    public function __construct(
        $this_id = null,
        $this_item_id = null,
        $this_item_image_side = null,
        $this_item_image_url = null,
        $this_sort_order = null
    ) {
        // Run parent constructor
        parent::__construct();

        // Full constructor: use input vars to set up property values of this object
        if (isset($this_id)
            && isset($this_item_id)
            && isset($this_item_image_side)
            && isset($this_item_image_url)
            && isset($this_sort_order)
        ) {
            $this->Id = $this_id;
            $this->ItemId = $this_item_id;
            $this->ItemImageSide = $this_item_image_side;
            $this->ItemImageUrl = $this_item_image_url;
            $this->SortOrder = $this_sort_order;
        } elseif (isset($this_id)) {
            // ID constructor: get values from database and populate property values
            // Retrieve this row
            $q = "SELECT * FROM item_image WHERE id = ?";
            $id = intval($this_id);
            $r = parent::returnResultSet($q, $id);
            $hasRow = false;
            // Set property values to values in result set
            foreach ($r as $l) {
                $this->Id = $l->id;
                $this->ItemId = $l->item_id;
                $this->ItemImageSide = $l->item_image_side;
                $this->ItemImageUrl = $l->item_image_url;
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
            $this->ItemId = "";
            $this->ItemImageSide = "";
            $this->ItemImageUrl = "";
            $this->SortOrder = "";
        }
    }

    // Get a list of all ItemImage items and return as array of objects
    public function getAllItemImage($orderby = "id")
    {
        // Retrieve all _item_image
        $q = "SELECT * FROM item_image ORDER BY " . addslashes($orderby);
        $r = parent::returnResultSet($q);
        // Array to hold ItemImage objects
        $arrItemImage = array();
        // Create a ItemImage object for each row and add to array
        foreach ($r as $l) {
            $thisObj = new ItemImage(
                $l->id,
                $l->item_id,
                $l->item_image_side,
                $l->item_image_url,
                $l->sort_order
            );
            $arrItemImage[] = $thisObj;
        }
        // Return array of objects
        return $arrItemImage;
    }

    // Get a list of all ItemImage items by ItemId and return as array of objects
    public function getAllItemImageByItemId($item_id = null, $orderby = 'sort_order')
    {
        // Retrieve all ItemImage By ItemId
        $q = "SELECT * FROM item_image WHERE item_id = ? ORDER BY " . $orderby . " ";
        $var = $item_id;
        $r = parent::returnResultSet($q, $var);
        // Array to hold ItemImage objects
        $arrItemImage = array();
        // Create a ItemImage object for each row and add to array
        foreach ($r as $l) {
            $thisObj = new ItemImage(
                $l->id,
                $l->item_id,
                $l->item_image_side,
                $l->item_image_url,
                $l->sort_order
            );
            $arrItemImage[] = $thisObj;
        }
        // Return array of objects
        return $arrItemImage;
    }

    // Persist changes to the db
    public function update()
    {
        $q = "UPDATE item_image SET ";
        $q .= "item_id = '" . addslashes($this->ItemId) . "', ";
        $q .= "item_image_side = '" . addslashes($this->ItemImageSide) . "', ";
        $q .= "item_image_url = '" . addslashes($this->ItemImageUrl) . "', ";
        $q .= "sort_order = '" . addslashes($this->SortOrder) . "' ";
        $q .= "WHERE id = " . intval($this->Id);
        parent::updateQuery($q);
        $this->resetSortOrder($this->ItemId);
    }

    // Save to database as new object and assign ID
    public function create()
    {
        $q = "INSERT INTO item_image (";
        $q .= "item_id, ";
        $q .= "item_image_side, ";
        $q .= "item_image_url, ";
        $q .= "sort_order";
        $q .= ") VALUES (";
        $q .= "'" . addslashes($this->ItemId) . "', ";
        $q .= "'" . addslashes($this->ItemImageSide) . "', ";
        $q .= "'" . addslashes($this->ItemImageUrl) . "', ";
        $q .= "'" . addslashes($this->SortOrder) . "')";
        $this->Id = parent::insertQuery($q);
        $this->resetSortOrder($this->ItemId);
    }

    // Delete function takes ID as parameter
    public function delete($deleteID)
    {
        $q = "SELECT * FROM item_image WHERE id = ?";
        $r = parent::ReturnResultSet($q, $deleteID);
        $item_id = $r[0]->item_id;
        
        $q = "DELETE FROM item_image WHERE id = " . intval($deleteID);
        parent::updateQuery($q);
        $this->resetSortOrder($item_id);
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

    // Get a list of all ItemImage items by ItemId and return as array of objects
    public function getAllItemImageByItemIdItemImageSide($item_id = '', $item_image_side = '')
    {
        // Retrieve all ItemImage By ItemId
        $q = "SELECT * FROM item_image WHERE item_id = ? AND item_image_side = ? ";
        $var = array($item_id, $item_image_side);
        $r = parent::returnResultSet($q, $var);
        // Array to hold ItemImage objects
        $arrItemImage = array();
        // Create a ItemImage object for each row and add to array
        foreach ($r as $l) {
            $thisObj = new ItemImage(
                $l->id,
                $l->item_id,
                $l->item_image_side,
                $l->item_image_url,
                $l->sort_order
            );
            $arrItemImage[] = $thisObj;
        }
        // Return array of objects
        return $arrItemImage;
    }

    private function resetSortOrder($item_id) {
    	$q = "SELECT id, sort_order FROM item_image WHERE item_id = " . $item_id . " ORDER BY sort_order ";
        $r = parent::returnResultSet($q);
        $i = 10;
        foreach ($r as $l) {
			$q = "UPDATE item_image SET sort_order = '" . $i . "' WHERE id = '" . $l->id . "' ";
        	parent::updateQuery($q);
			$i += 10;
		}
    }

    public function sortValues($id, $direction) {
        // Get current sort value
        $q = "SELECT item_id, sort_order FROM item_image WHERE id = " . $id;
        $r = parent::returnresultSet($q);
        $l = $r[0];
        $sortVal = $l->sort_order;
        $item_id= $l->item_id;

        if ($direction == 'U') {
            // Move item that is currently in the new position to this item's position
            $q = "UPDATE item_image SET sort_order = " . $sortVal . " WHERE item_id = " . $item_id . " AND sort_order = " . ($sortVal-10);
        	parent::updateQuery($q);
            // Put selected item in new position
            $q = "UPDATE item_image SET sort_order = " . ($sortVal-10) . " WHERE id = " . $id;
        	parent::updateQuery($q);
        }
        else {
            // Move item that is currently in the new position to this item's position
            $q = "UPDATE item_image SET sort_order = " . $sortVal . " WHERE item_id = " . $item_id . " AND sort_order = " . ($sortVal+10);
        	parent::updateQuery($q);
            // Put selected item in new position
            $q = "UPDATE item_image SET sort_order = " . ($sortVal+10) . " WHERE id = " . $id;
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
		    $this->ItemImageUrl = $_FILES['image_file']['name'];
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
	    return "files/itemimages/";
	}

    public function GetItemImageCountByItemId($item_id)
    {
        // Retrieve all ItemImage By ItemId
        $q = "SELECT COUNT(*) As NumCount FROM item_image WHERE item_id = ?";
        $var = $item_id;
        $r = parent::returnResultSet($q, $var);
        return $r[0]->NumCount;
    }

}