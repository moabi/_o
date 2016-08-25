/*online-booking*/
var $ = jQuery,
	$ajxPrefix = (window.location.hostname === 'localhost') ? '/onlyoo': '';
	ajaxUrl = $ajxPrefix+'/wp-admin/admin-ajax.php';
var bookingPage = '/reservation-service/';
var isBookingTpl = $('#booking-wrapper').length;
var daysSelector = $('#daysSelector'),
	sliderRange = $("#slider-range"),
	tripNameInput = $('#tripName'),
		lieuInput = $("#lieu"),
		themeInput = $("#theme");
var minBudget = sliderRange.data('min');
var maxBudget = sliderRange.data('max');
var maxDefinedDaysOption = $('#days-modifier').data('max');
var maxDefinedDays = parseInt( maxDefinedDaysOption,10);
var reservation = {
	user 	  : '',
	name	  : '',
	theme 	  : '',
	lieu      : '',
	sejour    : '',
	departure : '',
	arrival   : '',
	days      : '',
	participants : '',
	budgetPerMin    : '',
	budgetPerMax    : '',
	globalBudgetMin : '',
	globalBudgetMax : '',
	currentBudget   : 0,
	currentDay   : '',
	tripObject   : {}

};

var USERID = $('#user-logged-in-infos').attr("data-id");;


$.noty.defaults = {
	layout: 'bottom',
	theme: 'defaultTheme', // or 'relax'
	type: 'alert',
	text: '', // can be html or string
	dismissQueue: true, // If you want to use queue feature set this true
	template: '<div id="add_success" class="active"><span class="noty_text"></span><div class="noty_close"></div></div>',
	animation: {
		open: {height: 'toggle'}, // or Animate.css class names like: 'animated bounceInLeft'
		close: {height: 'toggle'}, // or Animate.css class names like: 'animated bounceOutLeft'
		easing: 'swing',
		speed: 500 // opening & closing animation speed
	},
	timeout: 1800, // delay for closing event. Set false for sticky notifications
	force: false, // adds notification to the beginning of queue when set to true
	modal: false,
	maxVisible: 2, // you can set max visible notification for dismissQueue true option,
	killer: false, // for close all notifications before show
	closeWith: ['click'], // ['click', 'button', 'hover', 'backdrop'] // backdrop click will close all notifications
	callback: {
		onShow: function() {},
		afterShow: function() {},
		onClose: function() {},
		afterClose: function() {},
		onCloseClick: function() {},
	},
	buttons: false // an array of buttons
};


/**
 * notyAction
 *
 * @param action
 * @param msg
 * @param btnTxt
 * @param btnDecline
 * @param confirmationMsg
 */
function notyAction(action,msg,btnTxt,btnDecline, confirmationMsg){
	var n = noty ({
		layout: 'center',
		modal: true,
		text: msg,
		template: '<div class="noty_message"><span class="noty_text"></span><div class="noty_close"></div></div>',
		closeWith:['button'],
		buttons: [
			{addClass: 'btn-reg btn btn-primary', text: btnTxt, onClick: function($noty) {

				$noty.close();
				doAction = action();
				var n = noty({text: confirmationMsg});

			}
			},
			{addClass: 'btn-reg btn btn-danger', text: btnDecline, onClick: function($noty) {
				$noty.close();
			}
			}
		],
		type: 'confirm'

	});
}


/**
 * remove get param from url
 * used when activity is added with get param
 * @param key string
 * @param sourceURL string
 * @returns {*}
 */
function removeParam(key, sourceURL) {

    var rtn = sourceURL.split("?")[0],
        param,
        params_arr = [],
        queryString = (sourceURL.indexOf("?") !== -1) ? sourceURL.split("?")[1] : "";
    if (queryString !== "") {
        params_arr = queryString.split("&");
        for (var i = params_arr.length - 1; i >= 0; i -= 1) {
            param = params_arr[i].split("=")[0];
            if (param === key) {
                params_arr.splice(i, 1);
            }
        }
        rtn = rtn + "?" + params_arr.join("&");
        window.history.pushState('',document.title,rtn);
    }
    

    return rtn;
}


/**
 * doAjaxRequest
 * wp ajax request
 * retrieve post from filtering
 * 
 * @param theme
 * @param geo
 * @param type
 *
 * @return string
 */
function doAjaxRequest( theme , geo, type, searchTextTerm ){
	//console.log(type);
	jQuery.ajax({
		url: ajaxUrl,
		settings:{
			cache : true
		},
		data:{
			'action':'do_ajax',
			'theme':theme,
			'geo' : geo,
			'type' : type,
			'search': searchTextTerm,
			'count':1
		},
		//JSON can cause issues on Chrome ? use text instead ?
		dataType: 'JSON',
		success:function(data){
			jQuery('#activities-content').empty().append(jQuery('<div>', {
				html : data
			}));
			//once data is loaded
			changingTerms();
			//console.log(data);
		},
		error: function(errorThrown){
			console.warn('error');
			console.log(errorThrown);
			var n = noty({
				text: 'Echec du filtre de recherche :(',
				template: '<div id="add_success" class="active error"><span class="noty_text"></span><div class="noty_close"></div></div>'
			});
		}
	});


}


/**
 * ajaxPostRequest
 * request a post by ID with a target container
 * @param id integer post->ID
 * @param target string class/ID to append data
 */
function ajaxPostRequest( id,target ){
	//console.log(type);
	jQuery.ajax({
		url: ajaxUrl,
		settings:{
			cache : true
		},
		data:{
			'action':'do_ajax',
			'id':id
		},
		//JSON can cause issues on Chrome ? use text instead ?
		dataType: 'JSON',
		success:function(data){
			$(target).empty().append($('<div>', {
				html : data
			}));
			
		},
		error: function(errorThrown){
			console.log(errorThrown);
			$(target).empty().append($('<div>', {
				html : 'Erreur du chargement,désolé pour cet inconvénient !'
			}));
		}
	});


}
//BOOKING FN

function resetReservation(){
	notyAction( deleteAllActivities , 'Souhaitez-vous tout recommencer ?', 'oui', 'non','séjour reinitialisé');
}
/**
 * Storage -- cookies
 * 
 * 
 * @param cname
 * @param cvalue
 * @param exdays
 */
