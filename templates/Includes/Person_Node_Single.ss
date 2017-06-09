<% if isMale || canView || IsPublicFigure %>
<li class="{$CSSClasses}" data-birth="{$CSSBirth}" data-death="{$CSSDeath}">
    <a href="#" title="{$FullName}" data-url="{$InfoLink}" class="info-item">{$PersonName}</a>
</li>
<% end_if %>