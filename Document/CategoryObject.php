<?php

/*
 * This file is part of the ONGR package.
 *
 * (c) NFQ Technologies UAB <info@nfq.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace ONGR\MagentoConnectorBundle\Document;

use ONGR\ElasticsearchBundle\Annotation as ES;

/**
 * ElasticSearch Category object.
 *
 * @ES\Object
 */
class CategoryObject
{
    /**
     * @var string
     *
     * @ES\Property(name="id", type="string")
     */
    protected $id;

    /**
     * @var string
     *
     * @ES\Property(name="title", type="string")
     */
    protected $title;

    /**
     * @var UrlObject
     *
     * @ES\Property(name="url", type="object", objectName="ONGRMagentoConnectorBundle:UrlObject")
     */
    protected $url;

    /**
     * @var string
     *
     * @ES\Property(name="path", type="string")
     */
    protected $path;

    /**
     * @var string[]
     *
     * @ES\Property(name="categories", type="string", multiple=true)
     */
    protected $categories;

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param string $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * @return UrlObject
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @param UrlObject $url
     */
    public function setUrl($url)
    {
        $this->url = $url;
    }

    /**
     * @param string $url
     */
    public function setUrlString($url)
    {
        $urlObject = new UrlObject();
        $urlObject->setUrl($url);
        $this->url = $urlObject;
    }

    /**
     * @return string[]
     */
    public function getCategories()
    {
        return $this->categories;
    }

    /**
     * @param string[] $categories
     */
    public function setCategories($categories)
    {
        $this->categories = $categories;
    }

    /**
     * @param string $category
     */
    public function addCategory($category)
    {
        $this->categories[] = $category;
    }

    /**
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * @param string $path
     */
    public function setPath($path)
    {
        $this->path = $path;
    }
}
