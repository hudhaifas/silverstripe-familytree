<% with Person %>
<article class="col-md-8 col-lg-8">
    <div class="row">
        <p><%t Genealogist.SUGGEST_EDIT_ON 'Suggest edit on' %></p>
        <h1>$FirstName <span style="font-size: 50%;">$Father.FullName</span></h1>
    </div>

    <div class="row">
        $Up.Form_Suggest($ID)
    </div>
</article>

<article class="col-md-4 col-lg-4">
    <a href="{$TreeLink}" target="_blanck" title="<%t Genealogist.SHOW_THIS 'Show this person tree' %>"><%t Genealogist.SHOW_THIS 'Show this person tree' %></a>
    <br />

    <% if BirthDate %><%t Genealogist.BIRTHDATE 'Birth Date' %>: $BirthDate<br /><% end_if %>
    <% if DeathDate %><%t Genealogist.DEATHDATE 'Death Date' %>: $DeathDate<br /><% end_if %>
    <% if Age %><%t Genealogist.AGE 'Age' %>: $Age<br /><% end_if %>

    <hr />

    <b><%t Genealogist.FATHER 'Father' %></b><br />
    <a href="{$Father.SuggestLink}">$Father.FullName</a>

    <% if Mother && Mother.canView || Mother.IsPublicFigure %>
        <hr />

        <b><%t Genealogist.MOTHER 'Mother' %></b><br />
        <a href="{$Mother.SuggestLink}">$Mother.FullName</a>
        <br />
    <% end_if %>

    <% if Husbands && ViewableHusbands %>
        <hr />
        <p>
            <b><%t Genealogist.HUSBANDS 'Husbands' %></b>: $Husbands.Count<br />

            <ul>
                <% loop Husbands.Sort(HusbandOrder) %>
                    <% if canView || IsPublicFigure %>
                        <li><a href="{$SuggestLink}">$FullName</a></li>
                    <% end_if %>
                <% end_loop %>
            </ul>
        </p>

    <% else_if Wives && ViewableWives %>
        <hr />
        <p>
            <b><%t Genealogist.WIVES 'Wives' %></b>: $Wives.Count<br />

            <ul>
                <% loop Wives.Sort(WifeOrder) %>
                    <% if canView || IsPublicFigure %>
                        <li><a href="{$SuggestLink}">$FullName</a></li>
                    <% end_if %>
                <% end_loop %>
            </ul>
        </p>
    <% end_if %>

    <% if Sons && ViewableSons %>
        <hr />
        <p>
            <b><%t Genealogist.SONS 'Sons' %></b>: $SonsCount<br />

            <% loop Sons %>
                <% if canView || IsPublicFigure %>
                    <a href="{$SuggestLink}" title="$FullName">$AliasName</a><% if not Last %><%t Genealogist.COMMA ',' %><% end_if %>
                <% end_if %>
            <% end_loop %>
        </p>
    <% end_if %>

    <% if Daughters && ViewableDaughters %>
        <hr />
        <p>
            <b><%t Genealogist.DAUGHTERS 'Daughters' %></b>: $DaughtersCount<br />

            <% loop Daughters %>
                <% if canView || IsPublicFigure %>
                    <a href="{$SuggestLink}" title="$FullName">$AliasName</a><% if not Last %><%t Genealogist.COMMA ',' %><% end_if %>
                <% end_if %>
            <% end_loop %>
        </p>
    <% end_if %>
</article>
<% end_with %>