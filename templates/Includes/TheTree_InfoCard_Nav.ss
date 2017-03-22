<div>
    <a href="{$TreeLink}" class="options-item btn btn-default" title="<%t Genealogist.SHOW_THIS 'Show this person tree' %>"><i class="fa fa-leaf" aria-hidden="true"></i></a>
    <a href="{$Root.TreeLink}" class="options-item btn btn-default" title="<%t Genealogist.SHOW_CLAN 'Show this persons clan tree' %>"><i class="fa fa-pagelines" aria-hidden="true"></i></a>
    <a href="{$SuggestLink}" class="btn btn-default" target="_blank" title="<%t Genealogist.SUGGEST_PERSON_EDIT 'Suggest edit on this person' %>"><i class="fa fa-comment" aria-hidden="true"></i></a>
    <a href="{$ObjectLink}" class="btn btn-default" target="_blank" title="<%t Genealogist.SHOW_PROFILE 'Show Person Profile' %>"><i class="fa fa-user" aria-hidden="true"></i></a>

    <% if hasPermission %>
    <div class="btn-group dropup">
        <a id="ttt" class="btn btn-default dropdown-toggle" data-toggle="dropdown" title="<%t Genealogist.EDIT_THIS_PERSON 'Edit this Person' %>">
            <i class="fa fa-pencil" aria-hidden="true"></i><span class="caret"></span>
        </a>

        <ul class="dropdown-menu pull-right" role="menu">
            <li><a href="{$EditLink(self)}" class="ajax-modal"><%t Genealogist.EDIT_THIS_PERSON 'Edit this Person' %></a></li>
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
            <li><a href="{$EditLink(delete)}" class="ajax-modal"><%t Genealogist.DELETE_THIS_PERSON 'Delete this Person' %></a></li>
        </ul>
    </div>
    <% end_if %>
</div>