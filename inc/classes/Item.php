<?php
/////////////////////////////////////////////////////////////////////////////
//
// Item class - represents the item database table
// Created on: 2015-12-03
/////////////////////////////////////////////////////////////////////////////

require_once("BaseModelPdo.php");

class Item extends BaseModelPdo
{
    public $Id;
    public $CategoryId;
    public $ItemName;
    public $BackgroundColor;
    public $ContactEmail;
    public $GalleryDescription;
    public $SortOrder;

    // Default constructor creates an empty object
    public function __construct(
        $this_id = null,
        $this_category_id = null,
        $this_item_name = null,
        $this_background_color = null,
        $this_contact_email = null,
        $this_gallery_description = null,
        $this_sort_order = null
    ) {
        // Run parent constructor
        parent::__construct();

        // Full constructor: use input vars to set up property values of this object
        if (isset($this_id)
            && isset($this_category_id)
            && isset($this_item_name)
            && isset($this_background_color)
            && isset($this_contact_email)
            && isset($this_gallery_description)
            && isset($this_sort_order)
        ) {
            $this->Id = $this_id;
            $this->CategoryId = $this_category_id;
            $this->ItemName = $this_item_name;
            $this->BackgroundColor = $this_background_color;
            $this->ContactEmail = $this_contact_email;
            $this->GalleryDescription = $this_gallery_description;
            $this->SortOrder = $this_sort_order;
        } elseif (isset($this_id)) {
            // ID constructor: get values from database and populate property values
            // Retrieve this row
            $q = "SELECT * FROM item WHERE id = ?";
            $id = intval($this_id);
            $r = parent::returnResultSet($q, $id);
            $hasRow = false;
            // Set property values to values in result set
            foreach ($r as $l) {
                $this->Id = $l->id;
                $this->CategoryId = $l->category_id;
                $this->ItemName = $l->item_name;
                $this->BackgroundColor = $l->background_color;
                $this->ContactEmail = $l->contact_email;
                $this->GalleryDescription = $l->gallery_description;
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
            $this->ItemName = "";
            $this->BackgroundColor = "";
            $this->ContactEmail = "";
            $this->GalleryDescription = "";
            $this->SortOrder = "";
        }
    }

    // Get a list of all Item items and return as array of objects
    public function getAllItem($orderby = "sort_order")
    {
        // Retrieve all _item
        $q = "SELECT * FROM item ORDER BY " . addslashes($orderby). " ";
        $r = parent::returnResultSet($q);
        // Array to hold Item objects
        $arrItem = array();
        // Create a Item object for each row and add to array
        foreach ($r as $l) {
            $thisObj = new Item(
                $l->id,
                $l->category_id,
                $l->item_name,
                $l->background_color,
                $l->contact_email,
                $l->gallery_description,
                $l->sort_order
            );
            $arrItem[] = $thisObj;
        }
        // Return array of objects
        return $arrItem;
    }

    // Persist changes to the db
    public function update()
    {
        $q = "UPDATE item SET ";
        $q .= "category_id = '" . addslashes($this->CategoryId) . "', ";
        $q .= "item_name = '" . addslashes($this->ItemName) . "', ";
        $q .= "background_color = '" . addslashes($this->BackgroundColor) . "', ";
        $q .= "contact_email = '" . addslashes($this->ContactEmail) . "', ";
        $q .= "gallery_description = '" . addslashes($this->GalleryDescription) . "', ";
        $q .= "sort_order = '" . addslashes($this->SortOrder) . "' ";
        $q .= "WHERE id = " . intval($this->Id);
        parent::updateQuery($q);
        $this->resetSortOrder($this->CategoryId);
    }

    // Save to database as new object and assign ID
    public function create()
    {
        $q = "INSERT INTO item (";
        $q .= "category_id, ";
        $q .= "item_name, ";
        $q .= "background_color, ";
        $q .= "contact_email, ";
        $q .= "gallery_description, ";
        $q .= "sort_order";
        $q .= ") VALUES (";
        $q .= "'" . addslashes($this->CategoryId) . "', ";
        $q .= "'" . addslashes($this->ItemName) . "', ";
        $q .= "'" . addslashes($this->BackgroundColor) . "', ";
        $q .= "'" . addslashes($this->ContactEmail) . "', ";
        $q .= "'" . addslashes($this->GalleryDescription) . "', ";
        $q .= "'9990')";
        $this->Id = parent::insertQuery($q);
        $this->resetSortOrder($this->CategoryId);
    }

    // Delete function takes ID as parameter
    public function delete($deleteID)
    {
        $q = "SELECT * FROM item WHERE id = ?";
        $r = parent::ReturnResultSet($q, $deleteID);
        $category_id = $r[0]->category_id;
        
        $q = "DELETE FROM item WHERE id = " . intval($deleteID);
        parent::updateQuery($q);
        $this->resetSortOrder($category_id);
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

    // Get a list of all Item items and return as array of objects
    public function getCountItemByCategoryId($category_id = "")
    {
        // Retrieve all _item
        $q = "SELECT COUNT(*) AS cnt FROM item WHERE category_id = '" . addslashes($category_id) . "' ";
        $r = parent::returnResultSet($q);
        return $r[0]->cnt;
    }

    public function getAllItemByCategoryId($category_id = "", $orderby = "sort_order")
    {
        // Retrieve all _item
        $q = "SELECT * FROM item WHERE category_id = '" . addslashes($category_id) . "' ";
        $q .= "ORDER BY " . addslashes($orderby). " ";
        $r = parent::returnResultSet($q);
        // Array to hold Item objects
        $arrItem = array();
        // Create a Item object for each row and add to array
        foreach ($r as $l) {
            $thisObj = new Item(
                $l->id,
                $l->category_id,
                $l->item_name,
                $l->background_color,
                $l->contact_email,
                $l->gallery_description,
                $l->sort_order
            );
            $arrItem[] = $thisObj;
        }
        // Return array of objects
        return $arrItem;
    }

    private function resetSortOrder($category_id) {
    	$q = "SELECT id, sort_order FROM item WHERE category_id = " . $category_id . " ORDER BY sort_order ";
        $r = parent::returnResultSet($q);
        $i = 10;
        foreach ($r as $l) {
			$q = "UPDATE item SET sort_order = '" . $i . "' WHERE id = '" . $l->id . "' ";
        	parent::updateQuery($q);
			$i += 10;
		}
    }

    public function sortValues($id, $direction) {
        // Get current sort value
        $q = "SELECT category_id, sort_order FROM item WHERE id = " . $id;
        $r = parent::returnresultSet($q);
        $l = $r[0];
        $sortVal = $l->sort_order;
        $category_id = $l->category_id;

        if ($direction == 'U') {
            // Move item that is currently in the new position to this item's position
            $q = "UPDATE item SET sort_order = " . $sortVal . " WHERE category_id = " . $category_id . " AND sort_order = " . ($sortVal-10);
        	parent::updateQuery($q);
            // Put selected item in new position
            $q = "UPDATE item SET sort_order = " . ($sortVal-10) . " WHERE id = " . $id;
        	parent::updateQuery($q);
        }
        else {
            // Move item that is currently in the new position to this item's position
            $q = "UPDATE item SET sort_order = " . $sortVal . " WHERE category_id = " . $category_id . " AND sort_order = " . ($sortVal+10);
        	parent::updateQuery($q);
            // Put selected item in new position
            $q = "UPDATE item SET sort_order = " . ($sortVal+10) . " WHERE id = " . $id;
        	parent::updateQuery($q);
        }
    }

}