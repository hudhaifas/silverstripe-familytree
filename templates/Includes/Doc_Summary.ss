<p class="title"><a href="$ObjectLink" title="$Title">$Title.LimitCharacters(50)</a></p>

<% if $Date %>
    <p class="details"><%t Genealogist.DATE 'Date' %>: $Date</p>
<% end_if %>
<% if Collector %>
    <p class="details"><%t Genealogist.COLLECTOR 'Collector' %>: $Collector</p>
<% end_if %>
<% if Description %>
    <p class="details"><%t Genealogist.DESCRIPTION 'Description' %>: $Description</p>
<% end_if %>
