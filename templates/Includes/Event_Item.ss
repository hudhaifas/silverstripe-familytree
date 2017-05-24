<div>
    <a <% if not $isObjectDisabled %>href="$ObjectLink"<% end_if %> title="$ObjectTitle">
        <div class="thumbnail text-center col-sm-12 dataobject-image">
            <% include List_Image %>

            <% if not $isObjectDisabled %>
                <div class="mask">
                    <div class="info"><%t DataObjectPage.MORE_ABOUT 'More' %></div>
                </div>
            <% end_if %>
        </div>


        <div class="content col-sm-12 dataobject-summary">
            <% include Single_Summary %>
        </div>		
    </a>
    
    <div class="event-ago">
        $EventAgo
    </div>
</div>