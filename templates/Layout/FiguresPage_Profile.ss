<div class="dataobject-page">
    <div class="container" style="margin-bottom: 1.5em;">
        <div class="row" >
            <div class="col-md-4">
                $ObjectSearchForm
            </div>

            <div class="col-md-4"></div>

            <div class="col-md-4">
                <% with Person %>
                    <% include Profile_Nav %>
                <% end_with %>
            </div>
        </div>
    </div>

    <% with Person %>
    <div class="container">
        <div class="row user-header">
            <div class="user-card">
                <div class="user-picture">
                    <div class="thumbnail text-center imgBox">
                        <% include Single_Image %>
                    </div>
                </div>

                <div class="user-brief">
                    <p class="user-title">$Title</p>

                    <% if not isTribe %>
                        <p class="user-info">
                            <% if BirthDate && BirthDateEstimated %>
                                <%t Genealogist.ESTIMATED_BIRTHDATE 'Birth Date (Estimated)' %>: $BirthYear
                            <% else_if BirthDate %>
                                <%t Genealogist.BIRTHDATE 'Birth Date' %>: $BirthDate
                            <% else_if CalculatedBirthYear %>
                                <%t Genealogist.CALCULATIONS_BIRTHDATE 'Birth Date (Calculations)' %>: $CalculatedBirthYear
                            <% end_if %>
                        </p>

                        <p class="user-info">
                            <% if DeathDate && DeathDateEstimated %>
                                <%t Genealogist.ESTIMATED_DEATHDATE 'Death Date (Estimated)' %>: $DeathYear
                            <% else_if DeathDate %>
                                <%t Genealogist.DEATHDATE 'Death Date' %>: $DeathDate
                            <% else_if CalculatedDeathYear %>
                                <%t Genealogist.CALCULATIONS_DEATHDATE 'Death Date (Calculations)' %>: $CalculatedDeathYear
                            <% end_if %>
                        </p>

                        <% if Age %>
                            <p  class="user-info"><%t Genealogist.AGE 'Age' %>: $Age</p>
                        <% end_if %>                
                    <% end_if %>
                </div>
            </div>
        </div>   
    </div>

    <div class="user-pills container-fullwidth">
        <ul class="nav nav-tabs">
            <% if ObjectTabs %>
                <% loop ObjectTabs %>
                    <li class="<% if First %>active<% end_if %>"><a href="#tab{$Pos}" data-toggle="tab">$Title</a></li>
                <% end_loop %>
            <% end_if %>
        </ul>
    </div>

    <div class="container">
        <div class="tab-content">
            <% if ObjectTabs %>
                <% loop ObjectTabs %>
                    <div id="tab{$Pos}" class="tab-pane fade <% if First %>in active<% end_if %>">
                        <p>$Content</p>
                    </div>
                <% end_loop %>
            <% end_if %>
        </div>
    </div>
    <% end_with %>
</div>