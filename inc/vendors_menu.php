<section id="subtitle">
    <h2>Chào mừng nhà cung cấp</h2>
    <div>
    Bạn có thể quản lý các nhà phân phối và sản phẩm của mình ở đây.
    </div>
    <br />
</section>

<div id="menu">
    <ul>
        <li><a href="products.php" <?php if($_GET['currentPage'] == 'products') echo 'class="active"'; ?>>Danh mục sản phẩm</a></li>
        <li><a href="distributors.php" <?php if($_GET['currentPage'] == 'distributors') echo 'class="active"'; ?>>Danh sách các nhà phân phối</a></li>
        <li><a href="opportunities.php" <?php if($_GET['currentPage'] == 'opportunities') echo 'class="active"'; ?>>Opportunities</a></li>
        <li><a href="customerwon.php" <?php if($_GET['currentPage'] == 'customerwon') echo 'class="active"'; ?>>Customers/Won</a></li>
    </ul>
</div>