<% if ShowTimeline %>
    <a class="btn btn-primary" href="" id="timeline-btn"><%t Genealogist.TIMELINE 'Timeline' %></a>
    <div class="well" style="display: none">
        <input 
            id="timeline-input" 
            data-slider-id='timeline-slider'
            type="text" 
            data-slider-min="1900"
            data-slider-max="2017"
            data-slider-step="1"
            data-slider-value="1980" />
    </div>
<% end_if %>

<% loop Trees %>
    <ul id="genealogy-relations" class="genealogy-kinship" style="display:none" data-kinship="k{$Pos}" data-multiple="{$Up.Multiple}">
        $Tree
    </ul>

    <div id="k{$Pos}" class="genealogy-tree dragscroll col-md-{$Up.Cols}"></div>
<% end_loop %>
    
<div id="tree-loader" class="ajax-loader">
    <span></span>
    <i class="fa fa-spinner fa-pulse fa-4x fa-fw"></i>
</div>

