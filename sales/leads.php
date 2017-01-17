<?php
use phpGrid\C_DataGrid;

require_once("../phpGrid/conf.php");      

include_once('../inc/head.php');
?>

<h1>Custom CRM</h1>

<?php
$_GET['currentPage'] = 'leads';
include_once('../inc/menu.php');
?>

<h3>My Leads</h2>
<?php
$dg = new C_DataGrid("SELECT id, contact_last, company, phone, email, website, Status, lead_referral_source, sales_rep, date_of_initial_contact FROM contact", "id", "contact");
$dg->set_query_filter(" Status = 1 && sales_rep = 1 ");
$dg->set_col_hidden('id')->set_col_hidden('Status')->set_col_hidden('sales_rep', false);
$dg -> set_col_format("email", "email");
$dg -> set_col_link("website");
$dg->enable_edit();
$dg->set_scroll(true, 300);

$sdg = new C_DataGrid("SELECT * FROM notes", "id", "notes");
$sdg->set_query_filter(" Sales_Rep = 1 ");

/*
$sdg->set_col_hidden('Contact', false)->set_col_hidden('Sales_Rep', false)->set_col_hidden('Is_New_Todo');
$sdg->set_col_hidden('id', false)->set_col_hidden('Task_Update', false);

$sdg->set_col_property('Todo_Type_ID', array('editable'=>false,'hidedlg'=>true));
$sdg->set_col_property('Todo_Desc_ID', array('editable'=>false,'hidedlg'=>true));
$sdg->set_col_property('Todo_Due_Date', array('editable'=>false,'hidedlg'=>true));
$sdg->set_col_property('Task_Status', array('editable'=>false,'hidedlg'=>true));
*/

$sdg->set_col_hidden('id')->set_col_hidden('Contact', false);

$sdg->set_col_edittype('Add_Task_or_Meeting', 'select', 'Select id, status From task_status');
$sdg->set_col_edittype('Task_Status', 'select', 'Select id, status From task_status');
$sdg->set_col_edittype('Is_New_Todo', 'select', '0:No;1:Yes');
$sdg->set_col_edittype('Todo_Type_ID', 'select', 'Select id, type From todo_type');
$sdg->set_col_edittype('Todo_Desc_ID', 'select', 'Select id, description From todo_desc');
//$sdg->set_col_edittype('Contact', 'select', 'Select id, concat(contact_first, " ", contact_last) From contact');

//$sdg->set_col_default('Contact', ###current####);
//$sdg->set_col_default('Sales_Rep', 1);

$sdg->enable_edit();


$dg->set_masterdetail($sdg, 'Contact', 'id');
$dg -> display();
?>

<?php
include_once('../inc/footer.php');
?>