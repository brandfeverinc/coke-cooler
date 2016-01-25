<?php
/////////////////////////////////////////////////////////////////////////////
//
// ItemInfoType class - represents the item_info_type database table
// Created on: 2015-12-23
/////////////////////////////////////////////////////////////////////////////

require_once("BaseModelPdo.php");

class ItemInfoType extends BaseModelPdo
{
    public $Id;
    public $ItemInfoTypeName;

    // Default constructor creates an empty object
    public function __construct(
        $this_id = null,
        $this_item_info_type_name = null
    ) {
        // Run parent constructor
        parent::__construct();

        // Full constructor: use input vars to set up property values of this object
        if (isset($this_id)
            && isset($this_item_info_type_name)
        ) {
            $this->Id = $this_id;
            $this->ItemInfoTypeName = $this_item_info_type_name;
        } elseif (isset($this_id)) {
            // ID constructor: get values from database and populate property values
            // Retrieve this row
            $q = "SELECT * FROM item_info_type WHERE id = ?";
            $id = intval($this_id);
            $r = parent::returnResultSet($q, $id);
            $hasRow = false;
            // Set property values to values in result set
            foreach ($r as $l) {
                $this->Id = $l->id;
                $this->ItemInfoTypeName = $l->item_info_type_name;
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
            $this->ItemInfoTypeName = "";
        }
    }

    // Get a list of all ItemInfoType items and return as array of objects
    public function getAllItemInfoType($orderby = "id")
    {
        // Retrieve all Item_info_type
        $q = "SELECT * FROM item_info_type ORDER BY " . addslashes($orderby);
        $r = parent::returnResultSet($q);
        // Array to hold ItemInfoType objects
        $arrItemInfoType = array();
        // Create a ItemInfoType object for each row and add to array
        foreach ($r as $l) {
            $thisObj = new ItemInfoType(
                $l->id,
                $l->item_info_type_name
            );
            $arrItemInfoType[] = $thisObj;
        }
        // Return array of objects
        return $arrItemInfoType;
    }

    // Persist changes to the db
    public function update()
    {
        $q = "UPDATE item_info_type SET ";
        $q .= "item_info_type_name = '" . addslashes($this->ItemInfoTypeName) . "' ";
        $q .= "WHERE id = " . intval($this->Id);
        parent::updateQuery($q);
    }

    // Save to database as new object and assign ID
    public function create()
    {
        $q = "INSERT INTO item_info_type (";
        $q .= "item_info_type_name";
        $q .= ") VALUES (";
        $q .= "'" . addslashes($this->ItemInfoTypeName) . "')";
        $this->Id = parent::insertQuery($q);
    }

    // Delete function takes ID as parameter
    public function delete($deleteID)
    {
        $q = "DELETE FROM item_info_type WHERE id = " . intval($deleteID);
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

}