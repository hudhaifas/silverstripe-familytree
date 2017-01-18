<div class="">
    <% if Photo %>
        <img class="img-responsive" src="$Photo.PaddedImage(280, 410).URL" />
    <% else %>
        <img class="img-responsive" src= "genealogist/images/default-person.png" />
    <% end_if %>
</div>