function setCookie(cname, cvalue, exdays) {
	var d = new Date();
	d.setTime(d.getTime() + (exdays*24*60*60*1000));
	var expires = "expires="+d.toUTCString();
	document.cookie = cname + "=" + cvalue + "; " + expires;
	console.log(reservation);
}


/*
	deleteCookie
*/
function delete_cookie( name ) {
  document.cookie = name + '=; expires=Thu, 01 Jan 1970 00:00:01 GMT;';
}
/**
 * getCookie
 * 
 * 
 * @param cname
 * @returns {*}
 */
function getCookie(cname) {
	var name = cname + "=";
	var ca = document.cookie.split(';');
	for(var i=0; i<ca.length; i++) {
		var c = ca[i];
		while (c.charAt(0)==' ') c = c.substring(1);
		if (c.indexOf(name) === 0) return c.substring(name.length, c.length);
	}
	return "";
}

/**
 * checkCookie
 */
function checkCookie() {
	//var trip = getCookie("reservation");
	var trip = Cookies.getJSON('reservation');

	if (trip) {
		loadTrip( trip );
		console.log('trip exist already');

	} else {
		console.log("Welcome new user ");
		initTrip();

	}
}

function read_cookie(cname) {
	var result = document.cookie.match(new RegExp(cname + '=([^;]+)'));
	result && (result = JSON.parse(result[1]));
	return result;
}
/**
 * store reservation as a cookie
 * run this function to momentay store object
 * @param reservation
 */
function tripToCookie(reservation){
	cookieValue = JSON.stringify(reservation);
	Cookies.set('reservation', cookieValue, { expires: 1, path: '/' });

}



/************************
 * User Account functions
 ***********************/

/**
 * estimateUserTrip
 * ask for a quote
 * will change invoice state
 * @param tripID
 */
function estimateUserTrip(tripID){
	$.ajax({
		url: ajaxUrl,
		data:{
			'action':'do_ajax',
			'estimateUserTrip' : tripID
		},
		dataType: 'JSON',
		success:function(data){
			console.log(data);
			var n = noty({text: 'Demande envoyée'});
			$('#ut-' + tripID).remove();
		},
		error: function(errorThrown){
			var n = noty({
				text: 'Echec de la demande :(',
				template: '<div id="add_success" class="active error"><span class="noty_text"></span><div class="noty_close"></div></div>'
			});
			console.warn(errorThrown.responseText);
		}
	});
}

/**
 * deleteUserTrip
 * loggedIn User delete its trip
 * 
 * @param tripID
 */
function deleteUserTrip(tripID){

	var n = noty ({
		layout: 'center',
		modal: true,
		text: 'Ëtes-vous sûr de vouloir <br />supprimer cet évènement !',
		template: '<div class="noty_message"><span class="noty_text"></span><div class="noty_close"></div></div>',
		closeWith:['button'],
		buttons: [
			{addClass: 'btn-reg btn btn-primary', text: 'Oui', onClick: function($noty) {

				$noty.close();
				$.ajax({
					url: '/wp-admin/admin-ajax.php',
					data:{
						'action':'do_ajax',
						'deleteUserTrip' : tripID
					},
					dataType: 'JSON',
					success:function(data){
						console.log(data);
						var n = noty({text: 'Suppression effectuée'});
						$('#ut-' + tripID).remove();
					},
					error: function(errorThrown){
						var n = noty({
							text: 'Echec de la suppression :(',
							template: '<div id="add_success" class="active error"><span class="noty_text"></span><div class="noty_close"></div></div>'
						});
						console.warn(errorThrown.responseText);
					}
				});


			}
			},
			{addClass: 'btn-reg btn btn-danger', text: 'Non', onClick: function($noty) {
				$noty.close();
			}
			}
		],
		type: 'confirm',

	});

}


/************************
 *  Global functions
 ***********************/

/**
 *  saveTrip
 *  to DB, ajax request
 *
 * @param existingTripId unique ID if exist, will perform an update of the trip (mandatory)
 */
function saveTrip(existingTripId){
	tripName = tripNameInput.val();
	if(tripName === ''){
		tripNameInput.addClass('required').attr('placeholder','Nom de votre reservation');
	} else{
		tripNameInput.removeClass('required');
		//set name and store it in reservation object
		reservation.name = tripName;
		tripToCookie(reservation);
		//default value for existing Trip
		if(!existingTripId){
			existingTripId = 0;
		}
		//request the ajax store fn
		$.ajax({
			url: ajaxUrl+'/wp-admin/admin-ajax.php',
			data:{
				'action':'do_ajax',
				'reservation' : 1,
				'bookinkTrip': tripName,
				'existingTripId' : existingTripId
			},
			dataType: 'JSON',
			success:function(data){
				console.log(data);
				if(data === '10'){
					var n = noty({
						text: 'Il n\'est pas possible d\'enregistrer plus de 10 élements.Merci d\'effacer des events dans votre compte',
						template: '<div id="add_success" class="active error"><span class="noty_text"></span><div class="noty_close"></div></div>'
					});
				} else if( data === null){
					var n = noty({
						text: 'enregistrement non effectué, merci de nous contacter directement',
						template: '<div id="add_success" class="active error"><span class="noty_text"></span><div class="noty_close"></div></div>'
					});
				} else if( data === 'updated'){
					var n = noty({
						text: 'enregistrement mis à jour'
					});
				} else if( data === 'stored'){
					var n = noty({text: 'Résérvation effectué ! elle est visible dans "mon compte"'});
				} else {
					var n = noty({
						text: 'enregistrement non effectué, merci de nous contacter directement',
						template: '<div id="add_success" class="active error"><span class="noty_text"></span><div class="noty_close"></div></div>'
					});
				}
				setTimeout(function(){
					window.location = ajaxUrl+'/compte';
				}, 1200)
				


			},
			error: function(errorThrown){
				var n = noty({
					text: 'Echec de la sauvegarde :(',
					template: '<div id="add_success" class="active error"><span class="noty_text"></span><div class="noty_close"></div></div>'
				});
				console.log(errorThrown.responseText);
			}
		});
	}




}

/**
 * Add a small animation
 * emphasis the addToTrip function
 * 
 * @param id
 */
