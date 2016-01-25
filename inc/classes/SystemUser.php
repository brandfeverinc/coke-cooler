<?php
/////////////////////////////////////////////////////////////////////////////
//
// SystemUser class - represents the system_user database table
// Created on: 2015-12-02
/////////////////////////////////////////////////////////////////////////////

require_once("BaseModelPdo.php");

class SystemUser extends BaseModelPdo
{
    public $Id;
    public $FirstName;
    public $LastName;
    public $Username;
    public $Password;
    public $EmailAddress;
    public $DateCreated;
    public $IsActive;
    public $IsAdmin;

    // Default constructor creates an empty object
    public function __construct(
        $this_id = null,
        $this_first_name = null,
        $this_last_name = null,
        $this_username = null,
        $this_password = null,
        $this_email_address = null,
        $this_date_created = null,
        $this_is_active = null,
        $this_is_admin = null
    ) {
        // Run parent constructor
        parent::__construct();

        // Full constructor: use input vars to set up property values of this object
        if (isset($this_id)
            && isset($this_first_name)
            && isset($this_last_name)
            && isset($this_username)
            && isset($this_password)
            && isset($this_email_address)
            && isset($this_date_created)
            && isset($this_is_active)
            && isset($this_is_admin)
        ) {
            $this->Id = $this_id;
            $this->FirstName = $this_first_name;
            $this->LastName = $this_last_name;
            $this->Username = $this_username;
            $this->Password = $this_password;
            $this->EmailAddress = $this_email_address;
            $this->DateCreated = $this_date_created;
            $this->IsActive = $this_is_active;
            $this->IsAdmin = $this_is_admin;
        } elseif (isset($this_id)) {
            // ID constructor: get values from database and populate property values
            // Retrieve this row
            $q = "SELECT * FROM system_user WHERE id = ?";
            $id = intval($this_id);
            $r = parent::returnResultSet($q, $id);
            $hasRow = false;
            // Set property values to values in result set
            foreach ($r as $l) {
                $this->Id = $l->id;
                $this->FirstName = $l->first_name;
                $this->LastName = $l->last_name;
                $this->Username = $l->username;
                $this->Password = $l->password;
                $this->EmailAddress = $l->email_address;
                $this->DateCreated = $l->date_created;
                $this->IsActive = $l->is_active;
                $this->IsAdmin = $l->is_admin;
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
            $this->FirstName = "";
            $this->LastName = "";
            $this->Username = "";
            $this->Password = "";
            $this->EmailAddress = "";
            $this->DateCreated = "";
            $this->IsActive = "";
            $this->IsAdmin = "";
        }
    }

    // Get a list of all SystemUser items and return as array of objects
    public function getAllSystemUser($orderby = "id")
    {
        // Retrieve all System_user
        $q = "SELECT * FROM system_user ORDER BY " . addslashes($orderby);
        $r = parent::returnResultSet($q);
        // Array to hold SystemUser objects
        $arrSystemUser = array();
        // Create a SystemUser object for each row and add to array
        foreach ($r as $l) {
            $thisObj = new SystemUser(
                $l->id,
                $l->first_name,
                $l->last_name,
                $l->username,
                $l->password,
                $l->email_address,
                $l->date_created,
                $l->is_active,
                $l->is_admin
            );
            $arrSystemUser[] = $thisObj;
        }
        // Return array of objects
        return $arrSystemUser;
    }

    // Persist changes to the db
    public function update()
    {
        $q = "UPDATE system_user SET ";
        $q .= "first_name = '" . addslashes($this->FirstName) . "', ";
        $q .= "last_name = '" . addslashes($this->LastName) . "', ";
        $q .= "username = '" . addslashes($this->Username) . "', ";
        $q .= "password = '" . addslashes($this->Password) . "', ";
        $q .= "email_address = '" . addslashes($this->EmailAddress) . "', ";
        $q .= "date_created = '" . addslashes($this->DateCreated) . "', ";
        $q .= "is_active = '" . addslashes($this->IsActive) . "', ";
        $q .= "is_admin = '" . addslashes($this->IsAdmin) . "' ";
        $q .= "WHERE id = " . intval($this->Id);
        parent::updateQuery($q);
    }

    // Save to database as new object and assign ID
    public function create()
    {
        $q = "INSERT INTO system_user (";
        $q .= "first_name, ";
        $q .= "last_name, ";
        $q .= "username, ";
        $q .= "password, ";
        $q .= "email_address, ";
        $q .= "date_created, ";
        $q .= "is_active, ";
        $q .= "is_admin";
        $q .= ") VALUES (";
        $q .= "'" . addslashes($this->FirstName) . "', ";
        $q .= "'" . addslashes($this->LastName) . "', ";
        $q .= "'" . addslashes($this->Username) . "', ";
        $q .= "'" . addslashes($this->Password) . "', ";
        $q .= "'" . addslashes($this->EmailAddress) . "', ";
        $q .= "'" . addslashes($this->DateCreated) . "', ";
        $q .= "'" . addslashes($this->IsActive) . "', ";
        $q .= "'" . addslashes($this->IsAdmin) . "')";
        $this->Id = parent::insertQuery($q);
    }

    // Delete function takes ID as parameter
    public function delete($deleteID)
    {
        $q = "DELETE FROM system_user WHERE id = " . intval($deleteID);
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

	public function checkLogin($username, $password)
	{
        // retrieve this row
        $q = "SELECT * FROM system_user WHERE is_active AND username = '".addslashes($username)."' AND password = '".addslashes($password)."'";
        $r = parent::returnResultSet($q);
        $hasRow = false;
        // set property values to values in result set
        foreach ($r as $l) {
            $thisObj = new SystemUser($l->id,
                $l->first_name,
                $l->last_name,
                $l->username,
                $l->password,
                $l->email_address,
                $l->date_created,
                $l->is_active,
                $l->is_admin);
            $hasRow = true;
            return $thisObj;
        }
        // make sure there was a row returned; if not, set ID to -1 to indicate a bad object
        if (!$hasRow) {
            $this->Id = -1;
        }

        return $this;
    } // end CheckLogin()

}