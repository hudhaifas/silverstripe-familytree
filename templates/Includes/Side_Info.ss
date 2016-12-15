<div class="panel-heading clearfix">
    <h4 class="panel-title pull-left">
        <a data-toggle="collapse" data-parent="#accordion" href="#collapse2" id="collapse2-btn" title="$FullName">$AliasName</a>
    </h4>

    <div class="pull-right">
        <a href="{$ShowLink}" class="options-item" title="<%t Genealogist.SHOW_THIS 'Show this person tree' %>"><i class="fa fa-dot-circle-o" aria-hidden="true"></i></a>
        <a href="{$Father.ShowLink}" class="options-item" title="<%t Genealogist.SHOW_FATHER 'Show this persons father tree' %>"><i class="fa fa-arrow-circle-o-left" aria-hidden="true"></i></a>
        <a href="{$Root.ShowLink}" class="options-item" title="<%t Genealogist.SHOW_CLAN 'Show this persons clan tree' %>"><i class="fa fa-arrow-circle-o-up" aria-hidden="true"></i></a>
        <a href="{$SuggestLink}" target="_blank" title="<%t Genealogist.SUGGEST_PERSON_EDIT 'Suggest edit on this person' %>"><i class="fa fa-comment" aria-hidden="true"></i></a>
        <% if $hasPermission %>
            <a href="{$EditLink}" target="_blank" title="<%t Genealogist.EDIT_THIS 'Edit this person' %>"><i class="fa fa-pencil" aria-hidden="true"></i></a>
        <% end_if %>
    </div>
</div>

<div id="collapse2" class="panel-collapse collapse">
    <div class="panel-body">
        $FullName<br />
        <% if Note %><%t Genealogist.NOTE 'Note' %>: $Note<br /><% end_if %>
        <% if BirthDate %><%t Genealogist.BIRTHDATE 'Birth Date' %>: $BirthDate<br /><% end_if %>
        <% if DeathDate %><%t Genealogist.DEATHDATE 'Death Date' %>: $DeathDate<br /><% end_if %>
        <% if Age %><%t Genealogist.AGE 'Age' %>: $Age<br /><% end_if %>

        <% if $hasPermission && Mother %>
            <hr />

            <b><%t Genealogist.MOTHER 'Mother' %></b><br />
            <a href="#" data-url="{$Mother.InfoLink()}" class="info-item">$Mother.FullName</a>
            <br />
        <% end_if %>

        <% if Husbands %>
            <hr />
            <b><%t Genealogist.HUSBANDS 'Husbands' %></b><br />
            <% loop Husbands %>
                <a href="#" data-url="{$InfoLink()}" class="info-item">$FullName</a><br />
            <% end_loop %>

        <% else_if $hasPermission && Wives %>
            <hr />

            <b><%t Genealogist.WIVES 'Wives' %></b><br />
            <% loop Wives %>
                <a href="#" data-url="{$InfoLink()}" class="info-item">$FullName</a><br />
            <% end_loop %>
        <% end_if %>

        <% if Children %>
            <hr />

            <b><%t Genealogist.OFFSPRING 'Offspring' %></b><br />
            <%t Genealogist.SONS 'Sons' %>: $SonsCount<br />

            <% if $hasPermission %>
                <%t Genealogist.DAUGHTERS 'Daughters' %>: $DaughtersCount<br />
            <% end_if %>

            <%t Genealogist.MALES 'Males' %>: $MalesCount<br />

            <% if $hasPermission %>
                <%t Genealogist.FEMALES 'Females' %>: $FemalesCount<br />
                <%t Genealogist.TOTAL 'Total' %>: $OffspringCount<br />
            <% end_if %>

            <hr />

            <b><%t Genealogist.ALIVE 'Alive' %></b><br />
            <%t Genealogist.SONS 'Sons' %>: {$SonsCount(1)}<br />

            <% if $hasPermission %>
                <%t Genealogist.DAUGHTERS 'Daughters' %>: $DaughtersCount(1)<br />
            <% end_if %>

            <%t Genealogist.MALES 'Males' %>: $MalesCount(1)<br />
            <% if $hasPermission %>
                <%t Genealogist.FEMALES 'Females' %>: $FemalesCount(1)<br />
                <%t Genealogist.TOTAL 'Total' %>: $OffspringCount(1)<br />
            <% end_if %>
        <% end_if %>
    </div>
</div>