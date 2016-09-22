<div class="item"><?php
    $o = new wpdreamsCustomSelect("orderby", "Result ordering",
        array(
            'selects' => wpdreams_setval_or_getoption($sd, 'orderby_def', $_dk),
            'value' => wpdreams_setval_or_getoption($sd, 'orderby', $_dk)
        ));
    $params[$o->getName()] = $o->getData();
    ?>
    <p class="descMsg">This is only secondary if the Relevance is enabled. (Relevance Options panel)</p>
</div>
<div class="item">
    <?php
    $fields = wpdreams_setval_or_getoption($sd, 'results_order', $_dk);

    // For updating to 4.5
    if (strpos($fields, "attachments") === false) $fields = $fields . "|attachments";

    $o = new wpdreamsSortable("results_order", "Mixed results order", $fields);
    $params[$o->getName()] = $o->getData();
    ?>
</div>