<?php
include_once("../phpGrid_Lite/conf.php");      
include_once('../inc/head.php');

$tableName = (isset($_GET['gn']) && $_GET['gn'] !== '') ? $_GET['gn'] : 'users';
?>

<section id="subtitle">
    <h2>Welcome! Manager</h2>
    <div>
    You can manage your sales team and contacts here.
    </div>
    <br />
</section>

<?php include_once('../inc/manager_menu.php'); ?>
<?php
include_once('../inc/footer.php');
?>