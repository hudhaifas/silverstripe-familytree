<p class="title"><a <% if canView || IsPublicFigure %>href="$ObjectLink"<% end_if %> title="$Title">$Title.LimitCharacters(50)</a></p>

<p class="details"><a href="{$TreeLink}"><%t Genealogist.SHOW_TREE 'Show genealogist tree' %></a></p>

<% if not isClan %>
    <% if canView || IsPublicFigure %>
        <p class="details edit">
            <% if BirthDate && BirthDateEstimated %>
                <%t Genealogist.ESTIMATED_BIRTHDATE 'Birth Date (Estimated)' %>: $BirthYear
            <% else_if BirthDate %>
                <%t Genealogist.BIRTHDATE 'Birth Date' %>: $BirthDate
            <% else_if CalculatedBirthYear %>
                <%t Genealogist.CALCULATIONS_BIRTHDATE 'Birth Date (Calculations)' %>: $CalculatedBirthYear
            <% end_if %>
        </p>

        <p class="details edit">
            <% if DeathDate && DeathDateEstimated %>
                <%t Genealogist.ESTIMATED_DEATHDATE 'Death Date (Estimated)' %>: $DeathYear
            <% else_if DeathDate %>
                <%t Genealogist.DEATHDATE 'Death Date' %>: $DeathDate
            <% else_if CalculatedDeathYear %>
                <%t Genealogist.CALCULATIONS_DEATHDATE 'Death Date (Calculations)' %>: $CalculatedDeathYear
            <% end_if %>
        </p>

        <% if Age %>
            <p class="details"><%t Genealogist.AGE 'Age' %>: $Age</p>
        <% end_if %>
    <% end_if %>
<% end_if %>