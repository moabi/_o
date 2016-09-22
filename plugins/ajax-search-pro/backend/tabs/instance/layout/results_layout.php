<div class="item">
    <?php
    $o = new wpdreamsCustomSelect("resultstype", "Results layout type", array(
        'selects'=>wpdreams_setval_or_getoption($sd, 'resultstype_def', $_dk),
        'value'=>wpdreams_setval_or_getoption($sd, 'resultstype', $_dk)
    ));
    $params[$o->getName()] = $o->getData();
    ?>
</div>
<p class='infoMsg'>If you are using <b>Polaroid</b> layout type, then <b>block</b> position is highly recommended!</p>
<div class="item">
    <?php
    $o = new wpdreamsCustomSelect("resultsposition", "Results layout position", array(
        'selects'=>wpdreams_setval_or_getoption($sd, 'resultsposition_def', $_dk),
        'value'=>wpdreams_setval_or_getoption($sd, 'resultsposition', $_dk)
    ));
    $params[$o->getName()] = $o->getData();
    ?>
</div>
<div class="item">
    <?php
    $o = new wpdreamsNumericUnit("resultsmargintop", "Block layout margin top", array(
        'value' => wpdreams_setval_or_getoption($sd, 'resultsmargintop', $_dk),
        'units'=>array('px'=>'px')
    ));
    $params[$o->getName()] = $o->getData();
    ?>
</div>
<div class="item">
    <?php
    $o = new wpdreamsText("defaultsearchtext", "Default search text", wpdreams_setval_or_getoption($sd, 'defaultsearchtext', $_dk));
    $params[$o->getName()] = $o->getData();
    ?>
</div>
<div class="item">
    <?php
    $o = new wpdreamsYesNo("showmoreresults", "Show 'More results..' text in the bottom of the search box?", wpdreams_setval_or_getoption($sd, 'showmoreresults', $_dk));
    $params[$o->getName()] = $o->getData();
    ?>
</div>
<div class="item">
    <?php
    $o = new wpdreamsText("showmoreresultstext", "' Show more results..' text", wpdreams_setval_or_getoption($sd, 'showmoreresultstext', $_dk));
    $params[$o->getName()] = $o->getData();
    ?>
</div>
<div class="item">
    <?php
    $o = new wpdreamsText("more_redirect_url", "' Show more results..' url", wpdreams_setval_or_getoption($sd, 'more_redirect_url', $_dk));
    $params[$o->getName()] = $o->getData();
    ?>
</div>
<div class="item">
    <?php
    $o = new wpdreamsYesNo("showauthor", "Show author in results?", wpdreams_setval_or_getoption($sd, 'showauthor', $_dk));
    $params[$o->getName()] = $o->getData();
    ?>
</div>
<div class="item">
	<?php
	$o = new wpdreamsCustomSelect("author_field", "Author field",
		array(
			'selects' => array(
				array('option' => 'Display name', 'value' => 'display_name'),
				array('option' => 'Login name', 'value' => 'login')
			),
			'value' => wpdreams_setval_or_getoption($sd, 'author_field', $_dk)
		));
	$params[$o->getName()] = $o->getData();
	?>
</div>
<div class="item">
    <?php
    $o = new wpdreamsYesNo("showdate", "Show date in results?", wpdreams_setval_or_getoption($sd, 'showdate', $_dk));
    $params[$o->getName()] = $o->getData();
    ?>
</div>
<div class="item">
    <?php
    $o = new wpdreamsYesNo("showdescription", "Show description in results?", wpdreams_setval_or_getoption($sd, 'showdescription', $_dk));
    $params[$o->getName()] = $o->getData();
    ?>
</div>
<div class="item">
    <?php
    $o = new wpdreamsTextSmall("descriptionlength", "Description length", wpdreams_setval_or_getoption($sd, 'descriptionlength', $_dk));
    $params[$o->getName()] = $o->getData();
    ?>
</div>