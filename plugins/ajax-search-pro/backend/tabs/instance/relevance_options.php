<div class='item'>
    <p class='infoMsg'>
        Every result gets a relevance value based on the weight numbers set below. The weight is the measure of
        importance.<br/>
        When two results have the same relevance value, then the <strong>default ordering</strong> will be used to
        determine their position.<br/>
        You can change this ordering on the general options tab. (<strong>Result ordering</strong> option)
    </p>
</div>
<div class='item'>
    <p class='infoMsg'>
        Also note: If you have <b>fulltext</b> search enabled, then these settings are irrelevant.
    </p>
</div>
<div class="item">
    <?php
    $o = new wpdreamsYesNo("userelevance", "Sort results by relevance", wpdreams_setval_or_getoption($sd, 'userelevance', $_dk));
    $params[$o->getName()] = $o->getData();
    ?>
</div>
<fieldset>
    <legend>Exact matches weight</legend>
    <div class="item">
        <?php
        $o = new wpdreamsCustomSelect("etitleweight", "Title weight", array('selects' => wpdreams_setval_or_getoption($sd, 'weight_def', $_dk), 'value' => wpdreams_setval_or_getoption($sd, 'etitleweight', $_dk)));
        $params[$o->getName()] = $o->getData();
        ?>
    </div>
    <div class="item">
        <?php
        $o = new wpdreamsCustomSelect("econtentweight", "Content weight", array('selects' => wpdreams_setval_or_getoption($sd, 'weight_def', $_dk), 'value' => wpdreams_setval_or_getoption($sd, 'econtentweight', $_dk)));
        $params[$o->getName()] = $o->getData();
        ?>
    </div>
    <div class="item">
        <?php
        $o = new wpdreamsCustomSelect("eexcerptweight", "Excerpt weight", array('selects' => wpdreams_setval_or_getoption($sd, 'weight_def', $_dk), 'value' => wpdreams_setval_or_getoption($sd, 'eexcerptweight', $_dk)));
        $params[$o->getName()] = $o->getData();
        ?>
    </div>
    <div class="item">
        <?php
        $o = new wpdreamsCustomSelect("etermsweight", "Terms weight", array('selects' => wpdreams_setval_or_getoption($sd, 'weight_def', $_dk), 'value' => wpdreams_setval_or_getoption($sd, 'etermsweight', $_dk)));
        $params[$o->getName()] = $o->getData();
        ?>
    </div>
</fieldset>
<fieldset>
    <legend>Random matches weight</legend>
    <div class="item">
        <?php
        $o = new wpdreamsCustomSelect("titleweight", "Title weight", array('selects' => wpdreams_setval_or_getoption($sd, 'weight_def', $_dk), 'value' => wpdreams_setval_or_getoption($sd, 'titleweight', $_dk)));
        $params[$o->getName()] = $o->getData();
        ?>
    </div>
    <div class="item">
        <?php
        $o = new wpdreamsCustomSelect("contentweight", "Content weight", array('selects' => wpdreams_setval_or_getoption($sd, 'weight_def', $_dk), 'value' => wpdreams_setval_or_getoption($sd, 'contentweight', $_dk)));
        $params[$o->getName()] = $o->getData();
        ?>
    </div>
    <div class="item">
        <?php
        $o = new wpdreamsCustomSelect("excerptweight", "Excerpt weight", array('selects' => wpdreams_setval_or_getoption($sd, 'weight_def', $_dk), 'value' => wpdreams_setval_or_getoption($sd, 'excerptweight', $_dk)));
        $params[$o->getName()] = $o->getData();
        ?>
    </div>
    <div class="item">
        <?php
        $o = new wpdreamsCustomSelect("termsweight", "Terms weight", array('selects' => wpdreams_setval_or_getoption($sd, 'weight_def', $_dk), 'value' => wpdreams_setval_or_getoption($sd, 'termsweight', $_dk)));
        $params[$o->getName()] = $o->getData();
        ?>
    </div>
</fieldset>
<div class="item">
    <input name="submit_<?php echo $search['id']; ?>" type="submit" value="Save this search!"/>
</div>

