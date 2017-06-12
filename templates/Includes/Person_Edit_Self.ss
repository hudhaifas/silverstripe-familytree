<% with Gender %>
<div class="panel panel-default">
    <div class="panel-heading">$FullName</div>

    <div class="panel-body">
        <div class="row dataobject-details">
            <div class="col-sm-4"></div>
            <div class="col-sm-4 dataobject-image">
                <a title="$Title">
                    <div class="thumbnail text-center imgBox">
                        <% include Single_Image %>
                    </div>
                </a>
            </div>
            <div class="col-sm-4"></div>
        </div>

        $Up.Form_EditPerson($ID)
    </div>
</div>
<% end_with %>