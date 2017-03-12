<div class="pull-right">
    <a href="{$TreeLink}" class="options-item" title="<%t Genealogist.SHOW_THIS 'Show this person tree' %>"><i class="fa fa-leaf" aria-hidden="true"></i></a>
    <a href="{$Father.TreeLink}" class="options-item" title="<%t Genealogist.SHOW_FATHER 'Show this persons father tree' %>"><i class="fa fa-arrow-circle-o-left" aria-hidden="true"></i></a>
    <a href="{$Root.TreeLink}" class="options-item" title="<%t Genealogist.SHOW_CLAN 'Show this persons clan tree' %>"><i class="fa fa-pagelines" aria-hidden="true"></i></a>
    <a href="{$SuggestLink}" target="_blank" title="<%t Genealogist.SUGGEST_PERSON_EDIT 'Suggest edit on this person' %>"><i class="fa fa-comment" aria-hidden="true"></i></a>
    <% if hasPermission %>
        <a href="{$EditLink}" target="_blank" title="<%t Genealogist.EDIT_THIS 'Edit this person' %>"><i class="fa fa-pencil" aria-hidden="true"></i></a>
    <% end_if %>
</div>

<div>
    <p>$AliasName</p>
    <% if hasPermission %>
        <% if BirthDate %><%t Genealogist.BIRTHDATE 'Birth Date' %>: $BirthDate<br /><% end_if %>
        <% if DeathDate %><%t Genealogist.DEATHDATE 'Death Date' %>: $DeathDate<br /><% end_if %>
        <% if Age %><%t Genealogist.AGE 'Age' %>: $Age<br /><% end_if %>
    <% end_if %>

    <% if $Father %>
        <p>
            <b><%t Genealogist.FATHER 'Father' %></b>: $Father.FullName
        </p>
    <% end_if %>

    <% if hasPermission && Mother %>
        <p>
            <b><%t Genealogist.MOTHER 'Mother' %></b>: $Mother.FullName
        </p>
    <% end_if %>

    <% if Husbands %>
        <p>
            <b><%t Genealogist.HUSBANDS 'Husbands' %></b>: $Husbands.Count<br />

            <ul>
                <% loop Husbands.Sort(HusbandOrder) %>
                    <li>$FullName</li>
                <% end_loop %>
            </ul>
        </p>

    <% else_if hasPermission && Wives %>
        <p>
            <b><%t Genealogist.WIVES 'Wives' %></b> ($Wives.Count): <br />

            <ul>
                <% loop Wives.Sort(WifeOrder) %>
                <li>$FullName</li>
                <% end_loop %>
            </ul>
        </p>
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

