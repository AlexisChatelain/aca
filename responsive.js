function openNav(){
	/*Script qui gère l'affichage du menu lorsque l'utilisateur est sur mobile
	ou sur un petit écran, il ajoute une classe à la balise body*/
	if(document.body.className == "nav-active")
		document.body.className = ""
	else
		document.body.className = "nav-active"
}