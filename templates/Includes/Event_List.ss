<h4>$ListTitle <super>($Results.Count)</super></h4>

<div class="events-container">
    <% if Results  %>
        <div class="dataobject-grid">
            <% loop $Results %>
                <% if not $isObjectDisabled %>
                    <div class="dataobject-item events-item">
                        <% include Event_Item %>
                    </div>
                <% end_if %>
            <% end_loop %>
        </div>

    <% else %>
        <div class="row">
            <p><%t DataObjectPage.SEARCH_NO_RESULTS 'Sorry, your search query did not return any results.' %></p>
        </div>
    <% end_if %>
</div>