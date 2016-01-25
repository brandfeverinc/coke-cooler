<?php
/////////////////////////////////////////////////////////////////////////////
//
// Category class - represents the category database table
// Created on: 2015-12-02
/////////////////////////////////////////////////////////////////////////////

require_once("BaseModelPdo.php");

class Category extends BaseModelPdo
{
    public $Id;
    public $CategoryName;
    public $TitleHeading;
    public $SubtitleHeading;
    public $BackgroundColor;
    public $CategoryDescription;
    public $CategoryImageUrl;
    public $ContactEmail;
    public $LinkUrl;
    public $IsActive;
    public $SortOrder;

    // Default constructor creates an empty object
    public function __construct(
        $this_id = null,
        $this_category_name = null,
        $this_title_heading = null,
        $this_subtitle_heading = null,
        $this_background_color = null,
        $this_category_description = null,
        $this_category_image_url = null,
        $this_contact_email = null,
        $this_link_url = null,
        $this_is_active = null,
        $this_sort_order = null
    ) {
        // Run parent constructor
        parent::__construct();

        // Full constructor: use input vars to set up property values of this object
        if (isset($this_id)
            && isset($this_category_name)
            && isset($this_title_heading)
            && isset($this_subtitle_heading)
            && isset($this_background_color)
            && isset($this_category_description)
            && isset($this_category_image_url)
            && isset($this_contact_email)
            && isset($this_link_url)
            && isset($this_is_active)
            && isset($this_sort_order)
        ) {
            $this->Id = $this_id;
            $this->CategoryName = $this_category_name;
            $this->TitleHeading = $this_title_heading;
            $this->SubtitleHeading = $this_subtitle_heading;
            $this->BackgroundColor = $this_background_color;
            $this->CategoryDescription = $this_category_description;
            $this->CategoryImageUrl = $this_category_image_url;
            $this->ContactEmail = $this_contact_email;
            $this->LinkUrl = $this_link_url;
            $this->IsActive = $this_is_active;
            $this->SortOrder = $this_sort_order;
        } elseif (isset($this_id)) {
            // ID constructor: get values from database and populate property values
            // Retrieve this row
            $q = "SELECT * FROM category WHERE id = ?";
            $id = intval($this_id);
            $r = parent::returnResultSet($q, $id);
            $hasRow = false;
            // Set property values to values in result set
            foreach ($r as $l) {
                $this->Id = $l->id;
                $this->CategoryName = $l->category_name;
                $this->TitleHeading = $l->title_heading;
                $this->SubtitleHeading = $l->subtitle_heading;
                $this->BackgroundColor = $l->background_color;
                $this->CategoryDescription = $l->category_description;
                $this->CategoryImageUrl = $l->category_image_url;
                $this->ContactEmail = $l->contact_email;
                $this->LinkUrl = $l->link_url;
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
            $this->CategoryName = "";
            $this->TitleHeading = "";
            $this->SubtitleHeading = "";
            $this->BackgroundColor = "";
            $this->CategoryDescription = "";
            $this->CategoryImageUrl = "";
            $this->ContactEmail = "";
            $this->LinkUrl = "";
            $this->IsActive = "";
            $this->SortOrder = "";
        }
    }

    // Get a list of all Category items and return as array of objects
    public function getAllCategory($orderby = "sort_order")
    {
        // Retrieve all _category
        $q = "SELECT * FROM category ORDER BY " . addslashes($orderby);
        $r = parent::returnResultSet($q);
        // Array to hold Category objects
        $arrCategory = array();
        // Create a Category object for each row and add to array
        foreach ($r as $l) {
            $thisObj = new Category(
                $l->id,
                $l->category_name,
                $l->title_heading,
                $l->subtitle_heading,
                $l->background_color,
                $l->category_description,
                $l->category_image_url,
                $l->contact_email,
                $l->link_url,
                $l->is_active,
                $l->sort_order
            );
            $arrCategory[] = $thisObj;
        }
        // Return array of objects
        return $arrCategory;
    }

    // Get a list of all Category items by IsActive and return as array of objects
    public function getAllCategoryByIsActive($is_active = null, $orderby = "sort_order")
    {
        // Retrieve all Category By IsActive
        $q = "SELECT * FROM category WHERE is_active = " . addslashes($is_active) . " ";
        $q .= "ORDER BY " . addslashes($orderby) . " ";
        $r = parent::returnResultSet($q);
        // Array to hold Category objects
        $arrCategory = array();
        // Create a Category object for each row and add to array
        foreach ($r as $l) {
            $thisObj = new Category(
                $l->id,
                $l->category_name,
                $l->title_heading,
                $l->subtitle_heading,
                $l->background_color,
                $l->category_description,
                $l->category_image_url,
                $l->contact_email,
                $l->link_url,
                $l->is_active,
                $l->sort_order
            );
            $arrCategory[] = $thisObj;
        }
        // Return array of objects
        return $arrCategory;
    }

    // Persist changes to the db
    public function update()
    {
        $q = "UPDATE category SET ";
        $q .= "category_name = '" . addslashes($this->CategoryName) . "', ";
        $q .= "title_heading = '" . addslashes($this->TitleHeading) . "', ";
        $q .= "subtitle_heading = '" . addslashes($this->SubtitleHeading) . "', ";
        $q .= "background_color = '" . addslashes($this->BackgroundColor) . "', ";
        $q .= "category_description = '" . addslashes($this->CategoryDescription) . "', ";
        $q .= "category_image_url = '" . addslashes($this->CategoryImageUrl) . "', ";
        $q .= "contact_email = '" . addslashes($this->ContactEmail) . "', ";
        $q .= "link_url = '" . addslashes($this->LinkUrl) . "', ";
        $q .= "is_active = '" . addslashes($this->IsActive) . "', ";
        $q .= "sort_order = '" . addslashes($this->SortOrder) . "' ";
        $q .= "WHERE id = " . intval($this->Id);
        parent::updateQuery($q);
        $this->resetSortOrder();
    }

    // Save to database as new object and assign ID
    public function create()
    {
        $q = "INSERT INTO category (";
        $q .= "category_name, ";
        $q .= "title_heading, ";
        $q .= "subtitle_heading, ";
        $q .= "background_color, ";
        $q .= "category_description, ";
        $q .= "category_image_url, ";
        $q .= "contact_email, ";
        $q .= "link_url, ";
        $q .= "is_active, ";
        $q .= "sort_order";
        $q .= ") VALUES (";
        $q .= "'" . addslashes($this->CategoryName) . "', ";
        $q .= "'" . addslashes($this->TitleHeading) . "', ";
        $q .= "'" . addslashes($this->SubtitleHeading) . "', ";
        $q .= "'" . addslashes($this->BackgroundColor) . "', ";
        $q .= "'" . addslashes($this->CategoryDescription) . "', ";
        $q .= "'" . addslashes($this->CategoryImageUrl) . "', ";
        $q .= "'" . addslashes($this->ContactEmail) . "', ";
        $q .= "'" . addslashes($this->LinkUrl) . "', ";
        $q .= "'" . addslashes($this->IsActive) . "', ";
        $q .= "'9990')";
        $this->Id = parent::insertQuery($q);
        $this->resetSortOrder();
    }

    // Delete function takes ID as parameter
    public function delete($deleteID)
    {
        $q = "DELETE FROM category WHERE id = " . intval($deleteID);
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
    	$q = "SELECT id, sort_order FROM category ORDER BY sort_order ";
        $r = parent::returnResultSet($q);
        $i = 10;
        foreach ($r as $l) {
			$q = "UPDATE category SET sort_order = '" . $i . "' WHERE id = '" . $l->id . "' ";
        	parent::updateQuery($q);
			$i += 10;
		}
    }

    public function sortValues($id, $direction) {
        // Get current sort value
        $q = "SELECT sort_order FROM category WHERE id = " . $id;
        $r = parent::returnresultSet($q);
        $l = $r[0];
        $sortVal = $l->sort_order;

        if ($direction == 'U') {
            // Move item that is currently in the new position to this item's position
            $q = "UPDATE category SET sort_order = " . $sortVal . " WHERE sort_order = " . ($sortVal-10);
        	parent::updateQuery($q);
            // Put selected item in new position
            $q = "UPDATE category SET sort_order = " . ($sortVal-10) . " WHERE id = " . $id;
        	parent::updateQuery($q);
        }
        else {
            // Move item that is currently in the new position to this item's position
            $q = "UPDATE category SET sort_order = " . $sortVal . " WHERE sort_order = " . ($sortVal+10);
        	parent::updateQuery($q);
            // Put selected item in new position
            $q = "UPDATE category SET sort_order = " . ($sortVal+10) . " WHERE id = " . $id;
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
		    $this->CategoryImageUrl = $_FILES['image_file']['name'];
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
	    return "files/categoryimages/";
	}

}