<% with Person %>
<article class="col-md-8 col-lg-8">
    <div class="row">
            $Up.SearchPerson
    </div>

    <div class="row">
        <h1>$AliasName <span style="font-size: 50%;">$Father.FullName</span></h1>
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
                            <%t Genealogist.ADD_FATHER 'Add Father' %>
                        </a>
                    </h4>
                </div>

                <div id="collapse2" class="panel-collapse collapse">
                    <div class="panel-body">
                        $Up.Form_AddFather($ID)
                    </div>
                </div>
            </div>

            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4 class="panel-title">
                        <a data-toggle="collapse" data-parent="#accordion" href="#collapse3">
                            <%t Genealogist.CAHNGE_FATHER 'Change Father' %>
                        </a>
                    </h4>
                </div>

                <div id="collapse3" class="panel-collapse collapse">
                    <div class="panel-body">
                        $Up.Form_ChangeFather($ID)
                    </div>
                </div>
            </div>

            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4 class="panel-title">
                        <a data-toggle="collapse" data-parent="#accordion" href="#collapse-methor">
                            <%t Genealogist.CHANGE_MOTHER 'Change Mother' %>
                        </a>
                    </h4>
                </div>

                <div id="collapse-methor" class="panel-collapse collapse">
                    <div class="panel-body">
                        $Up.Form_ChangeMother($ID)
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

                <div id="collapse4" class="panel-collapse collapse">
                    <div class="panel-body">
                        $Up.Form_AddSons($ID)
                    </div>
                </div>
            </div>

            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4 class="panel-title">
                        <a data-toggle="collapse" data-parent="#accordion" href="#collapse5">
                            <%t Genealogist.ADD_DAUGHTERS 'Add Daughters' %>
                        </a>
                    </h4>
                </div>

                <div id="collapse5" class="panel-collapse collapse">
                    <div class="panel-body">
                        $Up.Form_AddDaughters($ID)
                    </div>
                </div>
            </div>

            <%-- 
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4 class="panel-title">
                        <a data-toggle="collapse" data-parent="#accordion" href="#collapse6">
                            <%t Genealogist.DELETE_PERSON 'Delete Person' %>
                        </a>
                    </h4>
                </div>

                <div id="collapse6" class="panel-collapse collapse">
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
    <a href="{$ShowLink}" target="_blanck" title="<%t Genealogist.SHOW_THIS 'Show this person tree' %>"><%t Genealogist.SHOW_THIS 'Show this person tree' %></a>
    <br />

    <% if BirthDate %><%t Genealogist.BIRTHDATE 'Birth Date' %>: $BirthDate<br /><% end_if %>
    <% if DeathDate %><%t Genealogist.DEATHDATE 'Death Date' %>: $DeathDate<br /><% end_if %>
    <% if Age %><%t Genealogist.AGE 'Age' %>: $Age<br /><% end_if %>

    <hr />

    <b><%t Genealogist.FATHER 'Father' %></b><br />
    <a href="{$Father.EditLink()}">$Father.FullName</a>
    
    <% if Mother %>
        <hr />

        <b><%t Genealogist.MOTHER 'Mother' %></b><br />
        <a href="{$Mother.EditLink()}">$Mother.FullName</a>
        <br />
    <% end_if %>

    <% if Husbands %>
        <hr />
        <b><%t Genealogist.HUSBANDS 'Husbands' %></b><br />
        <% loop Husbands %>
            <a href="{$EditLink()}">$FullName</a><br />
        <% end_loop %>

    <% else_if $hasPermission && Wives %>
        <hr />

        <b><%t Genealogist.WIVES 'Wives' %></b><br />
        <% loop Wives %>
            <a href="{$EditLink()}">$FullName</a><br />
        <% end_loop %>
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