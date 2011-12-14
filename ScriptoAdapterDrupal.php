<?php
require_once 'Scripto/Adapter/Interface.php';

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
    $pages = array();
    foreach (_scripto_get_pages($node) as $page) {
      $pages[$page['fid']] = $page['filename'];
    }
    return $pages;
  }
  
  public function getDocumentPageFileUrl($documentId, $pageId) {
    $file = file_load($pageId);
    return file_create_url($file->uri);
  }
  
  public function getDocumentFirstPageId($documentId) {
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
    return false;
  }
  
  public function documentPageTranscriptionIsImported($documentId, $pageId) {
    return false;
  }
  
  public function importDocumentPageTranscription($documentId, $pageId, $text) {
    return false;
  }

  public function importDocumentTranscription($documentId, $text) {
    $node = node_load($documentId);
    
    $text = Scripto::removeNewPPLimitReports($text);
    
    // Build the long_text field structure, in full HTML so no markup is 
    // filtered out.
    $node->scripto_transcription[$node->language][0] = array(
      'value' => $text,
      'format' => 'full_html',
    );
    
    // Update the node with the new text.
    field_attach_update('node', $node);
  }
}
