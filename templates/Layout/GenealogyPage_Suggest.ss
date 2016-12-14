<% with Person %>
<article class="col-md-8 col-lg-8">
    <div class="row">
        <p><%t Genealogist.SUGGEST_EDIT_ON 'Suggest edit on' %></p>
        <h1>$Name <span style="font-size: 50%;">$Father.FullName</span></h1>
    </div>

    <div class="row">
        $Up.Form_Suggest($ID)
    </div>
</article>

<article class="col-md-4 col-lg-4">
    <% if BirthDate %><%t Genealogist.BIRTHDATE 'Birth Date' %>: $BirthDate<br /><% end_if %>
    <% if DeathDate %><%t Genealogist.DEATHDATE 'Death Date' %>: $DeathDate<br /><% end_if %>
    <% if Age %><%t Genealogist.AGE 'Age' %>: $Age<br /><% end_if %>

    <hr />

    <b><%t Genealogist.FATHER 'Father' %></b><br />
    <a href="{$Father.SuggestLink}">$Father.FullName</a>

    <% if Mother %>
    <hr />

    <b><%t Genealogist.MOTHER 'Mother' %></b><br />
    <a href="{$Mother.SuggestLink}">$Mother.FullName</a>
    <br />
    <% end_if %>

    <% if Husband %>
    <hr />

    <b><%t Genealogist.SPOUSE 'Spouse' %></b><br />
    <a href="{$Husband.SuggestLink}">$Husband.FullName</a>

    <% else_if Wife %>
    <hr />

    <b><%t Genealogist.SPOUSE 'Spouse' %></b><br />
    <a href="{$Wife.SuggestLink}">$Wife.FullName</a>
    <% end_if %>

    <% if Children %>
    <hr />

    <b><%t Genealogist.SONS 'Sons' %></b>: $SonsCount<br />
    <% loop $Sons %>
    <a href="{$SuggestLink}">$AliasName</a><% if not Last %><%t Genealogist.COMMA ',' %><% end_if %>
    <% end_loop %>
    <br />

    <hr />
    <b><%t Genealogist.DAUGHTERS 'Daughters' %></b>: $DaughtersCount<br />
    <% loop Daughters %>
    <a href="{$SuggestLink}">$AliasName</a><% if not Last %><%t Genealogist.COMMA ',' %><% end_if %>
    <% end_loop %>
    <% end_if %>

</article>
<% end_with %>