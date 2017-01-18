<script>
    $(document).ready(function () {

        loadGallery(true, 'a.thumbnail');

        //This function disables buttons when needed
        function disableButtons(counter_max, counter_current) {
            $('#prev-doc, #next-doc').show();
            if (counter_max == counter_current) {
                $('#next-doc').hide();
            } else if (counter_current == 1) {
                $('#prev-doc').hide();
            }
        }

        /**
         *
         * @param setIDs        Sets IDs when DOM is loaded. If using a PHP counter, set to false.
         * @param setClickAttr  Sets the attribute for the click handler.
         */
        function loadGallery(setIDs, setClickAttr) {
            var current_image,
                    selector,
                    counter = 0;

            $('#next-doc, #prev-doc').click(function () {
                if ($(this).attr('id') == 'prev-doc') {
                    current_image--;
                } else {
                    current_image++;
                }

                selector = $('[data-image-id="' + current_image + '"]');
                updateGallery(selector);
            });

            function updateGallery(selector) {
                current_image = selector.data('image-id');
                $('#doc-caption').html(selector.find('#caption').html());
                $('#doc-title').text(selector.data('title'));
                $('#doc-image').attr('src', selector.data('image'));
                disableButtons(counter, selector.data('image-id'));
            }

            if (setIDs == true) {
                $('[data-image-id]').each(function () {
                    counter++;
                    $(this).attr('data-image-id', counter);
                });
            }
            $(setClickAttr).on('click', function () {
                updateGallery($(this));
            });
        }
    });
</script>

<% with Person %>
<div class="col-md-9">
    <div class="row">
        <div class="col-lg-3 col-md-6 col-xs-12">
            <% include Images_Person %>
        </div>

        <div class="col-lg-9 col-md-6 col-xs-12">
            <h2>$FullName <a href="{$ShowLink}" target="_blanck" title="<%t Genealogist.SHOW_THIS 'Show this person tree' %>"><i class="fa fa-external-link" aria-hidden="true" style="font-size: 50%;"></i></a></h2>

            <% if BirthDate %><%t Genealogist.BIRTHDATE 'Birth Date' %>: $BirthDate<br /><% end_if %>
            <% if DeathDate %><%t Genealogist.DEATHDATE 'Death Date' %>: $DeathDate<br /><% end_if %>
            <% if Age %><%t Genealogist.AGE 'Age' %>: $Age<br /><% end_if %>

        </div>

    </div>

    <div class="row">
        <ul class="nav nav-tabs">
            <% if Biography %>
                <li class="active"><a data-toggle="tab" href="#biography"><%t Genealogist.BIOGRAPHY "Biography" %></a></li>
            <% end_if %>

            <% if Documents %>
                <li><a data-toggle="tab" href="#documents"><%t Genealogist.DOCUMENTS "Documents" %></a></li>
            <% end_if %>
        </ul>

        <div class="tab-content">
            <% if Biography %>
                <div id="biography" class="tab-pane fade in active">
                    $Biography
                </div>
            <% end_if %>

            <% if Documents %>
                <div id="documents" class="tab-pane fade in">
                    <div>
                    <% loop Documents %>
                        <div class="col-lg-3 col-md-4 col-xs-6 thumb">
                            <a class="thumbnail" href="#"
                               data-image-id=""
                               data-toggle="modal"
                               data-title="{$Title}"
                               data-caption=""
                               data-image="{$Docuement.URL}"
                               data-target="#image-gallery">
                                <img class="img-responsive" src="$Docuement.PaddedImage(280, 410).URL" />
                                <div class="hidden" id="caption">{$Description}</div>
                            </a>
                        </div>
                    <% end_loop %>
                    </div>

                    <div class="modal fade" id="image-gallery" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                        <div class="modal-dialog" style="width: 90%;">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
                                    <h4 class="modal-title" id="doc-title"></h4>
                                </div>

                                <div class="modal-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <img id="doc-image" class="img-responsive" src="" />
                                        </div>
                                        <div class="col-md-6 text-justify" id="doc-caption">
                                            This text will be overwritten by jQuery
                                        </div>
                                    </div>
                                </div>

                                <div class="modal-footer">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <button type="button" id="prev-doc" class="btn btn-default pull-left">Previous</button>
                                        </div>

                                        <div class="col-md-6">
                                            <button type="button" id="next-doc" class="btn btn-default pull-right">Next</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <% end_if %>

        </div>
    </div>
</div>

<div class="col-md-3">
    <% if $Father %>
        <b><%t Genealogist.FATHER 'Father' %></b><br />
        <a href="{$Father.ShowLink()}" target="_blanck">$Father.FullName</a>
    <% end_if %>

    <% if Mother %>
        <hr />
        <b><%t Genealogist.MOTHER 'Mother' %></b><br />

        <a href="{$Mother.ShowLink()}" target="_blanck">$Mother.FullName</a>
        <br />
    <% end_if %>

    <% if Husbands %>
        <hr />
        <b><%t Genealogist.HUSBANDS 'Husbands' %></b><br />

        <% loop Husbands %>
            <a href="{$ShowLink()}" target="_blanck">$FullName</a><br />
        <% end_loop %>

    <% else_if Wives %>
        <hr />
        <b><%t Genealogist.WIVES 'Wives' %></b><br />

        <% loop Wives %>
            <a href="{$ShowLink()}" target="_blanck">$FullName</a><br />
        <% end_loop %>
    <% end_if %>

    <% if Sons %>
        <hr />
        <b><%t Genealogist.SONS 'Sons' %></b>: $SonsCount<br />

        <% loop Sons.sort('BirthDate DESC').sort('Created ASC') %>
            <a href="{$ShowLink()}" target="_blanck" title="$FullName">$AliasName</a><% if not Last %><%t Genealogist.COMMA ',' %><% end_if %>
        <% end_loop %>
        <br />
    <% end_if %>

    <% if Daughters %>
        <hr />
        <b><%t Genealogist.DAUGHTERS 'Daughters' %></b>: $DaughtersCount<br />

        <% loop Daughters.sort('BirthDate DESC').sort('Created ASC') %>
            <a href="{$ShowLink()}" target="_blanck" title="$FullName">$AliasName</a><% if not Last %><%t Genealogist.COMMA ',' %><% end_if %>
        <% end_loop %>
    <% end_if %>
</div>
<% end_with %>