<% if ShowTimeline %>
    <a class="btn btn-primary" href="" id="timeline-btn"><%t Genealogist.TIMELINE 'Timeline' %></a>
    <div class="well" style="display: none">
        <div class="row">
            <div class="col-sm-1">
                <button id="timeline-control" class="timeline-control center-block"><i class="fa fa-3x fa-play-circle" aria-hidden="true"></i></button>
            </div>
            
            <div class="col-sm-11">
                <input
                    id="timeline-input" 
                    data-slider-id='timeline-slider'
                    type="text" 
                    data-slider-min="1900"
                    data-slider-max="2017"
                    data-slider-step="1"
                    data-slider-value="1980" />
            </div>
        </div>
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

<% include Poopover_Filters %>
<% include Popover_Kinship %>
<% include Popover_Roots %>
