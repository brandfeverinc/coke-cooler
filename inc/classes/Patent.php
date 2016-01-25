<?php
/////////////////////////////////////////////////////////////////////////////
//
// Patent class - represents the patent database table
// Created on: 2016-01-15
/////////////////////////////////////////////////////////////////////////////

require_once("BaseModelPdo.php");

class Patent extends BaseModelPdo
{
    public $Id;
    public $CategoryId;
    public $PatentName;
    public $PatentImageUrl;
    public $PatentAbstract;
    public $PatentProbableAssignee;
    public $PatentAssigneesStd;
    public $PatentAssignees;
    public $SortOrder;

    // Default constructor creates an empty object
    public function __construct(
        $this_id = null,
        $this_category_id = null,
        $this_patent_name = null,
        $this_patent_image_url = null,
        $this_patent_abstract = null,
        $this_patent_probable_assignee = null,
        $this_patent_assignees_std = null,
        $this_patent_assignees = null,
        $this_sort_order = null
    ) {
        // Run parent constructor
        parent::__construct();

        // Full constructor: use input vars to set up property values of this object
        if (isset($this_id)
            && isset($this_category_id)
            && isset($this_patent_name)
            && isset($this_patent_image_url)
            && isset($this_patent_abstract)
            && isset($this_patent_probable_assignee)
            && isset($this_patent_assignees_std)
            && isset($this_patent_assignees)
            && isset($this_sort_order)
        ) {
            $this->Id = $this_id;
            $this->CategoryId = $this_category_id;
            $this->PatentName = $this_patent_name;
            $this->PatentImageUrl = $this_patent_image_url;
            $this->PatentAbstract = $this_patent_abstract;
            $this->PatentProbableAssignee = $this_patent_probable_assignee;
            $this->PatentAssigneesStd = $this_patent_assignees_std;
            $this->PatentAssignees = $this_patent_assignees;
            $this->SortOrder = $this_sort_order;
        } elseif (isset($this_id)) {
            // ID constructor: get values from database and populate property values
            // Retrieve this row
            $q = "SELECT * FROM patent WHERE id = ?";
            $id = intval($this_id);
            $r = parent::returnResultSet($q, $id);
            $hasRow = false;
            // Set property values to values in result set
            foreach ($r as $l) {
                $this->Id = $l->id;
                $this->CategoryId = $l->category_id;
                $this->PatentName = $l->patent_name;
                $this->PatentImageUrl = $l->patent_image_url;
                $this->PatentAbstract = $l->patent_abstract;
                $this->PatentProbableAssignee = $l->patent_probable_assignee;
                $this->PatentAssigneesStd = $l->patent_assignees_std;
                $this->PatentAssignees = $l->patent_assignees;
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
            $this->PatentName = "";
            $this->PatentImageUrl = "";
            $this->PatentAbstract = "";
            $this->PatentProbableAssignee = "";
            $this->PatentAssigneesStd = "";
            $this->PatentAssignees = "";
            $this->SortOrder = "";
        }
    }

    // Get a list of all Patent items and return as array of objects
    public function getAllPatent($orderby = "id")
    {
        // Retrieve all Patent
        $q = "SELECT * FROM patent ORDER BY " . addslashes($orderby);
        $r = parent::returnResultSet($q);
        // Array to hold Patent objects
        $arrPatent = array();
        // Create a Patent object for each row and add to array
        foreach ($r as $l) {
            $thisObj = new Patent(
                $l->id,
                $l->category_id,
                $l->patent_name,
                $l->patent_image_url,
                $l->patent_abstract,
                $l->patent_probable_assignee,
                $l->patent_assignees_std,
                $l->patent_assignees,
                $l->sort_order
            );
            $arrPatent[] = $thisObj;
        }
        // Return array of objects
        return $arrPatent;
    }

    // Get a list of all Patent items by CategoryId and return as array of objects
    public function getAllPatentByCategoryId($category_id = null)
    {
        // Retrieve all Patent By CategoryId
        $q = "SELECT * FROM patent WHERE category_id = ? ORDER BY sort_order";
        $var = addslashes($category_id);
        $r = parent::returnResultSet($q, $var);
        // Array to hold Patent objects
        $arrPatent = array();
        // Create a Patent object for each row and add to array
        foreach ($r as $l) {
            $thisObj = new Patent(
                $l->id,
                $l->category_id,
                $l->patent_name,
                $l->patent_image_url,
                $l->patent_abstract,
                $l->patent_probable_assignee,
                $l->patent_assignees_std,
                $l->patent_assignees,
                $l->sort_order
            );
            $arrPatent[] = $thisObj;
        }
        // Return array of objects
        return $arrPatent;
    }

    // Persist changes to the db
    public function update()
    {
        $q = "UPDATE patent SET ";
        $q .= "category_id = '" . addslashes($this->CategoryId) . "', ";
        $q .= "patent_name = '" . addslashes($this->PatentName) . "', ";
        $q .= "patent_image_url = '" . addslashes($this->PatentImageUrl) . "', ";
        $q .= "patent_abstract = '" . addslashes($this->PatentAbstract) . "', ";
        $q .= "patent_probable_assignee = '" . addslashes($this->PatentProbableAssignee) . "', ";
        $q .= "patent_assignees_std = '" . addslashes($this->PatentAssigneesStd) . "', ";
        $q .= "patent_assignees = '" . addslashes($this->PatentAssignees) . "', ";
        $q .= "sort_order = '" . addslashes($this->SortOrder) . "' ";
        $q .= "WHERE id = " . intval($this->Id);
        parent::updateQuery($q);
        $this->resetSortOrder();
    }

    // Save to database as new object and assign ID
    public function create()
    {
        $q = "INSERT INTO patent (";
        $q .= "category_id, ";
        $q .= "patent_name, ";
        $q .= "patent_image_url, ";
        $q .= "patent_abstract, ";
        $q .= "patent_probable_assignee, ";
        $q .= "patent_assignees_std, ";
        $q .= "patent_assignees, ";
        $q .= "sort_order";
        $q .= ") VALUES (";
        $q .= "'" . addslashes($this->CategoryId) . "', ";
        $q .= "'" . addslashes($this->PatentName) . "', ";
        $q .= "'" . addslashes($this->PatentImageUrl) . "', ";
        $q .= "'" . addslashes($this->PatentAbstract) . "', ";
        $q .= "'" . addslashes($this->PatentProbableAssignee) . "', ";
        $q .= "'" . addslashes($this->PatentAssigneesStd) . "', ";
        $q .= "'" . addslashes($this->PatentAssignees) . "', ";
        $q .= "'" . addslashes($this->SortOrder) . "')";
        $this->Id = parent::insertQuery($q);
        $this->resetSortOrder();
    }

    // Delete function takes ID as parameter
    public function delete($deleteID)
    {
        $q = "DELETE FROM patent WHERE id = " . intval($deleteID);
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
    	$q = "SELECT id, sort_order FROM patent ORDER BY sort_order ";
        $r = parent::returnResultSet($q);
        $i = 10;
        foreach ($r as $l) {
			$q = "UPDATE patent SET sort_order = '" . $i . "' WHERE id = '" . $l->id . "' ";
        	parent::updateQuery($q);
			$i += 10;
		}
    }

    public function sortValues($id, $direction) {
        // Get current sort value
        $q = "SELECT sort_order FROM patent WHERE id = " . $id;
        $r = parent::returnresultSet($q);
        $l = $r[0];
        $sortVal = $l->sort_order;

        if ($direction == 'U') {
            // Move item that is currently in the new position to this item's position
            $q = "UPDATE patent SET sort_order = " . $sortVal . " WHERE sort_order = " . ($sortVal-10);
        	parent::updateQuery($q);
            // Put selected item in new position
            $q = "UPDATE patent SET sort_order = " . ($sortVal-10) . " WHERE id = " . $id;
        	parent::updateQuery($q);
        }
        else {
            // Move item that is currently in the new position to this item's position
            $q = "UPDATE patent SET sort_order = " . $sortVal . " WHERE sort_order = " . ($sortVal+10);
        	parent::updateQuery($q);
            // Put selected item in new position
            $q = "UPDATE patent SET sort_order = " . ($sortVal+10) . " WHERE id = " . $id;
        	parent::updateQuery($q);
        }
    }

	// checks to see if file upload is needed
	public function handleFileUploads() {
		if ($_FILES['patent_image_url']['error'] <> 4) {
			return $this->uploadFile();
		}
		
		return 'success';
	}
	
	// upload function for saving photo
	public function uploadFile() {
	    
		$appPath = $_SERVER['DOCUMENT_ROOT'];
		
		// get the temp location and type
		$uploaded_file = $_FILES['patent_image_url']['tmp_name'];
		
		$err = '';
        //if ($err == '') {		
    	//	if (intval($info[0]) != 310 || intval($info[1]) != 215) {
    	//	    $err = 'Error: Image must be 310 pixels wide by 215 pixels high.';
    	//	}
    	//}

		$success = move_uploaded_file($uploaded_file,$appPath."/" . $this->GetPath() . $_FILES['patent_image_url']['name']);

		if ($success) {
		    $this->PatentImageUrl = $_FILES['patent_image_url']['name'];
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
	    return "files/patents/";
	}

}
