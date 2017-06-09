<div class="">
    <a href="{$TreeLink}" class="options-item btn btn-default" title="<%t Genealogist.SHOW_THIS 'Show this person tree' %>"><i class="fa fa-leaf" aria-hidden="true"></i></a>
    <a href="{$Root.TreeLink}" class="options-item btn btn-default" title="<%t Genealogist.SHOW_CLAN 'Show this persons clan tree' %>"><i class="fa fa-pagelines" aria-hidden="true"></i></a>
    <a href="{$SuggestLink}" class="btn btn-default" target="_blank" title="<%t Genealogist.SUGGEST_PERSON_EDIT 'Suggest edit on this person' %>"><i class="fa fa-comment" aria-hidden="true"></i></a>
    <% if canView || IsPublicFigure %>
        <a href="{$ObjectLink}" class="btn btn-default" target="_blank" title="<%t Genealogist.SHOW_PROFILE 'Show Person Profile' %>"><i class="fa fa-user" aria-hidden="true"></i></a>
    <% end_if %>

    <% if canEdit %>
    <div class="btn-group dropup">
        <a id="ttt" class="btn btn-default dropdown-toggle" data-toggle="dropdown" title="<%t Genealogist.EDIT_THIS_PERSON 'Edit this Person' %>">
            <i class="fa fa-pencil" aria-hidden="true"></i> <span class="caret"></span>
        </a>
        
        <% include Person_Edit_Options %>
    </div>
    <% end_if %>
</div>