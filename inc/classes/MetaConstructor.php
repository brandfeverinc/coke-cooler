<?php
/**
 *  MetaConstructor (Jay version) class - constructs a class from a db table
 *  PHP PDO compatible version by Jay Davis 05/07/2015
 *    - Updated to conform to PSR1 and PSR2
**/

class MetaConstructor extends BaseModelPdo {
    public $Name;
    public $arrFields;
    
    // Default constructor creates an empty object
    public function __construct($thisName = null, $thisClass = null, $arrFields = null) {
        // Run parent constructor
        parent::__construct();
//        if (!$this->isdev) {
//            return NULL;
//            }
        
        // ID constructor: set name
        if (isset($thisName)) {
            $this->Name = $thisName;
            $this->Class = $thisClass;
        }
        // Empty constructor: set all properties to empty strings
        else {
            $this->Name = "";
            $this->Class = "";
        }
    }
    
    public function getAllTableNames() {
        $field = 'Tables_in_' . $this->database;
        $q = "SHOW TABLES FROM " . $this->database;
        $r = parent::returnResultSet($q);
        // Array to hold table names objects
        $arrTableNames = array();
        foreach ($r as $l) {
            $arrTableNames[] = $l->$field;
        }
        // Return array of table names
        return $arrTableNames;
    }
    
    public function getTableFields() {
        if (!isset($this->arrFields)) {
            $q = "DESCRIBE " . addslashes($this->Name);
            $r = parent::returnResultSet($q);
            $this->arrFields = Array();
            foreach ($r as $l) {
                $this->arrFields[] = Array($l->Field, $l->Type, $l->Null, $l->Key, $l->Default, $l->Extra);
            }
        }
    }
        
    public function displayTable($select_bys) {
        echo 'Table Name: ' . $this->Name;
        echo '<table class="border">';
        echo '<tr>';
        echo '<th style="font-weight: bold; border: 1px solid #ccc; border-bottom: 1px solid #999;">Create Getter?</th>';        
        echo '<th style="font-weight: bold; border: 1px solid #ccc; border-bottom: 1px solid #999;">Field</th>';
        echo '<th style="font-weight: bold; border: 1px solid #ccc; border-bottom: 1px solid #999;">Type</th>';
        echo '<th style="font-weight: bold; border: 1px solid #ccc; border-bottom: 1px solid #999;">Null</th>';
        echo '<th style="font-weight: bold; border: 1px solid #ccc; border-bottom: 1px solid #999;">Key</th>';
        echo '<th style="font-weight: bold; border: 1px solid #ccc; border-bottom: 1px solid #999;">Default</th>';
        echo '<th style="font-weight: bold; border: 1px solid #ccc; border-bottom: 1px solid #999;">Extras</th>';
        echo '</tr>';
        $this->getTableFields();
        foreach ($this->arrFields as $arrFieldVals) {
            echo '<tr>';
            $checked = (in_array($arrFieldVals[0], $select_bys)) ? 'checked' : '';
            echo '<td><input type="checkbox" id="getter[]" name="getter[]" value="' . $arrFieldVals[0] . '" ' . $checked .'/></td>';
            foreach ($arrFieldVals as $field) {
                echo '<td>' . $field . '</td>';
                }
            echo '</tr>';
            }
        echo '</table>';
    }
    
