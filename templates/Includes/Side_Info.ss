<div class="panel-heading clearfix">
    <h4 class="panel-title pull-left">
        <a data-toggle="collapse" data-parent="#accordion" href="#collapse1">$AliasName</a>
    </h4>

    <div class="pull-right">
        <a href="{$Link}" class="options-item"><i class="fa fa-eye" aria-hidden="true"></i></a>
        <a href="{$Father.Link}" class="options-item"><i class="fa fa-level-up" aria-hidden="true"></i></a>
        <a href="{$Root.Link}" class="options-item"><i class="fa fa-undo" aria-hidden="true"></i></span></a>
    </div>
</div>

<div id="collapse1" class="panel-collapse collapse in">
    <div class="panel-body">
        $FullName<br />
        <% if BirthDate %>BirthDate: $BirthDate<br /><% end_if %>
        <% if DeathDate %>DeathDate: $DeathDate<br /><% end_if %>
        <% if Age %>Age: $Age<br /><% end_if %>

        <% if Wife %>
        <hr />

        <b>Spouse</b><br />
        $Wife.FullName<br />
        <% end_if %>

        <% if Children %>
        <hr />

        <b>Offspring</b><br />
        Offspring: $OffspringCount<br />
        Sons: $SonsCount<br />
        Daughters: $DaughtersCount<br />
        Males: $MalesCount<br />
        Females: $FemalesCount<br />

        <hr />

        <b>Alive</b><br />
        Offspring: $OffspringCount(1)<br />
        Sons: {$SonsCount(1)}<br />
        Daughters: $DaughtersCount(1)<br />
        Males: $MalesCount(1)<br />
        Females: $FemalesCount(1)<br />
        <% end_if %>
    </div>
</div>