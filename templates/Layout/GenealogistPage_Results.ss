<div class="row">
    <% if $Results %>
        <% loop $Results %>
            <div class="col-md-6">
                <a href="$EditLink" title="$FullName">
                    <h5>$Name <span style="font-size: 90%; font-weight: normal;">$Father.FullName.LimitCharacters(45)</span></h5>
                </a>
                <% if Mother %>
                    <sub><%t Genealogist.MOTHER 'Mother' %>: $Mother.FullName</sub>
                <% end_if %>
                <hr />
            </div>
        <% end_loop %>
    <% end_if %>
</div>

<div class="row">
    <% with $Results %>
        <% include Paginate %>
    <% end_with %>
</div>