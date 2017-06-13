<ul class="dropdown-menu pull-right" role="menu">
    <li><a href="{$EditLink(self)}" class="ajax-modal"><%t Genealogist.EDIT_THIS_PERSON 'Edit this Person' %></a></li>
    <li><a href="{$EditLink(settings)}" class="ajax-modal"><%t Genealogist.EDIT_SETTINGS 'Edit Settings' %></a></li>
    <% if not isClan %>
        <li><a href="{$EditLink(parents)}" class="ajax-modal"><%t Genealogist.EDIT_PARENTS 'Edit Parents' %></a></li>
        <% if isMale %>
            <li><a href="{$EditLink(children)}" class="ajax-modal"><%t Genealogist.EDIT_CHILDREN 'Edit Children' %></a></li>
        <% end_if %>
        <li>
            <a href="{$EditLink(spouses)}" class="ajax-modal">
                <% if isMale %>
                    <%t Genealogist.EDIT_WIVES 'Edit Wives' %>
                <% else %>
                    <%t Genealogist.EDIT_HUSBANDS 'Edit Husbands' %>
                <% end_if %>
            </a>
        </li>
    <% end_if %>
    
    <% if canDelete %>
        <li><a href="{$EditLink(delete)}" class="ajax-modal"><%t Genealogist.DELETE_THIS_PERSON 'Delete this Person' %></a></li>
    <% end_if %>
</ul>