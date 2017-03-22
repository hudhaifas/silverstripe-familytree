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

<div class="card-controls">
    <div>
        <a href="{$TreeLink}" class="options-item btn btn-default" title="<%t Genealogist.SHOW_THIS 'Show this person tree' %>"><i class="fa fa-leaf" aria-hidden="true"></i></a>
        <!--<a href="{$Father.TreeLink}" class="options-item btn" title="<%t Genealogist.SHOW_FATHER 'Show this persons father tree' %>"><i class="fa fa-arrow-circle-o-left" aria-hidden="true"></i></a>-->
        <a href="{$Root.TreeLink}" class="options-item btn btn-default" title="<%t Genealogist.SHOW_CLAN 'Show this persons clan tree' %>"><i class="fa fa-pagelines" aria-hidden="true"></i></a>
        <a href="{$SuggestLink}" class="btn btn-default" target="_blank" title="<%t Genealogist.SUGGEST_PERSON_EDIT 'Suggest edit on this person' %>"><i class="fa fa-comment" aria-hidden="true"></i></a>
        <a href="{$ObjectLink}" class="btn btn-default" target="_blank" title="<%t Genealogist.SHOW_PROFILE 'Show Person Profile' %>"><i class="fa fa-user" aria-hidden="true"></i></a>
        <% if hasPermission %>
            <a href="{$EditLink}" id="manual-ajax" class="btn btn-default" target="_blank" title="<%t Genealogist.EDIT_THIS 'Edit this person' %>"><i class="fa fa-pencil" aria-hidden="true"></i></a>
        <% end_if %>
        <div class="btn-group dropup">
            <a id="ttt" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                <i class="fa fa-pencil" aria-hidden="true"></i><span class="caret"></span>
            </a>
            
            <ul class="dropdown-menu" role="menu">
                <li><a href="#"><%t Genealogist.EDIT_QUICK_EDIT 'Quick Edit' %></a></li>
                <li><a href="#"><%t Genealogist.EDIT_QUICK_EDIT 'Edit Parents' %></a></li>
                <li><a href="#"><%t Genealogist.EDIT_QUICK_EDIT 'Add Children' %></a></li>
                <li><a href="#"><%t Genealogist.EDIT_QUICK_EDIT 'Add Wife' %></a></li>
                <li><a href="#"><i class="fa fa-pencil" aria-hidden="true"></i><%t Genealogist.EDIT_QUICK_EDIT 'Delete Person' %></a></li>
            </ul>
        </div>
    </div>
</div>