function addActivityAnimation(id){

	flyingTarget = $('.dayblock.current').offset();
	flyingStart = $('#ac-'+ id);
	flyingStartPoint = flyingStart.offset();

	flyingStart.find('img').clone().appendTo(flyingStart)
			.addClass('future-flying-img animated zoomOutRight');

	setTimeout(function(){
		$('.future-flying-img')
				.fadeOut(500)
				.remove();
	}, 1000);
}

/**
 * addActivity
 * Add an activity to a selected/default day
 *
 * @param id the trip id - post->id
 * @param activityname activityname post->name
 * @param price acf - prix - should be static
 * @param icon string fa icon
 * @param order integer
 */
function addActivity(id,activityname,price,icon,order){

	getLength = reservation.tripObject[reservation.currentDay][id];

	if(!getLength){
		reservation.tripObject[reservation.currentDay][id] = {
			name  : activityname,
			price : price,
			type  : icon,
			order : order
		};
		//console.log('obj price : ' + price);
		reservation.currentBudget = parseInt(reservation.currentBudget,10) + parseInt(price,10);
		tripType = (icon) ? icon : 'notDefined';
		$htmlDay = $('.dayblock[data-date="'+ reservation.currentDay +'"] .day-content');
		$htmlDay.append('<div onmouseover="loadSingleActivity(this, \''+ id +'\')" data-order="'+order+'" data-id="'+ id +'" class="dc"><i class="fa '+tripType+'"></i><span class="popit"></span>'+ activityname +' <span class="dp">'+ price +' € </span> <div class="fa fa-trash-o" onclick="deleteActivity(\''+ reservation.currentDay +'\', '+ id +', '+ price +')"></div></div>');

		$htmlDay.find('div.dc').sort(function (a, b) {
					return +a.getAttribute('data-order') - +b.getAttribute('data-order');
				})
				.appendTo( $htmlDay );

		checkBudget();
		addActivityAnimation(id);
		var n = noty({text: 'Ajouté à votre séjour'});
		tripToCookie(reservation);
	} else {
		var n = noty({
			text: 'cette activité est déjà présente sur cette journée',
			template: '<div id="add_success" class="active error"><span class="noty_text"></span><div class="noty_close"></div></div>'
		});
	}

}


/**
 * loadSingleActivity
 * @param el object
 * @param id integer post->ID
 */
function loadSingleActivity(el,id){
	target = $(el).find('.popit');
	if(target.hasClass('filled')){
	
	} else {
		ajaxPostRequest( id,target );
		target.addClass('filled');
	}
	
	
}

/**
 * deleteActivity
 * delete Activity from Obj for Main Booking TPL
 *
 * @param day string format dd/mm/yyyy
 * @param id integer activity ID
 * @param price integer
 */
function deleteActivity(day,id,price){
		var n = noty ({
		layout: 'center',
		modal: true,
		text: 'Êtes vous sûr de vouloir supprimer cette activité de votre programme ?',
		template: '<div class="noty_message"><span class="noty_text"></span><div class="noty_close"></div></div>',
		closeWith:['button'],
		buttons: [
			{addClass: 'btn-reg btn btn-primary', text: 'Poursuivre et supprimer', onClick: function($noty) {

				$noty.close();
				//console.log(day);
				$('.dayblock[data-date="'+day+'"]').find('.day-content div[data-id="'+ id +'"]').remove();
				var target = $('.dayblock[data-date="'+day+'"]').find('.day-wrapper');
				target.addClass('anim-effect-boris');
				setTimeout(function(){
					target.removeClass('anim-effect-boris');
				}, 300);
				obj = reservation.tripObject[day];
				//console.log('obj price : ' + price);
				reservation.currentBudget = parseInt( (reservation.currentBudget - price),10);
				delete obj[id];
				checkBudget();
				tripToCookie(reservation);

			}
			},
			{addClass: 'btn-reg btn btn-danger', text: 'Annuler l\'opération', onClick: function($noty) {
				$noty.close();
			}
			}
		],
		type: 'confirm',

	});

}


/**
 * deleteSejourActivity
 * delete Activity from Obj for a SEJOUR
 *
 * @param dayNumber integer
 * @param id integer
 * @param price integer
 * @param obj unique ID of the sejour
 */
function deleteSejourActivity(dayNumber,id,price,obj){
	//console.log(day);
	$('.day-content div[data-id="'+ id +'"]').remove();
	point = obj.tripObject[Object.keys(obj.tripObject)[dayNumber]];
	console.log(point);
	obj.currentBudget = parseInt( (obj.currentBudget - price),10);
	delete point[id];
}



/************************
 *   DAYS RELATED FUNCTIONS
 ***********************/

/**
 * check if arrival is before departure
 * check number of days
 * 
 * @param start
 * @param end
 * @returns {*}
 */
function checkIfDateOk(start,end){

	//console.log(moment(start).format("DD/MM/YYYY") + ' => initials dates  <= ' + moment(end).format("DD/MM/YYYY"));

	isReversedDate = moment(start).isAfter(end);

	if(isReversedDate === true ){

		console.warn('issue with departure date : ' + end);
		console.log(reservation.arrival + ' is after ' + reservation.departure);
		console.log(start + ' is after ' + end);

		//add One day to rebuild an event with 2 days, but empty :(
		end = start.add(1,'days').format("DD/MM/YYYY");
		reservation.departure = end;
		reservation.days = 2;
		var n = noty({
			text: 'Erreur dans le calcul des jours...nous avons du tout reconstruire...désolé',
			template: '<div id="add_success" class="active error"><span class="noty_text"></span><div class="noty_close"></div></div>'
		});
	} else {
		//console.log(reservation.arrival + ' is before ' + reservation.departure + ' so it is fine...');
	}


	return isReversedDate;

}


/**
 * check number of days allowed
 * 
 * @param days
 * @returns {*}
 */
function checkNumberOfDays(days){
	if(days > maxDefinedDays || isNaN(days) === true){
		console.warn('number of days too high or wrong');
		days = maxDefinedDays;
	}

	return days;
}

/**
 * check if there is same amount of day
 * in startToEnd and in tripObject
 * 
 * @param start
 * @param end
 */
function checkCoherence(start,end){
	calcNumberOfDays = parseInt(end.diff(start , 'days'),10) + 1;
	tripNbDays = Object.keys(reservation.tripObject).length;
	//console.log(tripNbDays,calcNumberOfDays);
	if(tripNbDays !== calcNumberOfDays){
		console.warn('diff between tripObj and dates');
	}
}
/*
 * define dates for the trip
 * check if object dates exists, calculate range, define nb of days
 * create html days list (without activites)
 * calc max number of days
 */
