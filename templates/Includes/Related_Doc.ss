<div class="doc col-xs-12 col-sm-6 col-md-12">
    <div class="doc-img">
        <a href="$Link" title="$Title">
            <div class="thumbnail text-center related-default">
                <% if $Docuement %>
                    <img src="$Docuement.SetSize(102,149).URL" class="img-responsive related-img" alt="" />
                <% else %>
                    <img alt="" class="img-responsive" src= "librarian/images/book-cover.jpg" />

                    <div class="caption" style="">
                        <h4>$Title.LimitCharacters(100)</h4>
                    </div>
                <% end_if %>
            </div>
        </a>
    </div>

    <div class="doc-desc">
        <p class="title"><a href="$Link" title="$BookName">$Title.LimitCharacters(30)</a></p>
        <p class="author">$Author.Title.LimitCharacters(28)</p>
        <% if Date %><p class="line"><%t Genealogist.DATE "Date" %>: $Date</p><% end_if %>
    </div>
</div>