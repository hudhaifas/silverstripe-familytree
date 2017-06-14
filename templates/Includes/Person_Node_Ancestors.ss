<% if isMale || canView || IsPublicFigure %>
<li class="{$CSSClasses}" <% if not isClan %>data-birth="{$CSSBirth}" data-death="{$CSSDeath}"<% end_if %>>
    <a href="#" title="<% if not isMalesOnly %>$FullName<% end_if %>" data-url="{$InfoLink}" class="info-item">{$PersonName}</a>
    <ul>
        $AncestorsLeaves
    </ul>
</li>
<% end_if %>
