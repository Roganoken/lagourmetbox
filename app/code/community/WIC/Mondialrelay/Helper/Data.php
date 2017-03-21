<?php

/**
 * Magento
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 *
 * @copyright  Copyright (c) 2013 Mondial Relay
 * @author : Web in Color (http://www.webincolor.fr)
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

class WIC_Mondialrelay_Helper_Data extends Mage_Core_Helper_Abstract
{

	public static $indicator = array(
		'BE' => '32'
		,'FR' => '33'
		,'ES' => '34'
	);

	protected $_statArray = array(	
		"1"=>"Enseigne invalide",
		"2"=>"Numéro d'enseigne vide ou inexistant",
		"3"=>"Numéro de compte enseigne invalide",
		"4"=>"",
		"5"=>"Numéro de dossier enseigne invalide",
		"6"=>"",
		"7"=>"Numéro de client enseigne invalide",
		"8"=>"",
		"9"=>"Nom de ville non reconnu ou non unique",
		"10"=>"Type de collecte invalide ou incorrect (1/D > Domicile -- 3/R > Relais)",
		"11"=>"Numéro de Point Relais de collecte invalide",
		"12"=>"Pays du Point Relais de collecte invalide",
		"13"=>"Type de livraison invalide ou incorrect (1/D > Domicile -- 3/R > Relais)",
		"14"=>"Numéro du Point Relais de livraison invalide",
		"15"=>"Pays du Point Relais de livraison invalide",
		"16"=>"Code pays invalide",
		"17"=>"Adresse invalide",
		"18"=>"Ville invalide",
		"19"=>"Code postal invalide",
		"20"=>"Poids du colis invalide",
		"21"=>"Taille (Longueur + Hauteur) du colis invalide",
		"22"=>"Taille du Colis invalide",
		"23"=>"",
		"24"=>"Numéro de Colis Mondial Relay invalide",
		"25"=>"",
		"26"=>"",
		"27"=>"",
		"28"=>"Mode de collecte invalide",
		"29"=>"Mode de livraison invalide",
		"30"=>"Adresse (L1) de l'expéditeur invalide",
		"31"=>"Adresse (L2) de l'expéditeur invalide",
		"32"=>"",
		"33"=>"Adresse (L3) de l'expéditeur invalide",
		"34"=>"Adresse (L4) de l'expéditeur invalide",
		"35"=>"Ville de l'expéditeur invalide",
		"36"=>"Code postal de l'expéditeur invalide",
		"37"=>"Pays de l'expéditeur invalide",
		"38"=>"Numéro de téléphone de l'expéditeur invalide",
		"39"=>"Adresse e-mail de l'expéditeur invalide",
		"40"=>"Action impossible sans ville ni code postal",
		"41"=>"Mode de livraison invalide",
		"42"=>"Montant CRT invalide",
		"43"=>"Devise CRT invalide",
		"44"=>"Valeur du colis invalide",
		"45"=>"Devise de la valeur du colis invalide",
		"46"=>"Plage de numéro d'expédition épuisée",
		"47"=>"Nombre de colis invalide",
		"48"=>"Multi-colis en Point Relais Interdit",
		"49"=>"Mode de collecte ou de livraison invalide",
		"50"=>"Adresse (L1) du destinataire invalide",
		"51"=>"Adresse (L2) du destinataire invalide",
		"52"=>"",
		"53"=>"Adresse (L3) du destinataire invalide",
		"54"=>"Adresse (L4) du destinataire invalide",
		"55"=>"Ville du destinataire invalide",
		"56"=>"Code postal du destinataire invalide",
		"57"=>"Pays du destinataire invalide",
		"58"=>"Numéro de téléphone du destinataire invalide",
		"59"=>"Adresse e-mail du destinataire invalide",
		"60"=>"Champ texte libre invalide",
		"61"=>"Top avisage invalide",
		"62"=>"Instruction de livraison invalide",
		"63"=>"Assurance invalide ou incorrecte",
		"64"=>"Temps de montage invalide",
		"65"=>"Top rendez-vous invalide",
		"66"=>"Top reprise invalide",
		"67"=>"",
		"68"=>"",
		"69"=>"",
		"70"=>"Numéro de Point Relais invalide",
		"71"=>"",
		"72"=>"Langue expéditeur invalide",
		"73"=>"Langue destinataire invalide",
		"74"=>"Langue invalide",
		"75"=>"",
		"76"=>"",
		"77"=>"",
		"78"=>"",
		"79"=>"",
		"80"=>"Code tracing : Colis enregistré",
		"81"=>"Code tracing : Colis en traitement chez Mondial Relay",
		"82"=>"Code tracing : Colis livré",
		"83"=>"Code tracing : Anomalie",
		"84"=>"(Réservé Code Tracing)",
		"85"=>"(Réservé Code Tracing)",
		"86"=>"(Réservé Code Tracing)",
		"87"=>"(Réservé Code Tracing)",
		"88"=>"(Réservé Code Tracing)",
		"89"=>"(Réservé Code Tracing)",
		"90"=>"AS400 indisponible",
		"91"=>"Numéro d'expédition invalide",
		"92"=>"",
		"93"=>"Aucun élément retourné par le plan de tri",
		"94"=>"Colis Inexistant",
		"95"=>"Compte Enseigne non activé",
		"96"=>"Type d'enseigne incorrect en Base",
		"97"=>"Clé de sécurité invalide",
		"98"=>"Service Indisponible",
		"99"=>"Erreur générique du service"
		);
		
		
		public function convertStatToTxt($stat){
			return $this->_statArray[$stat];
		}
		
	public function getTotalWeight() {
		$weight = 0;
		$quote = Mage::getSingleton('checkout/session')->getQuote();
		$address_shipping = $quote->getShippingAddress();
		$items = $quote->getAllItems();
		$weight = 0;
		foreach($items as $item) {
			$weight += ($item->getWeight() * $item->getQty()) ;
		}
		return $weight;
	}
	
	public function getOptionAPI() {
		$weight = $this->getTotalWeight();
		$option = array(
			'weight' => '"'.($weight * 1000).'"', 
			'enseigne' => Mage::getStoreConfig('shipping/mondialrelay/enseigne'), 
		);
		return $option;
	}
		
}