<?php

namespace Drupal\node_json_data_extended\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Drupal\node_json_data_extended\Form\ExtendedSiteInformationForm;
use Drupal\node\Entity\NodeType;
use Drupal\node\Entity\Node;

/**
 * Class ApiController
 * @package Drupal\node_json_data\Controller
 */
class ExtendedApiController {

  /**
   * @return JsonResponse
   */
  public function index($enteredApi, $enteredContentType, $enteredId) {

    // To get the machine name of content type of all the existing nodes.                           
    $contentTypes = \Drupal::service('entity_type.manager')->
    getStorage('node')->loadMultiple();

    $savedContentTypes = [];
    foreach ($contentTypes as $contentType) {
    $savedContentTypes[$contentType->id()] = $contentType->bundle();
    }
    
    $query = \Drupal::entityQuery('node')->condition('nid', $enteredId);        
    $nodes_ids = $query->execute();
    $nodeCondition = count($nodes_ids);
    
    // To get the API saved via configuration form.
    $savedApi['apikey'] = \Drupal::config('system.site')->get('apikey');

     // To check whether the user hass entered existed content type.
    foreach($savedContentTypes as $type){

    // To check whether the user entered correct API and a node that exist.
    if(($savedApi['apikey'] == $enteredApi) && $nodeCondition != 0 && $type == $enteredContentType){
      return new JsonResponse([ 'data' => $this->getData($enteredApi, $enteredContentType, $enteredId), 'method' => 'GET', 'status'=> 200]);
    }
  }
     return new JsonResponse(['Error' => 'Please enter the correct API, Content Type, and Node', 'Status'=> 404]);
    
  }

  /**
   * @return array
   */
  public function getData($enteredApi, $enteredContentType, $enteredId) {
    $result=[];
    $query = \Drupal::entityQuery('node')->condition('nid', $enteredId);        
    $nodes_ids = $query->execute();
    // To get the node id and description of the required node
    if ($nodes_ids) {
      foreach ($nodes_ids as $node_id) {
        $node = Node::load($node_id);
        $nodeds = $node->id();
        $result[] = [
          "id" => $node->id(),
          "description" => $node->getTitle(),
        ];
        
      }
    }
    return $result;
  }
}