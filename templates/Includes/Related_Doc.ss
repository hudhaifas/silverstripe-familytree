<div class="doc col-xs-12 col-sm-6 col-md-12">
    <div class="doc-img">
        <a href="$Link" title="$Title">
            <div class="thumbnail text-center related-default">
                <% if $Docuement %>
                    <img src="$Docuement.SetSize(105,149).URL" class="img-responsive related-img" alt="" />
                <% else %>
                    <img alt="" class="img-responsive" src= "genealogist/images/default-doc.jpg" />

                    <div class="caption" style="">
                        <h4>$Title.LimitCharacters(100)</h4>
                    </div>
                <% end_if %>
            </div>
        </a>
    </div>

    <div class="doc-desc">
        <p class="title"><a href="$Link" title="$Title">$Title.LimitCharacters(30)</a></p>
        <% if Date %><p class="line"><%t Genealogist.DATE "Date" %>: $Date</p><% end_if %>
        <% if Collector %><p class="line"><%t Genealogist.COLLECTION "Collection" %>: $Collector</p><% end_if %>
    </div>
</div>