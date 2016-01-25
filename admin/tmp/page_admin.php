<?php
session_start();
header("Cache-control: private"); //IE 6 Fix
include("includes/config.php");
include("includes/checklogin.php");

require_once("../inc/classes/Template.php");
require_once("../inc/classes/Page.php");
require_once("../inc/classes/PagePart.php");
require_once("../inc/classes/TemplatePartType.php");
require_once("../inc/classes/Helpers.php");

// page vars
$message = "";
$id = "";
$pwmsg = '';

if ($_REQUEST["id"] == "") {
    $id = 0;
    $_REQUEST['mode'] = 'a';
}
else {
    $id = $_REQUEST["id"];
    $_REQUEST['mode'] = 'e';
}

// if form was submitted:
if ($_POST['commit'] == "Cancel") {
	header("Location:page_list.php");
	exit;
}

if ($_POST['commit'] == "Save Page") {
	
	if ($id == 0) {
		// add the listing

         if (trim($_REQUEST['template_id']) == '' || trim($_REQUEST['menu_title']) == '' || trim($_REQUEST['page_title']) == '' || trim($_REQUEST['friendly_url']) == '') {
            $message = 'Page Template, Menu Title, Page Title and Friendly URL must all be filled out.';
            }
         else {
            $objPage = new Page();
            $objPage->TemplateId = $_REQUEST['template_id'];
            $objPage->MenuTitle = $_REQUEST['menu_title'];
            $objPage->PageTitle = $_REQUEST['page_title'];
            $objPage->TitleTag = $_REQUEST['title_tag'];
            //$objPage->FriendlyUrl = $_REQUEST['friendly_url'];
            $objPage->MetaKeywords = $_REQUEST['meta_keywords'];
            $objPage->MetaDescription = $_REQUEST['meta_description'];
            if ($_REQUEST['is_menu'] == '1') {
                $objPage->IsMenu = 1;
            }
            else {
                $objPage->IsMenu = 0;
            }
            if ($_REQUEST['published'] == '1') {
                $objPage->Published = 1;
            }
            else {
                $objPage->Published = 0;
            }
            //$objPage->ParentPageId = $_REQUEST['parent_page_id'];
            $objPage->Create();
            
		    // redirect to listing list
		    header("Location:page_admin.php?id=" . $objPage->Id . "&formaction-edit");
		    exit;
         }
	}
	else {

         //if (trim($_REQUEST['menu_title']) == '' || trim($_REQUEST['page_title']) == '' || trim($_REQUEST['friendly_url']) == '') {
         if (trim($_REQUEST['menu_title']) == '' || trim($_REQUEST['page_title']) == '') {
            $message = 'Menu Title and Page Title must be filled out.';
            $menu_title = $_REQUEST['menu_title'];
            $page_title = $_REQUEST['page_title'];
            $title_tag = $_REQUEST['title_tag'];
            $friendly_url = $_REQUEST['friendly_url'];
            $meta_keywords = $_REQUEST['meta_keywords'];
            $meta_description = $_REQUEST['meta_description'];
            $is_menu = $_REQUEST['is_menu'];
            $published = $_REQUEST['published'];
            $parent_page_id = $_REQUEST['parent_page_id'];
            }
         else {
             $objPage = new Page($_REQUEST['page_id']);
             $objPage->MenuTitle = $_REQUEST['menu_title'];
             $objPage->PageTitle = $_REQUEST['page_title'];
             $objPage->TitleTag = $_REQUEST['title_tag'];
             //$objPage->FriendlyUrl = $_REQUEST['friendly_url'];
             $objPage->MetaKeywords = $_REQUEST['meta_keywords'];
             $objPage->MetaDescription = $_REQUEST['meta_description'];
                if (isset($_REQUEST['is_menu'])) {
                    if ($_REQUEST['is_menu'] == '1') {
                        $objPage->IsMenu = 1;
                    }
                    else {
                        $objPage->IsMenu = 0;
                    }
                }
                else {
                    $objPage->IsMenu = 0;
                }
             if ($_REQUEST['published'] == '1') {
                 $objPage->Published = 1;
             }
             else {
                 $objPage->Published = 0;
             }
             //$objPage->ParentPageId = $_REQUEST['parent_page_id'];
             $objPage->Update();

             $objPagePart = new PagePart();
             $oPageParts = $objPagePart->GetAllPagePartByPageId($_REQUEST['page_id']);
             foreach ($oPageParts as $pagepart) {
                 $objUpdatePagePart = new PagePart($pagepart->Id);
                 $objUpdatePagePart->Content = $_REQUEST['page_part_' . $pagepart->Id];
                 $objUpdatePagePart->DateModified = date('Y-m-d H:i:s');
                 $objUpdatePagePart->Update();
             }

    		// redirect to listing list
    		header("Location:page_list.php");
    		exit;
         }

	}
}
else {
	if ($_REQUEST['mode'] == 'e') {
		//listing
        $page = new Page($id);
        $template_id = $page->TemplateId;
        $menu_title = $page->MenuTitle;
        $page_title = $page->PageTitle;
        $title_tag = $page->TitleTag;
        $friendly_url = $page->FriendlyUrl;
        $meta_keywords = $page->MetaKeywords;
        $meta_description = $page->MetaDescription;
        $is_menu = $page->IsMenu;
        $published = $page->Published;
        $parent_page_id = $page->ParentPageId;
	}
}

