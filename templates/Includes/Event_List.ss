<h4>$ListTitle <super>($Results.Count)</super></h4>
<div class="cover-container">
    <% loop Results %>
    <% if not $isObjectDisabled %>
    <div class="cover-item">
        <a <% if not $isObjectDisabled %>href="$ObjectLink"<% end_if %> title="$ObjectTitle ($EventDate)">
            <div class="thumbnail text-center col-sm-12 col-xs-4 dataobject-image">
                <% include List_Image %>

                <% if not $isObjectDisabled %>
                <div class="mask">
                    <div class="info"><%t DataObjectPage.MORE_ABOUT 'More' %></div>
                </div>
                <% end_if %>
            </div>

            <div class="content col-sm-12 col-xs-8 dataobject-summary">
                <% include Single_Summary %>
            </div>		
        </a>
    </div>
    <% end_if %>
    <% end_loop %>
</div>
