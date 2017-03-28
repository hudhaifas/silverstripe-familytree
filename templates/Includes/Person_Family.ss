<div>
    <% if $Father %>
        <p>
            <b><%t Genealogist.FATHER 'Father' %></b><br />
            <a href="{$Father.ObjectLink()}">$Father.FullName</a>
        </p>
    <% end_if %>

    <% if Mother %>
        <p>
            <b><%t Genealogist.MOTHER 'Mother' %></b><br />

            <a href="{$Mother.ObjectLink()}">$Mother.FullName</a>
        </p>
    <% end_if %>

    <% if Husbands %>
        <p>
            <b><%t Genealogist.HUSBANDS 'Husbands' %></b>: $Husbands.Count<br />

            <ul>
                <% loop Husbands.Sort(HusbandOrder) %>
                    <li><a href="{$ObjectLink()}">$FullName</a></li>
                <% end_loop %>
            </ul>
        </p>

    <% else_if Wives %>
        <p>
            <b><%t Genealogist.WIVES 'Wives' %></b>: $Wives.Count<br />

            <ul>
                <% loop Wives.Sort(WifeOrder) %>
                <li><a href="{$ObjectLink()}">$FullName</a></li>
                <% end_loop %>
            </ul>
        </p>
    <% end_if %>

    <% if Sons %>
        <p>
            <b><%t Genealogist.SONS 'Sons' %></b>: $SonsCount<br />

            <% loop Sons %>
                <a href="{$ObjectLink()}" title="$FullName">$AliasName</a><% if not Last %><%t Genealogist.COMMA ',' %><% end_if %>
            <% end_loop %>
        </p>
    <% end_if %>

    <% if Daughters %>
        <p>
            <b><%t Genealogist.DAUGHTERS 'Daughters' %></b>: $DaughtersCount<br />

            <% loop Daughters %>
                <a href="{$ObjectLink()}" title="$FullName">$AliasName</a><% if not Last %><%t Genealogist.COMMA ',' %><% end_if %>
            <% end_loop %>
        </p>
    <% end_if %>
</div>