function defineTripDates(){

	if(!reservation.departure){
		console.warn('departure did not exist');
		reservation.departure = $('#departure').val();
		reservation.arrival = $('#arrival').val();
	}

	var start   = moment(reservation.arrival,"DD/MM/YYYY");
	var end = moment(reservation.departure,"DD/MM/YYYY");

	checkIfDateOk(start,end);
	checkCoherence(start,end);
	//define range & modify html
	momentstart = moment(start).format("DD/MM/YYYY");
	//console.log(momentstart, endFormatted);
	var range = moment().range(start,end);
	//console.log(range);
	//define number of days
	//we need the exact nb starting from 1 - that's why we add one here
	calcNumberOfDays = parseInt(end.diff(start , 'days'),10) + 1;
	//console.log('calcNumberOfDays' + calcNumberOfDays);
	reservation.days = checkNumberOfDays(calcNumberOfDays);

	i = 0;
	//for each days, define an obj
	range.by('days', function(momentTime) {
		var dayIs = moment(momentTime).format("DD/MM/YYYY");
		var niceDayIs = moment(momentTime).format("dddd DD MMMM ");
		//define first day as the curent day in the global var
		if(i === 0){
			reservation.currentDay = dayIs;
		}
		//day obj is not defined we define it 
		if(!reservation.tripObject[dayIs]){
			reservation.tripObject[dayIs] = {};
		}
		//build html list
		var currentClass = (i === 0) ? 'current' : 'classic';
		var removeFn = (i !== reservation.days - 1) ? '' : '<i onclick="removeLastDay();" class="fa fa-times rd"></i>';

		$('#daysTrip').append('<div class="dayblock '+currentClass+' fa fa-star" data-date="'+ dayIs +'" ><div class="day-wrapper">'+removeFn+'<span onclick="changeCurrentDay(\''+ dayIs+'\');" class="js-change fs1" aria-hidden="true" data-icon=""></span>'+ niceDayIs +'</div><div class="day-content"></div></div>');
		i++;
	});
}

/**
 *  add a day to event
 *  maximum is 4 days
 *  minimum is 2 days
 *  increment number of days
 *  update departure day
 *  update trip object
 */
function addADay(){
	//define max number of days
	maxDays = (maxDefinedDays) ? (maxDefinedDays - 1) : 3;
	if(reservation.days > maxDays){

		var n = noty({
			text: 'Nombre maximum de jour atteint',
			template: '<div id="add_success" class="active error"><span class="noty_text"></span><div class="noty_close"></div></div>'
		});

	} else {
		$('.rd').remove();
		//define last day
		lastDay = moment(reservation.departure, "DD/MM/YYYY");
		reservation.departure = lastDay.add(1, 'days').format("DD/MM/YYYY");
		//$( "#departure" ).datepicker( "setDate", reservation.departure );
		var niceDayIs = lastDay.format("dddd DD MMMM ");
		dayIs = reservation.departure;
		reservation.days++;
		reservation.tripObject[dayIs] = {};
		var removeFn = '<i onclick="removeLastDay();" class="fa fa-times rd"></i>';
		//html append Day
		$('#daysTrip').append('<div class="dayblock  fa fa-star" data-date="'+ reservation.departure +'" ><div class="day-wrapper">'+removeFn+'<span onclick="changeCurrentDay(\''+ reservation.departure+'\');" class="js-change fs1" aria-hidden="true" data-icon=""></span>'+ niceDayIs +'</div><div class="day-content"></div></div>');

		//store the day added
		tripToCookie(reservation);
		var n = noty({text: 'Jour ajouté'});
	}

	setdaysCount();

}

/**
 * set the number of days (input#daysCount)
 */
function setdaysCount(){
	if(reservation){
		$('#daysCount').val(reservation.days);
	} else {
		console.warn('not able to count days');
	}

}

/**
 * Delete all activites for each day
 */
function deleteAllActivities(){
	//check if reservation.tripObject has activity
	daysObj = reservation.tripObject;
	days = Object.keys(daysObj).length;

	if (days !== 0) {
		//iterate thrue days
		for (var day in daysObj) {
			if (daysObj.hasOwnProperty(day)) {
				activities = Object.keys(daysObj[day]).length;
				if (activities > 0) {
					for(var id in daysObj[day]){
						delete daysObj[day][id];
						$('.dayblock[data-date="'+ day +'"]').find('.dc[data-id="'+id+'"]').remove();
					}

				}
			}
		}
	}
	//store the day added
	tripToCookie(reservation);
	var n = noty({text: 'remise à zero'});
}
/**
 * reservationHasActivity
 * Check if reservation object or a single day has activity
 *
 * @param dayObj format:20/02/2010
 *
 * return integer (number of activities)
 */
function reservationActivityCounter(dayObj){
	if(dayObj){
		selectedDay = reservation.tripObject[dayObj];
		return Object.getOwnPropertyNames(selectedDay).length;
	}else {
		//check if reservation.tripObject has activity
		daysObj = reservation.tripObject;
		days = Object.keys(daysObj).length;

		if (days !== 0) {
			$activitiesNumber = [];
			//iterate thrue days
			for (var day in daysObj) {
				if (daysObj.hasOwnProperty(day)) {
					activities = Object.keys(daysObj[day]).length;
					if (activities > 0) {
						for (var id in daysObj[day]) {
							$activitiesNumber.push(id);
						}
					}
				}
			}
		return $activitiesNumber.length;

		}else {
			return 0;
		}

	}

}

/**
 *
 * linked to removeLastDay()
 * perform the last day removal
 */