    public function buildAdminInterfaceList() {
        $className = $this->camelCase($this->Name);
        $output = '<h1>' . ucwords(str_replace("_", " ", $this->Name)) . 's</h1>' . "\n";
	    $output .= '<form id="create_' . $this->Name . '" method="POST" action="' . $this->Name . 'admin.php">' . "\n";
		$output .= $this->t(1) . '<p><input type="submit" class="slick" id="formaction" name="formaction" value="Create New ' . ucwords(str_replace("_", " ", $this->Name)) . '"></p>' . "\n";
	    $output .= '</form>' . "\n";
        $output .= "<table border=\"0\" id=\"settings\">\n";
        $output .= $this->t(1) . "<tr>\n";
        foreach ($this->arrFields as $field) {
            $output .= $this->t(2) . "<th>" . ucwords(str_replace("_", " ", $field[0])) . "</th>\n";
        }
        $output .= $this->t(2) . "<th colspan='2'>Actions</th>\n";
        $output .= $this->t(1) . "</tr>\n";
        
        $output .= "<?php \n"; 
        $output .= "require_once('../inc/classes/" . $className . ".php');\n";
        $output .= "\$obj = new " . $className . "();\n";
        //$output .= $obj->Id . "<br>";
        $output .= '$rows = $obj->getAll' . $this->camelCase($this->Name) . '();
        $num_fields = count(get_object_vars($rows[0]));
        $numRow = 0;
        foreach ($rows as $row) {
            $rowClass = "even-row";
            if ($numRow % 2)
            {
                $rowClass = "odd-row";
            }
            $numRow++;
            echo "' . $this->t(1) . '<tr class=" . $rowClass . ">";
            $i = 1;
            foreach ($row as $key => $value) {
                if ($i < $num_fields) {
                    switch ($key) {
                        case ("XXXfieldXXX"):
                            // Sample field name to handle differently
                            break;
                        default:
                            echo "<td>" . $value . "</td>";
                        break;
                    }
                }
                $i++;
            }
            echo "<td><a href=\"' . $this->Name . 'admin.php?formaction=edit&id=" . $row->Id . "\">Edit</a></td><td><a href=\"' . $this->Name . 'admin.php?formaction=delete&id=" . $row->Id . "\">Delete</a></td>";
            echo "\n" . "' . $this->t(1) . '</tr>\n";
        }' . "\n";
        $output .= "?>" . "\n";
        
        $output .= "</table>\n";
        //$output .= "</pre>";
        return "<pre>" . htmlentities($output) . "</pre>";
    }

