<div class="pull-right">
    <a href="{$TreeLink}" class="btn btn-default" title="<%t Genealogist.SHOW_THIS 'Show this person tree' %>"><i class="fa fa-leaf" aria-hidden="true"></i></a>
    <a href="{$SuggestLink}" class="btn btn-default" title="<%t Genealogist.SUGGEST_PERSON_EDIT 'Suggest edit on this person' %>"><i class="fa fa-comment" aria-hidden="true"></i></a>

    <% if canEdit %>
    <div class="btn-group">
        <a id="ttt" class="btn btn-default dropdown-toggle" data-toggle="dropdown" title="<%t Genealogist.EDIT_THIS_PERSON 'Edit this Person' %>">
            <i class="fa fa-pencil" aria-hidden="true"></i> <span class="caret"></span>
        </a>

        <% include Person_Edit_Options %>
    </div>
    <% end_if %>
</div>