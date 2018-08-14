<?php
/**
 * admin_edit_domaine.php
 * Interface de creation/modification des sites, domaines et des ressources de l'application GRR
 * Dernière modification : $Date: 2018-08-14 11:30$
 * @author    JeromeB
 * @copyright Copyright 2003-2018 Team DEVOME - JeromeB
 * @link      http://www.gnu.org/licenses/licenses.html
 *
 * This file is part of GRR.
 *
 * GRR is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 */

$grr_script_name = "admin_edit_room.php";

$ok = NULL;
if (Settings::get("module_multisite") == "Oui")
	$id_site = isset($_POST["id_site"]) ? $_POST["id_site"] : (isset($_GET["id_site"]) ? $_GET["id_site"] : -1);
$action = isset($_POST["action"]) ? $_POST["action"] : (isset($_GET["action"]) ? $_GET["action"] : NULL);
$add_area = isset($_POST["add_area"]) ? $_POST["add_area"] : (isset($_GET["add_area"]) ? $_GET["add_area"] : NULL);
$area_id = isset($_POST["area_id"]) ? $_POST["area_id"] : (isset($_GET["area_id"]) ? $_GET["area_id"] : NULL);
$retour_page = isset($_POST["retour_page"]) ? $_POST["retour_page"] : (isset($_GET["retour_page"]) ? $_GET["retour_page"] : NULL);
$room = isset($_POST["room"]) ? $_POST["room"] : (isset($_GET["room"]) ? $_GET["room"] : NULL);
$id_area = isset($_POST["id_area"]) ? $_POST["id_area"] : (isset($_GET["id_area"]) ? $_GET["id_area"] : NULL);
$change_area = isset($_POST["change_area"]) ? $_POST["change_area"] : NULL;
$area_name = isset($_POST["area_name"]) ? $_POST["area_name"] : NULL;
$access = isset($_POST["access"]) ? $_POST["access"] : NULL;
$ip_adr = isset($_POST["ip_adr"]) ? $_POST["ip_adr"] : NULL;
//$room_name = isset($_POST["room_name"]) ? $_POST["room_name"] : NULL;
//$description = isset($_POST["description"]) ? $_POST["description"] : NULL;
//$capacity = isset($_POST["capacity"]) ? $_POST["capacity"] : NULL;
$duree_max_resa_area1  = isset($_POST["duree_max_resa_area1"]) ? $_POST["duree_max_resa_area1"] : NULL;
$duree_max_resa_area2  = isset($_POST["duree_max_resa_area2"]) ? $_POST["duree_max_resa_area2"] : NULL;
//$delais_max_resa_room  = isset($_POST["delais_max_resa_room"]) ? $_POST["delais_max_resa_room"] : NULL;
//$delais_min_resa_room  = isset($_POST["delais_min_resa_room"]) ? $_POST["delais_min_resa_room"] : NULL;
//$delais_option_reservation  = isset($_POST["delais_option_reservation"]) ? $_POST["delais_option_reservation"] : NULL;
//$allow_action_in_past  = isset($_POST["allow_action_in_past"]) ? $_POST["allow_action_in_past"] : NULL;
//$dont_allow_modify  = isset($_POST["dont_allow_modify"]) ? $_POST["dont_allow_modify"] : NULL;
//$qui_peut_reserver_pour  = isset($_POST["qui_peut_reserver_pour"]) ? $_POST["qui_peut_reserver_pour"] : NULL;
//$who_can_see  = isset($_POST["who_can_see"]) ? $_POST["who_can_see"] : NULL;
$max_booking = isset($_POST["max_booking"]) ? $_POST["max_booking"] : NULL;
settype($max_booking, "integer");
if ($max_booking<-1)
	$max_booking = -1;
//$statut_room = isset($_POST["statut_room"]) ? "0" : "1";
//$show_fic_room = isset($_POST["show_fic_room"]) ? "y" : "n";
/*if (isset($_POST["active_ressource_empruntee"]))
	$active_ressource_empruntee = 'y';
else
{
	$active_ressource_empruntee = 'n';
	// toutes les reservations sont considerees comme restituee
	grr_sql_query("update ".TABLE_PREFIX."_entry set statut_entry = '-' where room_id = '".$room."'");
}
if (isset($_POST["active_cle"]))
	$active_cle = 'y';
else
{
	$active_cle = 'n';
	// toutes les reservations sont considerees comme restituee
	grr_sql_query("update ".TABLE_PREFIX."_entry set statut_entry = '-' where room_id = '".$room."'");
}*/
//$picture_room = isset($_POST["picture_room"]) ? $_POST["picture_room"] : NULL;
//$comment_room = isset($_POST["comment_room"]) ? $_POST["comment_room"] : NULL;
//$show_comment = isset($_POST["show_comment"]) ? "y" : "n";
$change_done = isset($_POST["change_done"]) ? $_POST["change_done"] : NULL;
if(!isset($_POST["area_order"]) || empty($_POST["area_order"])){
	$area_order = 0;
} else{
	$area_order = $_POST["area_order"];
}