    public function buildAdminInterfaceEdit() {
        $className = $this->camelCase($this->Name);
        $output = 'function displayAdd' . ucwords(str_replace("_", "", $this->Name)) . 'Table(';
        $i = 0;
        foreach ($this->arrFields as $field) {
            if ($i > 0) {
                $output .= ", ";
            }
            $output .= '$' . $field[0] . " = NULL";
            $i++;
        }
        $output .= ', $msg = NULL)
{
    require_once("../inc/classes/' . $className . '.php");
    if(isset($msg))
    {
        echo "<p class=\"error\">" . $msg . "</p>";
    }

    if(isset($id))
    {
        $' . $this->Name . ' = new ' . $className . '($id);' . "\n";
        foreach ($this->arrFields as $field) {
            if ($field[0] != "id") {
                $output .= $this->t(2) . '$' . $field[0] . ' = $' . $this->Name . '->' . $this->camelCase($field[0]) . ';' . "\n";
            }
        }
        $output .= $this->t(1) . '}' . "\n";
        $output .= '?>' . "\n";
    
        $output  .= "<h1>" . ucwords(str_replace("_", " ", $this->Name)) . " - Data Entry</h1>\n";
        $output .= "<br />\n";
        $output .= '<form id="add_' . $this->Name . '_submit" name="add_' . $this->Name . '_submit"  action="' . $this->Name . 'admin.php" method="post">
    <table border="0" id="modify_table">' . "\n";
    
        foreach ($this->arrFields as $field) {
            $i = 0;
            if ($field[0] != 'id') {
                $output .= $this->t(2) . '<tr>' . "\n";
                $output .= $this->t(3) . '<td width="140"><strong>';
                $output .= ucwords(str_replace("_", " ", $field[0]));
                $output .= ': </strong></td>' . "\n";
                $output .= $this->t(3) . '<td>';
                $output .= '<input type="text" name="';
                $output .= $field[0] . '" value="<?php echo $' . $field[0] . '; ?>" id="' . $field[0] . '"';
                if (substr($field[1], 0, 7) == 'varchar') {
                    $tmp_arr = preg_split("/[\(\)]/", $field[1]);
                    $field_len = $tmp_arr[1];
                    $output .= ' maxlength="' . $field_len . '"';
                }
                $output .= '></td>' . "\n";
                $output .= $this->t(2) . '</tr>' . "\n";
                $i++;
            }
        }
        $output .= '
    </table>
    
    <p>
        <?php
            if(empty($id))
            {
        ?>
                <input type="submit" class="slick" id="formaction" name="formaction" value="Add New ' . ucwords(str_replace("_", " ", $this->Name)) . '">
        <?php
            }
            else {
        ?>
                <input type="hidden" name="id" value="<?php echo $id; ?>" id="id">
                <input type="submit" class="slick" id="formaction" name="formaction" value="Save Edits">
        <?php
            }
        ?>
     <input type="submit" class="slick" id="formaction" name="formaction" value="Cancel">
    </p>
</form>' . "\n";
        $output .= '<?php' . "\n" . '}' . "\n";
        
        // Add the updating code, to go in the top of the admin page:
        $output .= "\n----------\nFOR UPDATE\n----------\n";
        $output .= $this->t(2) . "case 'Save Edits':\n";
        $output .= $this->t(3) . '$obj = new ' . $this->camelCase($this->Name) . '($_REQUEST["id"]);' . "\n";
        foreach ($this->arrFields as $field) {
            if ($field[0] != 'id') {
                $output .= $this->t(3) . '$obj->' . $this->camelCase($field[0]) . ' = $_REQUEST["' . $field[0] . '"];' . "\n";
            }
        }
        $output .= $this->t(3) . 'if ($_REQUEST["formaction"] == "Save Edits") {' . "\n";
        $output .= $this->t(4) . '$obj->update();' . "\n";
        $output .= $this->t(3) . '}' . "\n";
        $output .= $this->t(3) . 'else {' . "\n";
        $output .= $this->t(4) . '$obj->create();' . "\n";
        $output .= $this->t(3) . '}' . "\n";
    
    return "<pre>" . htmlentities($output) . "</pre>";
   
    }

    public function buildClass($slect_bys = array()) {
        $output  = $this->getTableFields();
        $output .= $this->buildClassOpen();
        $output .= $this->buildRequiredFiles();
        $output .= $this->buildConstructor();
        $output .= $this->buildSelectAll();
        foreach($slect_bys as $select_field){
            $output .= $this->buildSelectBy($select_field);
        }
        $output .= $this->buildUpdate();
        $output .= $this->buildInsert();
        $output .= $this->buildDelete();
        $output .= $this->buildSave();
        $output .= $this->buildCustomClassComment();
        $output .= $this->buildClassClose();
        return $output;
    }
    public function t($tcount) {
        $t = '    '; //tab
        $ret = "";
        for ($i = 0; $i < $tcount; $i++) { $ret .= $t; }
        return $ret;
    }
    public function nl() {
        return '
';
    }
    public function buildClassOpen() {
        $output  = '&lt;?php' . $this->nl();
        $output .= '/////////////////////////////////////////////////////////////////////////////' . $this->nl();
        $output .= '//' . $this->nl();
        $output .= '// ' . $this->camelCase($this->Class) . ' class - represents the ' . $this->Name . ' database table' . $this->nl();
        $output .= '// Created on: ' . date("Y-m-d") . $this->nl();
        $output .= '/////////////////////////////////////////////////////////////////////////////' . $this->nl() . $this->nl();
        return $output;
    }
    public function buildRequiredFiles() {
        $output = "";
        $output = 'require_once("BaseModelPdo.php");' . $this->nl() . $this->nl();
        return $output;
    }
    public function buildConstructor() {
        $output = 'class ' . $this->camelCase($this->Class) . ' extends BaseModelPdo' . $this->nl() . '{' . $this->nl();
        foreach ($this->arrFields as $arrFieldVals) {
            $output .= $this->t(1) . 'public $' . $this->camelCase($arrFieldVals[0])  . ';' . $this->nl();
            } 
        $output .= '' . $this->nl();
        $output .= $this->t(1) . '// Default constructor creates an empty object' . $this->nl();
        $output .= $this->t(1) . 'public function __construct(' . $this->nl();
        $tmp = "";
        foreach ($this->arrFields as $arrFieldVals) {
            $tmp .= ($tmp != "") ? ',' . $this->nl() . $this->t(2) : $this->t(2);
            $tmp .= '$this_' . $arrFieldVals[0]  . ' = null';
            }
        $output .= $tmp . $this->nl() . '    ) {' . $this->nl();
        $output .= $this->t(2) . '// Run parent constructor' . $this->nl();
        $output .= $this->t(2) . 'parent::__construct();' . $this->nl() . $this->nl();
        $output .= $this->t(2) . '// Full constructor: use input vars to set up property values of this object' . $this->nl();
        $tmp = "";
        foreach ($this->arrFields as $arrFieldVals) {
            if ($tmp != "") $tmp .= $this->t(3) . '&& ';
            $tmp .= 'isset($this_' . $arrFieldVals[0]  . ')' . $this->nl();
            }
        $output .= $this->t(2) . 'if (' . $tmp;
        $output .= $this->t(2) . ') {' . $this->nl();
        foreach ($this->arrFields as $arrFieldVals) {
            $output .= $this->t(3) . '$this->' . $this->camelCase($arrFieldVals[0]) . ' = $this_' . $arrFieldVals[0]  . ';' . $this->nl();
            }
        $output .= $this->t(2) . '} elseif (isset($this_' . $this->arrFields[0][0] . ')) {' . $this->nl();
        $output .= $this->t(3) . '// ID constructor: get values from database and populate property values' . $this->nl();
        $output .= $this->t(3) . '// Retrieve this row' . $this->nl() . '';
        $output .= $this->t(3) . '$q = "SELECT * FROM ' . $this->Name . ' WHERE ' . $this->arrFields[0][0] . ' = ?";' . $this->nl() . '';
        $output .= $this->t(3) . '$id = intval($this_' . $this->arrFields[0][0] . ');' . $this->nl() . '';
        $output .= $this->t(3) . '$r = parent::returnResultSet($q, $id);' . $this->nl();
        $output .= $this->t(3) . '$hasRow = false;' . $this->nl();
        $output .= $this->t(3) . '// Set property values to values in result set' . $this->nl();
        $output .= $this->t(3) . 'foreach ($r as $l) {' . $this->nl();
        foreach ($this->arrFields as $arrFieldVals) {
            $output .= $this->t(4) . '$this->' . $this->camelCase($arrFieldVals[0])  . ' = $l->' . $arrFieldVals[0] . ';' . $this->nl();
            }
        $output .= $this->t(4) . '$hasRow = true;' . $this->nl();
        $output .= $this->t(3) . '}' . $this->nl();
        $output .= $this->t(3) . '// Make sure there was a row returned; if not, set ID to -1 to indicate a bad object' . $this->nl();
        $output .= $this->t(3) . 'if (!$hasRow) {' . $this->nl() . $this->t(4) . '$this->' . $this->camelCase($this->arrFields[0][0]) . ' = -1;' . $this->nl() . $this->t(3) . '}' . $this->nl();
        $output .= $this->t(2) .'}' . $this->nl() . $this->nl();
        $output .= $this->t(2) . '// Empty constructor: set all properties to empty strings' . $this->nl();
        $output .= $this->t(2) . 'else {' . $this->nl();
        $output .= $this->t(3) . '$this->' . $this->camelCase($this->arrFields[0][0])  . ' = 0;' . $this->nl();
        for ($i = 1; $i < count($this->arrFields); $i++) {
            $output .= $this->t(3) . '$this->' . $this->camelCase($this->arrFields[$i][0])  . ' = "";' . $this->nl();
            }
        $output .= $this->t(2) . '}' . $this->nl();
        $output .= $this->t(1) . '}' . $this->nl() . $this->nl();
        return $output;
    }
    public function buildSelectAll() {
        $output  = $this->t(1) . '// Get a list of all ' . $this->camelCase($this->Class) . ' items and return as array of objects' . $this->nl();
        $output .= $this->t(1) .'public function getAll' . $this->camelCase($this->Class) . '($orderby = "' . $this->arrFields[0][0] . '")' . $this->nl() . $this->t(1) . '{' . $this->nl();
        $output .= $this->t(2) . '// Retrieve all ' . ucwords($this->Name) . $this->nl();
        $output .= $this->t(2) . '$q = "SELECT * FROM ' . $this->Name . ' ORDER BY " . addslashes($orderby);' . $this->nl();
        $output .= $this->t(2) . '$r = parent::returnResultSet($q);' . $this->nl();
        $output .= $this->t(2) . '// Array to hold ' . $this->camelCase($this->Class) . ' objects' . $this->nl();
        $output .= $this->t(2) . '$arr' . $this->camelCase($this->Class) . ' = array();' . $this->nl();
        $output .= $this->t(2) . '// Create a ' . $this->camelCase($this->Class) . ' object for each row and add to array' . $this->nl();
        $output .= $this->t(2) . 'foreach ($r as $l) {' . $this->nl();
        $output .= $this->t(3) . '$thisObj = new ' . $this->camelCase($this->Class) . '(' . $this->nl();
        $tmp = "";
        foreach ($this->arrFields as $arrFieldVals) {
            if ($tmp != "") {
                $tmp .= ',' . $this->nl() . $this->t(4);
            }
            $tmp .= '$l->' . $arrFieldVals[0];
        }
        $output .= $this->t(4) . $tmp . $this->nl() . $this->t(3) . ');' . $this->nl();
        $output .= $this->t(3) . '$arr' . $this->camelCase($this->Class) . '[] = $thisObj;' . $this->nl();
        $output .= $this->t(2) . '}' . $this->nl();
        $output .= $this->t(2) . '// Return array of objects' . $this->nl();
        $output .= $this->t(2) . 'return $arr' . $this->camelCase($this->Class) . ';' . $this->nl();
        $output .= $this->t(1) . '}' . $this->nl() . $this->nl();
        return $output;
    }
    public function buildSelectBy($field_name) {
        $output  = $this->t(1) . '// Get a list of all ' . $this->camelCase($this->Class) . ' items by ' . $this->camelCase($field_name) . ' and return as array of objects' . $this->nl();
        $output .= $this->t(1) .'public function getAll' . $this->camelCase($this->Class) . 'By' . $this->camelCase($field_name) .'($'. $field_name . ' = null)' . $this->nl() . $this->t(1) . '{' . $this->nl();
        $output .= $this->t(2) . '// Retrieve all ' . $this->camelCase($this->Class) . ' By ' . $this->camelCase($field_name) . $this->nl();
//         $output .= $this->t(2) . '$q = "SELECT * FROM ' . $this->Name . ' WHERE ' . $field_name .' = \'" . addslashes($'. $field_name . ') . "\'";' . $this->nl();
        $output .= $this->t(2) . '$q = "SELECT * FROM ' . $this->Name . ' WHERE ' . $field_name .' = ?";' . $this->nl();
        $output .= $this->t(2) . '$var = addslashes($' . $field_name . ');' . $this->nl();
        $output .= $this->t(2) . '$r = parent::returnResultSet($q, $var);' . $this->nl();
        $output .= $this->t(2) . '// Array to hold ' . $this->camelCase($this->Class) . ' objects' . $this->nl();
        $output .= $this->t(2) . '$arr' . $this->camelCase($this->Class) . ' = array();' . $this->nl();
        $output .= $this->t(2) . '// Create a ' . $this->camelCase($this->Class) . ' object for each row and add to array' . $this->nl();
        $output .= $this->t(2) . 'foreach ($r as $l) {' . $this->nl();
        $output .= $this->t(3) . '$thisObj = new ' . $this->camelCase($this->Class) . '('. $this->nl();
        $tmp = "";
        foreach ($this->arrFields as $arrFieldVals) {
            if ($tmp != "") {
                $tmp .= ',' . $this->nl() . $this->t(4);
            }
            $tmp .= '$l->' . $arrFieldVals[0];
        }
        $output .= $this->t(4) . $tmp . $this->nl() . $this->t(3) . ');' . $this->nl();
        $output .= $this->t(3) . '$arr' . $this->camelCase($this->Class) . '[] = $thisObj;' . $this->nl();
        $output .= $this->t(2) . '}' . $this->nl();
        $output .= $this->t(2) . '// Return array of objects' . $this->nl();
        $output .= $this->t(2) . 'return $arr' . $this->camelCase($this->Class) . ';' . $this->nl();
        $output .= $this->t(1) . '}' . $this->nl() . $this->nl();
        return $output;
    }
    public function buildUpdate() {
        $output  = $this->t(1) . '// Persist changes to the db' . $this->nl();
        $output .= $this->t(1) . 'public function update()' . $this->nl() . $this->t(1) . '{' . $this->nl();
        $output .= $this->t(2) . '$q = "UPDATE ' . $this->Name . ' SET ";' . $this->nl();
        $tmp = "";
        for ($i = 1; $i < count($this->arrFields); $i++) {
            if ($tmp != "") $tmp .= ', ";' . $this->nl();
            $tmp .= $this->t(2) . '$q .= "' . $this->arrFields[$i][0] . ' = ';
            $tmp .= '\'" . addslashes($this->' . $this->camelCase($this->arrFields[$i][0]) . ') . "\'';
            }
        $output .= $tmp . ' ";' . $this->nl();
        $output .= $this->t(2) . '$q .= "WHERE ' . $this->arrFields[0][0]  . ' = " . intval($this->' . $this->camelCase($this->arrFields[0][0])  . ');' . $this->nl();
        $output .= $this->t(2) . 'parent::updateQuery($q);' . $this->nl();
        $output .= $this->t(1) . '}' . $this->nl() . $this->nl();
        return $output;
    }
    public function buildInsert() {
        $output  = $this->t(1) . '// Save to database as new object and assign ID' . $this->nl();
        $output .= $this->t(1) . 'public function create()' . $this->nl() . $this->t(1) . '{' . $this->nl();
        $output .= $this->t(2) . '$q = "INSERT INTO ' . $this->Name . ' (";' . $this->nl();
        $tmp = "";
        for ($i = 1; $i < count($this->arrFields); $i++) {
            if ($tmp != "") $tmp .= ', ";' . $this->nl();
            $tmp .= $this->t(2) . '$q .= "' . $this->arrFields[$i][0];
            }
        $output .= $tmp . '";' . $this->nl();
        $output .= $this->t(2) . '$q .= ") VALUES (";' . $this->nl();
        $tmp = "";
        for ($i = 1; $i < count($this->arrFields); $i++) {
            if ($tmp != "") $tmp .= ') . "\', ";' . $this->nl();
            $tmp .= $this->t(2) . '$q .= "\'" . addslashes($this->' . $this->camelCase($this->arrFields[$i][0]);
            }
        $output .= $tmp . ') . "\')";' . $this->nl();
        $output .= $this->t(2) . '$this->' . $this->camelCase($this->arrFields[0][0])  . ' = parent::insertQuery($q);' . $this->nl();
        $output .= $this->t(1) . '}' . $this->nl() . $this->nl();
        return $output;
    }
    public function buildDelete() {
        $output  = $this->t(1) . '// Delete function takes ID as parameter' . $this->nl();
        $output .= $this->t(1) . 'public function delete($deleteID)' . $this->nl() . $this->t(1) . '{' . $this->nl();
        $output .= $this->t(2) . '$q = "DELETE FROM ' . $this->Name . ' WHERE ' . $this->arrFields[0][0]  . ' = " . intval($deleteID);' . $this->nl();
        $output .= $this->t(2) . 'parent::updateQuery($q);' . $this->nl();
        $output .= $this->t(1) . '}' . $this->nl(). $this->nl();
        return $output;
    }
    public function buildSave() {
        $output = $this->t(1) . '// Generic save function: determines if this is an existing or new entry and calls appropriate function' . $this->nl();
        $output .= $this->t(1) . 'public function save()' . $this->nl();
        $output .= $this->t(1) . '{' . $this->nl();
        $output .= $this->t(2) . 'if ($this->Id == 0) {' . $this->nl();
        $output .= $this->t(3) . '// Empty classes are set with ID = 0' . $this->nl();
        $output .= $this->t(3) . '$this->create();' . $this->nl();
        $output .= $this->t(2) . '} elseif ($this->Id == -1) {' . $this->nl();
        $output .= $this->t(3) . '// Unretrievable rows are set up as ID = -1' . $this->nl();
        $output .= $this->t(3) . '// Do nothing' . $this->nl();
        $output .= $this->t(2) . '} else {' . $this->nl();
        $output .= $this->t(3) . '// Otherwise, it\'s an existing ID' . $this->nl();
        $output .= $this->t(3) . '$this->update();' . $this->nl();
        $output .= $this->t(2) . '}' . $this->nl();
        $output .= $this->t(1) . '} // end save()' . $this->nl();
        return $output;
    }
    public function buildCustomClassComment() {
        $output  = $this->nl();
        $output .= '/////////////////////////////////////////////////////////////////////////////' . $this->nl();
        $output .= '//                                                                         //' . $this->nl();
        $output .= '//       Place any functions not auto generated below this area.           //' . $this->nl();
        $output .= '//                                                                         //' . $this->nl();
        $output .= '/////////////////////////////////////////////////////////////////////////////' . $this->nl() . $this->nl();
        return $output;
    }
    public function buildClassClose() {
        $output = '}' . $this->nl();
        return $output;
    }
    public function camelCase($_fld) {
        // Change all underscores to spaces
        $f = str_replace('_', ' ', $_fld);
        // Upper case all words
        $f = ucwords($f);
        // Burp out all spaces
        $f = str_replace(' ', '', $f);
        return $f;
    }
}
?>