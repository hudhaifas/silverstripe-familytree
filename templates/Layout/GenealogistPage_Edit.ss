<% with Person %>
<article class="col-md-8 col-lg-8">
    <div class="row">
        $Up.SearchPerson
    </div>

    <div class="row">
        <h1>$AliasName <span style="font-size: 50%;">$Father.FullName <a href="{$ShowLink}" target="_blanck" title="<%t Genealogist.SHOW_THIS 'Show this person tree' %>"><i class="fa fa-external-link" aria-hidden="true"></i></a></span></h1>
    </div>

    <div class="row">
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
</article>

<article class="col-md-4 col-lg-4">
    <a href="{$ShowLink}" target="_blanck" title="<%t Genealogist.SHOW_THIS 'Show this person tree' %>"><%t Genealogist.SHOW_THIS 'Show this person tree' %></a>
    <br />

    <% if BirthDate %><%t Genealogist.BIRTHDATE 'Birth Date' %>: $BirthDate<br /><% end_if %>
    <% if DeathDate %><%t Genealogist.DEATHDATE 'Death Date' %>: $DeathDate<br /><% end_if %>
    <% if Age %><%t Genealogist.AGE 'Age' %>: $Age<br /><% end_if %>


    <% if Father %>
        <hr />
        <b><%t Genealogist.FATHER 'Father' %></b><br />
        
        <a href="{$Father.EditLink()}">$Father.FullName</a>
    <% end_if %>

    <% if Mother %>
        <hr />
        <b><%t Genealogist.MOTHER 'Mother' %></b><br />
        
        <a href="{$Mother.EditLink()}">$Mother.FullName</a>
    <% end_if %>

    <% if Husbands %>
        <hr />
        <b><%t Genealogist.HUSBANDS 'Husbands' %></b>: $Husbands.Count<br />
        
        <% loop Husbands %>
            <a href="{$EditLink()}">$FullName</a><br />
        <% end_loop %>

    <% else_if Wives %>
        <hr />
        <b><%t Genealogist.WIVES 'Wives' %></b>: $Wives.Count<br />
        
        <ul>
            <% loop Wives %>
            <li><a href="{$EditLink()}">$FullName</a><br /></li>
            <% end_loop %>
        </ul>
    <% end_if %>

    <% if Sons %>
        <hr />
        <b><%t Genealogist.SONS 'Sons' %></b>: $SonsCount<br />
        
        <% loop Sons.sort('BirthDate DESC').sort('Created ASC') %>
            <a href="{$EditLink()}" title="$FullName">$AliasName</a><% if not Last %><%t Genealogist.COMMA ',' %><% end_if %>
        <% end_loop %>
        <br />
    <% end_if %>

    <% if Daughters %>
        <hr />
        <b><%t Genealogist.DAUGHTERS 'Daughters' %></b>: $DaughtersCount<br />
        
        <% loop Daughters.sort('BirthDate DESC').sort('Created ASC') %>
            <a href="{$EditLink()}" title="$FullName">$AliasName</a><% if not Last %><%t Genealogist.COMMA ',' %><% end_if %>
        <% end_loop %>
    <% end_if %>

</article>
<% end_with %>