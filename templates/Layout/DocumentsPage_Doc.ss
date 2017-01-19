<% with Doc %>
<div class="col-md-9">
    <div class="row">
        <div class="col-lg-5 col-md-6 col-xs-12">
            <% include Images_Doc %>
        </div>

        <div class="col-lg-7 col-md-6 col-xs-12">
            <% if $Title %><h1><a href="$Link">$Title</a></h1><% end_if %>

            <% if $Date %><p class="information"><%t Genealogist.DATE "Date" %>: $Date</p><% end_if %>
            <% if $Collector %><p class="information"><%t Genealogist.COLLECTOR "Collector" %>: $Collector</p><% end_if %>

            <!-- Catagories -->
            <% if Tags %>
            <div>
                <h5><%t Genealogist.TAGS 'Tags' %></h5>
                <span class="information">
                    <% loop Tags %>
                        <a href="$Link">$Title</a><% if not Last %><%t Genealogist.COMMA ',' %> <% end_if %>
                    <% end_loop %>
                </span>
            </div>
            <% end_if %>
            
            <br />
            <% if $Description %><p class="information"><%t Genealogist.DESCRIPTION "Description" %>: $Description</p><% end_if %>
        </div>

    </div>

    <div class="row">
        <ul class="nav nav-tabs">
            <% if $Texts %>
                <li class="active"><a data-toggle="tab" href="#texts"><%t Genealogist.TEXTS "Texts" %></a></li>
            <% end_if %>

            <% if People %>
                <li><a data-toggle="tab" href="#people"><%t Genealogist.PEOPLE "People" %></a></li>
            <% end_if %>
        </ul>

        <div class="tab-content">
            <% if $Texts %>
                <div id="texts" class="tab-pane fade in active">
                    $Texts
                </div>
            <% end_if %>

            <% if People %>
                <div id="people" class="tab-pane fade in">
                    <% loop People %>
                        <h5><a href="$Link" >$FullName</a></h5>
                    <% end_loop %>
                </div>
            <% end_if %>

        </div>
    </div>
</div>

<div class="col-md-3">
    <% if $Related %>
        <h3 class="m_1"><%t Genealogist.ALSO_READ "Also Read" %></h3>

        <% loop $Related.Limit(4) %>
            <% include Related_Volume %>
        <% end_loop %>
    <% end_if %>
</div>
<% end_with %>