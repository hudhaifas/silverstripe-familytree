<p class="title"><a href="$ObjectLink" title="$Title">$Title.LimitCharacters(50)</a></p>

<p class="details"><a href="{$TreeLink}" target="_blank"><%t Genealogist.SHOW_TREE 'Show genealogist tree' %></a></p>

<% if hasPermission %>
    <p class="details edit"><a href="{$EditLink}" target="_blank"><%t Genealogist.EDIT_THIS 'Edit this person' %></a></p>
<% end_if %>

<% if $BirthDate %>
    <p class="details"><%t Genealogist.BIRTHDATE 'Birth Date' %>: $BirthDate</p>
<% end_if %>
<% if DeathDate %>
    <p class="details"><%t Genealogist.DEATHDATE 'Death Date' %>: $DeathDate</p>
<% end_if %>
<% if Age %>
    <p class="details"><%t Genealogist.AGE 'Age' %>: $Age</p>
<% end_if %>
