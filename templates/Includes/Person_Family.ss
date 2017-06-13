<% if not isTribe %>
    <div>
        <% if $Father %>
            <p>
                <b><%t Genealogist.FATHER 'Father' %></b><br />
                <a href="{$Father.ObjectLink}">$Father.FullName</a>
            </p>
        <% end_if %>

        <% if Mother && Mother.canView || Mother.IsPublicFigure %>
            <p>
                <b><%t Genealogist.MOTHER 'Mother' %></b><br />

                <a href="{$Mother.ObjectLink}">$Mother.FullName</a>
            </p>
        <% end_if %>

        <% if Husbands && ViewableHusbands %>
            <p>
                <b><%t Genealogist.HUSBANDS 'Husbands' %></b>: $Husbands.Count<br />

                <ul>
                    <% loop Husbands.Sort(HusbandOrder) %>
                        <% if canView || IsPublicFigure %>
                            <li><a href="{$ObjectLink}">$FullName</a></li>
                        <% end_if %>
                    <% end_loop %>
                </ul>
            </p>

        <% else_if Wives && ViewableWives %>
            <p>
                <b><%t Genealogist.WIVES 'Wives' %></b>: $Wives.Count<br />

                <ul>
                    <% loop Wives.Sort(WifeOrder) %>
                        <% if canView || IsPublicFigure %>
                            <li><a href="{$ObjectLink}">$FullName</a></li>
                        <% end_if %>
                    <% end_loop %>
                </ul>
            </p>
        <% end_if %>

        <% if Sons && ViewableSons %>
            <p>
                <b><%t Genealogist.SONS 'Sons' %></b>: $SonsCount<br />

                <% loop Sons %>
                    <% if canView || IsPublicFigure %>
                        <a href="{$ObjectLink}" title="$FullName">$AliasName</a><% if not Last %><%t Genealogist.COMMA ',' %><% end_if %>
                    <% end_if %>
                <% end_loop %>
            </p>
        <% end_if %>

        <% if Daughters  && ViewableDaughters %>
            <p>
                <b><%t Genealogist.DAUGHTERS 'Daughters' %></b>: $DaughtersCount<br />

                <% loop Daughters %>
                    <% if canView || IsPublicFigure %>
                        <a href="{$ObjectLink}" title="$FullName">$AliasName</a><% if not Last %><%t Genealogist.COMMA ',' %><% end_if %>
                    <% end_if %>
                <% end_loop %>
            </p>
        <% end_if %>
    </div>
<% else %>
    <p>
        <b><%t Genealogist.CLANS 'Branches' %></b>: $Branches.Count<br />

        <% loop Branches %>
            <% if canView || IsPublicFigure %>
                <a href="{$ObjectLink}" title="$FullName">$AliasName</a><% if not Last %><%t Genealogist.COMMA ',' %><% end_if %>
            <% end_if %>
        <% end_loop %>
    </p>
<% end_if %>