//$room_order = isset($_POST["room_order"]) ? $_POST["room_order"] : NULL;
$change_room = isset($_POST["change_room"]) ? $_POST["change_room"] : NULL;
$number_periodes = isset($_POST["number_periodes"]) ? $_POST["number_periodes"] : NULL;
$type_affichage_reser = isset($_POST["type_affichage_reser"]) ? $_POST["type_affichage_reser"] : NULL;
//$retour_resa_obli = isset($_POST["retour_resa_obli"]) ? $_POST["retour_resa_obli"] : NULL;
/*$moderate = isset($_POST['moderate']) ? $_POST["moderate"] : NULL;
if ($moderate == 'on')
	$moderate = 1;
else
	$moderate = 0;*/
settype($type_affichage_reser, "integer");

if (isset($_POST["change_room_and_back"]))
{
	$change_room = "yes";
	$change_done = "yes";
}

if (isset($_POST["change_area_and_back"]))
{
	$change_area = "yes";
	$change_done = "yes";
}
// memorisation du chemin de retour
if (!isset($retour_page))
{
	$retour_page = $back;
	// on nettoie la chaine :
	$long_chaine_a_supprimer = strlen(strstr($retour_page, "&amp;msg=")); // longueur de la chaine e partir de la premiere occurence de &amp;msg=
	if ($long_chaine_a_supprimer == 0)
		$long_chaine_a_supprimer = strlen(strstr($retour_page, "?msg="));
	$long = strlen($retour_page) - $long_chaine_a_supprimer;
	$retour_page = substr($retour_page, 0, $long);
}
// modification d'une resource : admin ou gestionnaire
if (authGetUserLevel(getUserName(),-1) < 6)
{
	if (isset($room))
	{
		// Il s'agit d'une modif de ressource
		if (((authGetUserLevel(getUserName(),$room) < 3)) || (!verif_acces_ressource(getUserName(), $room)))
		{
			showAccessDenied($back);
			exit();
		}
	}
	else
	{
		if (isset($area_id))
		{
			// On verifie que le domaine $area_id existe
			$test = grr_sql_query1("SELECT id FROM ".TABLE_PREFIX."_area WHERE id='".$area_id."'");
			if ($test == -1)
			{
				showAccessDenied($back);
				exit();
			}
			// Il s'agit de l'ajout d'une ressource
			// On verifie que l'utilisateur a le droit d'ajouter des ressources
			if ((authGetUserLevel(getUserName(), $area_id, 'area') < 4))
			{
				showAccessDenied($back);
				exit();
			}
		}
		else if (isset($id_area))
		{
			// On verifie que le domaine $area existe
			$test = grr_sql_query1("SELECT id FROM ".TABLE_PREFIX."_area WHERE id='".$id_area."'");
			if ($test == -1)
			{
				showAccessDenied($back);
				exit();
			}
			// Il s'agit de la modif d'un domaine
			if ((authGetUserLevel(getUserName(), $id_area, 'area') < 4))
			{
				showAccessDenied($back);
				exit();
			}
		}
	}
}
$msg ='';

