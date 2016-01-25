<?php
/////////////////////////////////////////////////////////////////////////////
//
// Setting class - represents the setting database table
// Created on: 2016-01-20
/////////////////////////////////////////////////////////////////////////////

require_once("BaseModelPdo.php");

class Setting extends BaseModelPdo
{
    public $Id;
    public $SettingName;
    public $SettingValue;

    // Default constructor creates an empty object
    public function __construct(
        $this_id = null,
        $this_setting_name = null,
        $this_setting_value = null
    ) {
        // Run parent constructor
        parent::__construct();

        // Full constructor: use input vars to set up property values of this object
        if (isset($this_id)
            && isset($this_setting_name)
            && isset($this_setting_value)
        ) {
            $this->Id = $this_id;
            $this->SettingName = $this_setting_name;
            $this->SettingValue = $this_setting_value;
        } elseif (isset($this_id)) {
            // ID constructor: get values from database and populate property values
            // Retrieve this row
            $q = "SELECT * FROM setting WHERE id = ?";
            $id = intval($this_id);
            $r = parent::returnResultSet($q, $id);
            $hasRow = false;
            // Set property values to values in result set
            foreach ($r as $l) {
                $this->Id = $l->id;
                $this->SettingName = $l->setting_name;
                $this->SettingValue = $l->setting_value;
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
            $this->SettingName = "";
            $this->SettingValue = "";
        }
    }

    // Get a list of all Setting items and return as array of objects
    public function getAllSetting($orderby = "id")
    {
        // Retrieve all Setting
        $q = "SELECT * FROM setting ORDER BY " . addslashes($orderby);
        $r = parent::returnResultSet($q);
        // Array to hold Setting objects
        $arrSetting = array();
        // Create a Setting object for each row and add to array
        foreach ($r as $l) {
            $thisObj = new Setting(
                $l->id,
                $l->setting_name,
                $l->setting_value
            );
            $arrSetting[] = $thisObj;
        }
        // Return array of objects
        return $arrSetting;
    }

    // Get a list of all Setting items by SettingName and return as array of objects
    public function getAllSettingBySettingName($setting_name = null)
    {
        // Retrieve all Setting By SettingName
        $q = "SELECT * FROM setting WHERE setting_name = ?";
        $var = addslashes($setting_name);
        $r = parent::returnResultSet($q, $var);
        // Array to hold Setting objects
        $arrSetting = array();
        // Create a Setting object for each row and add to array
        foreach ($r as $l) {
            $thisObj = new Setting(
                $l->id,
                $l->setting_name,
                $l->setting_value
            );
            $arrSetting[] = $thisObj;
        }
        // Return array of objects
        return $arrSetting;
    }

    // Persist changes to the db
    public function update()
    {
        $q = "UPDATE setting SET ";
        $q .= "setting_name = '" . addslashes($this->SettingName) . "', ";
        $q .= "setting_value = '" . addslashes($this->SettingValue) . "' ";
        $q .= "WHERE id = " . intval($this->Id);
        parent::updateQuery($q);
    }

    // Save to database as new object and assign ID
    public function create()
    {
        $q = "INSERT INTO setting (";
        $q .= "setting_name, ";
        $q .= "setting_value";
        $q .= ") VALUES (";
        $q .= "'" . addslashes($this->SettingName) . "', ";
        $q .= "'" . addslashes($this->SettingValue) . "')";
        $this->Id = parent::insertQuery($q);
    }

    // Delete function takes ID as parameter
    public function delete($deleteID)
    {
        $q = "DELETE FROM setting WHERE id = " . intval($deleteID);
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