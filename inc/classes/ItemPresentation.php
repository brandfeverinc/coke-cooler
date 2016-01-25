<?php
/////////////////////////////////////////////////////////////////////////////
//
// ItemPresentation class - represents the item_presentation database table
// Created on: 2016-01-12
/////////////////////////////////////////////////////////////////////////////

require_once("BaseModelPdo.php");

class ItemPresentation extends BaseModelPdo
{
    public $Id;
    public $ItemId;
    public $ItemPresentationName;
    public $ItemPresentationThumbnailUrl;

    // Default constructor creates an empty object
    public function __construct(
        $this_id = null,
        $this_item_id = null,
        $this_item_presentation_name = null,
        $this_item_presentation_thumbnail_url = null
    ) {
        // Run parent constructor
        parent::__construct();

        // Full constructor: use input vars to set up property values of this object
        if (isset($this_id)
            && isset($this_item_id)
            && isset($this_item_presentation_name)
            && isset($this_item_presentation_thumbnail_url)
        ) {
            $this->Id = $this_id;
            $this->ItemId = $this_item_id;
            $this->ItemPresentationName = $this_item_presentation_name;
            $this->ItemPresentationThumbnailUrl = $this_item_presentation_thumbnail_url;
        } elseif (isset($this_id)) {
            // ID constructor: get values from database and populate property values
            // Retrieve this row
            $q = "SELECT * FROM item_presentation WHERE id = ?";
            $id = intval($this_id);
            $r = parent::returnResultSet($q, $id);
            $hasRow = false;
            // Set property values to values in result set
            foreach ($r as $l) {
                $this->Id = $l->id;
                $this->ItemId = $l->item_id;
                $this->ItemPresentationName = $l->item_presentation_name;
                $this->ItemPresentationThumbnailUrl = $l->item_presentation_thumbnail_url;
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
            $this->ItemPresentationName = "";
            $this->ItemPresentationThumbnailUrl = "";
        }
    }

    // Get a list of all ItemPresentation items and return as array of objects
    public function getAllItemPresentation($orderby = "id")
    {
        // Retrieve all Item_presentation
        $q = "SELECT * FROM item_presentation ORDER BY " . addslashes($orderby);
        $r = parent::returnResultSet($q);
        // Array to hold ItemPresentation objects
        $arrItemPresentation = array();
        // Create a ItemPresentation object for each row and add to array
        foreach ($r as $l) {
            $thisObj = new ItemPresentation(
                $l->id,
                $l->item_id,
                $l->item_presentation_name,
                $l->item_presentation_thumbnail_url
            );
            $arrItemPresentation[] = $thisObj;
        }
        // Return array of objects
        return $arrItemPresentation;
    }

    // Get a list of all ItemPresentation items by ItemId and return as array of objects
    public function getAllItemPresentationByItemId($item_id = null)
    {
        // Retrieve all ItemPresentation By ItemId
        $q = "SELECT * FROM item_presentation WHERE item_id = ?";
        $var = addslashes($item_id);
        $r = parent::returnResultSet($q, $var);
        // Array to hold ItemPresentation objects
        $arrItemPresentation = array();
        // Create a ItemPresentation object for each row and add to array
        foreach ($r as $l) {
            $thisObj = new ItemPresentation(
                $l->id,
                $l->item_id,
                $l->item_presentation_name,
                $l->item_presentation_thumbnail_url
            );
            $arrItemPresentation[] = $thisObj;
        }
        // Return array of objects
        return $arrItemPresentation;
    }

    // Persist changes to the db
    public function update()
    {
        $q = "UPDATE item_presentation SET ";
        $q .= "item_id = '" . addslashes($this->ItemId) . "', ";
        $q .= "item_presentation_name = '" . addslashes($this->ItemPresentationName) . "', ";
        $q .= "item_presentation_thumbnail_url = '" . addslashes($this->ItemPresentationThumbnailUrl) . "' ";
        $q .= "WHERE id = " . intval($this->Id);
        parent::updateQuery($q);
    }

    // Save to database as new object and assign ID
    public function create()
    {
        $q = "INSERT INTO item_presentation (";
        $q .= "item_id, ";
        $q .= "item_presentation_name, ";
        $q .= "item_presentation_thumbnail_url";
        $q .= ") VALUES (";
        $q .= "'" . addslashes($this->ItemId) . "', ";
        $q .= "'" . addslashes($this->ItemPresentationName) . "', ";
        $q .= "'" . addslashes($this->ItemPresentationThumbnailUrl) . "')";
        $this->Id = parent::insertQuery($q);
    }

    // Delete function takes ID as parameter
    public function delete($deleteID)
    {
        $q = "DELETE FROM item_presentation WHERE id = " . intval($deleteID);
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

	// checks to see if file upload is needed
	public function handleFileUploads() {
		if ($_FILES['item_presentation_thumbnail_url']['error'] <> 4) {
			return $this->uploadFile();
		}
		
		return 'success';
	}
	
	// upload function for saving photo
	public function uploadFile() {
	    
		$appPath = $_SERVER['DOCUMENT_ROOT'];
		
		// get the temp location and type
		$uploaded_file = $_FILES['item_presentation_thumbnail_url']['tmp_name'];
		
		$err = '';
        //if ($err == '') {		
    	//	if (intval($info[0]) != 310 || intval($info[1]) != 215) {
    	//	    $err = 'Error: Image must be 310 pixels wide by 215 pixels high.';
    	//	}
    	//}

		$success = move_uploaded_file($uploaded_file,$appPath."/" . $this->GetPath() . $_FILES['item_presentation_thumbnail_url']['name']);

		if ($success) {
		    $this->ItemPresentationThumbnailUrl = $_FILES['item_presentation_thumbnail_url']['name'];
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
	    return "files/itempresentationimages/";
	}

    public function GetItemPresentationCountByItemId($item_id)
    {
        // Retrieve all ItemImage By ItemId
        $q = "SELECT COUNT(*) As NumCount FROM item_presentation WHERE item_id = ?";
        $var = $item_id;
        $r = parent::returnResultSet($q, $var);
        return $r[0]->NumCount;
    }

}