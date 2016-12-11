<form class="form-inline" $AttributesHTML role="search">
    <fieldset>
        <div class="form-group">
            <!-- Hidden input-->
            <div class="form-hidden">
                <input id="{$FormName}_SecurityID" name="SecurityID" type="hidden" value="{$SecurityID}" />
            </div>

            <label class="sr-only" for="{$FormName}_SearchTerm"><%t Genealogist.SEARCH 'Search' %></label>

            <div class="input-group">
                <input type="text" class="form-control" placeholder="<%t Genealogist.SEARCH 'Search' %>" name="SearchTerm" id="{$FormName}_SearchTerm" />

                <div class="input-group-btn">
                    <button id="{$FormName}_action_doSearchPerson" name="action_doSearchPerson" class="btn btn-default" type="submit">
                        <i class="glyphicon glyphicon-search"></i>
                    </button>
                </div>
            </div>
        </div>
    </fieldset>
</form>