<style>
    .cover-container {
        /*height: 180px;*/
        width: 100%;
        white-space: nowrap;
        overflow-x: scroll;
        overflow-y: hidden;
    }

    .cover-item {
        display: inline-block;
        margin: 8px 8px;
        white-space: initial;
        width: 144px;
        height: 240px;
        vertical-align: bottom;
        font-size: 12px;
    }
</style>

<div class="container">
    <% if hasPermission %>
    <div class="row">
        $Anniversaries(BORN_TODAY,Birth)
    </div>
    <% end_if %>

    <div class="row">
        $Anniversaries(DIED_TODAY,Death)
    </div>

    <div class="row">
        $Annuals(BORN_THIS_YEAR,Birth)
    </div>

    <div class="row">
        $Annuals(DIED_THIS_YEAR,Death)
    </div>
</div>