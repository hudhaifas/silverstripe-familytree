<li class="{$CSSClasses}" data-birth="{$CSSBirth}" data-death="{$CSSDeath}">
    <a href="#" title="<% if not isMalesOnly %>$FullName<% end_if %>" data-url="{$InfoLink}" class="info-item">{$PersonName}</a>
    <ul>
        $AncestorsLeaves
    </ul>
</li>
