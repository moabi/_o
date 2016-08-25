<div class="wrap">
<h2>Online-booking - Help</h2>
	<?php
	$obp = new Online_Booking_Public('online-booking','1.0');
	?>
<p>
	Pour modifier les events des users :
	<a target="_blank" href="<?php echo site_url().'/'.BOOKING_URL.'/?mod=on'; ?>">
		Modifier
	</a>
</p>


<h3>UserRoles</h3>
<ul>
<li>Entreprise</li>
<li>Particulier</li>
<li>Partner : right to upload files</li>
</ul>
<h3>Shortcodes</h3>
<ul>
	<li>[frontform] : Un formulaire avec les champs principaux, dirige vers la page de l'application</li>
	<li>[ob-sejours] : Liste des sejours (5)</li>
	<li>[ob-activities] : Liste des activités (8)</li>
</ul>

<h3>Dependancies</h3>

<ul>
	<li>JS : Slick Caroussel,noty, js-cookie,select2,jquery easing,Sticky-kit </li>
	<li>WP Plugins : ACF, Contact Form 7, User Pro</li>
</ul>


<hr />

<h3>Contact Form</h3>

<pre>
	<div class="cform devis " style="max-width:810px">
<h3>Créez votre évènement ou votre circuit</h3>
<div class="pure-g">
<div class="pure-u-1-2">
<label>Nombre de participants</label>
[number participants-864 min:1 max:500 "2"]
</div>
<div class="pure-u-1-2">
<label>Connaissez-vous les dates de votre évènement ?</label>
[radio radio-448 label_first use_label_element "Oui" "Non"]
</div>
</div>

<div class="pure-g">
<div class="pure-u-1-2">
<label>Date d'arrivée</label>
[date date-127]
</div>
<div class="pure-u-1-2">
<label>Date de fin</label>
[date date-back-127]
</div>
</div>


<div class="pure-g">
<div class="pure-u-1">
<label>Le lieu</label>
[select lieu-428]
</div></div>


<div class="pure-g">
<div class="pure-u-1">
<label>Description</label>
[textarea textarea-413 placeholder "Indiquez nous votre projet tel que vous l'imaginez"]
</div></div>

<hr />

<h3>Accompagnement</h3>

<div class="pure-g">
<div class="pure-u-1">
[checkbox checkbox-377 label_first use_label_element "Nos équipes" "Un guide" "Feuille de route"]
</div>
</div>

<h3>Votre budget par participant</h3>

<div class="pure-g">
<div class="pure-u-1">
<label>Budget par participant, hors transport et hébergement :</label>
[range number-550 min:50 max:600]
</div>
</div>

<h3>Ou en êtes vous dans ce projet ?</h3>
[checkbox checkbox-573 label_first use_label_element "J'ai besoin d'informations" "J'ai mes idées"]

<h3>Vos informations pour être mis en relation avec un conseiller</h3>

<div class="pure-g">
<div class="pure-u-1-2">
[text* prenom placeholder "Votre prénom"]
</div>
<div class="pure-u-1-2">
[text* your-name placeholder "Votre nom"]
</div>
</div>

<div class="pure-g">
<div class="pure-u-1-2">
[text text-733 placeholder "Société"]
</div>
<div class="pure-u-1-2">
[email*  email placeholder "Votre email"] 
</div>
</div>

<div class="pure-g">
<div class="pure-u-1-2">
[tel tel-95 placeholder "Téléphone"]
</div>
</div>

<p>[submit "Envoyer votre projet"]</p>
</div>

</pre>

</div>