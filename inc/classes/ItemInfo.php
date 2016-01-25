<?php
/////////////////////////////////////////////////////////////////////////////
//
// ItemInfo class - represents the item_info database table
// Created on: 2015-12-22
/////////////////////////////////////////////////////////////////////////////

require_once("BaseModelPdo.php");

class ItemInfo extends BaseModelPdo
{
    public $Id;
    public $ItemId;
    public $ItemInfoTypeId;
    public $ItemInfo;

    // Default constructor creates an empty object
    public function __construct(
        $this_id = null,
        $this_item_id = null,
        $this_item_info_type_id = null,
        $this_item_info = null
    ) {
        // Run parent constructor
        parent::__construct();

        // Full constructor: use input vars to set up property values of this object
        if (isset($this_id)
            && isset($this_item_id)
            && isset($this_item_info_type_id)
            && isset($this_item_info)
        ) {
            $this->Id = $this_id;
            $this->ItemId = $this_item_id;
            $this->ItemInfoTypeId = $this_item_info_type_id;
            $this->ItemInfo = $this_item_info;
        } elseif (isset($this_id)) {
            // ID constructor: get values from database and populate property values
            // Retrieve this row
            $q = "SELECT * FROM item_info WHERE id = ?";
            $id = intval($this_id);
            $r = parent::returnResultSet($q, $id);
            $hasRow = false;
            // Set property values to values in result set
            foreach ($r as $l) {
                $this->Id = $l->id;
                $this->ItemId = $l->item_id;
                $this->ItemInfoTypeId = $l->item_info_type_id;
                $this->ItemInfo = $l->item_info;
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
            $this->ItemInfoTypeId = "";
            $this->ItemInfo = "";
        }
    }

    // Get a list of all ItemInfo items and return as array of objects
    public function getAllItemInfo($orderby = "id")
    {
        // Retrieve all _item_info
        $q = "SELECT * FROM item_info ORDER BY " . addslashes($orderby);
        $r = parent::returnResultSet($q);
        // Array to hold ItemInfo objects
        $arrItemInfo = array();
        // Create a ItemInfo object for each row and add to array
        foreach ($r as $l) {
            $thisObj = new ItemInfo(
                $l->id,
                $l->item_id,
                $l->item_info_type_id,
                $l->item_info
            );
            $arrItemInfo[] = $thisObj;
        }
        // Return array of objects
        return $arrItemInfo;
    }

    // Get a list of all ItemInfo items by ItemId and return as array of objects
    public function getAllItemInfoByItemId($item_id = null)
    {
        // Retrieve all ItemInfo By ItemId
        $q = "SELECT * FROM item_info WHERE item_id = ?";
        $var = addslashes($item_id);
        $r = parent::returnResultSet($q, $var);
        // Array to hold ItemInfo objects
        $arrItemInfo = array();
        // Create a ItemInfo object for each row and add to array
        foreach ($r as $l) {
            $thisObj = new ItemInfo(
                $l->id,
                $l->item_id,
                $l->item_info_type_id,
                $l->item_info
            );
            $arrItemInfo[] = $thisObj;
        }
        // Return array of objects
        return $arrItemInfo;
    }

    // Persist changes to the db
    public function update()
    {
        $q = "UPDATE item_info SET ";
        $q .= "item_id = '" . addslashes($this->ItemId) . "', ";
        $q .= "item_info_type_id = '" . addslashes($this->ItemInfoTypeId) . "', ";
        $q .= "item_info = '" . addslashes($this->ItemInfo) . "' ";
        $q .= "WHERE id = " . intval($this->Id);
        parent::updateQuery($q);
    }

    // Save to database as new object and assign ID
    public function create()
    {
        $q = "INSERT INTO item_info (";
        $q .= "item_id, ";
        $q .= "item_info_type_id, ";
        $q .= "item_info";
        $q .= ") VALUES (";
        $q .= "'" . addslashes($this->ItemId) . "', ";
        $q .= "'" . addslashes($this->ItemInfoTypeId) . "', ";
        $q .= "'" . addslashes($this->ItemInfo) . "')";
        $this->Id = parent::insertQuery($q);
    }

    // Delete function takes ID as parameter
    public function delete($deleteID)
    {
        $q = "DELETE FROM item_info WHERE id = " . intval($deleteID);
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

    // Get a list of all ItemInfo items and return as array of objects
    public function getAllItemInfoByItemIdItemInfoTypeId($item_id = 0, $item_info_type_id = 0)
    {
        // Retrieve all _item_info
        $q = "SELECT * FROM item_info WHERE item_id = ? AND item_info_type_id = ? ";
        $var = array($item_id, $item_info_type_id);
        $r = parent::returnResultSet($q, $var);
        // Array to hold ItemInfo objects
        $arrItemInfo = array();
        // Create a ItemInfo object for each row and add to array
        foreach ($r as $l) {
            $thisObj = new ItemInfo(
                $l->id,
                $l->item_id,
                $l->item_info_type_id,
                $l->item_info
            );
            $arrItemInfo[] = $thisObj;
        }
        // Return array of objects
        return $arrItemInfo;
    }

}