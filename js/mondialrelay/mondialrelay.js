	//var weight = 999999; // en Kg
	var option = {};

	function loadMR_Map(zone_widget, option) {
		// Charge le widget dans la DIV d'id "Zone_Widget" avec les paramètres de base    
		// renverra la selection de l'utilisateur dans le champs d'ID "Retour_Widget"  
		 jQuery(zone_widget).MR_ParcelShopPicker({
				AutoSelect: true,
				Weight: option.weight,
				ColLivMod: option.dlv_mode,
                Target: "#Retour_Widget",  // selecteur jquery ou renvoyer l'ID du relais selectionné    
                Brand: option.enseigne,  // votre code client
				PostCode: option.postcode,
                Country: option.country,  /* pays*/  				
				OnParcelShopSelected: function PS_MRAddSelectedRelayPointInDB_Widget(data) {
					var str = '';
					str += data.Nom+"\n";
					if(data.Adresse1)
						str += data.Adresse1+"\n";
					if(data.Adresse2)
						str += data.Adresse2+"\n";						
					str += data.CP+"\n";
					//str += data.ID+"\n";
					str += data.Ville+"\n";
					str += data.Pays+"\n";
					
					str = str.split("\n").join("<br />");
					
					var newdata = {};
					newdata.Num = data.ID;
					newdata.LgAdr1 = data.Nom;
					newdata.LgAdr2 = '';
					newdata.LgAdr3 = data.Adresse1;
					newdata.LgAdr4 = data.Adresse2;
					newdata.Ville = data.Ville;
					newdata.Pays = data.Pays;
					newdata.permaLinkDetail = '';
					
					
					var input = jQuery("#s_method_mondialrelay_"+option.type);
					input.val('pointsrelais_'+data.ID);
				}
		});
	}