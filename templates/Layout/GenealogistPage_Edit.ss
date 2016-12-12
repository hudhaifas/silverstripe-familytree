<% with Person %>
<article class="col-md-8 col-lg-8">
    <div class="row">
        <h1>$Name <span style="font-size: 50%;">$Father.FullName</span></h1>
    </div>

    <div class="row">
        <div class="panel-group" id="accordion">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4 class="panel-title">
                        <a data-toggle="collapse" data-parent="#accordion" href="#collapse1">
                            <%t Genealogist.EDIT 'Edit' %>
                        </a>
                    </h4>
                </div>

                <div id="collapse1" class="panel-collapse collapse in">
                    <div class="panel-body">
                        $Up.Form_EditPerson($ID)
                    </div>
                </div>
            </div>

            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4 class="panel-title">
                        <a data-toggle="collapse" data-parent="#accordion" href="#collapse2">
                            <%t Genealogist.ADD_PARENT 'Add Parent' %>
                        </a>
                    </h4>
                </div>

                <div id="collapse2" class="panel-collapse collapse">
                    <div class="panel-body">
                        $Up.Form_AddParent($ID)
                    </div>
                </div>
            </div>

            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4 class="panel-title">
                        <a data-toggle="collapse" data-parent="#accordion" href="#collapse3">
                            <%t Genealogist.CAHNGE_PARENT 'Change Parent' %>
                        </a>
                    </h4>
                </div>

                <div id="collapse3" class="panel-collapse collapse">
                    <div class="panel-body">
                        $Up.Form_ChangeParent($ID)
                    </div>
                </div>
            </div>

            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4 class="panel-title">
                        <a data-toggle="collapse" data-parent="#accordion" href="#collapse4">
                            <%t Genealogist.ADD_SONS 'Add Sons' %>
                        </a>
                    </h4>
                </div>

                <div id="collapse4" class="panel-collapse collapse in">
                    <div class="panel-body">
                        $Up.Form_AddSons($ID)
                    </div>
                </div>
            </div>

            <%-- 
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4 class="panel-title">
                        <a data-toggle="collapse" data-parent="#accordion" href="#collapse4">
                            <%t Genealogist.DELETE_PERSON 'Delete Person' %>
                        </a>
                    </h4>
                </div>

                <div id="collapse4" class="panel-collapse collapse">
                    <div class="panel-body">
                        $Up.Form_DeletePerson($ID)
                    </div>
                </div>
            </div>
            --%>
        </div>    
    </div>
</article>

<article class="col-md-4 col-lg-4">
    <% if BirthDate %><%t Genealogist.BIRTHDATE 'Birth Date' %>: $BirthDate<br /><% end_if %>
    <% if DeathDate %><%t Genealogist.DEATHDATE 'Death Date' %>: $DeathDate<br /><% end_if %>
    <% if Age %><%t Genealogist.AGE 'Age' %>: $Age<br /><% end_if %>

    <hr />

    <b><%t Genealogist.FATHER 'Father' %></b><br />
    <a href="{$Father.EditLink()}" data-url="{$Father.EditLink()}" class="info-item">$Father.FullName</a>
    
    <% if Mother %>
        <hr />

        <b><%t Genealogist.MOTHER 'Mother' %></b><br />
        <a href="{$Mother.EditLink()}" data-url="{$Mother.EditLink()}" class="info-item">$Mother.FullName</a>
        <br />
    <% end_if %>

    <% if Husband %>
        <hr />

        <b><%t Genealogist.SPOUSE 'Spouse' %></b><br />
        <a href="{$Husband.EditLink()}" data-url="{$Husband.EditLink()}" class="info-item">$Husband.FullName</a>

    <% else_if Wife %>
    <hr />

    <b><%t Genealogist.SPOUSE 'Spouse' %></b><br />
        <a href="{$Wife.EditLink()}" data-url="{$Wife.EditLink()}" class="info-item">$Wife.FullName</a>
    <% end_if %>

    <% if Children %>
        <hr />

        <b><%t Genealogist.SONS 'Sons' %></b>: $SonsCount<br />
        <% loop $Sons %>
            <a href="{$EditLink()}" data-url="{$EditLink()}" class="info-item">$AliasName</a><% if not Last %><%t Genealogist.COMMA ',' %><% end_if %>
        <% end_loop %>
        <br />

        <hr />
        <b><%t Genealogist.DAUGHTERS 'Daughters' %></b>: $DaughtersCount<br />
        <% loop Daughters %>
            <a href="{$EditLink()}" data-url="{$EditLink()}" class="info-item">$AliasName</a><% if not Last %><%t Genealogist.COMMA ',' %><% end_if %>
        <% end_loop %>
    <% end_if %>

</article>
<% end_with %>