include("includes/pagetemplate.php");

function PageContent() {
	global $message;
    global $template_id;
    global $menu_title;
    global $page_title;
    global $title_tag;
    global $friendly_url;
    global $meta_keywords;
    global $meta_description;
    global $is_menu;
    global $published;
    global $parent_page_id;
	global $id;
?>

	<script type="text/javascript" src="tinymce/js/tinymce/tinymce.min.js"></script>
	<script type="text/javascript">
	tinyMCE.init({
	    selector : "textarea.mceEditor",
	    plugins : "image code pagebreak table spellchecker preview searchreplace print contextmenu paste fullscreen link nonbreaking",
	    image_advtab : true,
	    forced_root_block : "",
	    setup: function (editor) {
	        editor.on('init', function(args) {
	            editor = args.target;
	
	            editor.on('NodeChange', function(e) {
	                if (e && e.element.nodeName.toLowerCase() == 'img') {
	//                     width = e.element.width;
	//                     height = e.element.height;
	                    tinyMCE.DOM.setAttribs(e.element, {'class':'img_responsive', 'height' : null, 'width' : null});
	                    tinyMCE.DOM.setStyle(e.element, 'max-width', '100%');
	                }
	            });
	        });
	    },
		content_css : ["../css/bootstrip.min.css"],
	    document_base_url : "http://havells.boxkitemedia.com"
	});
	</script>

	<div id="content">

		<div class="layout">
	        <?php
	        if ($id == 0) {
	            $bread_title = 'Page Add';
	        }
	        else {
	            $bread_title = 'Page Edit';
	        }

		    $aLabels = array();
		    $aLinks = array();
		    $aLabels[0] = 'Home';
		    $aLinks[0] = 'mainpage.php';
		    $aLabels[1] = 'Pages';
		    $aLinks[1] = 'page_list.php';
		    $aLabels[2] = $bread_title;
		    $aLinks[2] = '';
		    echo Helpers::CreateBreadCrumbs($aLabels, $aLinks);
		    ?>

		    <p class="larger botspace heading"><?php echo $bread_title; ?></p>

			<div class="formstyle ">
                <?php
                if ($message <> "") {
                	echo("<p class=\"err\">".$message."</p>");
                }
                ?>
				<form method="post" action="page_admin.php">
					<table class="form-table">
         <?php
         if(!isset($id) || $id == 0) {
         ?>
         <tr>
            <td class="table-label" align="right"><strong>Page Template: </strong></td>
            <td>
               <select name="template_id">
                   <option value=""></option>
               <?php
               $objTemplate = new Template();
               $oTemplate = $objTemplate->GetAllTemplate('name');
               foreach ($oTemplate as $template) {
                   $cSelected = '';
                   if ($template_id == $template->Id) {
                       $cSelected = 'selected';
                   }
                   echo '<option ' . $cSelected . ' value="' . $template->Id . '">' . $template->Name . '</option>';
               }
               ?>
               </select>
            </td>
         </tr>
         <?php
         }
         ?>
         <tr>
            <td class="table-label" align="right"><strong>Menu Title: </strong></td>
            <td>
               <input type="text" class="form midform" class="form midform" maxlength="100" size="40" value="<?php echo $menu_title;?>" name="menu_title" /> <em>(This is what displays in the menu dropdown)</em>
            </td>
         </tr>
         <tr>
            <td class="table-label" align="right"><strong>Page Title: </strong></td>
            <td>
               <input type="text" class="form midform" class="form midform" maxlength="100" size="40" value="<?php echo $page_title;?>" name="page_title" /> <em>(This is what displays on the top of this page)</em>
            </td>
         </tr>
         <!--
         <tr>
            <td class="table-label" align="right"><strong>Friendly URL: </strong></td>
            <td>
               <input type="text" class="form midform" maxlength="100" size="40" value="<?php echo $friendly_url;?>" name="friendly_url" />
            </td>
         </tr>
         -->
         <tr>
            <td class="table-label" align="right"><strong>Title Tag: </strong></td>
            <td>
               <input type="text" class="form midform" maxlength="100" size="60" value="<?php echo $title_tag;?>" name="title_tag" />
            </td>
         </tr>
         <tr>
            <td class="table-label" align="right"><strong>META Keywords: </strong></td>
            <td>
               <input type="text" class="form midform" maxlength="255" size="60" value="<?php echo $meta_keywords;?>" name="meta_keywords" />
            </td>
         </tr>
         <tr>
            <td class="table-label" align="right" valign="top"><strong>META Description: </strong></td>
            <td>
               <textarea rows="3" cols="60" class="mceNoEditor" name="meta_description" style="width: 400px;"><?php echo $meta_description;?></textarea>
            </td>
         </tr>
         <tr>
            <td colspan="2">&nbsp;</td>
         </tr>
         
         <?php
     if(isset($id)) {
         $objPagePart = new PagePart();
         $oPageParts = $objPagePart->GetAllPagePartByPageId($id);
         foreach ($oPageParts as $pagepart) {
             $objTemplatePartType = new TemplatePartType($pagepart->TemplatePartId);
             
             if ($objTemplatePartType->Name == 'Right Column' && $pagepart->Content == '') {
                $content = '%%P3_REQUEST_A_QUOTE_SIDE%% %%P3_VIDEO_SIDE%% %%P3_TESTIMONIAL_SIDE%%';
             }
             else {
                $content = $pagepart->Content;
             }
             ?>
             <tr>
                <td class="table-label" align="right" valign="top"><strong><?php echo $objTemplatePartType->Name;?>: </strong></td>
                <td>
                   <textarea style="width:90%; height:400px;" class="mceEditor" id="page_part_<?php echo $pagepart->Id;?>" name="page_part_<?php echo $pagepart->Id;?>"><?php echo $content;?></textarea>
                </td>
             </tr>
             <tr>
                <td colspan="2">&nbsp;</td>
             </tr>
             <?php
         }
     }
         ?>
         <!--
         <tr>
            <td class="table-label" align="right"><strong>Is Menu Page?: </strong></td>
            <td valign="bottom">
               <input type="checkbox" <?php if ($is_menu == '1') { echo 'checked'; } ?> value="1" name="is_menu" />
            </td>
         </tr>
         -->
         <!--
         <tr>
            <td class="table-label" align="right"><strong>Parent Page: </strong></td>
            <td>
               <select name="parent_page_id">
                   <option value=""></option>
                   <option <?php if ($parent_page_id == '1') { echo 'selected'; } ?> value="1">Products</option>
                   <option <?php if ($parent_page_id == '2') { echo 'selected'; } ?> value="2">Media + Resources</option>
                   <option <?php if ($parent_page_id == '3') { echo 'selected'; } ?> value="3">Company</option>
                   <option <?php if ($parent_page_id == '4') { echo 'selected'; } ?> value="4">Contact</option>
               </select>
            </td>
         </tr>
         -->
         <tr>
            <td class="table-label" align="right"><strong>Published?: </strong></td>
            <td valign="bottom">
               <input type="checkbox" <?php if ($published == '1') { echo 'checked'; } ?> value="1" name="published" />
            </td>
         </tr>
					</table>
				
					<p class="submit"><input type="submit" name="commit" value="Save Page">&nbsp;&nbsp;<input type="submit" name="commit" value="Cancel"></p>
				
                    <input type="hidden" name="page_id" value="<?php echo $id; ?>" id="page_id">
                   	<input type="hidden" name="id" value="<? echo $id; ?>" />

				</form>
		</div>

	</div>

<?php
}
?>