<% with Person %>
<div class="panel panel-default">
    <div class="panel-heading">$ShortName</div>
    <div class="panel-body">
        <div class="row dataobject-details">
            <div class="col-sm-4 dataobject-image">
                <a title="$Title">
                    <div class="thumbnail text-center imgBox">
                        <% include Single_Image %>
                    </div>
                </a>
            </div>

            <div class="col-sm-8 dataobject-summary">
                <% include Single_Summary %>
            </div>
        </div>

        <%t Genealogist.EDIT 'Edit' %>
        $Up.Form_EditPerson($ID)
    </div>
</div>
<% end_with %>