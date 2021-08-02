<?php
	require_once("ConnexionClass.php");
	$login = new Login();
	//Pour le calendrier, utilisation d'une librairie exterieure pour avoir un meilleur
	//visuel et le rendre dynamique avec JavaScript

?>
<!DOCTYPE html>
<html lang="fr">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Calendrier</title>
	<link rel="stylesheet" href="/aca/css/style.css">
	<link rel="icon" type="image/x-icon" href="/aca/images/favicon.ico" />
	<link href='/aca/css/calendrier.css' rel='stylesheet' />
	<link href='/aca/lib/calendar/core/main.min.css' rel='stylesheet' />
	<link href='/aca/lib/calendar/daygrid/main.min.css' rel='stylesheet' />
	<link href='/aca/lib/calendar/timegrid/main.min.css' rel='stylesheet' />
	<link href='/aca/lib/calendar/list/main.min.css' rel='stylesheet' />
	<script src='/aca/lib/calendar/core/main.js'></script>
	<script src='/aca/lib/calendar/interaction/main.min.js'></script>
	<script src='/aca/lib/calendar/daygrid/main.min.js'></script>
	<script src='/aca/lib/calendar/timegrid/main.min.js'></script>
	<script src='/aca/lib/calendar/list/main.min.js'></script>
	<script src='/aca/lib/calendar/core/locales/fr.js'></script>
	<script src="/aca/responsive.js"></script>

	<script>
		function rgb2hex(rgb) {
			rgb = rgb.match(/^rgb\((\d+),\s*(\d+),\s*(\d+)\)$/);
			function hex(x) {
				return ("0" + parseInt(x).toString(16)).slice(-2);
			}
			return "#" + hex(rgb[1]) + hex(rgb[2]) + hex(rgb[3]);
		}
		
		document.addEventListener('DOMContentLoaded', function() {
		var Calendar = FullCalendar.Calendar;
		var Draggable = FullCalendarInteraction.Draggable
		
		<?php if($login->isAdmin()){ ?>

		var containerEl = document.getElementById('external-events-list');
		new Draggable(containerEl, {
		  itemSelector: '.external-event',
		  eventData: function(eventEl) {
			return {
			  title: eventEl.innerText.trim(),
			  color: window.getComputedStyle(eventEl).backgroundColor,
			  href: eventEl.href
			}
		  }
		});
		
		var colorPicker = document.getElementById('color-picker');
		colorPicker.onchange = function(){
			var addEventButton = document.getElementById('add-new-event');
			
			addEventButton.style.backgroundColor = colorPicker.value;
			addEventButton.style.borderColor = colorPicker.value;
		};
		
		var addEventButton = document.getElementById('add-new-event');
		addEventButton.onclick = function(){
			var val = document.getElementById('new-event').value;
			if (val.length == 0){
				return;
			}
			
			var event = document.createElement("div");
			event.style.backgroundColor = document.getElementById('color-picker').value;
			event.style.borderColor = "fff";
			event.id="div_cree";
			event.className += ' external-event';
			parent = document.getElementById("external-events-list");
			parent.insertBefore(event,parent.childNodes[0]);
			var div=document.getElementById('div_cree');
			var link = document.createElement("a");
			link.href = document.getElementById('link-event').value;
			link.target="_blank";
			link.innerText = document.getElementById('new-event').value;
			div.removeAttribute("id");
			div.appendChild(link);		
		};
		<?php } ?>

		var calendarEl = document.getElementById('calendar');
		calendar = new Calendar(calendarEl, {
		  eventSources: [
			{
			  url: '/aca/scripts/json.php', 
			}
		  ],
		  height: 600,
		  locale: 'fr',
		  plugins: [ 'interaction', 'dayGrid', 'timeGrid' ],
		  header: {
			left: 'prev,next today',
			center: 'title',
			right: 'dayGridMonth,timeGridWeek,timeGridDay'
		  },
		  editable: <?= isset($_SESSION["admin"]) && $_SESSION["admin"] == 1 ? 'true' : 'false' ?>,
		  droppable: <?= isset($_SESSION["admin"]) && $_SESSION["admin"] == 1 ? 'true' : 'false' ?>,
		  drop: function(arg) {
			if (document.getElementById('drop-checkbox').checked) {
			  arg.draggedEl.parentNode.removeChild(arg.draggedEl);
			}
		  }<?php if (isset($_SESSION["admin"]) && $_SESSION["admin"] == 1) {?>,
		  eventClick: function(arg) {
			if (confirm("Supprimer l'événement ?")) {
			  var xmlhttp = new XMLHttpRequest();
			  xmlhttp.open("GET", "/aca/scripts/delete_event.php/?id="+arg.event.id, true);
			  xmlhttp.send();
			  arg.event.remove()			
			}
		  }<?php }?>
		});
		calendar.render();

		});

		function saveEvents(){
			var events= new Array();
			events = calendar.getEvents();
			var n = 0;
			events.forEach(event => {
				n += 1;
				var xmlhttp = new XMLHttpRequest();
				id = "<?= isset($_SESSION['id']) ? $_SESSION['id'] : 'null';?>"
				if (id == 'null'){
					return;
				}
				var color = event.backgroundColor;
				if (!color.startsWith('#')){
					color = rgb2hex(color);
				}
				var startDate = new Date(event.start),
					sy = startDate.getFullYear(),
					sm = startDate.getMonth(),
					sd = startDate.getDate(),
					sad = event.allDay
				if(sad && event.end === null){

					xmlhttp.open("GET", "/aca/scripts/save_event.php/?id="+event.id+"&t=" + encodeURIComponent(event.title) + '&c='+encodeURIComponent(id)+'&r=' + encodeURIComponent(color) + "&sy="+sy+"&sm="+sm+"&sd="+sd+"&allday="+sad, true);
				}else if(sad && event.end !== null){
					var endDate = new Date(event.end),
						ey = endDate.getFullYear(),
						em = endDate.getMonth(),
						ed = endDate.getDate()

					xmlhttp.open("GET", "/aca/scripts/save_event.php/?id="+event.id+"&t=" + encodeURIComponent(event.title) + '&c='+encodeURIComponent(id)+'&r=' + encodeURIComponent(color) + "&sy="+sy+"&sm="+sm+"&sd="+sd+"&allday="+sad+"&ey="+ey+"&em="+em+"&ed="+ed, true);
				}else if(!sad && event.end === null){
					var sh = startDate.getHours(),
						smn = startDate.getMinutes()
					xmlhttp.open("GET", "/aca/scripts/save_event.php/?id="+event.id+"&t=" + encodeURIComponent(event.title) + '&c='+encodeURIComponent(id)+'&r=' + encodeURIComponent(color) +"&sy="+sy+"&sm="+sm+"&sd="+sd+"&sh="+sh+"&smn="+smn, true);
				}else{
					var sh = startDate.getHours(),
						smn = startDate.getMinutes()
					var endDate = new Date(event.end),
						ey = endDate.getFullYear(),
						em = endDate.getMonth(),
						ed = endDate.getDate(),
						eh = endDate.getHours(),
						emn = endDate.getMinutes()
					xmlhttp.open("GET", "/aca/scripts/save_event.php/?id="+event.id+"&t=" + encodeURIComponent(event.title) + '&c='+encodeURIComponent(id)+'&r=' + encodeURIComponent(color) +"&sy="+sy+"&sm="+sm+"&sd="+sd+"&sh="+sh+"&smn="+smn+"&ey="+ey+"&em="+em+"&ed="+ed+"&eh="+eh+"&emn="+emn, true);
				}
				xmlhttp.send();
			});
			alert(n + " événements enregistré !")
		}
	</script>
