<div class="pull-right">
    <a href="{$TreeLink}" class="options-item" title="<%t Genealogist.SHOW_THIS 'Show this person tree' %>"><i class="fa fa-dot-circle-o" aria-hidden="true"></i></a>
    <a href="{$Father.TreeLink}" class="options-item" title="<%t Genealogist.SHOW_FATHER 'Show this persons father tree' %>"><i class="fa fa-arrow-circle-o-left" aria-hidden="true"></i></a>
    <a href="{$Root.TreeLink}" class="options-item" title="<%t Genealogist.SHOW_CLAN 'Show this persons clan tree' %>"><i class="fa fa-arrow-circle-o-up" aria-hidden="true"></i></a>
    <a href="{$SuggestLink}" target="_blank" title="<%t Genealogist.SUGGEST_PERSON_EDIT 'Suggest edit on this person' %>"><i class="fa fa-comment" aria-hidden="true"></i></a>
    <% if hasPermission %>
        <a href="{$EditLink}" target="_blank" title="<%t Genealogist.EDIT_THIS 'Edit this person' %>"><i class="fa fa-pencil" aria-hidden="true"></i></a>
    <% end_if %>
</div>

<div>
    <p>$AliasName</p>
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

    <% if Sons %>
        <p>
            <b><%t Genealogist.SONS 'Sons' %></b> ($SonsCount): 
            <% loop Sons %>
                $AliasName<% if not Last %><%t Genealogist.COMMA ',' %><% end_if %>
            <% end_loop %>
        </p>
    <% end_if %>

    <% if hasPermission && Daughters %>
        <p>
            <b><%t Genealogist.DAUGHTERS 'Daughters' %></b> ($DaughtersCount): 

            <% loop Daughters %>
                $AliasName<% if not Last %><%t Genealogist.COMMA ',' %><% end_if %>
            <% end_loop %>
        </p>
    <% end_if %>
</div>

