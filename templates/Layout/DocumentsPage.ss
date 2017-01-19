<% include Menu_Side %>

<article class="col-md-8">

    <div class="row">
        $SearchBook
    </div>

    <% if $Query %>
    <div class="row">
        <%t Genealogist.SEARCH_QUERY 'You searched for &quot;{value}&quot;' value=$Query %>
    </div>
    <% end_if %>

    <% if $Individual %>
    <div class="row">
        <% if $Individual.Biography %>
        <a data-toggle="collapse" data-target="#biography">$Individual.FullName</a>
        <div id="biography" class="collapse justify">
            $Individual.Biography
        </div>
        <% else %>
        $Individual.FullName
        <% end_if %>
    </div>
    <% end_if %>
    
    <div class="row">
        <% if $Results %>
            <% loop $Results %>
            <div class="col-md-4">
                <a href="$Link">
                    <div class="thumbnail text-center books-default">
                        <% if $Docuement %>
                            <img src="$Docuement.PaddedImage(207,303).URL" alt="image" class="img-responsive zoom-img" />
                        <% else %>
                            <img alt="" class="img-responsive" src= "librarian/images/book-cover.jpg" />

                            <div class="caption" style="">
                                <h4>$Title.LimitCharacters(110)</h4>
                            </div>
                        <% end_if %>
                    </div>

                    <div>
                        <h5>$Title.LimitCharacters(70)</h5>
                        <% if Date %><p class="line"><%t Genealogist.DATE "Date" %>: $Date</p><% end_if %>
                        <% if Collector %><p class="line"><%t Genealogist.COLLECTION "Collection" %>: $Collector</p><% end_if %>
                    </div>		
                </a>
            </div>
            <% end_loop %>
        <% else %>
            <p><%t Genealogist.SEARCH_NO_RESULTS 'Sorry, your search query did not return any results.' %></p>
        <% end_if %>
    </div>

    <div class="row">
        <% with $Results %>
            <% include Paginate %>
        <% end_with %>
    </div>
</article>