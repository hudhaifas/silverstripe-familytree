<% if isMale || canView || IsPublicFigure %>
<li class="{$CSSClasses}" <% if not isClan %>data-birth="{$CSSBirth}" data-death="{$CSSDeath}"<% end_if %>>
    <a href="#" title="{$FullName}" data-url="{$InfoLink}" class="info-item">{$PersonName}</a>
    <ul>
        $DescendantsLeaves
    </ul>
</li>
<% end_if %>