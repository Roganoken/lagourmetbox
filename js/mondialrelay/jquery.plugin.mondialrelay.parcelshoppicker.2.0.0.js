
var Widgets = Widgets || function () {

    var private = {
        ashx: 'service.ashx',
        svc: 'services/parcelshop-picker.v2.0.0.svc',
        w_name: 'parcelshop-picker/v2.0.0',
        sw_url: '',
        img_url: 'www.mondialrelay.fr',
        bounds: null,
        map: null,
        overlays: [],
        InfoWindow: null,
        container: null,
        callback: null,
        mapLoaded: false,
        containerId: null,
        params: null,
        protocol: '',
		debug: false,
		box:'MRW-Map',

        jsonpcall: function (fn, paramArray, callbackFn) {
			if(private.debug)
				console.log("jsonpcall => callbackFn:" + callbackFn);
            // Create list of parameters in the form (http get format):
            // paramName1 = paramValue1 & paramName2 = paramValue2 &
            var paramList = '';
            if (paramArray.length > 0) {
                for (var i = 0; i < paramArray.length; i += 2) {
                    paramList += paramArray[i] + '=' + paramArray[i + 1] + '&';
                }
            }
			
			// =======================================
			// CORRECTION PROFILEO
			// =======================================
			// console.log(private.protocol + private.sw_url + '/' + fn + '?' + paramList + 'method=?');
            jQuery.getJSON(private.protocol + private.sw_url + '/' + fn + '?' + paramList + 'method=?', callbackFn);
        },

        loadhtml: function (container, urlraw, callback) {
			if(private.debug)
				console.log("loadhtml");
            var urlselector = (urlraw).split(" ", 1);
            var url = urlselector[0];
            var selector = urlraw.substring(urlraw.indexOf(' ') + 1, urlraw.length);
            private.container = container;
            private.callback = callback;
            private.jsonpcall(private.ashx, ['downloadurl', escape(url)],
				function (msg) {
				    // gets the contents of the Html in the 'msg'
				    // todo: apply selector
				    private.container.html(msg);
				    if (jQuery.isFunction(private.callback)) {
				        private.callback();
				    }
				});
        },

        Manage_Response: function (result, container, Target, TargetDisplay, TargetDisplayInfoPR) {
			if(private.debug)
				console.log("Manage_Response");	
			
            if (result.Error == null) {
                container.find(".MRW-Results").slideDown('slow');
                container.find(".MRW-RList").html(result.Value).show();
                if (private.params.ShowResultsOnMap) {
                    // Ajout des points sur la google map
                    //if (!private.mapLoaded) 
					//{
                        private.MR_LoadMap(private.params);
                        private.mapLoaded = true;
                    //}

                    // Supprime le contenu de la carte
                    private.MR_clearOverlays();

                    // Boucle sur les Points Relais
                    for (var i = 0; i < result.PRList.length; i++) {
                        // Ajout d'un marker pour chaque Point Relais

                        private.MR_AddGmapMarker(
								private.map,
								new google.maps.LatLng(result.PRList[i].Lat.replace(',', '.'), result.PRList[i].Long.replace(',', '.')),
								result.PRList[i],
								i,
								private.sw_url,
								Target,
								TargetDisplay,
								TargetDisplayInfoPR
							);

                    }

                    // Redimentionne la carte					
                    private.map.fitBounds(private.bounds);

                    // AutoSelect
                    if (private.params.AutoSelect) {
                        private.MR_FocusOnMaker(private.params.AutoSelect);
                    }
                } else {
                    jQuery('#'+private.box+'', private.container).html("");
                    for (var i = 0; i < result.PRList.length; i++) {
                        jQuery('#'+private.box+'', private.container).append(private.MR_BuildparcelShopDetails(result.PRList[i]))

                        jQuery.data(jQuery('#'+private.box+' > div:last-child')[0], "ParcelShop", result.PRList[i])


                        jQuery('#'+private.box+' > div:last-child').bind("select", function () {

                            private.MR_SelectparcelShop(jQuery.data(jQuery(this)[0], "ParcelShop"));
                        });


                        jQuery('#'+private.box+' > div', private.container).hide()
                    }
                }



            } else {
                container.find(".MRW-Results").hide();
                container.find(".MRW-Errors").html(result.Error).slideDown("slow");
            }

            container.find('.progressBar').hide();

            // Gestion du hover sur les items
            container.find('.PR-List-Item').mouseover(function () {
                jQuery(this).addClass("PR-hover");
            });
            container.find('.PR-List-Item').mouseout(function () {
                jQuery(this).removeClass("PR-hover");
            });

        },
        MR_Widget_Call: function (container, Target, TargetDisplay, TargetDisplayInfoPR) {
			if(private.debug)
				console.log("MR_Widget_Call");
            container.find(".MRW-Errors").hide();
            container.find('.progressBar').show();
            container.find(".MRW-Errors").html("");

            var a0 = container.find('input.Arg0')[0].value;
            var a1 = container.find('input.Arg1')[0].value;
            var a2 = container.find('input.Arg2')[0].value;
            var a3 = container.find('input.Arg3')[0].value;
            var a4 = container.find('input.Arg4')[0].value;
            var a5 = container.find('input.Arg5')[0].value;
            var a6 = container.find('input.Arg6')[0].value;
            var a7 = container.find('input.Arg7')[0].value;
            var a8 = private.params.VacationBefore || '';
            var a9 = private.params.VacationAfter || '';

            private.jsonpcall(private.w_name + "/" + private.svc + "/SearchPR",
				["Brand", a0, "Country", a1, "PostCode", a2, "ColLivMod", a3, "Weight", a4, "NbResults", a5, "SearchDelay", a6, "SearchFar", a7, "ClientContainerId", private.containerId, "VacationBefore", a8, "VacationAfter", a9],
				function (result) {
				    private.Manage_Response(result, container, Target, TargetDisplay, TargetDisplayInfoPR);
				});
        },
        MR_LoadMap: function (prms) {
			if(private.debug)
				console.log("MR_LoadMap");
            var myOptions = {
                zoom: 5,
                center: new google.maps.LatLng(46.80000, 1.69000),
                mapTypeId: google.maps.MapTypeId.ROADMAP,
                panControl: false, // Fl�ches de direction
                rotateControl: true,
                scaleControl: true, // Mesure de distance
                scrollwheel: prms.MapScrollWheel ? prms.MapScrollWheel : false, // Zoom avec la molette de la souris
                streetViewControl: prms.MapStreetView ? prms.MapStreetView : false, // Autorisation de StreetView
                zoomControl: true // Zoom
            };
            private.map = new google.maps.Map(document.getElementById(private.box), myOptions);
			
			google.maps.event.addListener(private.map, "idle", function(){
				google.maps.event.trigger(private.map, 'resize');
			}); 
			
			google.maps.event.addListener(private.map,'center_changed',function() {
				// google.maps.event.trigger(private.map, 'resize');
			});
			
            private.bounds = new google.maps.LatLngBounds();
            private.overlays = [];
        },
        MR_clearOverlays: function () {
			if(private.debug)
				console.log("MR_clearOverlays");
            for (var n = 0, overlay; overlay = private.overlays[n]; n++) {
                overlay.setMap(null);
            }
            // Clear overlays from collection
            private.overlays = [];
            private.bounds = new google.maps.LatLngBounds();
        },
        MR_FocusOnMaker: function (id) {
			if(private.debug)
				console.log("MR_FocusOnMaker");
            // Boucle sur les Markers
            for (var i = 0; i < private.overlays.length; i++) {
                // Test de validit�
                if (id == private.overlays[i].get("id")) {
                    private.MR_FocusOnMap(i);
                }
            }
        },

        MR_AddGmapMarker: function (map, latLng, PRI, Id, sw_url, Target, TargetDisplay, TargetDisplayInfoPR) {
			if(private.debug)
				console.log("MR_AddGmapMarker");
            // Get the letter for the marker
            var letter = String.fromCharCode("A".charCodeAt(0) + (private.overlays.length));

            // Create the marker
            var marker = new google.maps.Marker({
                position: latLng,
                map: map,
                icon: new google.maps.MarkerImage(private.protocol + private.sw_url + "/" + private.w_name + "/css/imgs/gmaps_pr02" + letter + ".png")
            });

            // Add clickListener
            google.maps.event.addListener(marker, 'click', function () {
                // Fermeture de la fen�tre pr�c�dente
                if (private.InfoWindow != null) { private.InfoWindow.close(); }

                private.InfoWindow = new google.maps.InfoWindow(
					{
						content: private.MR_BuildparcelShopDetails(PRI)
					}
				);

                private.InfoWindow.open(private.map, marker);
                private.map.setCenter(marker.getPosition());
            });

            // Add clickListener
            google.maps.event.addListener(marker, 'click', function () {
                private.MR_SelectparcelShop(PRI);
            });

            // Add Marker to Overlays collection
            private.overlays.push(marker);

            // Redimentionne la carte
            private.bounds.extend(latLng);
            //map.fitBounds(bounds);
            return marker;
        },
        MR_SelectparcelShop: function (PRI) {
			if(private.debug)
				console.log("MR_SelectparcelShop");
            jQuery(private.params.Target).val(PRI.Pays + '-' + PRI.ID).trigger('change');
            jQuery(private.params.TargetDisplay).html(PRI.Pays + '-' + PRI.ID);
            if (private.params.TargetDisplayInfoPR) {
                jQuery(private.params.TargetDisplayInfoPR).html(PRI.Nom + '<br/>' + PRI.Adresse1 + '<br/>' + PRI.Adresse2 + '<br/>' + PRI.Pays + '-' + PRI.CP + ' ' + PRI.Ville + ' ');
            }

            jQuery(".PR-Selected").removeClass("PR-Selected");
            jQuery('.PR-Id[Value="' + PRI.Pays + '-' + PRI.ID + '"]').parent().addClass("PR-Selected");

            if (private.params.OnParcelShopSelected) {
                private.params.OnParcelShopSelected(PRI)
            }
        },

        MR_BuildparcelShopDetails: function (PRI) {
			if(private.debug)
				console.log("MR_BuildparcelShopDetails");
            var content = '<div class="InfoWindow">'
						+ '<div class="PR-Name">' + PRI.Nom + '</div>'
						+ '<div class="Tabs-Btns">'
						+ '<span class="Tabs-Btn Tabs-Btn-Selected" id="btn_01" onclick="jQuery(\'#' + private.containerId + '\').trigger(\'TabSelect\',\'01\');">Info</span>'
						+ '<span class="Tabs-Btn" id="btn_02" onclick="jQuery(\'#' + private.containerId + '\').trigger(\'TabSelect\',\'02\');">Photo</span>'
						+ '</div>'
						+ '<div class="Tabs-Tabs">'
						+ '<div class="Tabs-Tab Tabs-Tab-Selected" id="tab_01">' + PRI.HoursHtmlTable + '</div>'
						+ '<div class="Tabs-Tab" id="tab_02">'
						+ '<img src="' + private.protocol + private.img_url + '/img/dynamique/pr.aspx?id=' + PRI.Pays + private.MR_pad_left(PRI.ID, '0', 6) + '" width="182" height="112"/>'
						+ '</div>'
						+ '</div>'
						+ '</div>'
            return content;
        },
        MR_loadjscssfile: function (filename, filetype) {
			if(private.debug)
				console.log("MR_loadjscssfile");
            var fileref;
            if (filetype == "js") {
                fileref = document.createElement('script');
                fileref.setAttribute("type", "text/javascript");
                fileref.setAttribute("src", filename);
            }
            else if (filetype == "css") {
                fileref = document.createElement("link");
                fileref.setAttribute("rel", "stylesheet");
                fileref.setAttribute("type", "text/css");
                fileref.setAttribute("href", filename);
            }
            if (typeof fileref != "undefined") { document.getElementsByTagName("head")[0].appendChild(fileref); }
        },

        MR_pad_left: function (s, c, n) {
			if(private.debug)
				console.log("MR_pad_left");
            if (!s || !c || s.length >= n) {
                return s;
            }

            var max = (n - s.length) / c.length;
            for (var i = 0; i < max; i++) {
                s = c + s;
            }

            return s;
        },


        // Initialisation du Widget apr�s chargement du contr�le
        MR_Widget_Init: function (container, prms) {
			if(private.debug)
				console.log("MR_Widget_Init");
            private.params = prms;
            // Autocomplete sur le nom de ville
            var t = container.find('input.iArg0');
            var autoCpl = jQuery("<div>");
            autoCpl.addClass("PR-AutoCplCity");
            autoCpl.css("width", t.width());

            container.find('.MRW-Search').append(autoCpl);
			container.find('.MRW-Search').css('display' , 'none');

            container.find('input.Arg2').keydown(function (e) {
                container.find('.PR-AutoCplCity').html("").slideUp("fast");
            });

            container.find('input.iArg0').keydown(function (e) {
                var keyCode = e.keyCode || e.which;

                var ia0 = container.find('input.iArg0')[0].value;
                var a2 = ""; //container.find('input.Arg2')[0].value;
                var a1 = container.find('input.Arg1')[0].value;

                var inp = String.fromCharCode(keyCode);
                //d�placement par les touches
                //en cas de touche fleche vers le bas 
                if (keyCode == 40) {
                    if (container.find('.PR-AutoCplCity .AutoCpl-Hover').length === 0) {
                        container.find('.PR-AutoCplCity div:first-child').addClass("AutoCpl-Hover");
                    } else if (container.find('.AutoCpl-Hover').next().length > 0) {
                        container.find('.AutoCpl-Hover').removeClass("AutoCpl-Hover").next().addClass("AutoCpl-Hover");
                    }
                }
                //en cas de touche fleche vers le haut
                else if (keyCode == 38) {
                    if (container.find('.PR-AutoCplCity .AutoCpl-Hover').length === 0) {
                        container.find('.PR-AutoCplCity div:last-child').addClass("AutoCpl-Hover");
                    } else if (container.find('.AutoCpl-Hover').prev().length > 0) {
                        container.find('.AutoCpl-Hover').removeClass("AutoCpl-Hover").prev().addClass("AutoCpl-Hover");
                    }
                }
                //en cas de touche entr�e
                else if ((keyCode == 13 || keyCode == 9) && container.find('.AutoCpl-Hover').length > 0) {
                    e.preventDefault();
                    container.find('input.Arg2')[0].value = container.find('.AutoCpl-Hover').attr("title");
                    container.find('input.iArg0')[0].value = container.find('.AutoCpl-Hover').attr("name");
                    container.find('.PR-AutoCplCity').html("").slideUp("fast");
                    return;
                }
                //pour toute autre touche de type caract�re
                else if (/[a-zA-Z0-9\-_ ]/.test(inp)) {
                    ia0 = ia0 + inp;
                    if (ia0.length > 3) {
                        container.find('.PR-AutoCplCity').css("top", (this.offsetTop + 20) + "px");
                        container.find('.PR-AutoCplCity').css("left", (this.offsetLeft) + "px");

                        private.jsonpcall(private.w_name + "/" + private.svc + "/AutoCPLCity",
						["PostCode", a2, "Country", a1, "City", ia0],
						function (result) {
						    container.find('.PR-AutoCplCity').html("");

						    for (var i = 0; i < result.Value.length; i++) {
						        var elm = jQuery("<div>");
						        elm.attr("title", result.Value[i].PostCode);
						        elm.attr("name", result.Value[i].Name);
						        elm.addClass("PR-City");

						        elm.html(result.Value[i].Name + " (" + result.Value[i].PostCode + ")");
						        container.find('.PR-AutoCplCity').append(elm);
						        elm.click(function () {
						            container.find('input.Arg2')[0].value = jQuery(this).attr("title");
						            container.find('input.iArg0')[0].value = jQuery(this).attr("name");
						            container.find('.PR-AutoCplCity').html("").slideUp("fast");
						        });
						    }
						    container.find('.PR-AutoCplCity').slideDown("fast");
						});

                    }
                }
                else {
                    container.find('.PR-AutoCplCity').html("").slideUp("fast");
                }
            });

            container.find('input.iArg0').blur(function (event) {
                if (container.find('.AutoCpl-Hover').length) {
                    container.find('input.Arg2')[0].value = container.find('.AutoCpl-Hover').attr("title");
                    container.find('input.iArg0')[0].value = container.find('.AutoCpl-Hover').attr("name");
                }

            });

            // Fonction au click sur le bouton rechercher
            container.find('.MRW-BtGo').click(function () {
                var btn = jQuery(this);
                private.MR_Widget_Call(container, prms.Target, prms.TargetDisplay, prms.TargetDisplayInfoPR);
                return false;
            });

            // Fonction au click sur la selection des pays
            container.find('.MRW-flag').click(function () {
                var btn = jQuery(this);
                container.find('.MRW-fl-Select').slideDown("fast").css("top", (this.offsetTop + this.height + 2) + "px").css("left", this.offsetLeft - 3 + "px");
            });

            // Fonction au click sur la selection d'un pays
            container.find('.MRW-fl-Item').click(function () {
                var btn = jQuery(this);
                container.find('.MRW-fl-Select').slideUp("fast");
                container.find('.MRW-flag').attr('src', btn.find('img').attr('src'));
                container.find('input.Arg1')[0].value = btn.find('img').attr('alt');
            });

            container.find('input.Arg0')[0].value = prms.Brand;
            container.find('input.Arg1')[0].value = prms.Country;
            container.find('input.Arg2')[0].value = prms.PostCode;
            container.find('input.Arg3')[0].value = prms.ColLivMod;
            container.find('input.Arg4')[0].value = prms.Weight;
            container.find('input.Arg5')[0].value = prms.NbResults;
            container.find('input.Arg6')[0].value = prms.SearchDelay;
            container.find('input.Arg7')[0].value = prms.SearchFar;

            if (prms.PostCode != "") { private.MR_Widget_Call(container, prms.Target, prms.TargetDisplay, prms.TargetDisplayInfoPR); }
        }
    };

    var pub = {
        MR_WidgetJq: function (Div, prms) {
			if(private.debug)
				console.log("MR_WidgetJq");
            var settings = jQuery.extend({
                CSS: "1",                            // (Facultatif) Utilisation du CSS par d�faut (1 = Oui)
                Target: "",                         // (Obligatoire) L'ID du Point Relais sera retourn� dans l'�l�ment d'ID INPUT "RetourWidget"
                TargetDisplay: "",                  // (Facultatif) L'ID du Point Relais sera retourn� dans l'�l�ment d'ID HTML "RetourDisplay"
                Country: "FR",                      // (Obligatoire) Pays pr�selectionn�
                PostCode: "",                       // (Facultatif) Code Postal pr�selectionn� 
                ColLivMod: "24R",                   // (Facultatif) Mode de collecte ou de livraison pr�vu
                Weight: "",                         // (Facultatif) Poids du colis en Grammes
                NbResults: "10",                     // (Facultatif) Nombre de r�sultat maximum affichable
                SearchDelay: "",                    // (Facultatif) D�lai pr�vu avant la remise du colis � Mondial Relay
                AllowedCountries: "",               // (Facultatif) Distance de recherche maximum
                SearchFar: "",                      // (Facultatif) Distance de recherche maximum
                MapScrollWheel: false,              // (Facultatif) Maps : Activation de la molette de la souris pour effectuer un Zoom
                MapStreetView: false,
                ShowResultsOnMap: true,
                UseSSL: false,
                ServiceUrl: 'widget.mondialrelay.com',
                OnParcelShopSelected: null
            }, prms);

            if (settings.UseSSL) {
                private.protocol = 'https://';
                settings.ServiceUrl= 'www.mondialrelay.fr/widget/';
            } else {
                private.protocol = 'http://';
            }

            private.sw_url = settings.ServiceUrl;

            if (settings.AllowedCountries == "") {
                settings.AllowedCountries = settings.Country;
            }

            if (!Div.attr("id")) { Div.attr("id", "MRParcelShopPicker_" + Math.floor((Math.random() * 10000000) + 1)) }

            private.containerId = Div.attr("id")

            if (settings.CSS != "0") {
                private.MR_loadjscssfile(private.protocol + private.sw_url + "/" + private.w_name + "/css/style.min.css", "css");
            }
            private.container = Div;
            private.loadhtml(private.container, "http://" + private.sw_url + "/" + private.w_name + "/services/widget.v2.0.0.aspx?allowedCountries=" + settings.AllowedCountries + "&Country=" + settings.Country + "&UseSSL=" + settings.UseSSL,
				function () { private.MR_Widget_Init(private.container, settings); });

            return this;
        },
        // load widget into 'container' from 'host'
        MR_Widget: function (Div, prms) {
            return pub.MR_WidgetJq(jQuery(Div), prms);
        },
        MR_Destroy: function (Div, prms) {
			if(private.debug)
				console.log("MR_Destroy");
            private.container = jQuery(Div);
            private.container.find('input.Arg2').unbind('keydown');
            private.container.find('input.iArg0').unbind('keydown');
            private.mapLoaded = false;
        },
        MR_FocusOnMap: function (i) {
			if(private.debug)
				console.log("MR_FocusOnMap");
            if (private.params.ShowResultsOnMap) {
                google.maps.event.trigger(private.overlays[i], "click");
            } else {
                jQuery('#'+private.box+' > div', private.container).hide();
                jQuery('#'+private.box+' > div:nth-child(' + (i + 1) + ')', private.container).show().trigger('select');
            }
			google.maps.event.trigger(private.map, "resize");
			// private.map.setZoom(15); 
        },
        MR_tabselect: function (tab) {
			if(private.debug)
				console.log("MR_tabselect");
            jQuery(".Tabs-Btn-Selected", private.container).removeClass("Tabs-Btn-Selected");
            jQuery('#btn_' + tab, private.container).addClass("Tabs-Btn-Selected");
            jQuery(".Tabs-Tab-Selected", private.container).removeClass("Tabs-Tab-Selected");
            jQuery('#tab_' + tab, private.container).addClass("Tabs-Tab-Selected");
        },
		MR_ResizeMap: function(i) {
			// console.log("MR_ResizeMap");			
			if(!isNaN(i)) {
				// this.MR_FocusOnMap(i);
			}
		}
		
		
    };

    return pub;
} ();


; (function ($, doc, win) {
    "use strict";

    var name = 'MondialRelay-ParcelShopPicker';

    jQuery.fn.MR_ParcelShopPicker = function (opts) {

        return this.each(function (i, el) {
            var base = el;
            base.init = function () {
                base.MR = new Widgets.MR_Widget(el, opts);
                jQuery("#" + base.id).bind("FocusOnMap", function (evt, id) {
                    this.MR.MR_FocusOnMap(id)
                });
                jQuery("#" + base.id).bind("TabSelect", function (evt, id) {
                    this.MR.MR_tabselect(id)
                });
				jQuery("#" + base.id).bind("MR_ResizeMap", function (evt, id) {
                    this.MR.MR_ResizeMap(id);
                });
            };



            base.init();

        });
    };
})(jQuery, document, window);