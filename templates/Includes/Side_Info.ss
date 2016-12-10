<div class="panel-heading clearfix">
    <h4 class="panel-title pull-left">
        <a data-toggle="collapse" data-parent="#accordion" href="#collapse2">$AliasName</a>
    </h4>

    <div class="pull-right">
        <a href="{$Link}" class="options-item"><i class="fa fa-eye" aria-hidden="true"></i></a>
        <a href="{$Father.Link}" class="options-item"><i class="fa fa-level-up" aria-hidden="true"></i></a>
        <a href="{$Root.Link}" class="options-item"><i class="fa fa-undo" aria-hidden="true"></i></span></a>
    </div>
</div>

<div id="collapse2" class="panel-collapse collapse">
    <div class="panel-body">
        $FullName<br />
        <% if BirthDate %><%t FamilyTree.BIRTHDATE 'Birth Date' %>: $BirthDate<br /><% end_if %>
        <% if DeathDate %><%t FamilyTree.DEATHDATE 'Death Date' %>: $DeathDate<br /><% end_if %>
        <% if Age %><%t FamilyTree.AGE 'Age' %>: $Age<br /><% end_if %>

        <% if $hasPermission && Mother %>
            <hr />

            <b><%t FamilyTree.MOTHER 'Mother' %></b><br />
            <a href="#" data-url="{$Mother.InfoLink()}" class="info-item">$Mother.FullName</a>
            <br />
        <% end_if %>

        <% if Husband %>
            <hr />

            <b><%t FamilyTree.SPOUSE 'Spouse' %></b><br />
            <a href="#" data-url="{$Husband.InfoLink()}" class="info-item">$Husband.FullName</a>

        <% else_if $hasPermission && Wife %>
            <hr />

            <b><%t FamilyTree.SPOUSE 'Spouse' %></b><br />
            <a href="#" data-url="{$Wife.InfoLink()}" class="info-item">$Wife.FullName</a>
        <% end_if %>

        <% if Children %>
            <hr />

            <b><%t FamilyTree.OFFSPRING 'Offspring' %></b><br />
            <%t FamilyTree.SONS 'Sons' %>: $SonsCount<br />

            <% if $hasPermission %>
                <%t FamilyTree.DAUGHTERS 'Daughters' %>: $DaughtersCount<br />
            <% end_if %>

            <%t FamilyTree.MALES 'Males' %>: $MalesCount<br />

            <% if $hasPermission %>
                <%t FamilyTree.FEMALES 'Females' %>: $FemalesCount<br />
                <%t FamilyTree.TOTAL 'Total' %>: $OffspringCount<br />
            <% end_if %>

            <hr />

            <b><%t FamilyTree.ALIVE 'Alive' %></b><br />
            <%t FamilyTree.SONS 'Sons' %>: {$SonsCount(1)}<br />

            <% if $hasPermission %>
                <%t FamilyTree.DAUGHTERS 'Daughters' %>: $DaughtersCount(1)<br />
            <% end_if %>

            <%t FamilyTree.MALES 'Males' %>: $MalesCount(1)<br />
            <% if $hasPermission %>
                <%t FamilyTree.FEMALES 'Females' %>: $FemalesCount(1)<br />
                <%t FamilyTree.TOTAL 'Total' %>: $OffspringCount(1)<br />
            <% end_if %>
        <% end_if %>
    </div>
</div>