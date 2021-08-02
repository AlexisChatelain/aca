<?php 

	/////////////VARIABLES-A-MODIFIER-FACILEMENT/////////////////////////////
	$decalage=1;
	$largeur=40;
	$offset_hauteur=$largeur/2;
	$cases_heures=3;
	$nb_lignes_bandeau=3;
	$couloirs=4;/////// ICI, A MODIFIER (PASSER A 6) POUR LA NOUVELLE PISCINE 
	$nb_jours=5;
	$nb_lignes_entete=2;
	$nb_lignes_jours=1;
	$min_horaire=8; // pour 8h de 8h/9h
	$taille_tableau=200;
	$taille_note=248;
	$nb_horaires=14;
	$offset_top_note=300;
	$duree=1;

	$g_min=$cases_heures*$largeur;
	$h_min=($nb_lignes_bandeau+$nb_lignes_entete+$nb_lignes_jours)*$largeur+$offset_hauteur;
	$g_max=($cases_heures+$nb_jours*$couloirs-1)*$largeur;
	$h_max=($nb_lignes_bandeau+$nb_lignes_entete+$nb_lignes_jours+$nb_horaires-1)*$largeur+$offset_hauteur;
	$offset_tableau=10;
	$offset_note=20;
	$taille_planning =($cases_heures+$nb_jours*$couloirs+$cases_heures)*$largeur;
	
	require_once("{$_SERVER['DOCUMENT_ROOT']}/aca/ConnexionClass.php");
	$login = new Login();
	if(!$login->isAdmin())
		$tailleadmin= $taille_planning + $offset_tableau + $taille_tableau ; 
	else 
		$tailleadmin= $taille_planning + $offset_tableau +$taille_tableau + $offset_note +$taille_note;
	$lmoinsd= $largeur-$decalage;
	$tpplusot=$taille_planning+$offset_tableau;
	$dernieresomme=$taille_planning + $offset_tableau +$taille_tableau + $offset_note;
		
/////////////FIN-VARIABLES-A-MODIFIER-FACILEMENT//////////////////////////

$title = "Planning";
$style_with_php=
"
<style>
.square{
  position:absolute;
  margin-top: -{$offset_hauteur}px;
  height: {$lmoinsd}px;
  width:{$lmoinsd}px}
  
.square1{background-color:yellow}
.square2{background-color:pink}
.square3{background-color:MediumVioletRed}
.square4{background-color:CornflowerBlue}
.square5{background-color:Orange}
.square6{background-color:PowderBlue}
.square7{background-color:SkyBlue}
.square8{background-color:Teal}
.square9{background-color:lightgray}
.square10{background-color:gray}
.square11{background-color:red}
.square12{background-color:cyan}
.square13{background-color:green}
.square14{background-color:purple}
.square15{background-color:blue}

#tableau table{
background-color: white;
border-collapse: collapse;
text-align:center;
}
#tableau .casevalidationhtml , #tableau #validationhtml{
padding:0px;
margin:0;
height:0;
width:0;
border: none;
}
html{
min-width: {$tailleadmin}px
}
#tableau table th,tr{
border: solid 1pt black;
}

#tableau .l{
border-left: solid 1pt black;
}
#tableau table th{
height:{$largeur}px;
width:".$cases_heures*$largeur."px;
}
#tableau table td{
border: dotted 1pt lightgray;
height:{$largeur}px;
width:{$largeur}px;
}

#entete{
height:".$nb_lignes_entete*$largeur."px;
background-image:radial-gradient(white, Aqua);
}
#tableau{
position: absolute;
top:".$nb_lignes_bandeau*$largeur."px;
}
#div_groupe th,  #div_groupe td{
height:{$largeur}px;
}
#div_groupe table{
background-color: white;
}
#div_groupe{
margin-left: {$tpplusot}px;
width: {$taille_tableau}px;
}
#note{
margin-left:{$dernieresomme}px;
position:absolute;
width: {$taille_note}px;
top:{$offset_top_note}px;
}

</style>";
require('fragments/head.php');
?>
<body>
<div class="content">

<?php require('fragments/nav.php');
	  require('fragments/nav_img.php'); 
?>

<div id="tableau">
<table>

<?php 
//bloc obligatoire validation html
echo "<tr id='validationhtml'>";
for ($unevariable=0;$unevariable<$cases_heures+$couloirs*$nb_jours+$cases_heures;$unevariable++) //
	echo "<th class='casevalidationhtml'></th>"; 
echo "</tr>";
?>

<tr id="entete">
	<th colspan=<?php echo $cases_heures+$couloirs*$nb_jours+$cases_heures; ?> >Planning des créneaux de l'AQUATIQUE CLUB AMBOISIEN
																			<br> Sous réserve de changement de dernière minute 	</th>