function removeLastDayAction(){
	lastDay = moment(reservation.departure, "DD/MM/YYYY");
	reservation.days--;
	lastDayString = lastDay.format("DD/MM/YYYY");
	//console.log(lastDayString);
	delete reservation.tripObject[lastDayString];
	$(".dayblock[data-date='"+ lastDayString +"']").remove();
	newDeparture = lastDay.subtract(1, 'days').format("DD/MM/YYYY");
	reservation.departure = newDeparture;
	reservation.currentDay = reservation.arrival;
	$(".dayblock[data-date='"+ reservation.currentDay +"']").addClass('current');
	//$( "#departure" ).datepicker( "setDate", reservation.departure );
	//add a del button
	var spanBtn = '<i onclick="removeLastDay();" class="fa fa-times rd"></i>';
	$(".dayblock:last-child").find('.day-wrapper').append(spanBtn);
	//store the day added
	//console.log(reservation);
	checkBudget();
	tripToCookie(reservation);
	setdaysCount();
	var n = noty({text: 'Jour supprimé'});
}
/**
 *
 *  remove Last day
 *  decrement number of days
 *  decrement departure day
 *  add a delete button to new last day
 *  warning only if the day has no activity
 */
function removeLastDay(){

	lastDay = moment(reservation.departure, "DD/MM/YYYY");
	lastDayString = lastDay.format("DD/MM/YYYY");

	if(parseInt(reservation.days,10) < 2) {
		var n = noty({
			text: 'Nombre minimum de jour atteint',
			template: '<div id="add_success" class="active error"><span class="noty_text"></span><div class="noty_close"></div></div>'
		});
	} else if( reservationActivityCounter(lastDayString) === 0){
		removeLastDayAction();
	} else{
		var n = noty ({
		layout: 'center',
		modal: true,
		text: 'Êtes vous sûr de vouloir supprimer cette journée de votre programme ?',
		template: '<div class="noty_message"><span class="noty_text"></span><div class="noty_close"></div></div>',
		closeWith:['button'],
		buttons: [
			{addClass: 'btn-reg btn btn-primary', text: 'Poursuivre et supprimer', onClick: function($noty) {

				$noty.close();
				removeLastDayAction();

			}
			},
			{addClass: 'btn-reg btn btn-danger', text: 'Annuler l\'opération', onClick: function($noty) {
				$noty.close();
			}
			}
		],
		type: 'confirm'

	});
	

	}
	setdaysCount();
}

/*
 * getDays
 *
 * return array days format d/m/y
 */
function getDays(){
	if(reservation){
		obj = reservation.tripObject;
		tripDays = Object.keys(reservation.tripObject);
		getDaysArray = [];
		//iterate through dates
		for (var i in tripDays) {
			if (tripDays.hasOwnProperty(i) && typeof(i) !== 'function') {
				tripDay = tripDays[i];
				getDaysArray.push(tripDay);
			}
		}
		return getDaysArray;
	} else {
		return false;
	}
}
/*
 * createDaysSelector
 * return string trip days, append to selector
 */
function createDaysSelector(){
	daysArray = getDays();
	countD = daysArray.length;
	daysSelector.empty();
	for(var i = 0; i < countD ; i++){
		currentDay = (daysArray[i] == reservation.currentDay) ? 'current': 'legal';
		daySelector = '<span class="'+currentDay+'" onClick="changeCurrentDay(\''+ daysArray[i] + '\')">'+ daysArray[i] +'</div>';
		daysSelector.append(daySelector);
	}
	
}
/*
 * selectYourDay
 * append days selector
 *
 * @param obj el selected activity
 */
function selectYourDay(el){
	createDaysSelector();
	daysSelector.insertAfter(el).stop( true, true ).fadeIn();
	$(el).parent().mouseleave(function(){
		daysSelector.stop( true, true ).fadeOut();
	});
	
}
/*
 * Get this day active
 * set it active globally and in html
 * popup success
 * @param day : string format dd/mm/yyyy
 */
function changeCurrentDay(day){
	reservation.currentDay = day;
	$(".dayblock[data-date='"+ day+"']").addClass('current').siblings().removeClass('current');
			var n = noty ({
			layout: 'bottomCenter',
			text: 'Changement de jour',
			template: '<div class="cdnoty"><span class="noty_text"></span></div>',
			timeout: 1000,
			type: 'information',
			animation: {
		        open: 'animated fadeIn', // jQuery animate function property object
		        close: 'animated fadeOut', // jQuery animate function property object
		        easing: 'swing', // easing
		        speed: 180 // opening & closing animation speed
		    }

		});
	tripToCookie(reservation);
	

}
/*
 * delete full Day
 * always delete the last day
 * need to calculate number of days and set departure/arrival
 * @param day : string format dd/mm/yyyy
 */
function removeDay(day){
	delete reservation.tripObject[day];
	reservation.days = reservation.days - 1;

	$(".dayblock[data-date='"+ day+"']").remove();
	reservation.departure = $('.dayblock:last-child').attr('data-date');
	tripToCookie(reservation);
	var n = noty({
		text: 'Jour supprimé'
		});

}


/**
 * changeDateRangeEvent
 * reload reservation Object
 * duplicate key,rename it,rename it to make it unique,del old key,remove prefix,rebuild html
 * re-set departure & arrival dates
 *
 * @param selectedDate
 */
function changeDateRangeEvent(selectedDate){
	obj = reservation.tripObject;
	oldTrip = Object.keys(reservation.tripObject);

	//console.log(oldTrip);
	//number of days can't be negative or null
	checkNumberOfDays(reservation.days);
	//we set the new departure date
	if(reservation.days === 1){
		reservation.departure = selectedDate;
	}

	//iterate through dates
	for (var i in oldTrip) {
		if (oldTrip.hasOwnProperty(i) && typeof(i) !== 'function') {
			oldDay = oldTrip[i];
			//calculate days
			formattDay = moment(oldDay, "DD/MM/YYYY");
			//if = 0 this is Events arrival
			if(parseInt(i,10) < 1){
				//console.log('define first day');
				incrementDay = selectedDate + '*';
				reservation.arrival = selectedDate;
				reservation.currentDay = selectedDate;
				//console.log(incrementDay);
				//replace with new day
				reservation.tripObject[incrementDay] = reservation.tripObject[oldDay];
				//delete old day
				delete reservation.tripObject[oldDay];
				//console.log(oldTrip[i]);

			} else {
				//console.log('more than one day');
				incrementDay = moment(selectedDate, "DD/MM/YYYY").add(i, 'days').format("DD/MM/YYYY");
				var dminys = parseInt((reservation.days),10);
				//console.log(i);
				//console.log('number of days = ',dminys);
				if( parseInt(i,10)  ===  dminys){
					//console.log('last event day');
					reservation.departure = incrementDay;
				}
				reservation.tripObject[incrementDay+ '*'] = reservation.tripObject[oldDay];
				delete reservation.tripObject[oldDay];
				//console.log(oldTrip[i]);

			}
		}
	}
	daysWithPrefix = Object.keys(reservation.tripObject);
	//remove prefix
	for (var i in oldTrip) {
		if (daysWithPrefix.hasOwnProperty(i) && typeof(i) !== 'function') {

			oldDay = daysWithPrefix[i];
			var res = oldDay.replace("*", "");
			reservation.tripObject[res] = reservation.tripObject[oldDay];
			delete reservation.tripObject[oldDay];
			//change last day
			//console.log(i);
			lastDay = parseInt(reservation.days,10) - 1;
			if( parseInt(i,10)   === lastDay ){
				//console.log('last day');
				reservation.departure = res;
			}
		}
	}


	//console.log(reservation);
	tripToCookie(reservation);
	reloadSidebar();
	var n = noty({text: 'date changée'});
}

