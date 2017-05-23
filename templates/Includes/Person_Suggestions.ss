<div>
    <% if canEdit && Suggestions %>
    <p>
        <b><%t Genealogist.SUGGESTIONS 'Suggestions' %></b>: $Suggestions.Count<br />
    </p>

    <table class="table">
        <tr>
            <th><%t Genealogist.FROM 'From' %></th>
            <th><%t Genealogist.SUBJECT 'Subject' %></th>
            <th><%t Genealogist.MESSAGE 'Message' %></th>
            <th><%t Genealogist.CREATED 'Created' %></th>
            <th><%t Genealogist.PROCEEDED 'Proceeded' %></th>
        </tr>

        <% loop Suggestions %>
        <tr>
            <td>$Name</td>
            <td>$Subject</td>
            <td>$Message</td>
            <td>$Created.Nice</td>
            <td>$Proceeded</td>
        </tr>
        <% end_loop %>
    </table>
    <% end_if %>
</div>
