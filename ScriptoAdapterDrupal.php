<?php
require_once 'Scripto/Adapter/Interface.php';

/**
 * @todo self::getDocumentPages()
 * @todo self::getDocumentFirstPageId()
 * @todo self::documentTranscriptionIsImported()
 * @todo self::documentPageTranscriptionIsImported()
 * @todo self::importDocumentPageTranscription()
 * @todo self::importDocumentTranscription()
 */
class ScriptoAdapterDrupal implements Scripto_Adapter_Interface {
  
  public function documentExists($documentId) {
    
    // Verify that the node exists.
    $node = node_load($documentId);
    if (!$node) {
      return false;
    }
    return true;
  }
  
  public function documentPageExists($documentId, $pageId) {
    
    // Verify that the node exists.
    $node = node_load($documentId);
    if (!$node) {
      return false;
    }
    
    // Verify that the file exists.
    $file = file_load($pageId);
    if (!$file) {
      return false;
    }
    
    // Verify that the file belongs to the specified node.
    $sql = "
    SELECT * 
    FROM {file_usage} 
    WHERE fid = :fid 
    AND module = :module 
    AND type = :type 
    AND id = :id";
    $result = db_query($sql, array(':fid' => $file->fid, ':module' => 'file', 
                                   ':type' => 'node', ':id' => $node->nid));
    if (!$result->fetchAll()) {
      return false;
    }
    
    return true;
  }
  
  public function getDocumentPages($documentId) {
    $node = node_load($documentId);
    // Get all file and image fields for this node. Consider the current field 
    // and cardinality order as the canonical order.
    return;
  }
  
  public function getDocumentPageFileUrl($documentId, $pageId) {
    $file = file_load($pageId);
    return file_create_url($file->uri);
  }
  
  public function getDocumentFirstPageId($documentId) {
    $node = node_load($documentId);
    return;
  }
  
  public function getDocumentTitle($documentId) {
    $node = node_load($documentId);
    return $node->title;
  }

  public function getDocumentPageName($documentId, $pageId) {
    $file = file_load($pageId);
    return $file->filename;
  }
  
  public function documentTranscriptionIsImported($documentId) {
    
  }
  
  public function documentPageTranscriptionIsImported($documentId, $pageId) {
    
  }
  
  public function importDocumentPageTranscription($documentId, $pageId, $text) {
    
  }

  public function importDocumentTranscription($documentId, $text) {
    
  }
}
