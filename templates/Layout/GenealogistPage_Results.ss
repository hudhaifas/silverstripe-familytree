<div class="row">
    <% if $Results %>
        <% loop $Results %>
            <div class="col-md-6">
                <a href="$EditLink">
                        <h5>$FullName</h5>
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