<div class="thumbnail text-center volume-default">
    <% if $Docuement %>
        <img class="img-responsive" src="$Docuement.PaddedImage(420,594).URL" />
    <% else %>
        <img class="img-responsive" src= "genealogist/images/default-doc.jpg" />

        <div class="caption" style="">
            <h4>$Title.LimitCharacters(110)</h4>
        </div>
    <% end_if %>
</div>