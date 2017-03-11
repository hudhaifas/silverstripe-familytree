<div class="">
    <div id="filters-list">
        <div class="checkbox"><label><input type="checkbox" id="ancestral" class="options-check" value="0"><%t Genealogist.ANCESTRAL_TREE 'Ancestral Tree' %></label></div>
        <div class="checkbox"><label><input type="checkbox" id="m" class="options-check" value="0"><%t Genealogist.SHOW_MALES 'Show Males' %></label></div>
        <div class="checkbox"><label><input type="checkbox" id="ms" class="options-check" value="0"><%t Genealogist.SHOW_MALES_CHILDREN 'Show Males Children' %></label></div>
        <% if hasPermission %>
            <div class="checkbox"><label><input type="checkbox" id="f" class="options-check" value="0"><%t Genealogist.SHOW_FEMALES 'Show Females' %></label></div>
            <div class="checkbox"><label><input type="checkbox" id="fs" class="options-check" value="0"><%t Genealogist.SHOW_FEMALES_CHILDREN 'Show Females Children' %></label></div>
        <% end_if %>
    </div>
</div>
