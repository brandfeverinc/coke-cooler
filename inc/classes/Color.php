<?php
/////////////////////////////////////////////////////////////////////////////
//
// Color class - represents the color database table
// Created on: 2016-01-15
/////////////////////////////////////////////////////////////////////////////

require_once("BaseModelPdo.php");

class Color extends BaseModelPdo
{
    public $Id;
    public $CategoryId;
    public $OverviewText;

    // Default constructor creates an empty object
    public function __construct(
        $this_id = null,
        $this_category_id = null,
        $this_overview_text = null
    ) {
        // Run parent constructor
        parent::__construct();

        // Full constructor: use input vars to set up property values of this object
        if (isset($this_id)
            && isset($this_category_id)
            && isset($this_overview_text)
        ) {
            $this->Id = $this_id;
            $this->CategoryId = $this_category_id;
            $this->OverviewText = $this_overview_text;
        } elseif (isset($this_id)) {
            // ID constructor: get values from database and populate property values
            // Retrieve this row
            $q = "SELECT * FROM color WHERE id = ?";
            $id = intval($this_id);
            $r = parent::returnResultSet($q, $id);
            $hasRow = false;
            // Set property values to values in result set
            foreach ($r as $l) {
                $this->Id = $l->id;
                $this->CategoryId = $l->category_id;
                $this->OverviewText = $l->overview_text;
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
            $this->OverviewText = "";
        }
    }

    // Get a list of all Color items and return as array of objects
    public function getAllColor($orderby = "id")
    {
        // Retrieve all Color
        $q = "SELECT * FROM color ORDER BY " . addslashes($orderby);
        $r = parent::returnResultSet($q);
        // Array to hold Color objects
        $arrColor = array();
        // Create a Color object for each row and add to array
        foreach ($r as $l) {
            $thisObj = new Color(
                $l->id,
                $l->category_id,
                $l->overview_text
            );
            $arrColor[] = $thisObj;
        }
        // Return array of objects
        return $arrColor;
    }

    // Get a list of all Color items by CategoryId and return as array of objects
    public function getAllColorByCategoryId($category_id = null)
    {
        // Retrieve all Color By CategoryId
        $q = "SELECT * FROM color WHERE category_id = ?";
        $var = addslashes($category_id);
        $r = parent::returnResultSet($q, $var);
        // Array to hold Color objects
        $arrColor = array();
        // Create a Color object for each row and add to array
        foreach ($r as $l) {
            $thisObj = new Color(
                $l->id,
                $l->category_id,
                $l->overview_text
            );
            $arrColor[] = $thisObj;
        }
        // Return array of objects
        return $arrColor;
    }

    // Persist changes to the db
    public function update()
    {
        $q = "UPDATE color SET ";
        $q .= "category_id = '" . addslashes($this->CategoryId) . "', ";
        $q .= "overview_text = '" . addslashes($this->OverviewText) . "' ";
        $q .= "WHERE id = " . intval($this->Id);
        parent::updateQuery($q);
    }

    // Save to database as new object and assign ID
    public function create()
    {
        $q = "INSERT INTO color (";
        $q .= "category_id, ";
        $q .= "overview_text";
        $q .= ") VALUES (";
        $q .= "'" . addslashes($this->CategoryId) . "', ";
        $q .= "'" . addslashes($this->OverviewText) . "')";
        $this->Id = parent::insertQuery($q);
    }

    // Delete function takes ID as parameter
    public function delete($deleteID)
    {
        $q = "DELETE FROM color WHERE id = " . intval($deleteID);
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
