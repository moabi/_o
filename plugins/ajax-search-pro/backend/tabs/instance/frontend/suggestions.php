<div class="item">
    <?php
    $o = new wpdreamsYesNo("frontend_show_suggestions", "Show the Suggested phrases?", wpdreams_setval_or_getoption($sd, 'frontend_show_suggestions', $_dk));
    $params[$o->getName()] = $o->getData();
    ?>
    <p class="descMsg">Will show the "Try these" as seen on the demo.</p>
</div>
<div class="item">
    <?php
    $o = new wpdreamsText("frontend_suggestions_text", "Suggestion text", wpdreams_setval_or_getoption($sd, 'frontend_suggestions_text', $_dk));
    $params[$o->getName()] = $o->getData();
    ?>
</div>
<div class="item">
    <?php
    $o = new wpdreamsTextarea("frontend_suggestions_keywords", "Keywords", wpdreams_setval_or_getoption($sd, 'frontend_suggestions_keywords', $_dk));
    $params[$o->getName()] = $o->getData();
    ?>
    <p class="descMsg">Comma separated!</p>
</div>