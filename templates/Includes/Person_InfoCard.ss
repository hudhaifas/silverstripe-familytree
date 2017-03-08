<div>
    <% if $Father %>
        <p>
            <b><%t Genealogist.FATHER 'Father' %></b>: $Father.FullName
        </p>
    <% end_if %>

    <% if Mother %>
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

    <% else_if Wives %>
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

    <% if Daughters %>
        <p>
            <b><%t Genealogist.DAUGHTERS 'Daughters' %></b> ($DaughtersCount): 

            <% loop Daughters %>
                $AliasName<% if not Last %><%t Genealogist.COMMA ',' %><% end_if %>
            <% end_loop %>
        </p>
    <% end_if %>
</div>
