<div class="pull-right">
    <button id="close-card" type="button" class="close close-card" aria-label="Close"><span aria-hidden="true">×</span></button>
</div>

<% if $ObjectImage && not $isObjectDisabled %>
    <div class="card-picture">
        <div>
            <img src="$ObjectImage.PaddedImage(300,300).Watermark.URL" data-origin="$ObjectImage.Watermark.URL">
        </div>
    </div>
<% end_if %>

<div>
    <p>
        $AliasSummary
    </p>

    <p><% if Note %><%t Genealogist.NOTE 'Note' %>: $Note<% end_if %></p>

    <% if not isTribe %>
        <% if Father %>
            <p><strong><%t Genealogist.FATHER 'Father' %></strong>: <a href="#" data-url="{$Father.InfoLink()}" class="info-item">$Father.FullName</a></p>
        <% end_if %>

        <% if Mother && Mother.canView %>
            <p><strong><%t Genealogist.MOTHER 'Mother' %></strong>: <a href="#" data-url="{$Mother.InfoLink()}" class="info-item">$Mother.FullName</a></p>
        <% end_if %>

        <p>
            <% if Husbands && ViewableHusbands %>
                <strong><%t Genealogist.HUSBANDS 'Husbands' %>: </strong>
                <% loop Husbands.Sort(HusbandOrder) %>
                    <a href="#" data-url="{$InfoLink}" class="info-item" title="$FullName">$AliasName</a><% if not Last %><%t Genealogist.COMMA ',' %><% end_if %>
                <% end_loop %>

            <% else_if Wives && ViewableWives %>
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
                <% if canViewDaughters %>
                    <th><%t Genealogist.DAUGHTERS 'Daughters' %></th>
                <% end_if %>
                <th><%t Genealogist.MALES 'Males' %></th>
                <% if canViewDaughters %>
                    <th><%t Genealogist.FEMALES 'Females' %></th>
                    <th><%t Genealogist.TOTAL 'Total' %></th>
                <% end_if %>
            </tr>
            <tr>
                <td><%t Genealogist.DESCENDANTS 'Descendants' %></td>
                <td>$SonsCount</td>
                <% if canViewDaughters %>
                    <td>$DaughtersCount</td>
                <% end_if %>
                <td>$MalesCount</td>
                <% if canViewDaughters %>
                    <td>$FemalesCount</td>
                    <td>$DescendantsCount</td>
                <% end_if %>
            </tr>
            <tr>
                <td><%t Genealogist.ALIVE 'Alive' %></td>
                <td>$SonsCount(1)</td>
                <% if canViewDaughters %>
                    <td>$DaughtersCount(1)</td>
                <% end_if %>
                <td>$MalesCount(1)</td>

                <% if canViewDaughters %>
                    <td>$FemalesCount(1)</td>
                    <td>$DescendantsCount(1)</td>
                <% end_if %>
            </tr>
        </table>
        <% end_if %>
        
    <% else %>
        <% if Clans %>
        <table class="table">
            <tr>
                <th></th>
                <th><%t Genealogist.CLANS 'Clans' %></th>
                <th><%t Genealogist.MALES 'Males' %></th>
                <% if canViewDaughters %>
                    <th><%t Genealogist.FEMALES 'Females' %></th>
                    <th><%t Genealogist.TOTAL 'Total' %></th>
                <% end_if %>
            </tr>
            <tr>
                <td><%t Genealogist.DESCENDANTS 'Descendants' %></td>
                <td>$Clans.Count</td>
                <td>$MalesCount</td>
                <% if canViewDaughters %>
                    <td>$FemalesCount</td>
                    <td>$DescendantsCount</td>
                <% end_if %>
            </tr>
            <tr>
                <td><%t Genealogist.ALIVE 'Alive' %></td>
                <td>--</td>
                <td>$MalesCount(1)</td>

                <% if canViewDaughters %>
                    <td>$FemalesCount(1)</td>
                    <td>$DescendantsCount(1)</td>
                <% end_if %>
            </tr>
        </table>
        <% end_if %>
    <% end_if %>
</div>

<div class="card-controls">
    <% include TheTree_InfoCard_Nav %>
</div>