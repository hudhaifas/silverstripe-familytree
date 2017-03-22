<% with Person %>
<div class="panel panel-default">
    <div class="panel-heading">$FullName</div>
    <div class="panel-body">
        <p>
            <% if Husbands %>
                <strong><%t Genealogist.HUSBANDS 'Husbands' %>:<br /></strong>
                <% loop Husbands.Sort(HusbandOrder) %>
                    $FullName<% if not Last %><br /><% end_if %>
                <% end_loop %>

            <% else_if hasPermission && Wives %>
                <strong><%t Genealogist.WIVES 'Wives' %>:<br /></strong>
                <% loop Wives.Sort(WifeOrder) %>
                    $FullName<% if not Last %><br /><% end_if %>
                <% end_loop %>
            <% end_if %>
        </p>

        <p>
        <% if $isMale %>
            <%t Genealogist.ADD_WIFE 'Add Wife' %>
        <% else %>
            <%t Genealogist.ADD_HUSBAND 'Add Husband' %>
        <% end_if %>
        $Up.Form_AddSpouse($ID)
        </p>

        <% if isMale && Wives.Count == 1%>
        <p>
            <%t Genealogist.SINGLE_WIFE 'This Person Has One Wife Only' %>
            $Up.Form_SingleWife($ID)
        </p>
        <% end_if %>
    </div>
</div>
<% end_with %>