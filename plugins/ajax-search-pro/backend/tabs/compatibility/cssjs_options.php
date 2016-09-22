<div class="item">
    <?php
    $o = new wpdreamsCustomSelect("js_source", "Javascript source", array(
            'selects'=>wpdreams_setval_or_getoption($com_options, 'js_source_def', 'asp_compatibility_def'),
            'value'=>wpdreams_setval_or_getoption($com_options, 'js_source', 'asp_compatibility_def')
        )
    );
    $params[$o->getName()] = $o->getData();
    ?>
    <p class="descMsg">
    <ul style="float:right;text-align:left;width:50%;">
        <li><b>Non minified</b> - Low Compatibility, Medium space</li>
        <li><b>Minified</b> - Low Compatibility, Low space</li>
        <li><b>Non minified Scoped</b> - High Compatibility, High space</li>
        <li><b>Minified Scoped</b> - High Compatibility, Medium space</li>
    </ul>
    <div class="clear"></div>
    </p>
</div>
<div class="item">
    <?php
    $o = new wpdreamsCustomSelect("css_compatibility_level", "CSS compatibility level", array(
            'selects'=>array(
                array('option'=>'Maximum', 'value'=>'maximum'),
                array('option'=>'Medium', 'value'=>'medium'),
                array('option'=>'Low', 'value'=>'low')
            ),
            'value'=>wpdreams_setval_or_getoption($com_options, 'css_compatibility_level', 'asp_compatibility_def')
        )
    );
    $params[$o->getName()] = $o->getData();
    ?>
    <p class="descMsg">
    <ul style="float:right;text-align:left;width:50%;">
        <li><b>Maximum</b> - Highy compatibility, big size</li>
        <li><b>Medium</b> - Medium compatibility, medium size</li>
        <li><b>Low</b> - Low compabibility, small size</li>
    </ul>
    <div class="clear"></div>
    </p>
</div>
<div class="item">
    <p class='infoMsg'>Set to yes if you are experiencing issues with the <b>search styling</b>, or if the styles are <b>not saving</b>!</p>
    <?php $o = new wpdreamsYesNo("forceinlinestyles", "Force inline styles?",
        wpdreams_setval_or_getoption($com_options, 'forceinlinestyles', 'asp_compatibility_def')
    ); ?>
</div>
<div class="item">
    <p class='infoMsg'>You can turn some of these off, if you are not using them.</p>
    <?php $o = new wpdreamsYesNo("loadpolaroidjs", "Load the polaroid gallery JS?",
        wpdreams_setval_or_getoption($com_options, 'loadpolaroidjs', 'asp_compatibility_def')
    ); ?>
    <p class='descMsg'>Don't turn this off if you are using the POLAROID layout.</p>
</div>
<div class="item">
    <?php $o = new wpdreamsYesNo("load_isotope_js", "Load the isotope JS?",
        wpdreams_setval_or_getoption($com_options, 'load_isotope_js', 'asp_compatibility_def')
    ); ?>
    <p class='descMsg'>Don't turn this off if you are using the ISOTOPIC layout.</p>
</div>
<div class="item">
    <?php $o = new wpdreamsYesNo("load_noui_js", "Load the NoUI slider JS?",
        wpdreams_setval_or_getoption($com_options, 'load_noui_js', 'asp_compatibility_def')
    ); ?>
    <p class='descMsg'>Don't turn this off if you are using SLIDERS in the custom field filters.</p>
</div>
<div class="item">
    <p class='infoMsg'>This might speed up the search, but also can cause incompatibility issues with other plugins.</p>
    <?php $o = new wpdreamsYesNo("usecustomajaxhandler", "Use the custom ajax handler?",
        wpdreams_setval_or_getoption($com_options, 'usecustomajaxhandler', 'asp_compatibility_def')
    ); ?>
</div>