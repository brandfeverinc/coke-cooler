<?
///////////////////////////////////////////////////////////////////////////////////////////////
//
// constructor.jay.php - automatically builds a class from a selected DB table
//
// Change Log
//
///////////////////////////////////////////////////////////////////////////////////////////////

error_reporting(E_ALL);
ini_set('display_errors', 1);

spl_autoload_register(function ($class) {
    include $class . ".php";
});

// page vars
$page_title = "";

// handle downloads
if(isset($_REQUEST['download']) && isset($_REQUEST['contents'])) {
    $file_name = $_REQUEST['filename'];
    header("Content-type: application/octet-stream");
    header("Content-Disposition: attachment; filename=\"". $file_name . ".php\"");
    echo $_REQUEST['contents'];
    exit;
}

$MC = (isset($_REQUEST['table'])) ? new MetaConstructor($_REQUEST['table'], $_REQUEST['table']) : new MetaConstructor();

function getTableOptions() {
    global $MC;
    $arrTables = $MC->getAllTableNames();
    foreach($arrTables as $table) {
        echo '<option value="' . $table . '">' . $table . '</option>';
        }
    }

function writeTable() {
    global $MC;
    if ((isset($_REQUEST['table'])) && ($_REQUEST['table'] != "")) {
        echo '<form action="constructor.jay.php" method="post">';
        if(isset($_REQUEST['getter'])){
            $select_bys = $_REQUEST['getter'];
        }
        else {
            $select_bys = array();
        }
        $MC->displayTable($select_bys);
        echo '<input type="hidden" name="table" value="' . $_REQUEST['table'] . '" />';
        echo '<input type="hidden" name="pageaction" value="c" />';
        echo '<input type="submit" value="Construct Class" />';
        echo '</form>';

        echo '<form action="constructor.jay.php" method="post">';
        echo '<input type="hidden" name="table" value="' . $_REQUEST['table'] . '" />';
        echo '<input type="hidden" name="pageaction" value="alist" />';
        echo '<input type="submit" value="Output Admin List" />';
        echo '</form>';

        echo '<form action="constructor.jay.php" method="post">';
        echo '<input type="hidden" name="table" value="' . $_REQUEST['table'] . '" />';
        echo '<input type="hidden" name="pageaction" value="elist" />';
        echo '<input type="submit" value="Output Admin Edit" />';
        echo '</form>';
        }
    }

function writeClass() {
    global $MC;
    if ((isset($_REQUEST['table'])) && ($_REQUEST['table'] != "") && (isset($_REQUEST['pageaction'])) && ($_REQUEST['pageaction'] == 'c')) {
        if(isset($_REQUEST['getter'])){
            $select_bys = $_REQUEST['getter'];
        }
        else {
            $select_bys = array();
        }
        $class_text = $MC->buildClass($select_bys);
        $class_name = $MC->camelCase($MC->Class);

        // for downloading a file.
        echo '<form action="constructor.jay.php" method="post" accept-charset="utf-8" id="download_form">';
            echo '<input type="hidden" name="filename" value="' . $class_name . '" id="filename">';
            echo '<textarea id="contents" name="contents" style="display:none;">';
                echo $class_text;
            echo '</textarea>';
            echo '<input type="submit" name="download" value="Download File" id="download">';
            echo '<br/><span id="download_arrow">&darr;</span>';
        echo '</form>';
        
        echo '<div id="d_clip_container" style="position:relative">
                <div id="d_clip_button">Copy to Clipboard</div>
              </div>';
        
        echo '<div id="codeblock"><pre>';
        echo $class_text;
        echo '</pre></div>';
    }
}

function writeAdminList() {
    global $MC;

    $admin_list_text .= $MC->buildAdminInterfaceList();
    
    echo $admin_list_text;
}

function writeAdminEdit() {
    global $MC;

    $admin_edit_text .= $MC->buildAdminInterfaceEdit();
    
    echo $admin_edit_text;
}

?>
<style>
    #codeblock{
        font-family: Verdana, Arial, Helvetica, sans-serif;
        border: 1px solid #ccc;
        background: #eee;
        padding: 10px;
        margin-top: 10px;
        color: blue;
        width: 800px;
        font-size: 12px;
    }
    #download_form {
        background-color:#B8FFBD;
        padding:30px;
        text-align:center;
        width:200px;
    }
    #d_clip_button {
        text-align:center; 
        border:1px solid black; 
        background-color:#ccc; 
        margin:10px; 
        padding:10px; 
        width: 200px;
    }
    #d_clip_button.hover { background-color:#eee; }
    #d_clip_button.active { background-color:#aaa; }
    
</style>
<script type="text/javascript" src="http://ben.soundenterprises.net/constructor/zeroclipboard/ZeroClipboard.js"></script>

<h1>Class Constructor</h1>
<form id="tableselector" action="constructor.jay.php">
    Class to create:
    <select name="table" onchange="document.getElementById('tableselector').submit()">
        <option value="">Select a Table</option>
        <? getTableOptions(); ?>
    </select>
</form>
<?
writeTable();
if (isset($_REQUEST['pageaction'])) {
    switch ($_REQUEST['pageaction']) {
        case 'c':
            writeClass();
            break;
        case 'alist':
            writeAdminList();
            break;
        case 'elist':
            writeAdminEdit();
            break;
    }
}
?>
<script type="text/javascript" charset="utf-8">
    ZeroClipboard.setMoviePath( 'http://ben.soundenterprises.net/constructor/zeroclipboard/ZeroClipboard.swf' );
    var clip = new ZeroClipboard.Client();
    clip.setText( '' );
    clip.glue( 'd_clip_button', 'd_clip_container' );
    
    clip.addEventListener( 'mouseDown', function(client) { 
        // set text to copy here
        clip.setText( document.getElementById('contents').value );
    } );
    
</script>