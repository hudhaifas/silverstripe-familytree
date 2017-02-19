<% if Trees.Count == 1 %>
    <a class="btn btn-primary" href="" id="ex20a"><%t Genealogist.TIMELINE 'Timeline' %></a>
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
    <div>
    </div>
<% end_if %>

<% loop Trees %>
    <ul id="genealogy-relations" class="genealogy-kinship" style="display:none" data-kinship="k{$Pos}">
        $Tree
    </ul>

    <div id="k{$Pos}" class="genealogy-tree dragscroll col-md-{$Up.Cols}"></div>
<% end_loop %>
    
<div id="tree-loader" class="ajax-loader">
    <span></span>
    <i class="fa fa-spinner fa-pulse fa-4x fa-fw"></i>
</div>

