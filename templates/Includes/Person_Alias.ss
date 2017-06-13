<strong>$ShortName</strong>

<% if not isClan %>
    <% if canView || IsPublicFigure %>
        <span style="font-size: 82%;">
            <% if BirthDate %>
                $BirthYear<% if BirthDateEstimated %><%t Genealogist.ESTIMATED '(Estimated)' %><% end_if %>
                <% else_if CalculatedBirthYear %>
                $CalculatedBirthYear<%t Genealogist.CALCULATIONS '(Calculations)' %>
            <% end_if %>

            <% if BirthDate || CalculatedBirthYear %>
                <% if DeathDate || CalculatedDeathYear %> - <% end_if %>
            <% end_if %>

            <% if DeathDate %>
                $DeathYear<% if DeathDateEstimated %><%t Genealogist.ESTIMATED '(Estimated)' %><% end_if %>
            <% else_if CalculatedDeathYear %>
                $CalculatedDeathYear<%t Genealogist.CALCULATIONS '(Calculations)' %>
            <% end_if %>
        </span>
    <% end_if %>
<% end_if %>