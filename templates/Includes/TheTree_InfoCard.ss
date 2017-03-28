<div class="pull-right">
    <button id="close-card" type="button" class="close close-card" aria-label="Close"><span aria-hidden="true">×</span></button>
</div>

<div>
    <p>
        $AliasSummary
    </p>

    <p><% if Note %><%t Genealogist.NOTE 'Note' %>: $Note<% end_if %></p>

    <% if $Father %>
        <p><strong><%t Genealogist.FATHER 'Father' %></strong>: <a href="#" data-url="{$Father.InfoLink()}" class="info-item">$Father.FullName</a></p>
    <% end_if %>

    <% if hasPermission && Mother %>
        <p><strong><%t Genealogist.MOTHER 'Mother' %></strong>: <a href="#" data-url="{$Mother.InfoLink()}" class="info-item">$Mother.FullName</a></p>
    <% end_if %>

    <p>
        <% if Husbands %>
            <strong><%t Genealogist.HUSBANDS 'Husbands' %>: </strong>
            <% loop Husbands.Sort(HusbandOrder) %>
                <a href="#" data-url="{$InfoLink}" class="info-item" title="$FullName">$AliasName</a><% if not Last %><%t Genealogist.COMMA ',' %><% end_if %>
            <% end_loop %>

        <% else_if hasPermission && Wives %>
            <strong><%t Genealogist.WIVES 'Wives' %>: </strong>
            <% loop Wives.Sort(WifeOrder) %>
                <a href="#" data-url="{$InfoLink}" class="info-item" title="$FullName">$AliasName</a><% if not Last %><%t Genealogist.COMMA ',' %><% end_if %>
            <% end_loop %>
        <% end_if %>
    </p>

    <% if Children %>
    <table class="table">
        <tr>
            <th></th>
            <th><%t Genealogist.SONS 'Sons' %></th>
            <% if hasPermission %>
                <th><%t Genealogist.DAUGHTERS 'Daughters' %></th>
            <% end_if %>
            <th><%t Genealogist.MALES 'Males' %></th>
            <% if hasPermission %>
                <th><%t Genealogist.FEMALES 'Females' %></th>
                <th><%t Genealogist.TOTAL 'Total' %></th>
            <% end_if %>
        </tr>
        <tr>
            <td><%t Genealogist.DESCENDANTS 'Descendants' %></td>
            <td>$SonsCount</td>
            <% if hasPermission %>
                <td>$DaughtersCount</td>
            <% end_if %>
            <td>$MalesCount</td>
            <% if hasPermission %>
                <td>$FemalesCount</td>
                <td>$DescendantsCount</td>
            <% end_if %>
        </tr>
        <tr>
            <td><%t Genealogist.ALIVE 'Alive' %></td>
            <td>$SonsCount(1)</td>
            <% if hasPermission %>
                <td>$DaughtersCount(1)</td>
            <% end_if %>
            <td>$MalesCount(1)</td>
            
            <% if hasPermission %>
                <td>$FemalesCount(1)</td>
                <td>$DescendantsCount(1)</td>
            <% end_if %>
        </tr>
    </table>
    <% end_if %>
</div>

<div class="card-controls">
    <% include TheTree_InfoCard_Nav %>
</div>