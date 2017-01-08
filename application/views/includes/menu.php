<nav>
<ul>

<?php 
echo "<li>".a_url("Accueil","core/")."</li>";
foreach ($rooms as $room) {
	echo "<li>".a_url(ucfirst($room->name),"core/room/".$room->name)."</li>";
}
?>
</ul>
</nav>