<?php

namespace App\Network;

class ServiceCurl {
/**
 * Service qui permet d'afficher sur une map marqueur par rapport aux coordonnéees
 *
 * @param string $adresse -> adresse postale dont on veut connaitre les coordonnées GPS
 *
 * @return array
 *
 * @example curl_get(adresse)
 */

public function curl_get($adresse) {

  $defaults = [
    //fournit l'URL à utiliser dans la demande
    // le type=street correspond à la recherche de l'adresse nom de rue
    //CURLOPT_URL => "http://api-adresse.data.gouv.fr/search/?q=$adresse&type=street",  //type=street??
    CURLOPT_URL => "http://api-adresse.data.gouv.fr/search/?q=$adresse&type=street",  // sans type=street pour test ville Paris

    //TRUE pour inclure l'en-tête dans la valeur de retour.
    CURLOPT_HEADER => 0,
    //TRUE pour retourner le transfert en tant que chaîne de caractères de la valeur retournée par
    //curl_exec() au lieu de l'afficher directement.
    CURLOPT_RETURNTRANSFER => TRUE,
    //Le temps maximum d'exécution de la fonction cURL exprimé en secondes.
    CURLOPT_TIMEOUT => 4
  ];
    $ch = curl_init();
    //Fixe plusieurs options pour une session cURL. Cette fonction est utile pour configurer
    //un grand nombre d'options cURL sans appeler à chaque fois curl_setopt().
    curl_setopt_array($ch,($defaults));

    if( ! $result = curl_exec($ch))
          {
            //enclencher une erreur utilisateur
          trigger_error(curl_error($ch));
          }
          curl_close($ch);
      return $result;
  }

}
 ?>
