<div class="pull-right">
    <a id="close-card" href="#" class="btn close-card" title="<%t Genealogist.CLOSE 'Close' %>"><i class="fa fa-times" aria-hidden="true"></i></a>
</div>

<div>
    <p>
        <strong>$AliasName</strong>

        <span style="font-size: 85%;">
            <% if BirthDate %>
                ($BirthYear<% if BirthDateEstimated %> <%t Genealogist.ESTIMATED '(Estimated)' %><% end_if %>
                <% else_if CalculatedBirthYear %>
                ($CalculatedBirthYear <%t Genealogist.CALCULATIONS '(Calculations)' %>
            <% end_if %>

            <% if BirthDate || CalculatedBirthYear %>
                <% if DeathDate || CalculatedDeathYear %> - <% else %>)<% end_if %>
            <% end_if %>

            <% if DeathDate %>
                $DeathYear<% if DeathDateEstimated %> <%t Genealogist.ESTIMATED '(Estimated)' %><% end_if %>)
            <% else_if CalculatedDeathYear %>
                $CalculatedDeathYear <%t Genealogist.CALCULATIONS '(Calculations)' %>)
            <% end_if %>
        </span>
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