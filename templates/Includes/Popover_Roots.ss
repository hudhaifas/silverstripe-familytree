<div class="hide">
    <div id="roots-list" style="max-height: 360px; overflow-y: auto;">
        <% loop RootClans.Sort(Name, ASC) %>
            <div class="list-group-item"><a href="{$TreeLink}" class="options-item">$ShortName</a></div>
        <% end_loop %>
    </div>
</div>