<% with Gender %>
<div class="panel panel-default">
    <div class="panel-heading">$FullName</div>
    <div class="panel-body">
        <p>
            <% if Husbands %>
                <b><%t Genealogist.HUSBANDS 'Husbands' %></b>: $Husbands.Count<br />
                <ul>
                    <% loop Husbands.Sort(HusbandOrder) %>
                        <li><a href="{$EditLink(self)}" class="ajax-modal-nested">$FullName</a></li>
                    <% end_loop %>
                </ul>

            <% else_if Wives %>
                <b><%t Genealogist.WIVES 'Wives' %></b>: $Wives.Count<br />

                <ul>
                    <% loop Wives.Sort(WifeOrder) %>
                    <li><a href="{$EditLink(self)}" class="ajax-modal-nested">$FullName</a></li>
                    <% end_loop %>
                </ul>
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