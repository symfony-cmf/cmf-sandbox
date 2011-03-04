<?php

namespace Uwe\Documents;

/**
  * @phpcr:Document(alias="Page", repositoryClass="Doctrine\ODM\PHPCR\DocumentRepository")
  */
class Page 
{
  /* @phpcr:Path */
  private $path;

  /* @phpcr:Node */
  private $node;

  /* @phpcr:String(name="title") */
  private $title;

  /*  @phpcr:String(name="meta_keywords") */
  private $meta_keywords;

  /*  @phpcr:String(name="meta_description") */
  private $meta_description;

  public function getPath()
  {
    return $this->path;
  }

  public function setPath($path)
  {
    $this->path = $path;
  }
 
  public function getNode()
  {
    return $this->node;
  }

  public function setNode($node)
  {
    $this->node = $node;
  }
 
  public function getTitle()
  {
    return $this->title;
  }

  public function setTitle($title)
  {
    $this->title = $title;
  }

  public function getMetaKeywords()
  {
    return $this->meta_keywords;
  }

  public function setMetaKeywords($meta_keywords)
  {
    $this->meta_keywords = $meta_keywords;
  }

  public function getMetaDEscription()
  {
    return $this->meta_description;
  }

  public function setMetaDescription($meta_description)
  {
    $this->meta_description = $meta_description;
  }

}
