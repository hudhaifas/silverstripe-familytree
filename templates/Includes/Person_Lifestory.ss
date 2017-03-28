<section class="tl-container">
    <div class="tl-plain-block">
        <p>Test test test</p>
    </div>

    <% loop LifeEvents %>
    <div class="tl-block">
        <div class="tl-ball">
            <div class="tl-ball-content">
                <% if DatePrecision == 'Accurate' %>
                    <span class="tl-ball-text">$Date.Format(M d)</span>
                <% else_if DatePrecision == 'Estimated' %>
                    <span class="tl-ball-text"><%t Genealogist.EST 'Est.' %></span>
                <% else_if DatePrecision == 'Calculated' %>
                    <span class="tl-ball-text"><%t Genealogist.CALC 'Calc.' %></span>
                <% end_if %>

                <% if Date.Year %><span class="tl-ball-title">$Date.Year</span><% end_if %>
                <% if Age %><span class="tl-ball-text"><%t Genealogist.AGE 'Age' %> $Age</span><% end_if %>
            </div>
        </div>

        <div class="tl-content">
            <span class="tl-content-place"><span class="tl-content-date">$Date.Format(M d)</span><%if $Location %> <i class="fa fa-map-marker" aria-hidden="true"></i> $Location<% end_if%></span>
            <span class="tl-content-title">$EventTitle</span>
            <% if RelatedPerson %><p class="tl-content-text">$RelatedPerson.Name</p><% end_if %>
            <div class="tl-content-text">$Content</div>
        </div>
    </div>
    <% end_loop %>
</section>