/**
 * changingTerms
 * should be triggerd once trip is fully loaded, not before
 * reload post
 */
function changingTerms(){
	$('.terms-change').change(function () {
		console.log('place/type triggered');
		if(reservationActivityCounter() > 0){
			var n = noty ({
				layout: 'center',
				modal: true,
				text: 'recharger les réglages et perdre activités ?',
				template: '<div class="noty_message"><span class="noty_text"></span><div class="noty_close"></div></div>',
				force: true, // adds notification to the beginning of queue when set to true
				maxVisible: 1, // you can set max visible notification for dismissQueue true option,
				killer: true, // for close all notifications before show
				closeWith:['button'],
				buttons: [
					{addClass: 'btn-reg btn btn-primary', text: 'Changer de place', onClick: function($noty) {

						$noty.close();
						deleteAllActivities();
						console.warn('need to reload sidebar');
						var n = noty({text: 'réalisé'});

					}
					},
					{addClass: 'btn-reg btn btn-danger', text: 'Garder la configuration', onClick: function($noty) {
						$noty.close();
					}
					}
				],
				type: 'confirm'

			});


		}else {
			loadPostsFromScratch();
		}

	});
}

/**
 * checkBudget
 *
 */
function checkBudget(){
	globalBudget = parseInt(reservation.budgetPerMax,10);
	actualCost = parseInt(reservation.currentBudget,10);
	if( globalBudget < actualCost){
		console.log('budget is too high');
		$('#budget-icon').addClass('exceeded').removeClass('ok');

	} else {
		console.log('budget is ok');
		$('#budget-icon').addClass('ok').removeClass('exceeded');
	}
}

/********************
SET THINGS
 ********************/

/*
 * set Budget in obj, store it in cookies
 * @param min : number
 * @parama max : number
 */
function setBudgetPer(min,max){
	reservation.budgetPerMin = min;
	reservation.budgetPerMax = max;
	console.log('set budget');
	checkBudget();
	//setCookie('reservation', JSON.stringify(reservation), 2);
	tripToCookie(reservation);
}
/*
 Enable to set the booking terms for Object reservation
 */
function setReservationTerms(theme, lieu){
	reservation.theme = theme;
	reservation.lieu = lieu;
	tripToCookie(reservation);
}

/**
 * setNumberOfPersonns
 * @param personNb Integer
 */
function setNumberOfPersonns(personNb){
	reservation.participants = parseIn(personNb,10);
	checkBudget();
	tripToCookie(reservation);
}
/**
 * setTripName
 * @param name
 */
function setTripName(name){
	if(!name || name == ""){
		name = reservation['name'];
	}
	tripNameInput.val(name);
}



/**
 * INIT TRIP
 *
 * @param $trip object the full reservation obj
 * @param gotoBookingPage bolean - are we on the booking page ?
 */
function loadTrip($trip,gotoBookingPage){
	console.log('load trip');
	reservation = {};
	reservation = $trip;
	reservation.user = (reservation.user) ? reservation.user :  USERID;
	reservation.currentBudget = 0;
	//either we need to go to the page or not
	if(gotoBookingPage === true){

		var n = noty ({
			layout: 'center',
			modal: true,
			text: 'Voulez-vous charger cet évènement ? Attention, vous risquez de perdre votre évènement en cours !',
			template: '<div class="noty_message"><span class="noty_text"></span><div class="noty_close"></div></div>',
			closeWith:['button'],
			buttons: [
				{addClass: 'btn-reg btn btn-primary', text: 'Ok', onClick: function($noty) {

					$noty.close();
					//setCookie('reservation', JSON.stringify($trip), 2);
					cookieValue = JSON.stringify($trip);
					Cookies.set('reservation', cookieValue, { expires: 7, path: '/' });
					window.location = bookingPage;

				}
				},
				{addClass: 'btn-reg btn btn-danger', text: 'Annuler', onClick: function($noty) {
					$noty.close();
				}
				}
			],
			type: 'confirm'

		});


	}else {
		$getBudgetMin = ( reservation.budgetPerMin ) ? reservation.budgetPerMin : 100;
		$getBudgetMax = ( reservation.budgetPerMax ) ? reservation.budgetPerMax : 300;

		$('#daysTrip').empty();
		tripNameInput.val(reservation.name);
		$( "#arrival" ).datepicker( "setDate", reservation.arrival );
		//$( "#departure" ).datepicker( "setDate", reservation.departure );
		$( "#slider-range" ).slider( "option", "values", [ reservation.budgetPerMin, reservation.budgetPerMax ] );
		//$( "#budget").val( reservation.budgetPerMax + "/" + reservation.budgetPerMax );
		$('#budget').val(reservation.budgetPerMin+'/'+reservation.budgetPerMax);
		$('#st').html(reservation.budgetPerMin);
		$('#end').html(reservation.budgetPerMax);
		$('#participants').val(reservation.participants );

		if(reservation.name){
			tripNameInput.val(reservation.name);
		}
		//set values for terms
		if( lieuInput.length ){
			lieuInput.select2("val", reservation.lieu);
		}
		if( themeInput.length ){
			themeInput.select2("val", reservation.theme);
		}		

		defineTripDates();
		setTripName();
		setdaysCount();
		the_activites();
		checkBudget();
		//console.log(reservation.theme);
		var n = noty({text: 'Chargement de votre voyage'});
		loadPostsFromScratch();

	}


}

