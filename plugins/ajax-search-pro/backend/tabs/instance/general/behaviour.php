<div class="item">
	<?php
	$o = new wpdreamsTextSmall("maxresults", "Max. results", wpdreams_setval_or_getoption($sd, 'maxresults', $_dk), array(array("func" => "ctype_digit", "op" => "eq", "val" => true)));
	$params[$o->getName()] = $o->getData();
	?>
	<p class="descMsg">Maximum results count. 10 is a good number for optimal performance.</p>
</div>
<div class="item">
	<?php
	$o = new wpdreamsYesNo("exactonly", "Show exact matches only?",
		wpdreams_setval_or_getoption($sd, 'exactonly', $_dk));
	$params[$o->getName()] = $o->getData();
	?>
	<p class="descMsg">If this is enabled, the Regular search engine is used. Index table engine doesn't support exact matches.</p>
</div>
<div class="item"><?php
    $o = new wpdreamsCustomSelect("keyword_logic", "Keyword logic",
        array(
            'selects' => wpdreams_setval_or_getoption($sd, 'keyword_logic_def', $_dk),
            'value' => wpdreams_setval_or_getoption($sd, 'keyword_logic', $_dk)
        ));
    $params[$o->getName()] = $o->getData();
    ?>
    <p class="descMsg">Only works in non-fulltext mode.</p>
</div>
<div class="item"><?php
    $o = new wpdreamsCustomSelect("term_logic", "Category/Term logic",
        array(
            'selects' => wpdreams_setval_or_getoption($sd, 'term_logic_def', $_dk),
            'value' => wpdreams_setval_or_getoption($sd, 'term_logic', $_dk)
        ));
    $params[$o->getName()] = $o->getData();
    ?>
    <p class="descMsg">Only works in non-fulltext mode.</p>
</div>
<div class="item">
    <?php
    $o = new wpdreamsYesNo("triggeronclick", "Trigger search when clicking on search icon?",
        wpdreams_setval_or_getoption($sd, 'triggeronclick', $_dk));
    $params[$o->getName()] = $o->getData();
    ?>
</div>
<div class="item">
    <?php
    $o = new wpdreamsYesNo("triggeronreturn", "Trigger search when hitting the return button?",
        wpdreams_setval_or_getoption($sd, 'triggeronreturn', $_dk));
    $params[$o->getName()] = $o->getData();
    ?>
</div>
<div class="item">
    <?php
    $o = new wpdreamsYesNo("trigger_on_facet", "Trigger search when changing a facet on settings?",
        wpdreams_setval_or_getoption($sd, 'trigger_on_facet', $_dk));
    $params[$o->getName()] = $o->getData();
    ?>
    <p class="descMsg">
        Will trigger the search if the user changes a checkbox, radio button, slider on the frontend
        search settings panel.
    </p>
</div>
<div class="item">
    <?php
    $o = new wpdreamsYesNo("triggerontype", "Trigger search when typing?",
        wpdreams_setval_or_getoption($sd, 'triggerontype', $_dk));
    $params[$o->getName()] = $o->getData();
    ?>
</div>
<div class="item">
    <?php
    $o = new wpdreamsTextSmall("charcount", "Minimal character count to trigger search",
        wpdreams_setval_or_getoption($sd, 'charcount', $_dk), array(array("func" => "ctype_digit", "op" => "eq", "val" => true)));
    $params[$o->getName()] = $o->getData();
    ?>
</div>
<div class="item">
    <?php
    $o = new wpdreamsYesNo("redirectonclick", "<b>Redirect</b> to search results page when clicking on search icon?",
        wpdreams_setval_or_getoption($sd, 'redirectonclick', $_dk));
    $params[$o->getName()] = $o->getData();
    ?>
</div>
<div class="item">
    <?php
    $o = new wpdreamsYesNo("redirect_on_enter", "<b>Redirect</b> to search results page when hitting the return key?",
        wpdreams_setval_or_getoption($sd, 'redirect_on_enter', $_dk));
    $params[$o->getName()] = $o->getData();
    ?>
</div>
<div class="item">
    <?php
    $o = new wpdreamsText("redirect_url", "<b>Redirect</b> to url?",
        wpdreams_setval_or_getoption($sd, 'redirect_url', $_dk));
    $params[$o->getName()] = $o->getData();
    ?>
</div>
<div class="item">
    <?php
    $o = new wpdreamsYesNo("override_default_results", "<b>Override</b> the default WordPress search results page?",
        wpdreams_setval_or_getoption($sd, 'override_default_results', $_dk));
    $params[$o->getName()] = $o->getData();
    ?>
</div>
