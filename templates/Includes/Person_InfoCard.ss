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
    <p>
        $AliasName
        <% if Note %><%t Genealogist.NOTE 'Note' %>: $Note<br /><% end_if %>
    </p>

    <p>
        <% if BirthDate && BirthDateEstimated %>
            $BirthYear <%t Genealogist.ESTIMATED '(Estimated)' %>
        <% else_if BirthDate %>
            $BirthDate
        <% else_if CalculatedBirthYear %>
            $CalculatedBirthYear <%t Genealogist.CALCULATIONS '(Calculations)' %>
        <% end_if %>

        <% if BirthDate || CalculatedBirthYear %>
            <% if DeathDate || CalculatedDeathYear %> - <% end_if %>
        <% end_if %>

        <% if DeathDate && DeathDateEstimated %>
            $DeathYear <%t Genealogist.ESTIMATED '(Estimated)' %>
        <% else_if DeathDate %>
            $DeathDate
        <% else_if CalculatedDeathYear %>
            $CalculatedDeathYear <%t Genealogist.CALCULATIONS '(Calculations)' %>
        <% end_if %>
    </p>

    <p>
        <% if $Father %>
           <b><%t Genealogist.FATHER 'Father' %></b>: <a href="#" data-url="{$Father.InfoLink()}" class="info-item">$Father.FullName</a><br />
        <% end_if %>

        <% if hasPermission && Mother %>
           <b><%t Genealogist.MOTHER 'Mother' %></b>: <a href="#" data-url="{$Mother.InfoLink()}" class="info-item">$Mother.FullName</a>
        <% end_if %>
     </p>

     <p>
     <% if Husbands %>
         <b><%t Genealogist.HUSBANDS 'Husbands' %>: </b>
         <% loop Husbands.Sort(HusbandOrder) %>
            <a href="#" data-url="{$InfoLink}" class="info-item" title="$FullName">$AliasName</a><% if not Last %><%t Genealogist.COMMA ',' %><% end_if %>
         <% end_loop %>

     <% else_if hasPermission && Wives %>
        <b><%t Genealogist.WIVES 'Wives' %>: </b>
         <% loop Wives.Sort(WifeOrder) %>
            <a href="#" data-url="{$InfoLink}" class="info-item" title="$FullName">$AliasName</a><% if not Last %><%t Genealogist.COMMA ',' %><% end_if %>
         <% end_loop %>
     <% end_if %>
    </p>

    <p>
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
     </p>
</div>

