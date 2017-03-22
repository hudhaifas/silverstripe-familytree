<div class="dataobject-page">
    <div class="row">
        <% with Person %>
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

                <div class="row" style="margin-top: 15px;">
                    <div class="panel-group" id="accordion">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h4 class="panel-title">
                                    <a data-toggle="collapse" data-parent="#accordion" href="#collapse-edit-person">
                                        <%t Genealogist.EDIT 'Edit' %>
                                    </a>
                                </h4>
                            </div>

                            <div id="collapse-edit-person" class="panel-collapse collapse">
                                <div class="panel-body">
                                    $Up.Form_EditPerson($ID)
                                </div>
                            </div>
                        </div>

                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h4 class="panel-title">
                                    <a data-toggle="collapse" data-parent="#accordion" href="#collapse-add-father">
                                        <%t Genealogist.ADD_FATHER 'Add Father' %>
                                    </a>
                                </h4>
                            </div>

                            <div id="collapse-add-father" class="panel-collapse collapse">
                                <div class="panel-body">
                                    $Up.Form_AddFather($ID)
                                </div>
                            </div>
                        </div>

                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h4 class="panel-title">
                                    <a data-toggle="collapse" data-parent="#accordion" href="#collapse-change-father">
                                        <%t Genealogist.CAHNGE_FATHER 'Change Father' %>
                                    </a>
                                </h4>
                            </div>

                            <div id="collapse-change-father" class="panel-collapse collapse">
                                <div class="panel-body">
                                    $Up.Form_ChangeFather($ID)
                                </div>
                            </div>
                        </div>

                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h4 class="panel-title">
                                    <a data-toggle="collapse" data-parent="#accordion" href="#collapse-change-methor">
                                        <%t Genealogist.CHANGE_MOTHER 'Change Mother' %>
                                    </a>
                                </h4>
                            </div>

                            <div id="collapse-change-methor" class="panel-collapse collapse">
                                <div class="panel-body">
                                    $Up.Form_ChangeMother($ID)
                                </div>
                            </div>
                        </div>

                        <% if isMale %>
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h4 class="panel-title">
                                    <a data-toggle="collapse" data-parent="#accordion" href="#collapse-add-sons">
                                        <%t Genealogist.ADD_SONS 'Add Sons' %>
                                    </a>
                                </h4>
                            </div>

                            <div id="collapse-add-sons" class="panel-collapse collapse">
                                <div class="panel-body">
                                    $Up.Form_AddSons($ID)
                                </div>
                            </div>
                        </div>

                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h4 class="panel-title">
                                    <a data-toggle="collapse" data-parent="#accordion" href="#collapse-add-daughers">
                                        <%t Genealogist.ADD_DAUGHTERS 'Add Daughters' %>
                                    </a>
                                </h4>
                            </div>

                            <div id="collapse-add-daughers" class="panel-collapse collapse">
                                <div class="panel-body">
                                    $Up.Form_AddDaughters($ID)
                                </div>
                            </div>
                        </div>
                        <% end_if %>

                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h4 class="panel-title">
                                    <a data-toggle="collapse" data-parent="#accordion" href="#collapse-add-spouse">
                                        <% if $isMale %>
                                            <%t Genealogist.ADD_WIFE 'Add Wife' %>
                                        <% else %>
                                            <%t Genealogist.ADD_HUSBAND 'Add Husband' %>
                                        <% end_if %>
                                    </a>
                                </h4>
                            </div>

                            <div id="collapse-add-spouse" class="panel-collapse collapse">
                                <div class="panel-body">
                                    $Up.Form_AddSpouse($ID)
                                </div>
                            </div>
                        </div>

                        <% if isMale %>
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h4 class="panel-title">
                                    <a data-toggle="collapse" data-parent="#accordion" href="#collapse-single-wife">
                                        <%t Genealogist.SINGLE_WIFE 'This Person Has One Wife Only' %>
                                    </a>
                                </h4>
                            </div>

                            <div id="collapse-single-wife" class="panel-collapse collapse">
                                <div class="panel-body">
                                    $Up.Form_SingleWife($ID)
                                </div>
                            </div>
                        </div>
                        <% end_if %>

                        <div class="panel panel-danger">
                            <div class="panel-heading">
                                <h4 class="panel-title">
                                    <a data-toggle="collapse" data-parent="#accordion" href="#collapse-delete-person">
                                        <%t Genealogist.DELETE_PERSON 'Delete This Person' %>
                                    </a>
                                </h4>
                            </div>

                            <div id="collapse-delete-person" class="panel-collapse collapse">
                                <div class="panel-body">
                                    $Up.Form_DeletePerson($ID)
                                </div>
                            </div>
                        </div>
                    </div>    
                </div>
        <% end_with %>
    </div>
</div>