<?php

namespace App\Twig;

use App\Twig\AppRuntime;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;
use App\Entity\Project;
use App\Form\ProjectType;
use App\Repository\ProjectRepository;

//Extension des models twig

class TwigmapExtend extends AbstractExtension
{
    // map
    public function getFunctions()
    {
        return array(
          //Ici on appelle les function qui sont crées plus bas et seront appelées dansles page twig comme ici on apelera 'leaflet_map'
            new TwigFunction('leaflet_map', array($this, 'leafletMapFunction')),
            new TwigFunction('leaflet_icone', array($this, 'leafletIconeFunction')),
        );
    }
    // On contruit la fonction leafletMap et elle sera appliqué dans la div qui a pour ID:$map
    // On test avec $map mais la carte ne s'affiche pas

    public function leafletMapFunction($map)
    // le L.map qui est la creation de la carte sera afféctée à la <div id="$map">
    {
        // $mapLeaflet = "<div id='$map'></div>
         $mapLeaflet = "<div id='mapid'></div>

        <script>
                // let mymap = L.map('$map').setView([48.8534, 2.3488], 11);

                 let mymap = L.map('mapid').setView([48.8534, 2.3488], 11);
                 display_map(mymap);
        </script>";

        return $mapLeaflet;
    }


    //icones
    public function leafletIconeFunction($icone)
    {
        $marker ="
        {% for p in projects %}
           display_marker(mymap, {{ p.latitude}}, {{ p.longitude }})
        {% endfor %}";
        return $marker;
    }
}