</tr>
<tr>
	<th colspan=<?php echo $cases_heures; ?> ></th>
	<th colspan=<?php echo $couloirs; ?> >Mardi</th>
	<th colspan=<?php echo $couloirs; ?> >Mercredi</th>
	<th colspan=<?php echo $couloirs; ?> >Jeudi</th>
	<th colspan=<?php echo $couloirs; ?> >Vendredi</th>
	<th colspan=<?php echo $couloirs; ?> >Samedi</th>
	<th colspan=<?php echo $cases_heures; ?>></th>
</tr>

<?php 		
for ($i=8;$i<22;$i++){
	if ($i==20)
		$x="20h/20h55";
	else if ($i==21)
		$x="20h50/21h45";
	else
		$x=$i."h/". strval($i+1) ."h";
		$fin_tableau="<tr><th colspan=".$cases_heures.">".$x."</th>";
		for ($j=0;$j<$nb_jours;$j++){
			$fin_tableau=$fin_tableau."<td class='l'>";
			for ($k=0;$k<$couloirs-1;$k++){
				$fin_tableau=$fin_tableau."</td><td>";
			}
		}		
	$fin_tableau=$fin_tableau."<th colspan=".$cases_heures.">".$x."</th></tr>";
	echo $fin_tableau;
}

?>


</table>
</div>  
<div id="div_groupe">
<table id="tableau_groupe">
<tr><th>Créneau</th><th>Groupe</th></tr>


<?php
if($login->isAdmin())
	$mouvement= " onmousedown='on_mouse_down_square(event,this.id)' ";
else
	$mouvement="";
$groupe=array();
require_once("{$_SERVER['DOCUMENT_ROOT']}/aca/config/database.php");
$result= $db->query("SELECT nom FROM categorie");
while ($row = $result->fetch_object())
	array_push($groupe,strval($row->nom));
for ($i=0;$i<count($groupe);$i++){
	echo "
	<tr><td class='groupe_square groupe_square".strval($i+1)."' id='container_square".strval($i+1)."'><div class='square square".strval($i+1)."' id='square".strval($i+1)."' ".$mouvement." ></div>
	</td><td>".$groupe[$i]."</td></tr>";
}
$nb=count($groupe)+1;
echo "</table>
</div>";
if($login->isAdmin())
	echo "<form id='form' action='scripts/planning.php' method='post'> 
	<div id='note'>
	<input type='button' value='Activer/désactiver le menu contextuel' onclick='if (bool==false) bool=true; else bool=false;' />	
	<br><br>Note pour les admins :<br>
			- Possibilité de prendre une case colorée du petit tableau avec le clic gauche et de l'emmener dans le planning.<br>
			- Possibilité de positionner des créneaux toutes les 30 min.<br>
			- Possibilité de supprimer un créneau avec un simple clic droit.<br>
			- Obligation d'appuyer sur 'Valider' pour sauvegarder les modifications<br>
		<br><br>
		<input type='submit' name='Valider' id='Valider' value='Valider' />	
		</div></form>";
	
require_once("{$_SERVER['DOCUMENT_ROOT']}/aca/config/database.php");
$result=$db->query("SELECT jour, debut, couloir, id_categorie FROM cours");
while($row=$result->fetch_assoc()){
	switch($row["jour"]){
		case "mardi":
		$row["jour"]=0;
		break;
		case "mercredi":
		$row["jour"]=1;
		break;
		case "jeudi":
		$row["jour"]=2;
		break;
		case "vendredi":
		$row["jour"]=3;
		break;
		case "samedi":
		$row["jour"]=4;
		break;
	}
	
	$offset=0;
	$heure=intval(substr($row["debut"], 0, 2));
	$minute=intval(substr($row["debut"], 3, 2));
	if ($minute==50)
		$heure=21;
	else if ($minute==30)
		$offset=$offset_hauteur;

	$gauche=$g_min+(($row["jour"])*($couloirs))*$largeur+($row["couloir"]-1)*$largeur+$decalage;
	$hauteur=$h_min+($heure-$min_horaire)*$largeur+$offset+$decalage;
	$msg="";
	if ($row["jour"]==2 && $heure==18 ) 
		$msg="ppg";		
	if ($row["jour"]==4 && $heure==9 && $row["id_categorie"]==1)
		$msg="prg";
	if ($row["jour"]==4 && $heure==10 && $row["id_categorie"]==1)
		$msg="bassin";
	if ($row["jour"]==4 && $heure==11 && $row["id_categorie"]==1)
		$msg="50m";
	if ($row["jour"]==4 && $heure==17)
		$msg="triathlètes";
	echo "<div class='square square".$row["id_categorie"]."' id='square".$nb."'".$mouvement." style='left: ".$gauche."px; top: ".$hauteur."px;' >".$msg."</div>";
	$nb+=1;
}
echo "<input type='hidden' id='nb' value=".$nb." />";
$result->free();
$db->close();
?>

