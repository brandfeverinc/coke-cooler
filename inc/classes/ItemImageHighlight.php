<?php
/////////////////////////////////////////////////////////////////////////////
//
// ItemImageHighlight class - represents the item_image_highlight database table
// Created on: 2015-12-08
/////////////////////////////////////////////////////////////////////////////

require_once("BaseModelPdo.php");

class ItemImageHighlight extends BaseModelPdo
{
    public $Id;
    public $ItemImageId;
    public $HotspotLeft;
    public $HotspotTop;
    public $ItemImageHighlightInfo;

    // Default constructor creates an empty object
    public function __construct(
        $this_id = null,
        $this_item_image_id = null,
        $this_hotspot_left = null,
        $this_hotspot_top = null,
        $this_item_image_highlight_info = null
    ) {
        // Run parent constructor
        parent::__construct();

        // Full constructor: use input vars to set up property values of this object
        if (isset($this_id)
            && isset($this_item_image_id)
            && isset($this_hotspot_left)
            && isset($this_hotspot_top)
            && isset($this_item_image_highlight_info)
        ) {
            $this->Id = $this_id;
            $this->ItemImageId = $this_item_image_id;
            $this->HotspotLeft = $this_hotspot_left;
            $this->HotspotTop = $this_hotspot_top;
            $this->ItemImageHighlightInfo = $this_item_image_highlight_info;
        } elseif (isset($this_id)) {
            // ID constructor: get values from database and populate property values
            // Retrieve this row
            $q = "SELECT * FROM item_image_highlight WHERE id = ?";
            $id = intval($this_id);
            $r = parent::returnResultSet($q, $id);
            $hasRow = false;
            // Set property values to values in result set
            foreach ($r as $l) {
                $this->Id = $l->id;
                $this->ItemImageId = $l->item_image_id;
                $this->HotspotLeft = $l->hotspot_left;
                $this->HotspotTop = $l->hotspot_top;
                $this->ItemImageHighlightInfo = $l->item_image_highlight_info;
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
            $this->ItemImageId = "";
            $this->HotspotLeft = "";
            $this->HotspotTop = "";
            $this->ItemImageHighlightInfo = "";
        }
    }

    // Get a list of all ItemImageHighlight items and return as array of objects
    public function getAllItemImageHighlight($orderby = "id")
    {
        // Retrieve all _item_image_highlight
        $q = "SELECT * FROM item_image_highlight ORDER BY " . addslashes($orderby);
        $r = parent::returnResultSet($q);
        // Array to hold ItemImageHighlight objects
        $arrItemImageHighlight = array();
        // Create a ItemImageHighlight object for each row and add to array
        foreach ($r as $l) {
            $thisObj = new ItemImageHighlight(
                $l->id,
                $l->item_image_id,
                $l->hotspot_left,
                $l->hotspot_top,
                $l->item_image_highlight_info
            );
            $arrItemImageHighlight[] = $thisObj;
        }
        // Return array of objects
        return $arrItemImageHighlight;
    }

    // Get a list of all ItemImageHighlight items by ItemImageId and return as array of objects
    public function getAllItemImageHighlightByItemImageId($item_image_id = null)
    {
        // Retrieve all ItemImageHighlight By ItemImageId
        $q = "SELECT * FROM item_image_highlight WHERE item_image_id = ?";
        $var = addslashes($item_image_id);
        $r = parent::returnResultSet($q, $var);
        // Array to hold ItemImageHighlight objects
        $arrItemImageHighlight = array();
        // Create a ItemImageHighlight object for each row and add to array
        foreach ($r as $l) {
            $thisObj = new ItemImageHighlight(
                $l->id,
                $l->item_image_id,
                $l->hotspot_left,
                $l->hotspot_top,
                $l->item_image_highlight_info
            );
            $arrItemImageHighlight[] = $thisObj;
        }
        // Return array of objects
        return $arrItemImageHighlight;
    }

    // Persist changes to the db
    public function update()
    {
        $q = "UPDATE item_image_highlight SET ";
        $q .= "item_image_id = '" . addslashes($this->ItemImageId) . "', ";
        $q .= "hotspot_left = '" . addslashes($this->HotspotLeft) . "', ";
        $q .= "hotspot_top = '" . addslashes($this->HotspotTop) . "', ";
        $q .= "item_image_highlight_info = '" . addslashes($this->ItemImageHighlightInfo) . "' ";
        $q .= "WHERE id = " . intval($this->Id);
        parent::updateQuery($q);
    }

    // Save to database as new object and assign ID
    public function create()
    {
        $q = "INSERT INTO item_image_highlight (";
        $q .= "item_image_id, ";
        $q .= "hotspot_left, ";
        $q .= "hotspot_top, ";
        $q .= "item_image_highlight_info";
        $q .= ") VALUES (";
        $q .= "'" . addslashes($this->ItemImageId) . "', ";
        $q .= "'" . addslashes($this->HotspotLeft) . "', ";
        $q .= "'" . addslashes($this->HotspotTop) . "', ";
        $q .= "'" . addslashes($this->ItemImageHighlightInfo) . "')";
        $this->Id = parent::insertQuery($q);
    }

    // Delete function takes ID as parameter
    public function delete($deleteID)
    {
        $q = "DELETE FROM item_image_highlight WHERE id = " . intval($deleteID);
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

    public function getCountItemImageHighlightByItemImageId($item_image_id = null)
    {
        // Retrieve all ItemImageHighlight By ItemImageId
        $q = "SELECT count(*) AS cnt FROM item_image_highlight WHERE item_image_id = ?";
        $var = addslashes($item_image_id);
        $r = parent::returnResultSet($q, $var);
        return $r[0]->cnt;
    }
		
}