/**
 * loadPostsFromScratch
 * get terms values
 * setReservationTerms
 * reloadPost
 */
function loadPostsFromScratch(){
	console.log('loadPostsFromScratch');
	var theme = $('#theme').val();
	if(theme === null){
		theme = $('select#theme option:first-child').attr('value');
		themeInput.val(theme).trigger("change");
	}
	var lieu = $('#lieu').val();
	if(lieu === null){
		lieu = $('select#lieu option:first-child').attr('value');
		lieuInput.val(lieu).trigger("change");
	}
	var type = $('#typeterms input[type=checkbox]').attr('value');
	checkedTypes = [];
	$("#typeterms input[type=checkbox]:checked").each(function(){
		checkedTypes.push($(this).val());
	});
	var searchTextTerm = $('input[name="ob_s"]').val();
	setReservationTerms(theme, lieu);
	doAjaxRequest(theme, lieu, checkedTypes,searchTextTerm);

}

/**
 * initTrip
 * init trip if there is no previous data, based on dates inputs
 * define global obj :  budget, participants, dates
 * init tripdates fn and activities fn
 */
function initTrip(){

	//get global var from project
	$participants = $('#participants').val();
	$budgetRange = $('#budget').val().split('/');
	reservation.user = (reservation.user) ? reservation.user :  USERID;
	reservation.participants = $participants;
	reservation.budgetPerMin = $budgetRange[0];
	reservation.budgetPerMax = $budgetRange[1];
	reservation.globalBudgetMin = $budgetRange[0] * $participants;
	reservation.globalBudgetMax = $budgetRange[1] * $participants;

	setTripName(name);
	defineTripDates();
	the_activites();
	setdaysCount();

	//Load Data
	loadPostsFromScratch();
	console.log('initTrip '+reservation);

}

/*
 * reloadSidebar
 *
 *
 */
 function reloadSidebar(){
	$('#daysTrip').empty();
	defineTripDates();
	the_activites();
 }

/*
 * get activites from the obj
 * calculate days, iterate thrue them to get activites
 * build html list of activites in days html list
 * get price obj
 * add to global Budget
 */
function the_activites(){
	//console.log(reservation);
	daysObj = reservation.tripObject;
	days = Object.keys(daysObj).length;

	if(days !== 0){
		//iterate thrue days
		for (var day in daysObj){
			if (daysObj.hasOwnProperty(day)) {
				activities = Object.keys(daysObj[day]).length;
				//if have activites, iterate thrue them
				if(activities > 0){
					for (var id in daysObj[day]){
						if (daysObj[day].hasOwnProperty(id)) {
							//console.log(id);
							var activityname = reservation.tripObject[day][id]['name'],
									price = reservation.tripObject[day][id]['price'],
									order = reservation.tripObject[day][id]['order'],
									type = reservation.tripObject[day][id]['type'];

							tripType = (type) ? type : 'notDefined';
							$htmlDay = $('.dayblock[data-date="'+ day +'"]').find('.day-content');
							//build html
							$htmlDay.append('<div onmouseover="loadSingleActivity(this, \''+ id +'\')" data-order="'+order+'" data-id="'+ id +'" class="dc"><i class="fa '+type+'"></i><span class="popit"></span>'+ activityname +' <span class="dp">'+ price +' €</span><div class="fa fa-trash-o" onclick="deleteActivity(\''+ day +'\', '+ id +', '+ price +')"></div></div>');
							$htmlDay.find('div.dc').sort(function (a, b) {
										return +a.getAttribute('data-order') - +b.getAttribute('data-order');
									})
									.appendTo( $htmlDay );

							//add to global budget
							//console.log('obj price : '+price);
							//console.log('Global : ' + reservation.currentBudget)
							reservation.currentBudget += price;
						}
					}
				}
			}
		}
	}
}




