﻿<?php
session_start();
require_once("../global.php");
$getID = db::query("SELECT * FROM users WHERE username = '".USER_NAME."'");
$b = mysql_fetch_assoc($getID);
$my_id = $b['id'];
if(isset($_GET['pageNumber'])){

$page = FilterText($_GET['pageNumber']);
$pagesize = FilterText($_GET['pageSize']);
$category = FilterText($_GET['categoryId']);

if(!empty($category)){

$sql_category = mysql_query("SELECT * FROM messenger_friendships WHERE category = '".$category."'") or die(mysql_error());
$friends_category = mysql_num_rows($sql_category);

}else{

$friends_category = 1;

}

if($friends_category > 0){
?>
   <div id="friend-list" class="clearfix">
<div id="friend-list-header-container" class="clearfix">
    <div id="friend-list-header">
        <div class="page-limit">
            <div class="big-icons friend-header-icon">Amigos
                <br />Ver
           		<?php if($pagesize == 30){ ?>
                30 |
                <a class="category-limit" id="pagelimit-50">50</a> |
                <a class="category-limit" id="pagelimit-100">100</a>
                <?php }elseif($pagesize == 50){ ?>
                <a class="category-limit" id="pagelimit-30">30</a> |
				50 |
                <a class="category-limit" id="pagelimit-100">100</a>
                <?php }elseif($pagesize == 100){ ?>
                <a class="category-limit" id="pagelimit-30">30</a> |
				<a class="category-limit" id="pagelimit-50">50</a> |
                100
                <?php } ?>
            </div>
        </div>
    </div>
	<div id="friend-list-paging">
		<?php
		if($page <> 1){
		$pageminus = $page - 1;
		echo "<a href=\"#\" class=\"friend-list-page\" id=\"page-".$pageminus."\">&lt;&lt;</a> |";
		}
		$afriendscount = mysql_query("SELECT COUNT(*) FROM messenger_friendships WHERE (user_one_id = '".$my_id."') AND category = '".$category."'") or die(mysql_error());
		$friendscount = mysql_result($afriendscount, 0);
		$pages = ceil($friendscount / $pagesize);
		if($pages == 1){
		echo "1";
		}else{
		$n = 0;

		while ($n < $pages) {
			$n++;
			if($n == $page){
			echo $n." |";
			} else {
			echo "<a href=\"#\" class=\"friend-list-page\" id=\"page-".$n."\">".$n."</a> |";
			}
		}

		if($page <> $pages){
		$pageplus = $page + 1;
		echo "<a href=\"#\" class=\"friend-list-page\" id=\"page-".$pageplus."\">&gt;&gt;</a>";
		}
		}
		?>
        </div>
    </div>

<form id="friend-list-form">
     <table id="friend-list-table" border="0" cellpadding="0" cellspacing="0">
        <thead>
            <tr class="friend-list-header">
                <th class="friend-select" />
                <th class="friend-name">
					<a class="sort">Nombre</a>
				</th>
                <th class="friend-login">
					<a class="sort">Último acceso</a>
				</th>
                <th class="friend-remove">Quitar</th>
            </tr>
		</thead>
		<tbody>
           <?php
		   $i = 0;
		   $offset = $pagesize * $page;
		   $offset = $offset - $pagesize;
		   $getem = mysql_query("SELECT * FROM messenger_friendships WHERE user_one_id = '".$my_id."' AND category = '".$category."' LIMIT ".$pagesize." OFFSET ".$offset."") or die(mysql_error());

		   while ($row = mysql_fetch_assoc($getem)) {
		           $i++;

		           if($i%2==1){
		               $even = "odd";
		           } else {
		               $even = "even";
		           }


		           		$friendsql = mysql_query("SELECT * FROM users WHERE id = '".$row['user_two_id']."'");


		           $friendrow = mysql_fetch_assoc($friendsql);
				   ?>
		    <tr class="<?php echo $even; ?>">
				<td><input type="checkbox" name="friendList" value="<?php echo $friendrow['id']; ?>" /></td>
				<td class="friend-name">
				<?php echo $friendrow['username']; ?>
				</td>
				<td class="friend-login" title="<?php echo $friendrow['last_online']; ?>"><?php echo timestamp($friendrow['last_online']); ?></td>
				<td class="friend-remove"><div id="remove-friend-button-<?php echo $friendrow['id']; ?>" class="friendmanagement-small-icons friendmanagement-remove remove-friend"></div></td>
			</tr>
			<?php } ?>
        </tbody>
    </table>
    <a class="select-all" id="friends-select-all" href="#">Seleccionar todo</a> |
    <a class="deselect-all" href=#" id="friends-deselect-all">Quitar selección</a>
</form>
<div id="category-options" class="clearfix">
<select id="category-list-select" name="category-list">
    <option value="0">Amigos</option>
	<?php
	$get_categorys = mysql_query("SELECT * FROM messenger_categorys WHERE owner_id = '".$my_id."'") or die(mysql_error());
	if(mysql_num_rows($get_categorys) > 0){
		while($crow = mysql_fetch_assoc($get_categorys)){
	$get_category = mysql_query("SELECT * FROM messenger_categorys WHERE id = '".$crow['id']."' LIMIT 1") or die(mysql_error());
	$row = mysql_fetch_assoc($get_category);
	?>
	<option value="<?php echo $row['id']; ?>"><?php echo $row['name']; ?></option>
	<?php } } ?>
</select>
<div class="friend-del"><a class="new-button red-button cancel-icon" href="#" id="delete-friends"><b><span></span>Eliminar amigos</b><i></i></a></div>
<div class="friend-move"><a class="new-button" href="#" id="move-friend-button"><b><span></span>Mover</b><i></i></a></div>

<?php } else { ?>
<div id="friend-list" class="clearfix">
<p style="padding-top: 11px;" class="last">No tienes amigos en esta categoría</p>
</div>

<?php } } elseif(isset($_POST['searchString'])){
$search = $_POST['searchString'];
$pagesize = $_POST['pageSize'];
$page = 1;

$sql = mysql_query("SELECT * FROM messenger_friendships WHERE user_one_id = '".$my_id."'");
		while ($row = mysql_fetch_assoc($sql)) {
					$friendsql = mysql_query("SELECT * FROM users WHERE id = '".$row['user_two_id']."' AND username LIKE '%".$search."%'");
		}
if(mysql_num_rows($friendsql) > 0){
?>
            <div id="friend-list" class="clearfix">
<div id="friend-list-header-container" class="clearfix">
    <div id="friend-list-header">
        <div class="page-limit">
            <div class="big-icons friend-header-icon">Amigos
                <br />Ver

           		<?php if($pagesize == 30){ ?>
                30 |
                <a class="category-limit" id="pagelimit-50">50</a> |
                <a class="category-limit" id="pagelimit-100">100</a>
                <?php }elseif($pagesize == 50){ ?>
                <a class="category-limit" id="pagelimit-30">30</a> |
				50 |
                <a class="category-limit" id="pagelimit-100">100</a>
                <?php }elseif($pagesize == 100){ ?>
                <a class="category-limit" id="pagelimit-30">30</a> |
				<a class="category-limit" id="pagelimit-50">50</a> |
                100
                <?php } ?>
            </div>
        </div>
    </div>
	<div id="friend-list-paging">
	<?php
		if($page <> 1){
		$pageminus = $page - 1;
		echo "<a href=\"#\" class=\"friend-list-page\" id=\"page-".$pageminus."\">&lt;&lt;</a> |";
		}
		$i = 0;
	    $list = 0;
		$sql = mysql_query("SELECT * FROM messenger_friendships WHERE user_one_id = '".$my_id."'") or die(mysql_error());
		while ($row = mysql_fetch_assoc($sql)) {
			   $i++;


					$friendsql = mysql_query("SELECT * FROM users WHERE id = '".$row['user_two_id']."' AND username LIKE '%".$search."%'");

	   $list = $list + mysql_num_rows($friendsql);
		}

		$pages = ceil($list / $pagesize);

		if($pages == 1){
		echo "1";
		}else{
		$n = 0;

		while ($n < $pages) {
			$n++;
			if($n == $page){
			echo $n." |";
			} else {
			echo "<a href=\"#\" class=\"friend-list-page\" id=\"page-".$n."\">".$n."</a> |";
			}
		}

		if($page <> $pages){
		$pageplus = $page + 1;
		echo "<a href=\"#\" class=\"friend-list-page\" id=\"page-".$pageplus."\">&gt;&gt;</a>";
		}
		}
	?>
        </div>
    </div>


<form id="friend-list-form">
    <table id="friend-list-table" border="0" cellpadding="0" cellspacing="0">
        <thead>
            <tr class="friend-list-header">
                <th class="friend-select" />
                <th class="friend-name">
					<a class="sort">Nombre</a>
				</th>
                <th class="friend-login">
					<a class="sort">Último acceso</a>
				</th>
                <th class="friend-remove">Quitar</th>
            </tr>
		</thead>
		<tbody>
           <?php
		   $i = 0;
		   $n = 0;
		   $getem = mysql_query("SELECT * FROM messenger_friendships WHERE user_one_id = '".$my_id."'") or die(mysql_error());

		   while ($row = mysql_fetch_assoc($getem)) {
		           $i++;


		           		$friendsql = mysql_query("SELECT * FROM users WHERE id = '".$row['user_two_id']."' AND username LIKE '%".$search."%'");


		           $friendrow = mysql_fetch_assoc($friendsql);

		           if(!empty($friendrow['username'])){

		           $n++;

				   if(IsEven($n)){
					   $even = "odd";
				   } else {
					   $even = "even";
		           }
				   ?>

			<tr class="<?php echo $even; ?>">
				<td><input type="checkbox" name="friendList" value="<?php echo $friendrow['id']; ?>" /></td>
				<td class="friend-name">
				<?php echo $friendrow['username']; ?>
				</td>
				<td class="friend-login" title="<?php echo $friendrow['last_online']; ?>"><?php echo timestamp($friendrow['last_online']); ?></td>
				<td class="friend-remove"><div id="remove-friend-button-<?php echo $friendrow['id']; ?>" class="friendmanagement-small-icons friendmanagement-remove remove-friend"></div></td>
			</tr>
			<?php } } ?>
        </tbody>
    </table>
    <a class="select-all" id="friends-select-all" href="#">Seleccionar todo</a> |
    <a class="deselect-all" href=#" id="friends-deselect-all">Quitar selección</a>
</form>
<div id="category-options" class="clearfix">
<select id="category-list-select" name="category-list">
    <option value="0">Amigos</option>
	<?php
	$get_categorys = mysql_query("SELECT * FROM messenger_categorys WHERE owner_id = '".$my_id."'") or die(mysql_error());
	if(mysql_num_rows($get_categorys) > 0){
		while($crow = mysql_fetch_assoc($get_categorys)){
	$get_category = mysql_query("SELECT * FROM messenger_categorys WHERE id = '".$crow['id']."' LIMIT 1") or die(mysql_error());
	$row = mysql_fetch_assoc($get_category);
	?>
	<option value="<?php echo $row['id']; ?>"><?php echo $row['username']; ?></option>
	<?php } } ?>
</select>
<div class="friend-del"><a class="new-button red-button cancel-icon" href="#" id="delete-friends"><b><span></span>Eliminar amigos</b><i></i></a></div>
<div class="friend-move"><a class="new-button" href="#" id="move-friend-button"><b><span></span>Mover</b><i></i></a></div>

<?php } else { ?>
<div id="friend-list" class="clearfix">
<p style="padding-top: 11px;" class="last">No se han encontrado amigos.</p>
</div>
<?php } } ?>