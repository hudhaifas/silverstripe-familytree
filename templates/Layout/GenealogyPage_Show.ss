<% with Person %>
<div class="col-md-9">
    <div class="row">
        <div class="col-lg-3 col-md-6 col-xs-12">
            <% include Images_Person %>
        </div>

        <div class="col-lg-9 col-md-6 col-xs-12">
            <h2>$FullName <a href="{$ShowLink}" target="_blanck" title="<%t Genealogist.SHOW_THIS 'Show this person tree' %>"><i class="fa fa-external-link" aria-hidden="true"></i></a></h2>

            <% if BirthDate %><%t Genealogist.BIRTHDATE 'Birth Date' %>: $BirthDate<br /><% end_if %>
            <% if DeathDate %><%t Genealogist.DEATHDATE 'Death Date' %>: $DeathDate<br /><% end_if %>
            <% if Age %><%t Genealogist.AGE 'Age' %>: $Age<br /><% end_if %>

        </div>

    </div>

    <div class="row">
        <ul class="nav nav-tabs">
            <% if Biography %>
                <li class="active"><a data-toggle="tab" href="#biography"><%t Genealogist.BIOGRAPHY "Biography" %></a></li>
            <% end_if %>
            <% if Biography %>
                <li><a data-toggle="tab" href="#documents"><%t Genealogist.DOCUMENTS "Documents" %></a></li>
            <% end_if %>
        </ul>
        <div class="tab-content">
            <% if Biography %>
                <div id="biography" class="tab-pane fade in active">
                    $Biography
                </div>
            <% end_if %>
            <% if Biography %>
                <div id="documents" class="tab-pane fade in active">
                </div>
            <% end_if %>

        </div>
    </div>
</div>

<div class="col-md-3">
    <% if $Father %>
        <b><%t Genealogist.FATHER 'Father' %></b><br />
        <a href="{$Father.ShowLink()}" target="_blanck">$Father.FullName</a>
    <% end_if %>

    <% if Mother %>
        <hr />
        <b><%t Genealogist.MOTHER 'Mother' %></b><br />
        
        <a href="{$Mother.ShowLink()}" target="_blanck">$Mother.FullName</a>
        <br />
    <% end_if %>

    <% if Husbands %>
        <hr />
        <b><%t Genealogist.HUSBANDS 'Husbands' %></b><br />
        
        <% loop Husbands %>
            <a href="{$ShowLink()}" target="_blanck">$FullName</a><br />
        <% end_loop %>

    <% else_if Wives %>
        <hr />
        <b><%t Genealogist.WIVES 'Wives' %></b><br />
        
        <% loop Wives %>
            <a href="{$ShowLink()}" target="_blanck">$FullName</a><br />
        <% end_loop %>
    <% end_if %>

    <% if Sons %>
        <hr />
        <b><%t Genealogist.SONS 'Sons' %></b>: $SonsCount<br />
        
        <% loop Sons.sort('BirthDate DESC').sort('Created ASC') %>
            <a href="{$ShowLink()}" target="_blanck" title="$FullName">$AliasName</a><% if not Last %><%t Genealogist.COMMA ',' %><% end_if %>
        <% end_loop %>
        <br />
    <% end_if %>

    <% if Daughters %>
        <hr />
        <b><%t Genealogist.DAUGHTERS 'Daughters' %></b>: $DaughtersCount<br />
        
        <% loop Daughters.sort('BirthDate DESC').sort('Created ASC') %>
            <a href="{$ShowLink()}" target="_blanck" title="$FullName">$AliasName</a><% if not Last %><%t Genealogist.COMMA ',' %><% end_if %>
        <% end_loop %>
    <% end_if %>
</div>
<% end_with %>