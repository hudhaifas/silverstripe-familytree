<section class="tl-container <% if isFemale %>female<% end_if %>">
    <div class="tl-plain-block">
        <p></p>
    </div>

    <% loop LifeEvents %>
        <% if canView %>
            <div class="tl-block $EventType">
                <div class="tl-ball">
                    <div class="tl-ball-content">
                        <% if DatePrecision == 'Accurate' %>
                            <span class="tl-ball-text">$EventDate.Format(M d)</span>
                        <% else_if DatePrecision == 'Estimated' %>
                            <span class="tl-ball-text"><%t Genealogist.EST 'Est.' %></span>
                        <% else_if DatePrecision == 'Calculated' %>
                            <span class="tl-ball-text"><%t Genealogist.CALC 'Calc.' %></span>
                        <% end_if %>

                        <% if EventDate.Year %><span class="tl-ball-title">$EventDate.Year</span><% end_if %>
                        <% if Age %><span class="tl-ball-text"><%t Genealogist.AGE 'Age' %> $Age</span><% end_if %>
                    </div>
                </div>

                <div class="tl-content">
                    <span class="tl-content-place">
                        <% if DatePrecision == 'Accurate' %><span class="tl-content-date">$EventDate.Format(M d)</span><% end_if%>
                        <%if EventPlace %> <i class="fa fa-map-marker" aria-hidden="true"></i> $PlaceTitle<% end_if%>
                    </span>
                    <span class="tl-content-title">$Title</span>
                    <div class="tl-content-text">$Content</div>
                    <% if RelatedPerson  && RelatedPerson.ID != Person.ID %>
                        <p class="tl-content-text"><a href="{$RelatedPerson.ShowLink}" title="{$RelatedPerson.FullName}">$RelatedPerson.AliasSummary</a></p>
                    <% end_if %>
                </div>
            </div>
        <% end_if %>
    <% end_loop %>
</section>