</head>

<body>
	<div class="content">
	<?php require('fragments/nav.php'); ?>
	<?php require('fragments/nav_img.php'); ?>
	<h1>Calendrier</h1>
	
	<div class="row">
	<?php if($login->isAdmin()){ ?>
	
	<div class="col-md-3" id="modif-calendar">
		<div class="box" id="external-events">
			<div class="box-header">
			  <h4 class="box-title">Evenements glissables</h4>
			</div>
			<div class="box-body">
			  <div id="external-events-list">
				<div class="external-event bg-red">Compétition régionale</div>
				<div class="external-event bg-aqua">Compétition départementale</div>
				<div class="external-event bg-yellow">Evénement club</div>
				<div class="checkbox" id="drop-remove">
				  <label>
					<input type="checkbox" id="drop-checkbox">
					Supprimer après mise en place
				  </label>
				</div>
			  </div>
			</div>
		</div>

		<div class="box box-solid">
			<div class="box-header with-border">
			  <h3 class="box-title">Créer un événement</h3>
			</div>
			<div class="box-body">
				<label for="color-picker">Couleur:	</label>
				<input type="color" id="color-picker" value="#EEBB01">
				<div class="input-group">
					<input id="new-event" type="text" class="form-control" maxlength="26" placeholder="Nom de l'événement..">
					<input id="link-event" type="text" class="form-control" placeholder="Lien de l'événement.. (facultatif)" >
					
					<div class="input-group-btn">
					  <button id="add-new-event" type="button" class="btn btn-primary">Ajouter</button>
					</div>
				</div>
			</div>
		</div>	
		<button type="button" id="save-events" onclick="saveEvents()" class="btn btn-primary">Enregistrer</button>
	</div>
	<?php } ?>

	<div class="col-md-<?= $login->isAdmin() ? 9 : 12 ?>">
		<div class="box calendrier">
			<div class="box-body no-padding">
				<div id='calendar'></div>
			</div>
		</div>
	</div>
    <div style='clear:both'></div>

  </div>
  	</div>

	<?php require('fragments/bas.php'); ?>
 
</body>
</html>	