jQuery(function () {
	//DATEPICKER settings
	//i18n momentjs
	moment.locale('fr', {
		months: "janvier_février_mars_avril_mai_juin_juillet_août_septembre_octobre_novembre_décembre".split("_"),
		monthsShort: "janv._févr._mars_avr._mai_juin_juil._août_sept._oct._nov._déc.".split("_"),
		weekdays: "dimanche_lundi_mardi_mercredi_jeudi_vendredi_samedi".split("_"),
		weekdaysShort: "dim._lun._mar._mer._jeu._ven._sam.".split("_"),
		weekdaysMin: "Di_Lu_Ma_Me_Je_Ve_Sa".split("_"),
		longDateFormat: {
			LT: "HH:mm",
			LTS: "HH:mm:ss",
			L: "DD/MM/YYYY",
			LL: "D MMMM YYYY",
			LLL: "D MMMM YYYY LT",
			LLLL: "dddd D MMMM YYYY LT"
		},
		calendar: {
			sameDay: "[Aujourd'hui à] LT",
			nextDay: '[Demain à] LT',
			nextWeek: 'dddd [à] LT',
			lastDay: '[Hier à] LT',
			lastWeek: 'dddd [dernier à] LT',
			sameElse: 'L'
		},
		relativeTime: {
			future: "dans %s",
			past: "il y a %s",
			s: "quelques secondes",
			m: "une minute",
			mm: "%d minutes",
			h: "une heure",
			hh: "%d heures",
			d: "un jour",
			dd: "%d jours",
			M: "un mois",
			MM: "%d mois",
			y: "une année",
			yy: "%d années"
		},
		ordinalParse: /\d{1,2}(er|ème)/,
		ordinal: function (number) {
			return number + (number === 1 ? 'er' : 'ème');
		},
		meridiemParse: /PD|MD/,
		isPM: function (input) {
			return input.charAt(0) === 'M';
		},
		meridiem: function (hours, minutes, isLower) {
			return hours < 12 ? 'PD' : 'MD';
		},
		week: {
			dow: 1, // Monday is the first day of the week.
			doy: 4 // The week that contains Jan 4th is the first week of the year.
		}
	});
	$.datepicker.setDefaults($.datepicker.regional["fr"]);

	//forms settings
	$('.date-picker').datepicker({
		dateFormat: "dd/mm/yy",
		altFormat: "dd/mm/yy",
		showOptions: { direction: "up" }
	});

	//sidebar
 	$("#side-stick").sticky({
	 topSpacing:70,
	 bottomSpacing:530
	 });
	 /*
	 $('#ob-btn-re').on('inview', function(event, isInView) {
	  if (isInView) {
	    // element is now visible in the viewport
	    console.log('stick');
	    $("#side-stick").sticky({
	 topSpacing:70,
	 bottomSpacing:530
	 });
	  } else {
	    // element has gone out of viewport
	    $("#side-stick").unstick();
	    console.log('test');
	  }
	});*/

	 //select 2
	$('.postform').select2({
		'width' : '96%'
	});

	$('.open-popup-link').magnificPopup({
		type:'inline',
		midClick: true // Allow opening popup on middle mouse click. Always set it to true if you don't provide alternative source in href.
	});

	//SLIDER RANGE SETTINGS
	var getBudgetMin = (reservation.budgetPerMin && reservation.budgetPerMin > minBudget) ? reservation.budgetPerMin : minBudget;
	var getBudgetMax = (reservation.budgetPerMax && reservation.budgetPerMax > minBudget) ? reservation.budgetPerMax : maxBudget;

	$("#slider-range").slider({
		range: true,
		min: minBudget,
		max: maxBudget,
		step: 10,
		values: [getBudgetMin, getBudgetMax],
		slide: function (event, ui) {
			$("#budget").val(ui.values[0] + "/" + ui.values[1]);
			$('#st').html(ui.values[0]);
			$('#end').html(ui.values[1]);
		},
		stop: function (event, ui) {
			setBudgetPer(ui.values[0], ui.values[1]);
		}
	});

	

	$("#arrival").datepicker({
		closeText: 'Fermer',
		prevText: 'Précédent',
		nextText: 'Suivant',
		currentText: 'Aujourd\'hui',
		monthNames: ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'],
		monthNamesShort: ['Janv.', 'Févr.', 'Mars', 'Avril', 'Mai', 'Juin', 'Juil.', 'Août', 'Sept.', 'Oct.', 'Nov.', 'Déc.'],
		dayNames: ['Dimanche', 'Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi'],
		dayNamesShort: ['Dim.', 'Lun.', 'Mar.', 'Mer.', 'Jeu.', 'Ven.', 'Sam.'],
		dayNamesMin: ['D', 'L', 'M', 'M', 'J', 'V', 'S'],
		weekHeader: 'Sem.',
		defaultDate: "+1w",
		dateFormat: "dd/mm/yy",
		altFormat: "dd/mm/yy",
		minDate: 0,
		changeMonth: true,
		numberOfMonths: 1,
		inline: true,
		showOtherMonths: true,
		beforeShow: function (input, inst) {
			$('#ui-datepicker-div').addClass('ll-skin-melon');
		},
		onClose: function (selectedDate) {
			var maxRange = moment(selectedDate, 'DD/MM/YYYY').add(2, 'days');
			var maxDate = maxRange.format('DD/MM/YYYY');
			//$( "#departure" ).datepicker( "option", "minDate", maxDate );
			//$("#departure").datepicker("setDate", maxDate);
			//console.log(selectedDate);
			arrival = moment(reservation.arrival, 'DD/MM/YYYY');
			//console.log(arrival);
			//if date does not change we don't move, otherwise we calculate the new days
			if(moment(selectedDate, 'DD/MM/YYYY').isSame(arrival)){
				console.log('no date move');
			} else {
				console.log('date move');
				changeDateRangeEvent(selectedDate);
			}


		}
	});


	$("#arrival-form").datepicker({
		closeText: 'Fermer',
		prevText: 'Précédent',
		nextText: 'Suivant',
		currentText: 'Aujourd\'hui',
		monthNames: ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'],
		monthNamesShort: ['Janv.', 'Févr.', 'Mars', 'Avril', 'Mai', 'Juin', 'Juil.', 'Août', 'Sept.', 'Oct.', 'Nov.', 'Déc.'],
		dayNames: ['Dimanche', 'Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi'],
		dayNamesShort: ['Dim.', 'Lun.', 'Mar.', 'Mer.', 'Jeu.', 'Ven.', 'Sam.'],
		dayNamesMin: ['D', 'L', 'M', 'M', 'J', 'V', 'S'],
		weekHeader: 'Sem.',
		defaultDate: "+1w",
		dateFormat: "dd/mm/yy",
		altFormat: "dd/mm/yy",
		minDate: 0,
		changeMonth: true,
		numberOfMonths: 1,
		inline: true,
		showOtherMonths: true,
		onClose: function (selectedDate) {
			console.log(selectedDate);
			$("#arrival-form").val(selectedDate);
		}
	});
	
	$("#departure").datepicker({
		defaultDate: "+1w",
		dateFormat: "dd/mm/yy",
		altFormat: "dd/mm/yy",
		changeMonth: true,
		numberOfMonths: 1,
		disabled: true,
		minDate: 0,
		inline: true,
		showOtherMonths: true,
		beforeShow: function (input, inst) {
			//$('#ui-datepicker-div').addClass('ll-skin-melon');
		},
		onClose: function (selectedDate) {
			//$("#arrival").datepicker("option", "maxDate", selectedDate);

		}
	});

	$('#participants').change(function () {
		var personNb = $(this).val();
		setNumberOfPersonns(personNb);
	});


	//filtering event
	$('#typeterms input[type=checkbox]').change(function(){
		loadPostsFromScratch();
	});
	$('.js-sub-s').click(function(e){
		loadPostsFromScratch();
	});


	//last action, set trip or load existant
	//loadTrip(exampleReservation);
	//check if there is an existing trip around the user
	if(isBookingTpl){
		checkCookie();
	} else {
		console.warn('not booking page');
	}


//RESERVATION - SINGLE PAGE
	var slickReservation = $('.slickReservation');
	if (slickReservation.length) {
		slickReservation.slick({
			autoplay: true,
			dots: false,
			infinie: true,
			arrows: true,
			prevArrow: '<i class="fa fa-chevron-left prevmulti"></i>',
            nextArrow: '<i class="fa fa-chevron-right nextmulti"></i>',
			slidesToShow: 1,
			slidesToScroll: 1,
			adaptiveHeight: false
			
		});
	}
	$('.img-pop').magnificPopup({
		type: 'image',
		gallery:{
			enabled:true
		}
		// other options
	});

});