// Ajout ou modification d'un domaine
if ((!empty($id_area)) || (isset($add_area)))
{
	if (isset($change_area))
	{
	// Affectation e un site : si aucun site n'a ete affecte
		if ((Settings::get("module_multisite") == "Oui") && ($id_site == -1))
		{
	  		// On affiche un message d'avertissement
			?>
			<script type="text/javascript">
				alert("<?php echo get_vocab('choose_a_site'); ?>");
			</script>
			<?php
	  		// On empeche le retour e la page admin_room
			unset($change_done);
		}
		else
		{
	  		// Un site a ete affecte, on peut continuer
	 		// la valeur par defaut ne peut etre inferiure au plus petit bloc reservable
			if ($_POST['duree_par_defaut_reservation_area'] < $_POST['resolution_area'])
				$_POST['duree_par_defaut_reservation_area'] = $_POST['resolution_area'];
			// la valeur par defaut doit etre un multiple du plus petit bloc reservable
			$_POST['duree_par_defaut_reservation_area'] = intval($_POST['duree_par_defaut_reservation_area'] / $_POST['resolution_area']) * $_POST['resolution_area'];
	  		// Duree maximale de reservation
			if (isset($_POST['enable_periods']))
			{
				if ($_POST['enable_periods'] == 'y')
					$duree_max_resa_area = $duree_max_resa_area2 * 1440;
				else
				{
					$duree_max_resa_area = $duree_max_resa_area1;
					if ($duree_max_resa_area >= 0)
						$duree_max_resa_area = max ($duree_max_resa_area, $_POST['resolution_area'] / 60, $_POST['duree_par_defaut_reservation_area'] / 60);
				}
				settype($duree_max_resa_area, "integer");
				if ($duree_max_resa_area < 0)
					$duree_max_resa_area = -1;
			}

			$display_days = "";
			for ($i = 0; $i < 7; $i++)
			{
				if (isset($_POST['display_day'][$i]))
					$display_days .= "y";
				else
					$display_days .= "n";
			}
			if ($display_days != "nnnnnnn")
			{
				while (!isset($_POST['display_day'][$_POST['weekstarts_area']]))
					$_POST['weekstarts_area']++;
			}
			if ($_POST['morningstarts_area'] > $_POST['eveningends_area'])
				$_POST['eveningends_area'] = $_POST['morningstarts_area'];
			if ($access)
				$access='r';
			else
				$access='a';
			if ((isset($id_area)) && !((isset($action) && ($action == "duplique_area"))))
			{
				// s'il y a changement de type de creneaux, on efface les reservations du domaines
				$old_enable_periods = grr_sql_query1("select enable_periods from ".TABLE_PREFIX."_area WHERE id='".$id_area."'");
				if ($old_enable_periods != $_POST['enable_periods'])
				{
					$del = grr_sql_query("DELETE ".TABLE_PREFIX."_entry FROM ".TABLE_PREFIX."_entry, ".TABLE_PREFIX."_room, ".TABLE_PREFIX."_area WHERE
						".TABLE_PREFIX."_entry.room_id = ".TABLE_PREFIX."_room.id and
						".TABLE_PREFIX."_room.area_id = ".TABLE_PREFIX."_area.id and
						".TABLE_PREFIX."_area.id = '".$id_area."'");
					$del = grr_sql_query("DELETE ".TABLE_PREFIX."_repeat FROM ".TABLE_PREFIX."_repeat, ".TABLE_PREFIX."_room, ".TABLE_PREFIX."_area WHERE
						".TABLE_PREFIX."_repeat.room_id = ".TABLE_PREFIX."_room.id and
						".TABLE_PREFIX."_room.area_id = ".TABLE_PREFIX."_area.id and
						".TABLE_PREFIX."_area.id = '".$id_area."'");
				}
				$sql = "UPDATE ".TABLE_PREFIX."_area SET
				area_name='".protect_data_sql($area_name)."',
				access='".protect_data_sql($access)."',
				order_display='".protect_data_sql($area_order)."',
				ip_adr='".protect_data_sql($ip_adr)."',
				calendar_default_values = 'n',
				duree_max_resa_area = '".protect_data_sql($duree_max_resa_area)."',
				morningstarts_area = '".protect_data_sql($_POST['morningstarts_area'])."',
				eveningends_area = '".protect_data_sql($_POST['eveningends_area'])."',
				resolution_area = '".protect_data_sql($_POST['resolution_area'])."',
				duree_par_defaut_reservation_area = '".protect_data_sql($_POST['duree_par_defaut_reservation_area'])."',
				eveningends_minutes_area = '".protect_data_sql($_POST['eveningends_minutes_area'])."',
				weekstarts_area = '".protect_data_sql($_POST['weekstarts_area'])."',
				enable_periods = '".protect_data_sql($_POST['enable_periods'])."',
				twentyfourhour_format_area = '".protect_data_sql($_POST['twentyfourhour_format_area'])."',
				max_booking='".$max_booking."',
				display_days = '".$display_days."'
				WHERE id=$id_area";
				if (grr_sql_command($sql) < 0)
				{
					fatal_error(0, get_vocab('update_area_failed') . grr_sql_error());
					$ok = 'no';
				}
			}
			else
			{
				$sql = "INSERT INTO ".TABLE_PREFIX."_area SET
				area_name='".protect_data_sql($area_name)."',
				access='".protect_data_sql($access)."',
				order_display='".protect_data_sql($area_order)."',
				ip_adr='".protect_data_sql($ip_adr)."',
				calendar_default_values = 'n',
				duree_max_resa_area = '".protect_data_sql($duree_max_resa_area)."',
				morningstarts_area = '".protect_data_sql($_POST['morningstarts_area'])."',
				eveningends_area = '".protect_data_sql($_POST['eveningends_area'])."',
				resolution_area = '".protect_data_sql($_POST['resolution_area'])."',
				duree_par_defaut_reservation_area = '".protect_data_sql($_POST['duree_par_defaut_reservation_area'])."',
				eveningends_minutes_area = '".protect_data_sql($_POST['eveningends_minutes_area'])."',
				weekstarts_area = '".protect_data_sql($_POST['weekstarts_area'])."',
				enable_periods = '".protect_data_sql($_POST['enable_periods'])."',
				twentyfourhour_format_area = '".protect_data_sql($_POST['twentyfourhour_format_area'])."',
				display_days = '".$display_days."',
				max_booking='".$max_booking."',
				id_type_par_defaut = '-1'
				";
				if (grr_sql_command($sql) < 0)
					fatal_error(1, "<p>" . grr_sql_error());
				$id_area = grr_sql_insert_id();
			}
	  		// Affectation e un site
			if (Settings::get("module_multisite") == "Oui")
			{
				$sql = "delete from ".TABLE_PREFIX."_j_site_area where id_area='".$id_area."'";
				if (grr_sql_command($sql) < 0)
					fatal_error(0, "<p>".grr_sql_error()."</p>");
				$sql = "INSERT INTO ".TABLE_PREFIX."_j_site_area SET id_site='".$id_site."', id_area='".$id_area."'";
				if (grr_sql_command($sql) < 0)
					fatal_error(0, "<p>".grr_sql_error()."</p>");
			}
			#Si area_name est vide on le change maintenant que l'on a l'id area
			if ($area_name == '')
			{
				$area_name = get_vocab("match_area")." ".$id_area;
				grr_sql_command("UPDATE ".TABLE_PREFIX."_area SET area_name='".protect_data_sql($area_name)."' WHERE id=$id_area");
			}
		  	#on cree ou recree ".TABLE_PREFIX."_area_periodes pour le domaine
			if (protect_data_sql($_POST['enable_periods']) == 'y')
			{
				if (isset($number_periodes))
				{
					settype($number_periodes, "integer");
					if ($number_periodes < 1)
						$number_periodes = 1;
					$del_periode = grr_sql_query("delete from ".TABLE_PREFIX."_area_periodes where id_area='".$id_area."'");
		  			#on efface le modele par defaut avec area=0
					$del_periode = grr_sql_query("delete from ".TABLE_PREFIX."_area_periodes where id_area='0'");
					$i = 0;
					$num = 0;
					while ($i < $number_periodes)
					{
						$temp = "periode_".$i;
						if (isset($_POST[$temp]))
						{
							$nom_periode = corriger_caracteres($_POST[$temp]);
							$reg_periode = grr_sql_query("insert into ".TABLE_PREFIX."_area_periodes set
								id_area='".$id_area."',
								num_periode='".$num."',
								nom_periode='".protect_data_sql($nom_periode)."'
								");
			  				#on cree un modele par defaut avec area=0
							$reg_periode = grr_sql_query("insert into ".TABLE_PREFIX."_area_periodes set
								id_area='0',
								num_periode='".$num."',
								nom_periode='".protect_data_sql($nom_periode)."'");
							$num++;
						}
						$i++;
					}
				}
			}
			$msg = get_vocab("message_records");
		}
	}
	if ($access=='a')
	{
		$sql = "DELETE FROM ".TABLE_PREFIX."_j_user_area WHERE id_area='$id_area'";
		if (grr_sql_command($sql) < 0)
			fatal_error(0, get_vocab('update_area_failed') . grr_sql_error());
	}
	if ((isset($change_done)) && (!isset($ok)))
	{
		if ($msg != '') {
			$_SESSION['displ_msg'] = 'yes';
			if (strpos($retour_page, ".php?") == "")
				$param = "?msg=".$msg;
			else
				$param = "&msg=".$msg;
		} else
		$param = '';
		Header("Location: ".$retour_page.$param);
		exit();
	}

	affiche_pop_up($msg,"admin");
	$avertissement = get_vocab("avertissement_change_type");
	?>
	<script type="text/javascript">
		function bascule()
		{
			menu_1 = document.getElementById('menu1');
			menu_2 = document.getElementById('menu2');
			if (document.getElementById('main').enable_periods[0].checked)
			{
				menu_1.style.display = "";
				menu_2.style.display = "none";
			}
			if (document.getElementById('main').enable_periods[1].checked)
			{
				menu_1.style.display = "none";
				menu_2.style.display = "";
			}
			alert("<?php echo $avertissement; ?>");
		}

		function aff_creneaux()
		{
			nb_cr = document.getElementById('nb_per');
			if (isNaN(Number(nb_cr.value)))
				nb_cr.value = 1;
			if (nb_cr.value > 50)
				nb_cr.value = 50;
			if (nb_cr.value < 1)
				nb_cr.value = 1;
			for (var i = 1; i <= nb_cr.value; i++)
			{
				document.getElementById('c' + i).style.display = '';
			}
			for (var i; i <= 50; i++)
			{
				document.getElementById('c' + i).style.display = 'none';
			}
			return false;
		}
	</script>
	<?php
	echo "<div class=\"page_sans_col_gauche\">";
	if (isset($id_area))
	{
		$res = grr_sql_query("SELECT * FROM ".TABLE_PREFIX."_area WHERE id=$id_area");
		if (! $res)
			fatal_error(0, get_vocab('error_area') . $id_area . get_vocab('not_found'));
		$row = grr_sql_row_keyed($res, 0);
		grr_sql_free($res);
		if ($action=="duplique_area")
			echo "<h2>".get_vocab("duplique_domaine")."</h2>";
		else
			echo "<h2>".get_vocab("editarea")."</h2>";
		if ($row["calendar_default_values"] == 'y')
		{
			$row["morningstarts_area"] = $morningstarts;
			$row["eveningends_area"] = $eveningends;
			$row["resolution_area"] = $resolution;
			$row["duree_par_defaut_reservation_area"] = $duree_par_defaut_reservation_area;
			$row["duree_max_resa_area"] = $duree_max_resa;
			$row["eveningends_minutes_area"] = $eveningends_minutes;
			$row["weekstarts_area"] = $weekstarts;
			$row["twentyfourhour_format_area"] = $twentyfourhour_format;
			$row["display_days"] = $display_days;
		}
		if ($row["enable_periods"] != 'y')
			$row["enable_periods"] = 'n';
		if (Settings::get("module_multisite") == "Oui")
			$id_site=grr_sql_query1("select id_site from ".TABLE_PREFIX."_j_site_area where id_area='".$id_area."'");
	}
	else
	{
		$row["id"] = '';
		$row["area_name"] = '';
		$row["order_display"]  = '';
		$row["access"] = '';
		$row["ip_adr"] = '';
		$row["morningstarts_area"] = $morningstarts;
		$row["eveningends_area"] = $eveningends;
		$row["resolution_area"] = $resolution;
		$row["duree_par_defaut_reservation_area"] = $resolution;
		$row["duree_max_resa_area"] = $duree_max_resa;
		$row["eveningends_minutes_area"] = $eveningends_minutes;
		$row["weekstarts_area"] = $weekstarts;
		$row["twentyfourhour_format_area"] = $twentyfourhour_format;
		$row["enable_periods"] = 'n';
		$row["display_days"] = "yyyyyyy";
		$row['max_booking'] = '-1';
		echo "<h2>".get_vocab('addarea')."</h2>";
	}
	?>
	<form action="admin_edit_room.php" method="post" id="main">
		<?php
		echo "<div>";
		if (isset($action))
			echo "<input type=\"hidden\" name=\"action\" value=\"duplique_area\" />\n";
		if (isset($retour_page))
			echo "<input type=\"hidden\" name=\"retour_page\" value=\"".$retour_page."\" />";
		if ($row['id'] != '')
			echo "<input type=\"hidden\" name=\"id_area\" value=\"".$row["id"]."\" />";
		if (isset($add_area))
			echo "<input type=\"hidden\" name=\"add_area\" value=\"".$add_area."\" />\n";
		echo "</div>";
		echo "<table border=\"1\" cellspacing=\"1\" cellpadding=\"6\"><tr>";
		// Nom du domaine
		echo "<td>".get_vocab("name").get_vocab("deux_points")."</td>\n";
		echo "<td style=\"width:30%;\"><input type=\"text\" name=\"area_name\" size=\"40\" value=\"".htmlspecialchars($row["area_name"])."\" /></td>\n";
		echo "</tr><tr>\n";
		// Ordre d'affichage du domaine
		echo "<td>".get_vocab("order_display").get_vocab("deux_points")."</td>\n";
		echo "<td><input type=\"text\" name=\"area_order\" size=\"1\" value=\"".htmlspecialchars($row["order_display"])."\" /></td>\n";
		echo "</tr><tr>\n";
		// Acces restreint ou non ?
		echo "<td>".get_vocab("access").get_vocab("deux_points")."</td>\n";
		echo "<td><input type=\"checkbox\" name=\"access\"";
		if ($row["access"] == 'r')
			echo "checked=\"checked\"";
		echo " /></td>\n";
		echo "</tr>";
		// Site
		if (Settings::get("module_multisite") == "Oui")
		{
	  	// Affiche une liste deroulante des sites;
			if (authGetUserLevel(getUserName(), -1, 'area') >= 6)
				$sql = "SELECT id,sitecode,sitename
			FROM ".TABLE_PREFIX."_site
			ORDER BY sitename ASC";
			else
				$sql = "SELECT id,sitecode,sitename
			FROM ".TABLE_PREFIX."_site s,  ".TABLE_PREFIX."_j_useradmin_site u
			WHERE s.id=u.id_site and u.login='".getUserName()."'
			ORDER BY s.sitename ASC";
			$res = grr_sql_query($sql);
			$nb_site = grr_sql_count($res);
			echo "<tr><td>".get_vocab('site').get_vocab('deux_points')."</td>\n";
			if ($nb_site > 1)
			{
				echo "<td><select class=\"form-control\" name=\"id_site\" >\n
				<option value=\"-1\">".get_vocab('choose_a_site')."</option>\n";
				for ($enr = 0; ($row1 = grr_sql_row($res, $enr)); $enr++)
				{
					echo "<option value=\"".$row1[0]."\"";
					if ($id_site == $row1[0])
						echo ' selected="selected"';
					echo '>'.htmlspecialchars($row1[2]);
					echo '</option>'."\n";
				}
				grr_sql_free($res);
				echo "</select></td></tr>";
			}
			else
			{
				// un seul site
				$row1 = grr_sql_row($res, 0);
				echo "<td>".$row1[2]."<input type=\"hidden\" name=\"id_site\" value=\"".$id_site."\" /></td></tr>\n";
			}
		}
		// Adresse IP client :
		if (OPTION_IP_ADR == 1)
		{
			echo "<tr>\n";
			echo "<td>".get_vocab("ip_adr").get_vocab("deux_points")."</td>";
			echo "<td><input class=\"form-control\" type=\"text\" name=\"ip_adr\" value=\"".htmlspecialchars($row["ip_adr"])."\" /></td>\n";
			echo "</tr>\n";
			echo "<tr>\n";
			echo "<td colspan=\"2\">".get_vocab("ip_adr_explain")."</td>\n";
			echo "</tr>\n";
		}
		echo "</table>";
		// Configuration des plages horaires ...
		echo "<h3>".get_vocab("configuration_plages_horaires")."</h3>";
		// Debut de la semaine: 0 pour dimanche, 1 pou lundi, etc.
		echo "<table border=\"1\" cellspacing=\"1\" cellpadding=\"6\">";
		echo "<tr>\n";
		echo "<td>".get_vocab("weekstarts_area").get_vocab("deux_points")."</td>\n";
		echo "<td style=\"width:30%;\"><select class=\"form-control\" name=\"weekstarts_area\" size=\"1\">\n";
		$k = 0;
		while ($k < 7)
		{
			$tmp=mktime(0, 0, 0, 10, 2 + $k, 2005);
			echo "<option value=\"".$k."\" ";
			if ($k == $row['weekstarts_area'])
				echo " selected=\"selected\"";
			echo ">".utf8_strftime("%A", $tmp)."</option>\n";
			$k++;
		}
		echo "</select></td>\n";
		echo "</tr>";
		// Definition des jours de la semaine e afficher sur les plannings et calendriers
		echo "<tr>\n";
		echo "<td>".get_vocab("cocher_jours_a_afficher")."</td>\n";
		echo "<td>\n";
		for ($i = 0; $i < 7; $i++)
		{
			echo "<label><input name=\"display_day[".$i."]\" type=\"checkbox\"";
			if (substr($row["display_days"], $i, 1) == 'y')
				echo " checked=\"checked\"";
			echo " />" . day_name($i) . "</label><br />\n";
		}
		echo "</td>\n";
		echo "</tr></table>";
		echo "<h3>".get_vocab("type_de_creneaux")."</h3>";
		echo "<table>";
		//echo "<p style=\"text-align:left;\"><b>ATTENTION :</b> Les deux types de configuration des creneaux sont incompatibles entre eux : un changement du type de creneaux entraene donc, apres validation, un <b>effacement de toutes les reservations  de ce domaine</b></p>.";
		echo "<tr><td colspan=\"2\"><label><input type=\"radio\" name=\"enable_periods\" value=\"n\" onclick=\"bascule()\" ";
		if ($row["enable_periods"] == 'n')
			echo "checked=\"checked\"";
		echo " />".get_vocab("creneaux_de_reservation_temps")."</label><br />";
		echo "<label><input type=\"radio\" name=\"enable_periods\" value=\"y\" onclick=\"bascule()\" ";
		if ($row["enable_periods"] == 'y')
			echo "checked=\"checked\"";
		echo " />".get_vocab("creneaux_de_reservation_pre_definis")."</label></td></tr></table>";
		//Les creneaux de reservation sont bases sur des intitules pre-definis.
		$sql_periode = grr_sql_query("SELECT num_periode, nom_periode FROM ".TABLE_PREFIX."_area_periodes where id_area='".$id_area."' order by num_periode");
		$num_periodes = grr_sql_count($sql_periode);
		if (!isset($number_periodes))
			if ($num_periodes == 0)
				$number_periodes = 10;
			else
				$number_periodes = $num_periodes;

			if ($row["enable_periods"] == 'y')
				echo "<table id=\"menu2\" border=\"1\" cellspacing=\"1\" cellpadding=\"6\">";
			else
				echo "<table style=\"display:none\" id=\"menu2\" border=\"1\" cellspacing=\"1\" cellpadding=\"6\">";
			echo "<tr><td>".get_vocab("nombre_de_creneaux").get_vocab("deux_points")."</td>";
			echo "<td style=\"width:30%;\"><input type=\"text\" id=\"nb_per\" name=\"number_periodes\" size=\"1\" onkeypress=\"if (event.keyCode==13) return aff_creneaux()\" value=\"$number_periodes\" />
			<a href=\"#Per\" onclick=\"javascript:return(aff_creneaux())\">".get_vocab("goto")."</a>\n";
			echo "</td></tr>\n<tr><td colspan=\"2\">";
			$i = 0;
			while ($i < 50)
			{
				$nom_periode = grr_sql_query1("select nom_periode FROM ".TABLE_PREFIX."_area_periodes where id_area='".$id_area."' and num_periode= '".$i."'");
				if ($nom_periode == -1)
					$nom_periode = "";
				echo "<table style=\"display:none\" id=\"c".($i+1)."\"><tr><td>".get_vocab("intitule_creneau").($i+1).get_vocab("deux_points")."</td>";
				echo "<td style=\"width:30%;\"><input type=\"text\" name=\"periode_".$i."\" value=\"".htmlentities($nom_periode)."\" size=\"20\" /></td></tr></table>\n";
				$i++;
			}
			// L'utilisateur ne peut reserver qu'une duree limitee (-1 desactivee), exprimee en jours
			if ($row["duree_max_resa_area"] > 0)
				$nb_jour = max(round($row["duree_max_resa_area"]/1440,0),1);
			else
				$nb_jour = -1;
			echo "</td></tr>\n<tr><td>".get_vocab("duree_max_resa_area2").get_vocab("deux_points");
			echo "\n</td><td><input class=\"form-control\" type=\"text\" name=\"duree_max_resa_area2\" size=\"5\" value=\"".$nb_jour."\" /></td></tr>\n";
			echo "</table>";
			// Cas ou les creneaux de reservations sont bases sur le temps
			if ($row["enable_periods"] == 'n')
				echo "<table id=\"menu1\" border=\"1\" cellspacing=\"1\" cellpadding=\"6\">";
			else
				echo "<table style=\"display:none\" id=\"menu1\" border=\"1\" cellspacing=\"1\" cellpadding=\"6\">";
			// Heure de debut de reservation
			echo "<tr>";
			echo "<td>".get_vocab("morningstarts_area").get_vocab("deux_points")."</td>\n";
			echo "<td style=\"width:30%;\"><select class=\"form-control\" name=\"morningstarts_area\" size=\"1\">\n";
			$k = 0;
			while ($k < 24)
			{
				echo "<option value=\"".$k."\" ";
				if ($k == $row['morningstarts_area']) echo " selected=\"selected\"";
				echo ">".$k."</option>\n";
				$k++;
			}
			echo "</select></td>\n";
			echo "</tr>";
			// Heure de fin de reservation
			echo "<tr>\n";
			echo "<td>".get_vocab("eveningends_area").get_vocab("deux_points")."</td>\n";
			echo "<td><select class=\"form-control\" name=\"eveningends_area\" size=\"1\">\n";
			$k = 0;
			while ($k < 24)
			{
				echo "<option value=\"".$k."\" ";
				if ($k == $row['eveningends_area']) echo " selected=\"selected\"";
				echo ">".$k."</option>\n";
				$k++;
			}
			echo "</select></td>\n";
			echo "</tr>";
			// Minutes e ajouter e l'heure $eveningends pour avoir la fin reelle d'une journee.
			echo "<tr>\n";
			echo "<td>".get_vocab("eveningends_minutes_area").get_vocab("deux_points")."</td>\n";
			echo "<td><input class=\"form-control\" type=\"text\" name=\"eveningends_minutes_area\" size=\"5\" value=\"".htmlspecialchars($row["eveningends_minutes_area"])."\" /></td>\n";
			echo "</tr>";
			// Resolution - quel bloc peut etre reserve, en secondes
			echo "<tr>\n";
			echo "<td>".get_vocab("resolution_area").get_vocab("deux_points")."</td>\n";
			echo "<td><input class=\"form-control\" type=\"text\" name=\"resolution_area\" size=\"5\" value=\"".htmlspecialchars($row["resolution_area"])."\" /></td>\n";
			echo "</tr>";
			// Valeur par defaut de la duree d'une reservation
			echo "<tr>\n";
			echo "<td>".get_vocab("duree_par_defaut_reservation_area").get_vocab("deux_points")."</td>\n";
			echo "<td><input class=\"form-control\" type=\"text\" name=\"duree_par_defaut_reservation_area\" size=\"5\" value=\"".htmlspecialchars($row["duree_par_defaut_reservation_area"])."\" /></td>\n";
			echo "</tr>";
			// Format d'affichage du temps : valeur 0 pour un affichage ee12 heuresee et valeur 1 pour un affichage  ee24 heureee.
			echo "<tr>\n";
			echo "<td>".get_vocab("twentyfourhour_format_area").get_vocab("deux_points")."</td>\n";
			echo "<td>\n";
			echo "<label><input type=\"radio\" name=\"twentyfourhour_format_area\" value=\"0\" ";
			if ($row['twentyfourhour_format_area'] == 0)
				echo " checked=\"checked\"";
			echo " />".get_vocab("twentyfourhour_format_12")."</label>\n<br />";
			echo "<label><input type=\"radio\" name=\"twentyfourhour_format_area\" value=\"1\" ";
			if ($row['twentyfourhour_format_area'] == 1)
				echo " checked=\"checked\"";
			echo " />".get_vocab("twentyfourhour_format_24")."</label>\n";
			echo "</td>\n";
			echo "</tr>\n";
			// L'utilisateur ne peut reserver qu'une duree limitee (-1 desactivee), exprimee en minutes
			echo "<tr>\n<td>".get_vocab("duree_max_resa_area").get_vocab("deux_points");
			echo "</td>\n<td><input class=\"form-control\" type=\"text\" name=\"duree_max_resa_area1\" size=\"5\" value=\"".$row["duree_max_resa_area"]."\" /></td></tr>\n";
			// Nombre max de reservation par domaine
			echo "<tr><td>".get_vocab("max_booking")." -  ".get_vocab("all_rooms_of_area").get_vocab("deux_points");
			echo "</td><td><input class=\"form-control\" type=\"text\" name=\"max_booking\" value=\"".$row['max_booking']."\" /></td>\n";
			echo "</tr></table>";
			Hook::Appel("hookEditArea1");
			echo "<div style=\"text-align:center;\">\n";
			echo "<input class=\"btn btn-primary\" type=\"submit\" name=\"change_area\" value=\"".get_vocab("save")."\" />\n";
			echo "<input class=\"btn btn-primary\" type=\"submit\" name=\"change_done\" value=\"".get_vocab("back")."\" />\n";
			echo "<input class=\"btn btn-primary\" type=\"submit\" name=\"change_area_and_back\" value=\"".get_vocab("save_and_back")."\" />";
			echo "</div></form>";
			echo "<script type=\"text/javascript\">";
			echo "aff_creneaux();";
			echo "</script>";
			echo "</div>";
		}
?>