<script>
var mouse_down = false;
var id_square = ""; 
var place=0;
var bool=false;

var decalage=<?php echo $decalage; ?>;
var largeur=<?php echo $largeur; ?>;
var offset_hauteur=<?php echo $offset_hauteur; ?>;
var cases_heures=<?php echo $cases_heures; ?>;
var nb_lignes_bandeau=<?php echo $nb_lignes_bandeau; ?>;
var couloirs=<?php echo $couloirs; ?>;
var nb_jours=<?php echo $nb_jours; ?>;
var nb_lignes_entete=<?php echo $nb_lignes_entete; ?>;
var nb_lignes_jours=<?php echo $nb_lignes_jours; ?>;
var min_horaire=<?php echo $min_horaire; ?>;
var nb_horaires=<?php echo $nb_horaires; ?>;
var duree=<?php echo $duree; ?>;
var g_min=<?php echo $g_min; ?>;
var h_min=<?php echo $h_min; ?>;
var g_max=<?php echo $g_max; ?>;
var h_max=<?php echo $h_max; ?>;

if(document.getElementById("Valider")){
	Valider.onmousedown=function(){
		var tout = document.getElementsByClassName('square');
		for (i=0;i<tout.length;i++){
			if (tout[i].style.left!="" && tout[i].style.top!=""){
			gauche=parseInt(tout[i].style.left)-decalage;
			hauteur=parseInt(tout[i].style.top)-decalage;		
			if (gauche<=g_max && gauche>=g_min && hauteur>=h_min && hauteur<=h_max){
				var jour=parseInt((gauche-g_min)/(couloirs*largeur))+2;
				var debut=(hauteur-h_min)/largeur+min_horaire;			
				var couloir=((gauche-g_min)%(couloirs*largeur))/largeur+1;
				var id_categorie = parseInt(tout[i].className.replace("square square", ""));
				input_form("jour[]",jour);
				input_form("debut[]",debut);
				input_form("duree[]",duree);
				input_form("couloir[]",couloir);
				input_form("id_categorie[]",id_categorie);}		
			}
		}
	}
}
function input_form(var_name,var_value){
	var container_form = document.getElementById('form');
	var input = document.createElement("input");
	input.type= "hidden";		
	input.name=var_name;
	input.value=var_value;
	container_form.appendChild(input);
}

function on_mouse_down_square(event,id) {
	el=document.getElementById(id);
	if (event.button== 2){
	el.remove();
	}else{
	 mouse_down=true; 
	 square = "#"+id;
	 id_square = id;}
	 nouveau(el);
}

function nouveau(el){
	var container = document.getElementsByClassName('groupe_square'+el.className.substr(13)); // 13 pour nombre de caractères dans "square square"
	for (i=0;i<container.length;i++){	
		var div = document.createElement("div");
		div.className= el.className;		
		div.id="square"+parseInt(nb.value);
		div.setAttribute("onmousedown", "on_mouse_down_square(event,this.id)");
		container[i].appendChild(div);
		nb.value=parseInt(parseInt(nb.value)+1);
	}
}
document.oncontextmenu = function() {return bool;}


<?php 
if($login->isAdmin())
	echo "document.onmousemove = on_mouse_move;
		  document.onmouseup = on_mouse_up;
		  
	function on_mouse_up(event){
	 mouse_down=false;
	 if (typeof  document.getElementById(id_square) != 'undefined') {
	 hauteur= document.getElementById(id_square).offsetTop/largeur;
	 if (Math.round(hauteur)==parseInt(hauteur))
	 document.getElementById(id_square).style.top=parseInt(hauteur)*largeur+offset_hauteur+decalage+'px';
	 else
	 document.getElementById(id_square).style.top=parseInt(hauteur)*largeur+largeur+decalage+'px';
	 gauche= parseInt(document.getElementById(id_square).offsetLeft/largeur);
	 document.getElementById(id_square).style.left=gauche*largeur+decalage+'px'
	}
	}
	function on_mouse_move(event) {
  if (mouse_down === true) {
    document.querySelector(square).style.left = event.clientX-offset_hauteur+'px';
    document.querySelector(square).style.top = event.clientY+'px';}
	}";
?>



</script>


  </div>  
 <?php require('fragments/bas.php'); ?>

 </body>
</html>