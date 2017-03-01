<p class="title"><a href="$ObjectLink" title="$Title">$Title.LimitCharacters(50)</a></p>

<p class="details"><a href="{$TreeLink}"><%t Genealogist.SHOW_TREE 'Show genealogist tree' %></a></p>

<% if hasPermission %>
    <p class="details edit"><a href="{$EditLink}"><%t Genealogist.EDIT_THIS 'Edit this person' %></a></p>
<% end_if %>

<% if BirthDate && BirthDateEstimated %>
    <%t Genealogist.ESTIMATED_BIRTHDATE 'Birth Date (Estimated)' %>: $BirthYear<br />
<% else_if BirthDate %>
    <%t Genealogist.BIRTHDATE 'Birth Date' %>: $BirthDate<br />
<% else_if CalculatedBirthYear %>
    <%t Genealogist.CALCULATIONS_BIRTHDATE 'Birth Date (Calculations)' %>: $CalculatedBirthYear<br />
<% end_if %>

<% if DeathDate && DeathDateEstimated %>
    <%t Genealogist.ESTIMATED_DEATHDATE 'Death Date (Estimated)' %>: $DeathYear<br />
<% else_if DeathDate %>
    <%t Genealogist.DEATHDATE 'Death Date' %>: $DeathDate<br />
<% else_if CalculatedDeathYear %>
    <%t Genealogist.CALCULATIONS_DEATHDATE 'Death Date (Calculations)' %>: $CalculatedDeathYear<br />
<% end_if %>

<% if Age %>
    <p class="details"><%t Genealogist.AGE 'Age' %>: $Age</p>
<% end_if %>
